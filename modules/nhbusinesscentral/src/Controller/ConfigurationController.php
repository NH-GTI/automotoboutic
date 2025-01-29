<?php

namespace InstanWeb\Module\NHBusinessCentral\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {
        $handlers = [
            $this->trans('Transmission Commande Business Central', 'Admin.NHBusinessCentral.Admin') => 'instanweb.module.nhbusinesscentral.businesscentralconfiguration.config.data.handler',
        ];

        $views = [];
        foreach($handlers as $title => $handler) {
            $dataHandler = $this->get($handler);

            $form = $dataHandler->getForm();
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
                if ($form->isSubmitted() && $form->isValid()) {
    
                    $errors = $dataHandler->save($form->getData());
    
                    if (empty($errors)) {
                        $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
    
                        return $this->redirectToRoute('admin_nhbusinesscentral_configuration');
                    }
    
                    $this->flashErrors($errors);
                }
            }
            $views[$title] = $form->createView();
        }
        return $this->render('@Modules/nhbusinesscentral/views/templates/admin/configuration.html.twig', ['forms' => $views]);
    }
}
