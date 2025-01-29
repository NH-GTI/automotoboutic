<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction;

use InstanWeb\Module\NHBusinessCentral\Model\Transaction;
use InstanWeb\Module\NHBusinessCentral\Repository\TransactionRepository;

/**
 * Méthodes à implémenter pour traiter un paquet de données
 */
interface TransactionProcessInterface
{
    /** Model Transaction */
    public function setTransaction(Transaction $transaction): self;

    /** Transaction repository */
    public function setTransactionRepository(TransactionRepository $transactionRepository): self;

    /** Méthode pour traiter un paquet de données
     * Doit appeler setFinished lorsque le traitement est terminé
    */
    public function process();

    /** Numéro de phase */
    public function getPhase() : int;

    /** Description de la phase */
    public function getPhaseLabel() :string;

    /** Début de paquet à traiter */
    public function setOffset(int $offset) : self;

    /** Taille du paquet à traiter */
    public function setLimit(int $limit) : self;

    /** Sélection des éléments du paquet à traiter (offset et limit sont inutiles) */
    public function setDetailsIds(array $ids) : self;

    /** Incrémente le nombre de nouveaux éléments dans le paquet de données */
    public function incrementNewCount() : self;

    /** Incrémente le nombre de traitements */
    public function incrementProcessCount() : self;

    /** Incrémente le nombre de traitements réussis */
    public function incrementSuccessCount() : self;

    /** Retourne le nombre de nouveaux éléments dans le paquet de données */
    public function getNewItemCount(): int;

    /** Retourne le nombre de traitements */
    public function getProcessCount(): int;

    /** Retourne le nombre de traitements réussis */
    public function getSuccessCount(): int;

    /** Fin de traitement */
    public function setFinished(): self;

    /** Renvoie vrai lorsque le traitement est terminé */
    public function isFinished(): bool;
}
