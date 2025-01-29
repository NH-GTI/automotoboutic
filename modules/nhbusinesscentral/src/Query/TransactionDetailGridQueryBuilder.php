<?php

namespace InstanWeb\Module\NHBusinessCentral\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;

final class TransactionDetailGridQueryBuilder extends AbstractDoctrineQueryBuilder
{
    private $searchCriteriaApplicator;
    private $requestStack;

    public function __construct(
        Connection $connection,
        string $dbPrefix,
        DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator,
        RequestStack $requestStack)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->requestStack = $requestStack;
    }
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria = null)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());

        $qb->select('
            tr.'.TransactionDetail::ID_FIELD.',
            tr.'.TransactionDetail::ID_PARENT_FIELD.',
            tr.item,
            tr.id_item,
            tr.comment,
            tr.status,
            tr.date_upd
            ')
            ->groupBy('tr.'. TransactionDetail::ID_FIELD)
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
        $qb->select('COUNT(tr.'.TransactionDetail::ID_FIELD.')');

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
            ->from($this->dbPrefix . TransactionDetail::TABLE_NAME, 'tr');

        foreach ($filters as $name => $value) {

            if (TransactionDetail::ID_FIELD === $name) {
                $qb
                    ->andWhere("tr.".TransactionDetail::ID_FIELD." = :$name")
                    ->setParameter($name, $value)
                ;

                continue;
            }

            if ('status' === $name) {
                $qb
                    ->andWhere("tr.status = :$name")
                    ->setParameter($name, $value);

                continue;
            }

            $qb
                ->andWhere(sprintf('tr.%s LIKE :%s', $name, $name))
                ->setParameter($name, '%' . $value . '%')
            ;
        }

        if ($idParent = $this->requestStack
                ->getCurrentRequest()
                ->getSession()
                ->get('admin_nhbusinesscentral_transactiondetail_grid_parent_id')) {
            $qb
                ->andWhere("tr.".TransactionDetail::ID_PARENT_FIELD." = :idParent")
                ->setParameter('idParent', $idParent)
            ;
        }

        return $qb;
    }
}
