<?php
if (!defined('STORE_COMMANDER'))
{
	exit;
}

use Sc\Service\Shippingbo\Shippingbo;

try
{
	$shippingboService = Shippingbo::getInstance();
	$langDir = Language::getIsoById(Tools::getValue('id_lang'));

}
catch (Exception $e)
{
	$shippingboService->sendResponse($e->getMessage());
}
?>

<div class="html_content">
    <div id="firstStartForm">
		<?php include __DIR__.'/../forms/cat_win-sbo_forms_initial_setup_form.html.php'; ?>
    </div>
</div>





