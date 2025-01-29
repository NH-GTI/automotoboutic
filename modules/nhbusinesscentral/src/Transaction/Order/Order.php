<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction\Order;

use InstanWeb\Module\NHBusinessCentral\Transaction\TransactionTrait;
use InstanWeb\Module\NHBusinessCentral\Transaction\TransactionInterface;
use InstanWeb\Module\NHBusinessCentral\Transaction\TransactionProcessInterface;
use InstanWeb\Module\NHBusinessCentral\Repository\TransactionRepository;

use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;

class Order implements TransactionInterface
{
    use TransactionTrait;

    public function __construct(TransactionRepository $transactionRepository, TransactionProcessInterface $communication)
    {
        $this->transactionRepository = $transactionRepository;
        $this->phases = [$communication];
    }

    public function addOrderAndProcess($transaction, $idOrder, $action)
    {
        $this->transaction = $transaction;

        $td = new TransactionDetail();
        $td->id_nhbusinesscentral_transaction = $this->transaction->id;
        $td->item = 'Order';
        $td->id_item = $idOrder.'.'.$action;
        $td->status = 0;
        $td->retry = 1;
        $td->comment = '';

        $this->transactionRepository->insertDetail($td);

        $this->process();
    }    
}
