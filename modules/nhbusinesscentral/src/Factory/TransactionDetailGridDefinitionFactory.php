<?php

namespace InstanWeb\Module\NHBusinessCentral\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;
//use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\StatusColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DateTimeColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;

final class TransactionDetailGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'nhbusinesscentraltransactiondetailgrid';

    protected function getID()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Transaction Details', [], 'Modules.NHBusinessCentral.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new BulkActionColumn('bulk'))
                    ->setOptions([
                        'bulk_field' => TransactionDetail::ID_FIELD,
                    ])
            )
            ->add(
                (new DataColumn(TransactionDetail::ID_FIELD))
                    ->setName($this->trans('ID', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => TransactionDetail::ID_FIELD
                    ])
            )
            ->add(
                (new DataColumn('item'))
                    ->setName($this->trans('Item', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'item',
                ])
            )
            ->add(
                (new DataColumn('id_item'))
                    ->setName($this->trans('Item ID', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'id_item'
                ])
            )
            ->add(
                (new StatusColumn('status'))
                    ->setName($this->trans('Status', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'status',
                ])
            )
            ->add(
                (new DataColumn('comment'))
                    ->setName($this->trans('Comment', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'comment',
                ])
            )
            ->add(
                (new DateTimeColumn('date_upd'))
                    ->setName($this->trans('Last update', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'date_upd',
                        'format' => 'd/m/Y H:i:s'
                ])
            )
            ->add(
                (new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Global'))
                    ->setOptions([
                        'actions' => (new RowActionCollection())
                            ->add((new LinkRowAction('refresh'))
                                ->setIcon('refresh')
                                ->setName($this->trans('Refresh', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'admin_nhbusinesscentral_transactiondetail_refresh',
                                    'route_param_name' => 'idTransactionDetail',
                                    'route_param_field' => TransactionDetail::ID_FIELD,
                                    'clickable_row' => true
                                ]))
                            ->add((new LinkRowAction('delete'))
                                ->setIcon('delete')
                                ->setName($this->trans('Delete', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'admin_nhbusinesscentral_transactiondetail_delete',
                                    'route_param_name' => 'idTransactionDetail',
                                    'route_param_field' => TransactionDetail::ID_FIELD,
                                    //'confirm_message' => $this->trans('Supprimer la reception de cette commande ?', [], 'Modules.NHBusinessCentral.Admin')
                                ]))
                    ])
            )
        ;
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter(TransactionDetail::ID_FIELD, TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search ID', [], 'Modules.NHBusinessCentral.Admin')
                    ]
                ])
                ->setAssociatedColumn(TransactionDetail::ID_FIELD)
            )
            ->add((new Filter('item', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search Item', [], 'Modules.NHBusinessCentral.Admin')
                    ]
                ])
                ->setAssociatedColumn('item')
            )
            ->add((new Filter('id_item', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search Item ID', [], 'Modules.NHBusinessCentral.Admin')
                    ]
                ])
                ->setAssociatedColumn('id_item')
            )            
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setAssociatedColumn('actions')
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID
                    ],
                    'redirect_route' => 'admin_nhbusinesscentral_transactiondetail_grid'
                ])
            )
        ;
    }

    protected function getBulkActions(): BulkActionCollectionInterface
    {
        return (new BulkActionCollection())
            ->add(
                (new SubmitBulkAction('refresh_selection'))
                    ->setName($this->trans('Refresh the selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_nhbusinesscentral_transactiondetail_bulkrefresh',
                    ]
                    ))
            ->add(
                (new SubmitBulkAction('delete_selection'))
                    ->setName($this->trans('Delete the selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_nhbusinesscentral_transactiondetail_bulkdelete',
                    ])
            ) 
        ;
    }    
}
