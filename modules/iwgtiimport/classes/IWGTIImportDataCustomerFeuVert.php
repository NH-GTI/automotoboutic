<?php
/**
* IW 2021
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWGTIImportData.php';
use PrestaShop\PrestaShop\Adapter\ServiceLocator;
use PrestaShop\PrestaShop\Adapter\CoreException;

class IWGTIImportDataCustomerFeuVert extends IWGTIImportData
{
    public function __construct($db, $export_type)
    {
        parent::__construct($db, $export_type);

        try {
            /** @var \PrestaShop\PrestaShop\Core\Crypto\Hashing $crypto */
            $this->crypto = ServiceLocator::get('\\PrestaShop\\PrestaShop\\Core\\Crypto\\Hashing');
        } catch (CoreException $e) {
        }
    }

    public function beforeImport()
    {
        $this->config_items = [];
        $headers = $this->db->getAllHeaders();
        foreach($headers as $id => $h) {
            $name = $h['name'];
            $this->config_items[] = [
                'data' => 'customer',
                'key' => 'id_customer',
                'header' => $name,
                'fields' => [
                    'id_customer' => 'Nr Magasin',
                    'id_gender' => '::1',
                    'id_lang' => '::1',
                    'id_default_group' => '::3',
                    'active' => '::1',
                    'company' => 'EAN',
                    'siret' => 'SIRET',
                    'firstname' => 'Prénom',
                    'lastname' => 'Nom',
                    'email' => 'Mail',
                    'passwd' => 'getCryptoPasswd',
                    'secure_key' => 'getSecureKey',
                    'date_add' => 'getCurrentDate',
                    'date_upd' => 'getCurrentDate',
                    'reset_password_validity' => '::0000-00-00 00:00:00',
                    'newsletter_date_add' => '::0000-00-00 00:00:00',
                    'birthday' => '::0000-00-00',
                ],
            ];
            $this->config_items[] = [
                'data' => 'customer_group',
                'key' => 'id_customer,id_group',
                'header' => $name,
                'fields' => [
                    'id_customer' => 'Nr Magasin',
                    'id_group' => '::3',
                ],
            ];
            // adresse de livraison
            $this->config_items[] = [
                'data' => 'address',
                'key' => 'id_address',
                'header' => $name,
                'fields' => [
                    'id_address' => 'Nr Magasin',
                    'id_country' => 'getCountry',
                    'id_state' => '::0',
                    'id_customer' => 'Nr Magasin',
                    'alias' => '::Livraison',
                    'lastname' => 'Adresse nom',
                    'firstname' => 'Adresse prénom',
                    'address1' => 'Adresse 1',
                    'address2' => 'Adresse 2',
                    'postcode' => 'Code Postal',
                    'city' => 'Ville',
                    'active' => '::1',
                    'phone' => 'Téléphone',
                    'date_add' => 'getCurrentDate',
                    'date_upd' => 'getCurrentDate',
                    'company' => 'Prénom',
                    'other' => '::',
                    'phone_mobile' => '::',
                    'vat_number' => 'Numéro de TVA',
                    'dni' => '::'
                ],
            ];
            // adresse de facturation
            $this->config_items[] = [
                'data' => 'address',
                'key' => 'id_address',
                'header' => $name,
                'fields' => [
                    'id_address' => 'getInvoiceAddressId',
                    'id_country' => 'getCountry',
                    'id_state' => '::0',
                    'id_customer' => 'Nr Magasin',
                    'alias' => '::Facturation',
                    'lastname' => 'Adresse facturation nom',
                    'firstname' => 'Adresse facturation prénom',
                    'address1' => 'Adresse 1 facturation',
                    'address2' => 'Adresse 2 facturation',
                    'postcode' => 'Code Postal facturation',
                    'city' => 'Ville facturation',
                    'active' => '::1',
                    'phone' => 'Téléphone',
                    'date_add' => 'getCurrentDate',
                    'date_upd' => 'getCurrentDate',
                    'company' => 'Société facturation',
                    'other' => '::',
                    'phone_mobile' => '::',
                    'vat_number' => 'Numéro de TVA',
                    'dni' => '::'
                ],  
            ];
        }
    }

    public function getCurrentDate()
    {
        return date('Y-m-d h:i:s');
    }

    public function getSecureKey()
    {
        return md5(uniqid(rand(), true));
    }

    public function getCryptoPasswd()
    {
        $passwd = $this->values[$this->name]['Mot de passe'];
        return isset($this->crypto) ? $this->crypto->hash($passwd) : $passwd;
    }

    public function getCountry()
    {
        return Db::getInstance()->getValue('SELECT id_country FROM '._DB_PREFIX_.'country WHERE iso_code = "' . $this->values[$this->name]['Code Pays'] . '"');
    }

    public function getInvoiceAddressId()
    {
        return 900000 + (int)$this->values[$this->name]['Nr Magasin'];
    }
}
