<?php

namespace InstanWeb\Module\NHBusinessCentral\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use InstanWeb\Module\NHBusinessCentral\Factory\TransactionGridDefinitionFactory;
use InstanWeb\Module\NHBusinessCentral\Factory\TransactionDetailGridDefinitionFactory;
use InstanWeb\Module\NHBusinessCentral\Filter\TransactionGridFilter;
use Symfony\Component\HttpFoundation\Request;
use PrestaShop\PrestaShop\Core\Exception\DatabaseException;

class TransactionGridController extends FrameworkBundleAdminController
{
    public function index(TransactionGridFilter $filter)
    {
        $gridFactory = $this->get('instanweb.module.nhbusinesscentral.transaction.grid.factory');
        $grid = $gridFactory->getGrid($filter);

        return $this->render('@Modules/nhbusinesscentral/views/templates/admin/transactiongrid.html.twig', [
            'transactiongrid' => $this->presentGrid($grid),
        ]);
    }

    public function showDetail(Request $request)
    {
        $request->getSession()->set('admin_nhbusinesscentral_transactiondetail_grid_parent_id', $request->attributes->get('idTransmission'));
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('InstanWeb\Module\NHBusinessCentral\Factory\TransactionDetailGridDefinitionFactory'),
            $request,
            TransactionDetailGridDefinitionFactory::GRID_ID,
            'admin_nhbusinesscentral_transactiondetail_grid'
        );
    }

    public function searchAction(Request $request)
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('InstanWeb\Module\NHBusinessCentral\Factory\TransactionGridDefinitionFactory'),
            $request,
            TransactionGridDefinitionFactory::GRID_ID,
            'admin_nhbusinesscentral_transaction_grid'
        );
    }

    public function deleteAction($idReception)
    {
        $errors = $this->deleteItems([$idReception]);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transaction_grid');
    }

    public function bulkDeleteAction(Request $request)
    {
        $idTransactionList = $this->getBulkStatesFromRequest($request);

        $errors = $this->deleteItems($idTransactionList);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful deletion.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transaction_grid');
    }

    public function processAction($idTransaction)
    {
        $errors = $this->transactionItems([$idTransaction]);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Successful process.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_transaction_grid');
    }

    /*public function bulkReceptionAction(Request $request)
    {
        $idReceptionList = $this->getBulkStatesFromRequest($request);

        $errors = $this->receptionItems($idReceiveList);

        if (0 === count($errors)) {
            $this->addFlash('success', $this->trans('Transmission réussie.', 'Admin.Notifications.Success'));
        } else {
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_nhbusinesscentral_reception_grid');
    }*/

    private function transactionItems($idTransactionList)
    {
        $errors  = [];
        $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');

        foreach($idTransactionList as $idTransaction) {

            $transaction = $repository->getById($idTransaction);

            $transactionName = strtolower($transaction->transaction);

            $process = $this->get("instanweb.module.nhbusinesscentral.transaction.$transactionName");

            if ($process->setIdTransaction($idTransaction)->process() == false) {
                $errors[] = [
                    'key' => 'Reception Error # %d',
                    'domain' => 'Admin.Catalog.Notification',
                    'parameters' => [$idTransaction],
                ];
            }
       }
       
       return $errors;
    }

    private function deleteItems($idTransactionList)
    {
        $errors = [];

        $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');

        foreach($idTransactionList as $id) {
            $errors = [];
            try {
                $repository->delete($id);
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

    private function getBulkStatesFromRequest(Request $request): array
    {
        $stateIds = $request->request->get('nhbusinesscentraltransactiongrid_bulk');

        if (!is_array($stateIds)) {
            return [];
        }

        foreach ($stateIds as $i => $stateId) {
            $stateIds[$i] = (int) $stateId;
        }

        return $stateIds;
    }
}
