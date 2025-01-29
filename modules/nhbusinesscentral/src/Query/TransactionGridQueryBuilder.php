<?php

namespace InstanWeb\Module\NHBusinessCentral\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use InstanWeb\Module\NHBusinessCentral\Model\Transaction;

final class TransactionGridQueryBuilder extends AbstractDoctrineQueryBuilder
{
    private $searchCriteriaApplicator;

    public function __construct(
        Connection $connection,
        string $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
    }
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria = null)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());

        $qb->select('
            tr.'.Transaction::ID_FIELD.',
            tr.transaction_label,
            !(tr.item_count > tr.item_success_count) AS status,
            tr.phase_label,
            tr.item_count,
            tr.item_success_count,
            (tr.item_count - tr.item_success_count) AS process,
            tr.comment,
            tr.date_upd
            ')
            ->groupBy('tr.'.Transaction::ID_FIELD)
        ;

        $this->searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb)
        ;
    
        return $qb;
    }

    /**
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return QueryBuilder
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria = null)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $qb->select('COUNT(tr.'.Transaction::ID_FIELD.')');

        return $qb;
    }

    /**
     * Get generic query builder.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     *
     */
    private function getQueryBuilder(array $filters)
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . Transaction::TABLE_NAME, 'tr');

        foreach ($filters as $name => $value) {

            if (Transaction::ID_FIELD === $name) {
                $qb
                    ->andWhere("tr.".Transaction::ID_FIELD." = :$name")
                    ->setParameter($name, $value)
                ;

                continue;
            }

            /*if ('status' === $name) {
                $qb
                    ->andWhere("ob.status = :$name")
                    ->setParameter($name, $value);

                continue;
            }*/

            $qb
                ->andWhere(sprintf('tr.%s LIKE :%s', $name, $name))
                ->setParameter($name, '%' . $value . '%')
            ;
        }

        return $qb;
    }
}
