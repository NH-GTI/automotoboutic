<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction;

use InstanWeb\Module\NHBusinessCentral\Model\Transaction;
use InstanWeb\Module\NHBusinessCentral\Repository\TransactionRepository;

trait TransactionProcessTrait {

    private ?Transaction $transaction = null;
    private ?TransactionRepository $transactionRepository = null;

    private int $offset = 0;
    private int $limit = 1;
    private int $itemNewCount = 0;
    private int $processCount = 0;
    private int $successCount = 0;
    private bool $isFinished = false;
    private array $detailsIds = [];

    /** Model Transaction */
    public function setTransaction(Transaction $transaction): self
    {
        $this->transaction = $transaction;
        return $this;
    }

    /** Transaction repository */
    public function setTransactionRepository(TransactionRepository $transactionRepository): self
    {
        $this->transactionRepository = $transactionRepository;
        return $this;
    }

    /** Numéro de phase */
    public function getPhase() : int
    {
        return static::PHASE;
    }

    /** Description de la phase */
    public function getPhaseLabel() :string
    {
        return static::PHASE_LABEL;
    }

    /** Début de paquet à traiter */
    public function setOffset(int $offset) : self
    {
        $this->offset = $offset;
        return $this;
    }

    /** Taille du paquet à traiter */
    public function setLimit(int $limit) : self
    {
        $this->limit = $limit;
        return $this;
    }

    /** Sélection des éléments du paquet à traiter (offset et limit sont inutiles) */
    public function setDetailsIds(array $ids) : self
    {
        $this->detailsIds = $ids;
        return $this;
    }

    /** Incrémente le nombre de nouveaux éléments dans le paquet de données */
    public function incrementNewCount() : self
    {
        $this->itemNewCount += 1;
        return $this;
    }

    /** Incrémente le nombre de traitements */
    public function incrementProcessCount() : self
    {
        $this->processCount += 1;
        return $this;
    }

    /** Incrémente le nombre de traitements réussis */
    public function incrementSuccessCount() : self
    {
        $this->successCount += 1;
        return $this;
    }

    /** Retourne le nombre de nouveaux éléments dans le paquet de données */
    public function getNewItemCount(): int
    {
        return $this->itemNewCount;
    }

    /** Retourne le nombre de traitements */
    public function getProcessCount(): int
    {
        return $this->processCount;
    }

    /** Retourne le nombre de traitements réussis */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /** Renvoie vrai lorsque le traitement est terminé */
    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /** Fin de traitement */
    public function setFinished(): self
    {
        $this->isFinished = true;
        return $this;
    }
}
