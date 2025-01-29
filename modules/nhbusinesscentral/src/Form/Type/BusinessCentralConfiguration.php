<?php

namespace InstanWeb\Module\NHBusinessCentral\Form\Type;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use InstanWeb\Module\NHBusinessCentral\Adapter\Config\BusinessCentralConfiguration as Config;

class BusinessCentralConfiguration extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Config::BASE_URI, TextType::class, [
                'label' => $this->trans('API Url', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::SALES_WS, TextType::class, [
                'label' => $this->trans('Sales WS', 'Modules.NHBusinessCentral.Admin'),
            ])            
            ->add(Config::TENANT_ID, TextType::class, [
                'label' => $this->trans('Tenant ID', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::CLIENT_ID, TextType::class, [
                'label' => $this->trans('Client ID', 'Modules.NHBusinessCentral.Admin'),
                'required' => false,
            ])
            ->add(Config::COMPANY_ID, TextType::class, [
                'label' => $this->trans('Company ID', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::CLIENT_SECRET, TextType::class, [
                'label' => $this->trans('Client secret', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::SCOPE, TextType::class, [
                'label' => $this->trans('Scope', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::ENVIRONMENT, TextType::class, [
                'label' => $this->trans('Environment', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::VERSION, TextType::class, [
                'label' => $this->trans('Version', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::TOKEN_URL, TextType::class, [
                'label' => $this->trans('Token Url', 'Modules.NHBusinessCentral.Admin'),
            ])
            ->add(Config::MAIL_NOTIFICATION, TextType::class, [
                'label' => $this->trans('Address mail for notification', 'Modules.NHBusinessCentral.Admin'),
                'required' => false,
            ]) 
        ;
    }
}
