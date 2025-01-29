<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction;

use InstanWeb\Module\NHBusinessCentral\Repository\TransactionRepository;

use InstanWeb\Module\NHBusinessCentral\Model\Transaction;
use Exception;

trait TransactionTrait {

    private ?TransactionRepository $transactionRepository = null;
    private $idTransaction = 0;
    private ?Transaction $transaction = null;
    private array $phases = [];
    private array $detailsIds = [];

    public function setIdTransaction($idTransaction) : self
    {
        $this->idTransaction = $idTransaction;

        return $this;
    }

    public function setDetailsIds(array $ids) : self
    {
        $this->detailsIds = $ids;

        return $this;
    }

    public function process()
    {    
        $this->transaction = $this->transactionRepository->getById($this->idTransaction);
        if ($count_phases = count($this->phases)) {
            $start_phase = $this->phases[0]->getPhase();
            $end_phase = $this->phases[$count_phases-1]->getPhase();
            try {
                if (count($this->detailsIds)) {
                    $this->transaction->phase = 0;
                }
                if ($this->transaction->phase < $start_phase || $this->transaction->phase > $end_phase) {
                    $this->transaction->phase = $start_phase;
                    $this->transaction->process_offset = 0;
                    $this->transaction->item_success_count = 0;
                    $this->transaction->item_count = $this->transactionRepository->getDetailsCount($this->idTransaction);
                }
                
                foreach($this->phases as $phase) {

                    if ($phase->getPhase() == $this->transaction->phase) {
                        $this->transaction->phase_label = $phase->getPhaseLabel();
                        $this->transaction->comment = '';
                        $phase
                            ->setTransaction($this->transaction)
                            ->setTransactionRepository($this->transactionRepository);

                        if (count($this->detailsIds)) {
                            $phase->setDetailsIds($this->detailsIds);
                        } else {
                            $phase
                                ->setOffset($this->transaction->process_offset)
                                ->setLimit($this->transaction->process_limit);
                        }

                        $phase->process();
                
                        if ($phase->isFinished()) {
                            $this->transaction->process_offset = 0;                            
                            if ($this->transaction->phase != $end_phase) {
                                $this->transaction->item_success_count = 0;    
                            } else {
                                $this->transaction->item_success_count = $this->transactionRepository->getDetailsCount($this->idTransaction, 1);
                            }
                            $this->transaction->phase += 1;
                        } else {
                            $this->transaction->process_offset += $phase->getProcessCount();
                            $this->transaction->item_count += $phase->getNewItemCount();
                            $this->transaction->item_success_count += $phase->getSuccessCount();
                        }
                        $this->transactionRepository->update($this->transaction);
                    }
                }
            } catch(Exception $e) {
                $this->transaction->comment = $e->getMessage();
                $this->transactionRepository->update($this->transaction);
                return false;
            }
        }
        return true;
    }
}
