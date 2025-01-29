{**
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
 * @version   Release: $Revision: 8.1.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 *}
<section class="{if $version_ps < 1.7} tab-pane tab_media{/if} {if $nrResponsive == '1'}nrResponsive{/if}"  id="netreviews_reviews_tab">
    <div id="netreviews_rating_section" data-group-name="{$nom_group|escape:'htmlall':'UTF-8'}"  data-url-ajax="{$modules_dir|escape:'htmlall':'UTF-8'}" data-idshop="{$id_shop|escape:'htmlall':'UTF-8'}" data-productid="{$product_id|escape:'htmlall':'UTF-8'}" data-current-page="1" data-current-option="horodate_DESC"  data-sortbynote="0" data-max-page="{$reviews_max_pages|escape:'htmlall':'UTF-8'}">
      <input type="hidden" value="{$av_idwebsite|escape:'htmlall':'UTF-8'}" id="av_idwebsite"/>
      <input type="hidden" value="{$avHelpfulURL|escape:'htmlall':'UTF-8'}" id="avHelpfulURL"/>
      <input type="hidden" value="{l s='Thank you, your vote will be published soon.' mod='netreviews'}" id="avHelpfulSuccessMessage"/>
      <input type="hidden" value="{l s='An error has occurred.' mod='netreviews'}" id="avHelpfulErrorMessage"/>
     <!-- Start netreviews_reviews_tab -->
        <div class="netreviews_tpl_v2" id="netreviews_reviews_tab">
            <table id="netreviews_table_tab">
                <tbody>
                    <tr>
                        <!-- General rate and filter by rates -->
                        <td class="netreviews_left_column">
                            <div>
                                <div class="netreviews_logo">
                                    <img id="netreviews_logo_img" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/logo_full_{$logo_lang|escape:'htmlall':'UTF-8'}.png" alt="{l s='Verified Reviews Logo' mod='netreviews'}">
                                </div>
                            </div>
                            <div class="netreviews_rating_content">
                                <div class="netreviews_global_rating">
                                    <div class="netreviews_global_rating_responsive">
                                        <div class="netreviews_note_generale_recommendations">
                                            <div class="netreviews_note_generale_recommendations_responsive">
                                                <div class="netreviews_note_generale">
                                                    <table class="netreviews_note_generale_table">
                                                        <tbody>
                                                        <tr class="netreviews_note_generale_table_tr">
                                                            <td class="netreviews_note_generale_table_td">{$average_rate|floatval}</td>
                                                            <td class="netreviews_note_generale_table_td">|</td>
                                                            <td class="netreviews_note_generale_table_td">5</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <p class="netreviews_subtitle">{$count_reviews|escape:'htmlall':'UTF-8'} {l s='customer reviews' mod='netreviews'}<i class="far fa-user"></i></p>
                                            </div>
                                            <p class="netreviews_subtitle">
                                                <b>{$percentageRecommendingProduct|escape:'htmlall':'UTF-8'}%</b> {l s='of people recommend this product' mod='netreviews'}.
                                            </p>
                                        </div>
                                        <div class="netreviews_resume_rates">
                                             {for $percentageRate = 1 to 5}
                                                {$star_star_rate = 6-$percentageRate}
                                                <div class="netreviews_stats_stars_big {if ! $reviews_rate_portion.$star_star_rate}netreviews_disabled_click{/if}" onclick="javascript:netreviewsFilter({$star_star_rate|escape:'htmlall':'UTF-8'})" title ="{$reviews_rate_portion.$star_star_rate|escape:'htmlall':'UTF-8'} {if $reviews_rate_portion.$star_star_rate > 1}{l s='reviews' mod='netreviews'}{else}{l s='review' mod='netreviews'}{/if}
                                                ">
                                                {if (!$use_image)}
                                                <div class="stat_star">
                                                    {for $stat_star = 1 to $star_star_rate}
                                                    <span class="nr-icon nr-star gold" style="color:#{$customized_star_color|escape:'htmlall':'UTF-8'}"></span>
                                                    {/for}
                                                </div>
                                                {else}
                                                    <div class="netreviews_image_stars">
                                                        <div class="netreviews_stars netreviews_stars_md">
                                                        <span style="width:{$reviews_rate_portion_persontage.$star_star_rate|escape:'htmlall':'UTF-8'}%;"></span>
                                                        </div>
                                                    </div>
                                                {/if}
                                                <div class="netreviews_percentage_rate">{$reviews_rate_portion_persontage.$star_star_rate|escape:'htmlall':'UTF-8'}%</div>
                                                <div class="netreviews_percentage_bar" style="background: linear-gradient(to right, rgb(173, 173, 173) {$reviews_rate_portion_persontage.$star_star_rate|escape:'htmlall':'UTF-8'}%, rgb(216, 216, 216) {$reviews_rate_portion_persontage.$star_star_rate|escape:'htmlall':'UTF-8'}%);"></div>
                                                </div>
                                             {/for}
                                        </div>
                                    </div>
                                    <div class="netreviews_afnor">
                                        <a class="netreviews_certification" target="_blank" rel="nofollow noreferrer noopener" href="{$url_certificat|escape:'htmlall':'UTF-8'}">{l s='View the trust certificate' mod='netreviews'}</a>
                                        <div>
                                            <label id="netreviews_informations_label">
                                                <span>{l s='Reviews subject to control' mod='netreviews'}</span>
                                                <span class="nr-icon nr-info"></span>
                                            </label>
                                            <div id="netreviews_informations">
                                                <div class="nr-icon nr-exit"></div>
                                            <ul>
                                              <li> {l s='For further information on the nature of the review controls, as well as the possibility of contacting the author of the review please consult our' mod='netreviews'} <a href="{l s='https://www.netreviews.com/consumers/en/transparency-charter/' mod='netreviews'}" target="_blank" rel="nofollow noreferrer noopener">{l s='Transparency Charter' mod='netreviews'}</a>.</li>
                                              <li> {l s='No inducements have been provided for these reviews' mod='netreviews'}</li>
                                              <li> {l s='Reviews are published and kept for a period of five years' mod='netreviews'}</li>
                                              <li> {l s='Reviews can not be modified: If a customer wishes to modify their review then they can do so by contacting Verified Reviews directly to remove the existing review and publish an amended one' mod='netreviews'}</li>
                                              <li> {l s='The reasons for deletion of reviews are available' mod='netreviews'} <a href="{$url_cgv|escape:'htmlall':'UTF-8'}#Rejet_de_lavis_de_consommateur" target="_blank" rel="nofollow noreferrer noopener">{l s='here' mod='netreviews'}</a>.
                                              </li>
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="netreviews_all_reviews">
                                <div onclick="javascript:netreviewsFilter('horodate_DESC')">
                                    <span class="display">{l s='All reviews' mod='netreviews'}</span>
                                    <i class="netreviews_arrow_up"></i>
                                </div>
                            </div>
                        </td>

                        <!-- Filters by and reviews -->
                        <td class="netreviews_right_column">

                            <!-- Filtering section -->
                            <div class="netreviews_rating_header">
                                <div class="netreviews_filtering_section"><label>{l s='Sort reviews by' mod='netreviews'} :
                                        <select id="netreviews_reviews_filter" name="netreviews_reviews_filter" onchange="javascript:netreviewsFilter(this.value);">
                                            <option value="horodate_DESC" selected="selected">---</option>
                                            <option value="horodate_DESC" class="sortinitial">{l s='Most recent' mod='netreviews'}</option>
                                            <option value="horodate_ASC">{l s='Oldest' mod='netreviews'}</option>
                                            <option value="rate_DESC">{l s='Best rating' mod='netreviews'}</option>
                                            <option value="rate_ASC">{l s='Worst rating' mod='netreviews'}</option>
                                            <option value="horodate_DESC" class="sortbynote">{l s='Rate' mod='netreviews'}</option>
                                             {if (!$hidehelpful)} <option value="helpfulrating_DESC">{l s='Most useful' mod='netreviews'}</option>{/if}
                                        </select></label>
                                </div>
                            </div>

                            <!-- The Modal for medias-->
                            <div id="netreviews_media_modal">
                                <div id="netreviews_media_content"></div>
                                <!-- The Close Button -->
                                <a id="netreviews_media_close">×</a>
                                <!-- Modal Content (The Image) -->
                            </div>

                            <!-- Start reviews section -->
                            <div id="netreviews_reviews_section">
                                <div id="avisVerifiesAjaxImage"></div>
                                <div id="ajax_comment_content">
                                    {include file=$ajax_dir}
                                </div>
                            </div>
                            <!-- End reviews section -->

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End netreviews_reviews_tab -->
    </div>
    <!-- OUR CODE - END -->
</section>
