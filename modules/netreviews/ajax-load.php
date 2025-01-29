<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2024 NetReviews SAS
 * @license   NetReviews
 *
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

if ($_POST) {
    require_once dirname(__FILE__) . '/../../config/config.inc.php';
    include_once dirname(__FILE__) . '/../../init.php';
    require_once dirname(__FILE__) . '/netreviewsModel.php';
    $allMultishopsReviews = false;
    $allLanguagesReviews = false;
    $Module_attr = Module::getInstanceByName('netreviews');
    $nrModel = new netreviewsModel();
    $productId = Tools::getValue('id_product');

    if (Tools::getValue('nom_group')) {
        if (false !== strpos(Tools::getValue('nom_group'), '_')) {
            // if find "_"
            $groupName = Tools::getValue('nom_group');
        } else {
            $groupName = '_' . Tools::getValue('nom_group');
        }
    } else {
        $groupName = null;
    }
    $shopId = (Tools::getValue('id_shop')) ?: null;
    $shopName = Configuration::get('PS_SHOP_NAME');
    $avisverifiesNbReviews = Configuration::get(
        'AV_NBOFREVIEWS',
        null,
        null,
        $shopId
    );
    $filterOption = Tools::getValue('filter_option');
    $currentPage = Tools::getValue('current_page');
    $currentOptionFilter = Tools::getValue('current_option_filter');
    $reviewsMaxPages = Tools::getValue('reviews_max_pages');
    $sortbynote = Tools::getValue('sortbynote');
    $localIdWebsite = Configuration::get(
        'AV_IDWEBSITE' . $groupName,
        null,
        null,
        $shopId
    );
    $localSecureKey = Configuration::get(
        'AV_CLESECRETE' . $groupName,
        null,
        null,
        $shopId
    );
    $hidehelpful = Configuration::get(
        'AV_HELPFULHIDE',
        null,
        null,
        $shopId
    ) ? 1 : 0;
    // 0 or null in defaut
    $hidemedia = Configuration::get(
        'AV_MEDIAHIDE',
        null,
        null,
        $shopId
    ) ? 1 : 0;
    // 0 or null in defaut
    $getMaxReviews = [];
    $reviews = [];
    $reviewsList = []; // Create array with all reviews data
    $myReview = [];
    $noteRange = [1, 2, 3, 4, 5];
    $filterByNote = false;

    $context = Context::getContext();
    $langId = (isset($context->language->id)
        && !empty($context->language->id)) ?
    $context->language->id : 1;
    $urlPage = netreviewsModel::getUrlProduct($productId, $langId);
    $avSpActive = Configuration::get(
        'AV_DISPLAYSNIPPETSITE',
        null,
        null,
        $shopId
    );
    $avSpP = Configuration::get(
        'AV_DISPLAYSNIPPETPRODUIT',
        null,
        null,
        $shopId
    );
    if ('1' == $avSpActive && ('4' == $avSpP || '5' == $avSpP)) {
        $snippets_active = true;
    }
    $rsChoice = Configuration::get(
        'AV_DISPLAYSNIPPETSITEGLOBAL',
        null,
        null,
        $shopId
    );

    $selectFilter = ['horodate_DESC', 'horodate_ASC', 'rate_DESC',
        'rate_ASC', 'helpfulrating_DESC', 'more', ];

    if (in_array($sortbynote, $noteRange)) {
        $filterByNote = true;
        $getMaxReviews = $nrModel->getProductReviews(
            $productId,
            $groupName,
            $shopId,
            $avisverifiesNbReviews,
            $currentPage,
            $currentOptionFilter,
            $sortbynote,
            true
        );
        $maxReviews = $getMaxReviews[0]['nbreviews'];
        $reviewsMaxPages = floor($maxReviews / $avisverifiesNbReviews) +
        ($maxReviews % $avisverifiesNbReviews > 0 ? 1 : 0);
    }

    if (in_array($filterOption, $selectFilter) || true === $filterByNote) {
        $reviews = $nrModel->getProductReviews(
            $productId,
            $groupName,
            $shopId,
            $avisverifiesNbReviews,
            $currentPage,
            $currentOptionFilter,
            $sortbynote,
            false
        );
        foreach ($reviews as $review) {
            // Create variable for template engine
            $myReview['ref_produit'] = $review['ref_product'];
            $myReview['id_product_av'] = $review['id_product_av'];
            $myReview['sign'] = sha1($localIdWebsite . $review['id_product_av'] . $localSecureKey);
            $myReview['helpful'] = $review['helpful'];
            $myReview['helpless'] = $review['helpless'];
            $myReview['rate'] = $review['rate'];
            $myReview['rate_percent'] = $review['rate'] * 20;
            $myReview['avis'] = html_entity_decode(urldecode($review['review']));
            // review date
            if ('10' == Tools::strlen($review['horodate'])) {
                $date = new DateTime();
                $date->setTimestamp($review['horodate']);
                $myReview['horodate'] = $date->format('d/m/Y');
            } else {
                $myReview['horodate'] = date('d/m/Y', strtotime($review['horodate']));
            }
            // order date
            if (isset($review['horodate_order']) && !empty($review['horodate_order'])) {
                $review['horodate_order'] = str_replace('"', '', $review['horodate_order']);
                $myReview['horodate_order'] = date('d/m/Y', strtotime($review['horodate_order']));
            } else {
                $myReview['horodate_order'] = $myReview['horodate'];
            }
            // in case imported reviews which have lack of this info
            if (!isset($review['horodate']) || empty($review['horodate'])) {
                $myReview['horodate'] = $myReview['horodate_order'];
            }

            $myReview['discussion'] = [];
            // renverser le nom et le prénom
            $customer_name = explode(' ', urldecode($review['customer_name']));
            $customer_name = array_values(array_filter($customer_name));
            $customer_name = array_diff($customer_name, ['.']);
            $myReview['customer_name_anonymous'] = ('Anonymous' == $customer_name[1]) ? true : false;
            $customer_name = array_reverse($customer_name);
            $customer_name = implode(' ', $customer_name);

            $myReview['customer_name'] = $customer_name;
            $unserialized_discussion = netreviewsModel::avJsonDecode(
                netreviewsModel::acDecodeBase64($review['discussion'])
            );
            if ($unserialized_discussion) {
                foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
                    $each_discussion = (array) $each_discussion;
                    $myReview['discussion'][$k_discussion] = [];
                    if ('10' == Tools::strlen($each_discussion['horodate'])) {
                        $date = new DateTime();
                        $date->setTimestamp($each_discussion['horodate']);
                        $myReview['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y');
                    } else {
                        $myReview['discussion'][$k_discussion]['horodate'] = date(
                            'd/m/Y',
                            strtotime($each_discussion['horodate'])
                        );
                    }
                    $myReview['discussion'][$k_discussion]['commentaire'] = urldecode($each_discussion['commentaire']);
                    if ('ecommercant' == $each_discussion['origine']) {
                        $myReview['discussion'][$k_discussion]['origine'] = $shopName;
                    } elseif ('internaute' == $each_discussion['origine']) {
                        $myReview['discussion'][$k_discussion]['origine'] = $myReview['customer_name'];
                    } else {
                        $myReview['discussion'][$k_discussion]['origine'] = $Module_attr->l('Moderator');
                    }
                }
            }
            // Media infos
            $myReview['media_content'] = [];
            if (isset($review['media_full'])) {
                $review_images_result = (array) netreviewsModel::avJsonDecode(
                    html_entity_decode($review['media_full'])
                );
                if (count($review_images_result) >= 1) {
                    foreach ($review_images_result as $k_media => $each_media) {
                        $myReview['media_content'][$k_media] = (array) $each_media;
                    }
                }
            }
            array_push($reviewsList, $myReview);
        }

        $av_ajax_translation = [];
        $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $shopId);
        if (version_compare(_PS_VERSION_, '1.4', '>=') && '1' != $use_star_format_image) {
            $stars_file = 'avisverifies-stars-font.tpl';
            $old_lang = false;
        } else {
            $stars_file = 'avisverifies-stars-image.tpl';
            $old_lang = true;
        }
        $av_template = Configuration::get('AV_TEMPLATE', null, null, $shopId);
        if ($av_template && '2' == $av_template) {
            $ajax_dir = netreviewsModel::tplFileExist('sub/ajax-load-tab-content-design-new.tpl');
            $design_template = 'avisverifies-tab-content-design-new.tpl';
        } else {
            $ajax_dir = netreviewsModel::tplFileExist('ajax-load-tab-content.tpl');
            $design_template = 'avisverifies-tab-content-design-classic.tpl';
        }

        $stars_dir = netreviewsModel::tplFileExist('sub/' . $stars_file);
        $design_dir = netreviewsModel::tplFileExist('sub/' . $design_template);
        $av_ajax_translation['a'] = $Module_attr->l('published');
        $av_ajax_translation['b'] = $Module_attr->l('the');
        $av_ajax_translation['c'] = $Module_attr->l('following an order made on');
        $av_ajax_translation['d'] = $Module_attr->l('Comment from');
        $av_ajax_translation['e'] = $Module_attr->l('Show exchanges');
        $av_ajax_translation['f'] = $Module_attr->l('Hide exchanges');
        $av_ajax_translation['g'] = $Module_attr->l('Did you find this helpful?');
        $av_ajax_translation['h'] = $Module_attr->l('Yes');
        $av_ajax_translation['i'] = $Module_attr->l('No');
        $av_ajax_translation['j'] = $Module_attr->l('More reviews...');
        $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $shopId)) ?
        Configuration::get('AV_STARCOLOR', null, null, $shopId) : 'FFCD00'; // default #FFCD00

        $smarty->assign([
            'modules_dir' => _MODULE_DIR_,
            'stars_dir' => $stars_dir,
            'design_dir' => $design_dir,
            'hidehelpful' => $hidehelpful,
            'hidemedia' => $hidemedia,
            'reviews' => $reviewsList,
            'current_page' => $currentPage,
            'reviews_max_pages' => $reviewsMaxPages,
            'old_lang' => $old_lang, // old version language variable translations
            'customized_star_color' => $customized_star_color,
            'av_ajax_translation' => $av_ajax_translation,
            'snippets_active' => !empty($snippets_active) ? $snippets_active : false,
            'rs_choice' => !empty($rsChoice) ? $rsChoice : false,
            'product_id' => $productId,
            'product_url' => !empty($urlPage) ? $urlPage : false,
            'enable_rich_snippets' => !empty($avSpActive) ? $avSpActive : false,
        ]);
        echo $smarty->fetch($ajax_dir);
    }
} else {
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    Tools::redirect('Location: ../');
    exit;
}
