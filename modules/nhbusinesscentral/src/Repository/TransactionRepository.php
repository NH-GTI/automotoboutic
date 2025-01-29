<?php

namespace InstanWeb\Module\NHBusinessCentral\Repository;

use Doctrine\DBAL\Connection;
use InstanWeb\Module\NHBusinessCentral\Model\Transaction;
use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;

class TransactionRepository
{
    /**
     * @var Connection the Database connection
     */
    private $connection;

    /**
     * @var string the Database prefix
     */
    private $databasePrefix;

    /**
     * @param Connection $connection
     * @param string $databasePrefix
     */
    public function __construct(Connection $connection, $databasePrefix)
    {
        $this->connection = $connection;
        $this->databasePrefix = $databasePrefix;
    }

    public function getById($idTransaction)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('tr.'.Transaction::ID_FIELD)
            ->from($this->databasePrefix . Transaction::TABLE_NAME, 'tr')
            ->andWhere('tr.'.Transaction::ID_FIELD.' = :id')
            ->setParameter('id', $idTransaction)
        ;

        $id = $qb->execute()->fetchColumn(0);

        return new Transaction($id);
    }

    public function getIdsByTransaction($transaction, $reference = false)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('tr.'.Transaction::ID_FIELD)
            ->from($this->databasePrefix . Transaction::TABLE_NAME, 'tr')
            ->andWhere('tr.transaction = :transaction')
            ->setParameter('transaction', $transaction)
        ;
        if ($reference) {
            $qb->andWhere('tr.reference = :reference')->setParameter('reference', $reference);
        }

        return $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function insert(Transaction $transaction)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert($this->databasePrefix . Transaction::TABLE_NAME)
            ->values([
                'transaction' => ':transaction',
                'transaction_label' => ':transaction_label',
                'reference' => ':reference',
                'phase' => ':phase',
                'phase_label' => ':phase_label',
                'process_offset' => ':process_offset',
                'process_limit' => ':process_limit',
                'item_count' => ':item_count',
                'item_success_count' => ':item_success_count',
                'comment' => ':comment',
                'date_add' => ':date_add',
                'date_upd' => ':date_upd',
            ])
            ->setParameters([
                'transaction' => $transaction->transaction,
                'transaction_label' => $transaction->transaction_label,
                'reference' => $transaction->reference,
                'phase' => $transaction->phase,
                'phase_label' => $transaction->phase_label,
                'process_offset' => $transaction->process_offset,
                'process_limit' => $transaction->process_limit,
                'item_count' => $transaction->item_count,
                'item_success_count' => $transaction->item_success_count,
                'comment' => $transaction->comment,
                'date_add' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s'),
            ]
            );

        return $qb->execute();
    }

    public function update(Transaction $transaction)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update($this->databasePrefix . Transaction::TABLE_NAME)
            ->andWhere(Transaction::ID_FIELD.' = :id')
            ->setParameter('id', $transaction->id)
        
            ->set('phase', ':phase')
            ->set('phase_label', ':phase_label')
            ->set('process_offset', ':process_offset')
            ->set('process_limit', ':process_limit')
            ->set('item_count', ':item_count')
            ->set('item_success_count', ':item_success_count')
            ->set('comment', ':comment')
            ->set('date_upd', ':date_upd')

            ->setParameter('phase', $transaction->phase)
            ->setParameter('phase_label', $transaction->phase_label)
            ->setParameter('process_offset', $transaction->process_offset)
            ->setParameter('process_limit', $transaction->process_limit)
            ->setParameter('item_count', $transaction->item_count)
            ->setParameter('item_success_count', $transaction->item_success_count)
            ->setParameter('comment', $transaction->transaction)
            ->setParameter('date_upd', date('Y-m-d H:i:s'));

        return $qb->execute();
    }

    public function delete($idTransaction)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->delete($this->databasePrefix . TransactionDetail::TABLE_NAME)
            ->andWhere(TransactionDetail::ID_PARENT_FIELD.' = :id')
            ->setParameter('id', $idTransaction);
        $qb->execute();

        $qb
            ->delete($this->databasePrefix . Transaction::TABLE_NAME)
            ->andWhere(Transaction::ID_FIELD.' = :id')
            ->setParameter('id', $idTransaction)
        ;

        return $qb->execute();
    }

    public function getDetailsIds($idTransaction, $offset, $nb, $status = false)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('tr.'.TransactionDetail::ID_FIELD)
            ->from($this->databasePrefix . TransactionDetail::TABLE_NAME, 'tr')
            ->andWhere(TransactionDetail::ID_PARENT_FIELD . ' = :idTransaction')
            ->setParameter('idTransaction', $idTransaction)
            ->addOrderBy('tr.'.TransactionDetail::ID_FIELD, 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($nb)
        ;
        if (false !== $status) {
            $qb
                ->andWhere('status = :status')
                ->setParameter('status', $status);
        }

        return $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);

    }

    public function getDetailsCount($idTransaction, $status = false)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('COUNT('.TransactionDetail::ID_FIELD.')')
            ->from($this->databasePrefix . TransactionDetail::TABLE_NAME)
            ->andWhere(TransactionDetail::ID_PARENT_FIELD . ' = :idTransaction')
            ->setParameter('idTransaction', $idTransaction)
        ;
        if (false !== $status) {
            $qb
                ->andWhere('status = :status')
                ->setParameter('status', $status);
        }

        return $qb->execute()->fetchColumn(0);

    }

    public function getByIdDetail($idTransactionDetail)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('tr.'.TransactionDetail::ID_FIELD)
            ->from($this->databasePrefix . TransactionDetail::TABLE_NAME, 'tr')
            ->andWhere('tr.'.TransactionDetail::ID_FIELD.' = :id')
            ->setParameter('id', $idTransactionDetail)
        ;

        $id = $qb->execute()->fetchColumn(0);

        return new TransactionDetail($id);
    }

    public function getDetailByItem($idTransaction, $item, $id_item)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('tr.'.TransactionDetail::ID_FIELD)
            ->from($this->databasePrefix . TransactionDetail::TABLE_NAME, 'tr')
            ->andWhere(TransactionDetail::ID_PARENT_FIELD . ' = :idTransaction')
            ->andWhere('item = :item')
            ->andWhere('id_item = :id_item')
            ->setParameter('idTransaction', $idTransaction)
            ->setParameter('item', $item)
            ->setParameter('id_item', $id_item)
        ;

        $id = $qb->execute()->fetchColumn(0);

        return new TransactionDetail($id);
    }

    public function insertDetail(TransactionDetail $detail)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert($this->databasePrefix . TransactionDetail::TABLE_NAME)
            ->values([
                TransactionDetail::ID_PARENT_FIELD => ':id_parent',
                'item' => ':item',
                'id_item' => ':id_item',
                'status' => ':status',
                'retry' => ':retry',
                'data' => ':data',
                'comment' => ':comment',
                'date_add' => ':date_add',
                'date_upd' => ':date_upd',
            ])
            ->setParameters([
                'id_parent' => $detail->id_nhbusinesscentral_transaction,
                'item' => $detail->item,
                'id_item' => $detail->id_item,
                'status' => $detail->status,
                'retry' => $detail->retry,
                'data' => $detail->data,
                'comment' => $detail->comment,
                'date_add' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s'),
            ]
            );

        return $qb->execute();        
    }

    public function updateDetail(TransactionDetail $detail)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update($this->databasePrefix . TransactionDetail::TABLE_NAME)
            ->andWhere(TransactionDetail::ID_FIELD.' = :id')

            ->set('status', ':status')
            ->set('retry', ':retry')
            ->set('data', ':data')
            ->set('comment', ':comment')
            ->set('date_upd', ':date_upd')

            ->setParameters([
                'id' => $detail->id,
                'status' => $detail->status,
                'retry' => $detail->retry,
                'data' => $detail->data,
                'comment' => $detail->comment,
                'date_upd' => date('Y-m-d H:i:s'),
            ]
            );

        return $qb->execute();        
    }

    public function deleteDetail($idTransactionDetail)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->delete($this->databasePrefix . TransactionDetail::TABLE_NAME)
            ->andWhere(TransactionDetail::ID_FIELD.' = :id')
            ->setParameter('id', $idTransactionDetail)
        ;

        return $qb->execute();
    }

    public function synchroProduct($idProduct, $operation)
    {
        if (!in_array($operation, ['A','U','D'])) return;

        $idTransactions = $this->getIdsByTransaction('Product');
        $idTransaction = array_pop($idTransactions);
        
        $td = $this->getDetailByItem($idTransaction, 'Product', $idProduct);

        if (!$td->id && $operation == 'D') return;  // pas de synchro en cas de suppression de produit et aucune synchro précédente

        $td->comment = 'A synchroniser';
        if ($td->id) {
            $td->status = 0;
            $this->updateDetail($td);
        } else {
            $td->id_nhbusinesscentral_transaction = $idTransaction;
            $td->item = 'Product';
            $td->id_item = $idProduct;
            $td->status = 0;
            $td->retry = 1;
            $td->data = '';
            $this->insertDetail($td);

            $transaction = $this->getById($idTransaction);
            $transaction->item_count += 1;
            $this->update($transaction);
        }
    }
}
