<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

try
{
    $shippingboService = Shippingbo::getInstance();

    $langDir = UISettings::getSetting('forceSCLangIso')?:Language::getIsoById(Tools::getValue('id_lang'));

    switch ($langDir) {
        case "fr" :
	    case "es" :
            $langFolder = $langDir;
		    break;
	    default :
		    $langFolder = "en";
		    break;
    }

    ob_start();
	include __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'intro.html.php';
	$intro = ob_get_clean();

	ob_start();
	include __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $langFolder . DIRECTORY_SEPARATOR . 'steps.html.php';
	$fs_steps = ob_get_clean();
}
catch (Exception $e)
{
    $shippingboService->sendResponse($e->getMessage());
    exit;
}
?>


<div class="html_content first_screen">
    <div id="firstStartLogo">
    <h2>
        <img class="shippingbo" src="lib/img/shippingbo/logo-shippingbo-gray-blue.svg" alt="<?php echo _l('Shippingbo'); ?>">
        <img class="sync" src="lib/img/sync.svg" alt="<?php echo _l('Sync'); ?>">
        <img  class="prestashop" src="lib/img/ps-logo-black.svg" alt="<?php echo _l('Prestashop'); ?>"></h2>
    </div>

    <?php if(!$shippingboService->checkRequirements()){?>
        <div class="message danger short">
            <ul>
                <?php foreach($shippingboService->getRequirementMessages() as $message) { ?>
                <li><?php echo $message; ?></li>
                <?php } ?>
            </ul>

        </div>
    <?php } else { ?>

    <div id="firstStartBegin">
        <?php echo $intro; ?>

        <div class="message warning short">
            <?php echo _l('Please note that custom developments or specific modules for managing packs are not compatible with this connector.'); ?>
        </div>

        <ul class="actions">
            <li>
                <button id="first-start-go"><?php echo _l('Start now!'); ?></button>
            </li>
            <li class="">
                <a class="dhxform_btn_txt" href="#" onclick="openFixMyPsWindow('prestashop_shippingbo');"><?php echo _l('Check my catalogue before starting'); ?></a>
            </li>
        </ul>


    </div>


    <div id="firstStartSteps">
	    <?php echo $fs_steps; ?>
    </div>

    <?php } ?>
</div>

<script>

    // SCRIPT CAROUSSEL
    const items = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;

    function showItem(index) {
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === index);
        });
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % items.length;
        showItem(currentIndex);

        document.getElementById('prevBtn').removeAttribute('disabled');
        document.getElementById('prevBtn').style.visibility = 'visible';
        if (currentIndex === 2) {
            document.getElementById('nextBtn').setAttribute('disabled', 'true');
            document.getElementById('nextBtn').classList.add('hide')
            document.getElementById('firstStartSetup').classList.remove('hide');
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        showItem(currentIndex);

        document.getElementById('nextBtn').removeAttribute('disabled');
        if (currentIndex === 0) {
            document.getElementById('prevBtn').style.visibility = 'hidden';
            document.getElementById('prevBtn').setAttribute('disabled', null);
        }
        if (currentIndex < 2) {
            document.getElementById('nextBtn').removeAttribute('disabled');
            document.getElementById('nextBtn').classList.remove('hide');
            document.getElementById('firstStartSetup').classList.add('hide');
        }
    });

    showItem(currentIndex);

    // BUTTON START STEPS
    document.getElementById('first-start-go').addEventListener('click', function(){
        document.getElementById('firstStartBegin').classList.add('hide');
        document.getElementById('firstStartSteps').classList.remove('hide');
    });

    // BUTTON CONFIGURE CONNEXION
    document.getElementById('firstStartSetup').addEventListener('click', () => {
        document.getElementById('firstStartLogo').classList.add('hide');
        document.getElementById('firstStartSteps').classList.add('hide');
        //wSboTabBar.tabs('first-start').close();
        wSboTabClick('initial-setup', undefined);
    });

    // INIT
    function initFirstStart(){
        document.getElementById('prevBtn').style.visibility = 'hidden';
        document.getElementById('firstStartSteps').classList.add('hide');
        document.getElementById('firstStartSetup').classList.add('hide');
    }

    initFirstStart();

</script>





