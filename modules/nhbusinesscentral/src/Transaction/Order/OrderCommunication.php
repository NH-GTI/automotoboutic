<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction\Order;

use InstanWeb\Module\NHBusinessCentral\Transaction\TransactionProcessTrait;
use InstanWeb\Module\NHBusinessCentral\Transaction\TransactionProcessInterface;

use InstanWeb\Module\NHBusinessCentral\Transaction\Tools\Curl;

use InstanWeb\Module\NHBusinessCentral\Transaction\Tools\ConfigTrait;
use InstanWeb\Module\NHBusinessCentral\Provider\ConfigurationData;

use InstanWeb\Module\NHBusinessCentral\Adapter\Notification\Email;

use Exception;

class OrderCommunication implements TransactionProcessInterface
{
    use TransactionProcessTrait;
    use ConfigTrait;

    private ConfigurationData $configuration;
    private $config;

    const PHASE = 1;
    const PHASE_LABEL = 'Communication';

    private $interfacePSWS;

    public function __construct(ConfigurationData $configuration)
    {
        $this->configuration = $configuration;
        $this->config = $this->getObjectConfig();
    }
    
    public function process()
    {
        if (count($this->detailsIds)) {
            $detailsIds = $this->detailsIds;
        } else {
            $detailsIds = $this->transactionRepository->getDetailsIds($this->transaction->id, $this->offset, $this->limit, 0);
        }

        if (count($detailsIds)) {

            foreach($detailsIds as $id) {

                $transactionDetail = $this->transactionRepository->getByIdDetail($id);

                $this->incrementProcessCount();

                try {
                    $item = explode('.', $transactionDetail->id_item);
                    if (count($item) == 2) {
                        $transactionDetail->data = json_encode([
                            'documentType' => $transactionDetail->item,
                            'documentNo' => $item[0],
                            'synchroAction' => $item[1]
                        ]);

                        $this->processOrder($transactionDetail);
                        $this->incrementSuccessCount();
                    }
                }
                catch (Exception $e) {
                    $transactionDetail->comment = $e->getMessage();
                    if ($this->config->mail_notification) {
                        $email = new Email();
                        $email->notify($this->config->mail_notification,  $transactionDetail->id_item);
                    }
                }

                $this->transactionRepository->updateDetail($transactionDetail);                
            }

            if (count($this->detailsIds)) {

                $this->setFinished();

            } else {

                $remainIds = $this->transactionRepository->getDetailsIds($this->transaction->id, $this->offset+$this->processCount, $this->limit, 0);
                if (count($remainIds) == 0) {
                    $this->setFinished();
                }
            }

        } else {
            $this->setFinished();
        }
    }

    private function processOrder($transactionDetail)
    {
        $tokenUrl = str_replace('{{tenantId}}', $this->config->tenant_id, $this->config->token_url);

        $curl = (new Curl())
            ->setUrl($tokenUrl)
            ->setOption([
                CURLOPT_RETURNTRANSFER => true,
            ])
            ->setHeader('Content-type: application/x-www-form-urlencoded')
            ->setRequest(Curl::REQUEST_POST)
            ->setData(http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => $this->config->client_id,
                'client_secret' => $this->config->client_secret,
                'scope' => $this->config->base_uri.$this->config->scope,
            ]))
            ->call();

        if ($curl->isResultHttpCodeAccepted()) {
            $result = (object)json_decode($curl->getResult());
            $token = $result->token_type.' '.$result->access_token;

            $sales = str_replace(
                [
                    '{{tenantId}}',
                    '{{environment}}',
                    '{{companyId}}'
                ],
                [
                    $this->config->tenant_id,
                    $this->config->environment, 
                    $this->config->company_id
                ],
                $this->config->sales_ws
            );

            $curl = (new Curl())
            ->setUrl($this->config->base_uri)
            ->setParam($sales)
            ->setHeader('Authorization:' . $token)
            ->setHeader('Content-type: application/json')
            ->setAcceptHttpCode([200,201])
            ->setOption([
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ])
            ->setRequest(Curl::REQUEST_POST)
            ->setData($transactionDetail->data)
            ->call();

            if ($curl->isResultHttpCodeAccepted()) {
                $result = (object)json_decode($curl->getResult());

                $transactionDetail->comment = $result->errorTxt;
                $transactionDetail->status = 1;

                if ($result->errorTxt && $this->config->mail_notification) {
                    $email = new Email();
                    $email->notify($this->config->mail_notification,  $transactionDetail->id_item);
                }

                return;
            }
        }

        throw new Exception($curl->getResultHttpCode() .' '.var_export($curl->getResult(),true));
    }
}
