<?php

namespace InstanWeb\Module\NHBusinessCentral\Adapter\Notification;

use PrestaShopLogger;
use Language;
use Exception;
use Mail;

class Email
{
    const LOG_SEVERITY_LEVEL = 3;
    const MAIL_TEMPLATE_NAME = 'orderbc_error';

    public function notify($addressMail, $idOrder)
    {    
        $idLang = (int)\Context::getContext()->language->id;
        $idShop = (int)\Context::getContext()->shop->id;

        $templateVars = [
            '{idOrder}' => $idOrder,
        ];

        $iso = Language::getIsoById($idLang);
        $mailsPath =  _PS_MODULE_DIR_ . 'NHBusinessCentral' . '/mails/';

        if (file_exists($mailsPath. $iso . '/'.self::MAIL_TEMPLATE_NAME.'.txt') &&
            file_exists($mailsPath . $iso . '/'.self::MAIL_TEMPLATE_NAME.'.html')
        ) {
            try {
                $sendStatus = Mail::Send(
                    $idLang,
                    self::MAIL_TEMPLATE_NAME,
                    'Erreur transmission Order BC',
                    $templateVars,
                    (string) $addressMail,
                    null,
                    (string) \Configuration::get('PS_SHOP_EMAIL'),
                    (string) \configuration::get('PS_SHOP_NAME'),
                    null,
                    null,
                    $mailsPath,
                    false,
                    $idShop
                );

                if ($sendStatus) {
                    return true;
                } else {
                    PrestaShopLogger::addLog(
                        sprintf(
                            'Order BC Error : Could not send email to address [%s]',
                            $addressMail
                        ),
                        self::LOG_SEVERITY_LEVEL
                    );
                }
            } catch (Exception $e) {
                PrestaShopLogger::addLog(
                    sprintf(
                        'Order BC Error : Could not send email to address [%s] because %s',
                        $addressMail,
                        $e->getMessage()
                    ),
                    self::LOG_SEVERITY_LEVEL
                );
            }
        }

        return false;
    }
}
