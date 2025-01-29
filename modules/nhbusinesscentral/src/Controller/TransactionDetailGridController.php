<?php

namespace InstanWeb\Module\NHBusinessCentral\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use InstanWeb\Module\NHBusinessCentral\Factory\TransactionDetailGridDefinitionFactory;
use InstanWeb\Module\NHBusinessCentral\Filter\TransactionDetailGridFilter;
use Symfony\Component\HttpFoundation\Request;
use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class TransactionDetailGridController extends FrameworkBundleAdminController
{
    public function index(TransactionDetailGridFilter $filter)
    {
        $gridFactory = $this->get('instanweb.module.nhbusinesscentral.transactiondetail.grid.factory');
        $grid = $gridFactory->getGrid($filter);

        return $this->render('@Modules/nhbusinesscentral/views/templates/admin/transactiondetailgrid.html.twig', [
            'transactiondetailgrid' => $this->presentGrid($grid),
        ]);
    }

    public function searchAction(Request $request)
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('InstanWeb\Module\NHBusinessCentral\Factory\TransactionDetailGridDefinitionFactory'),
            $request,
            TransactionDetailGridDefinitionFactory::GRID_ID,
            'admin_nhbusinesscentral_transactiondetail_grid'
        );
    }

    public function deleteAction($idTransactionDetail)
    {
        $errors = $this->deleteItems([$idTransactionDetail]);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transactiondetail_grid');
    }

    public function bulkDeleteAction(Request $request)
    {
        $idList = $this->getBulkStatesFromRequest($request);

        $errors = $this->deleteItems($idList);        

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transactiondetail_grid');
    }

    public function refreshAction($idTransactionDetail)
    {
        $errors = $this->refreshItems([$idTransactionDetail]);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful refresh.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transactiondetail_grid');
    }

    public function bulkRefreshAction(Request $request)
    {
        $idList = $this->getBulkStatesFromRequest($request);

        $errors = $this->refreshItems($idList);        

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transactiondetail_grid');
    }

    private function deleteItems($idList)
    {
        $errors = [];

        $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');

        foreach($idList as $id) {
            $errors = [];
            try {
                $repository->deleteDetail($id);
            } catch (DatabaseException $e) {
                $errors[] = [
                    'key' => 'Could not delete #%i',
                    'domain' => 'Admin.Catalog.Notification',
                    'parameters' => [$id],
                ];
            }
        }

        return $errors;
    }

    private function refreshItems($idList)
    {
        $errors = [];

        if (count($idList)) {

            $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');
            
            $transactionDetail = $repository->getByIdDetail($idList[0]);

            $transaction = $repository->getById($transactionDetail->id_nhbusinesscentral_transaction);

            $transactionName = strtolower($transaction->transaction);

            $process = $this->get("instanweb.module.nhbusinesscentral.transaction.$transactionName");

            if ($process->setIdTransaction($transaction->id)->setDetailsIds($idList)->process() == false) {
                $errors[] = [
                    'key' => 'Transaction Detail Error',
                    'domain' => 'Admin.Catalog.Notification',
                    'parameters' => []
                ];
            }
        }

        return $errors;
    }

    private function getBulkStatesFromRequest(Request $request): array
    {
        $stateIds = $request->request->get('nhbusinesscentraltransactiondetailgrid_bulk');

        if (!is_array($stateIds)) {
            return [];
        }

        foreach ($stateIds as $i => $stateId) {
            $stateIds[$i] = (int) $stateId;
        }

        return $stateIds;
    }
}
