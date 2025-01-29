<?php
if (!defined('STORE_COMMANDER')) { exit; }
// CHECK AUTHENTICATION ON SERVER SIDE #######################################################################

$unique_key = getScSessionItemValue('early_access', 'scc');

$response=makeDefaultCallToOurApi("scc/auth_ea.php", array('unique-key' => $unique_key), array( 'url' => _PS_BASE_URL_SSL_));

echo json_encode($response);
?>
