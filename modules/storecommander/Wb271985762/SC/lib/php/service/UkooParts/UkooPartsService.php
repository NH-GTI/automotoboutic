<?php

if ((defined('SC_UkooParts_ACTIVE') && SC_UkooParts_ACTIVE == 1) && SCI::moduleIsInstalled('ukooparts')) {
    if (!defined('UKOOPARTS_PANEL_DOMAIN')) {
        define('UKOOPARTS_PANEL_DOMAIN', Configuration::get('UKOOPARTS_PANEL_DOMAIN'));
    }
    if (!defined('UKOOPARTS_PANEL_API_TOKEN')) {
        define('UKOOPARTS_PANEL_API_TOKEN', Configuration::get('UKOOPARTS_PANEL_API_TOKEN'));
    }
    if (!defined('UKOOPARTS_API_BASE_URL')) {
        define('UKOOPARTS_API_BASE_URL', 'https://' . UKOOPARTS_PANEL_DOMAIN . '/api/');
    }

    class EveryPartsTools {
        public static function CheckProductNotInEveryParts($ps_id) {
            $responseGet = self::callAPI('products/' . $ps_id, 'GET', []);
            return ($responseGet['meta']['code'] == 404);
        }

        public static function SyncProductInEveryParts($ps_id) {
            $responseGet = self::callAPI('products/forceSyncProduct/' . $ps_id, 'GET', []);
            return ($responseGet['meta']['code'] == 200);
        }

        /**
         * @param string $endpoint /* ex: 'products/forceSyncProduct/'.$ps_id
         * @param string $verb /* GET POST PUT DELETE
         * @param array $post
         * @param bool $json_decode
         * @return array $response
         */
        public static function callAPI($endpoint, $verb, $post, $json_encode = false) {
            $headers = [
                'Authorization: Bearer ' . UKOOPARTS_PANEL_API_TOKEN,
                'Content-Type: application/json',
                'Output-Format' => 'JSON'
            ];
            return json_decode(sc_file_get_contents(UKOOPARTS_API_BASE_URL . $endpoint, strtoupper($verb), $post, $headers, null, $json_encode), true);
        }
    }
}