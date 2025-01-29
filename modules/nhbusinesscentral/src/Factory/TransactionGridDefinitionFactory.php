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
use InstanWeb\Module\NHBusinessCentral\Model\Transaction;

final class TransactionGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'nhbusinesscentraltransactiongrid';

    protected function getID()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Transactions', [], 'Modules.NHBusinessCentral.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            /*->add(
                (new BulkActionColumn('bulk'))
                    ->setOptions([
                        'bulk_field' => Transaction::ID_FIELD,
                    ])
            )*/
            ->add(
                (new DataColumn(Transaction::ID_FIELD))
                    ->setName($this->trans('ID', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => Transaction::ID_FIELD
                    ])
            )
            ->add(
                (new DataColumn('transaction_label'))
                    ->setName($this->trans('Transaction', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'transaction_label'
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
                (new DataColumn('phase_label'))
                    ->setName($this->trans('Phase', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'phase_label'
                    ])
            )
            ->add(
                (new DataColumn('item_count'))
                    ->setName($this->trans('Item count', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'item_count',
                ])
            )
            ->add(
                (new DataColumn('item_success_count'))
                    ->setName($this->trans('Item Success Count', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'item_success_count'
                ])
            )
            ->add(
                (new DataColumn('process'))
                    ->setName($this->trans('Item Remain Count', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'process'
                ])
            )
            ->add(
                (new DataColumn('comment'))
                    ->setName($this->trans('Comment', [], 'Modules.NHBusinessCentral.Admin'))
                    ->setOptions([
                        'field' => 'comment'
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
                            ->add((new LinkRowAction('transaction_detail'))
                                ->setIcon('zoom_in')
                                ->setName($this->trans('Details', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'admin_nhbusinesscentral_transaction_detail',
                                    'route_param_name' => 'idTransaction',
                                    'route_param_field' => Transaction::ID_FIELD,
                                    'clickable_row' => true
                                ]))
                            ->add((new LinkRowAction('transaction_process'))
                                ->setIcon('refresh')
                                ->setName($this->trans('Process', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'admin_nhbusinesscentral_transaction_process',
                                    'route_param_name' => 'idTransaction',
                                    'route_param_field' => Transaction::ID_FIELD,
                                ]))
                            /*->add((new LinkRowAction('delete'))
                                ->setIcon('delete')
                                ->setName($this->trans('Delete', [], 'Admin.Actions'))
                                ->setOptions([
                                    'route' => 'admin_nhbusinesscentral_transaction_delete',
                                    'route_param_name' => 'idTransaction',
                                    'route_param_field' => Transaction::ID_FIELD,
                                    //'confirm_message' => $this->trans('Supprimer la transmission de cette commande ?', [], 'Modules.NHBusinessCentral.Admin')
                                ]))*/
                            
                    ])
            )
        ;
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter(Transaction::ID_FIELD, TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search ID', [], 'Modules.NHBusinessCentral.Admin')
                    ]
                ])
                ->setAssociatedColumn(Transaction::ID_FIELD)
            )
            ->add((new Filter('transaction_label', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search Transaction', [], 'Modules.NHBusinessCentral.Admin')
                    ]
                ])
                ->setAssociatedColumn('transaction_label')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setAssociatedColumn('actions')
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID
                    ],
                    'redirect_route' => 'admin_nhbusinesscentral_transaction_grid'
                ])
            )
        ;
    }

    protected function getBulkActions(): BulkActionCollectionInterface
    {
        return (new BulkActionCollection())
            /*->add(
                (new SubmitBulkAction('delete_selection'))
                    ->setName($this->trans('Delete the selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_nhbusinesscentral_transaction_bulkdelete',
                    ])
            )*/
            /*->add(
                (new SubmitBulkAction('receive_selection'))
                    ->setName($this->trans('Recept the selection', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_nhbusinesscentral_receive_bulkreceive',
                    ])
            
        )*/;
    }    
}
