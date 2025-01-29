<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction;

/**
 * Méthodes à implémenter pour recevoir ou transmettre un paquet de données
 */
interface TransactionInterface
{
    /** ID Transaction */
    public function setIdTransaction($idTransaction) : self;

    /** La transaction s'effectuera sur une sélection de données (ID TransactionDetail) */
    public function setDetailsIds(array $ids) : self;

    /** Méthode principale */
    public function process();
}
