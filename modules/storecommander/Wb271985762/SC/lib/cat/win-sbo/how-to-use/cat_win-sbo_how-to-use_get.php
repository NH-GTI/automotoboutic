<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$langDir = Language::getIsoById(Tools::getValue('id_lang'));
$contentHowTo = makeCallToOurApi('content/blog/', [], ['iso_code' => 'fr', 'page_name' => 'sbo_how_to']);

// open links in new tab
$re = "/(<a\\b[^<>]*href=['\"]?http[^<>]+)>/is";
$subst = '$1 target="_blank">';
$contentHowTo = preg_replace($re, $subst, stripslashes($contentHowTo['content']));

$contentHowTo = str_replace('src="/img', 'src="https://www.storecommander.com/img', $contentHowTo);


$contentFaq = makeCallToOurApi('content/blog/', [], ['iso_code' => 'fr', 'page_name' => 'sbo_faq']);

// open links in new tab
$contentFaq = preg_replace($re, $subst, stripslashes($contentFaq['content']));
$contentFaq = str_replace('src="/img', 'src="https://www.storecommander.com/img', $contentFaq);

?>

<div class="html_content fullpage">
    <h2><?php echo _l('How does it work?', 1); ?></h2>
    <?php echo utf8_decode($contentHowTo); ?>

    <h2><?php echo _l('FAQ', 1); ?></h2>
    <?php echo utf8_decode($contentFaq); ?>
</div>





