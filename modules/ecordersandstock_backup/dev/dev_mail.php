<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . '/../../../config/config.inc.php';

echo dirname(__FILE__) . '/../mails/';

echo "\n<br>";

echo file_exists(dirname(__FILE__) . '/../mails/fr/commandes.html');

Mail::Send(
    (int) Configuration::get('PS_LANG_DEFAULT'),                // langue
    'commandes',                                                    // nom du fichier template SANS L'EXTENSION
    Mail::l(
        'Commandes à intégrer',                                 // sujet à traduire dans les langues du module
        (int) Configuration::get('PS_LANG_DEFAULT')
    ),
    array(                                                      // templatevars personnelles
        '{date}' => date('d/m/Y à H:i:s'),
        '{expe}' => 'moi'
    ),
    'm.lachere@lemon-interactive.fr',                                                    // destinataire mail
    null,                                                       // destinataire nom
    Configuration::get('PS_SHOP_EMAIL'),                        // expéditeur
    Configuration::get('PS_SHOP_NAME'),                         // expéditeur nom
/*    array(                                                      // fichier joint
        'content' => Tools::file_get_contents($output_dir . $output_name),
        'mime' => 'application/octet-stream',
        'name' => $output_name
    ),*/
    null,
    null,                                                       // Choix SMTP, non traité par le coeur < PS 1.4.6.1
    dirname(__FILE__) . '/../mails/'                                 // répertoire des mails templates
);