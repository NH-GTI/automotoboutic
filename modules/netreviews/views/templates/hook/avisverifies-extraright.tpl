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
{assign var="av_star_type" value="widget"}


{if ($snippets_type == "1")}
   {include file=$rich_snippets_microdata} <!-- file with microdata product and reviews tags -->
{elseif ($snippets_type == "2")}
   {include file=$rich_snippets_json} <!-- file with scripts for product and/or aggregateRating and reviews tags in json -->
{/if}


{if ($widgetlight eq '1')}
   <div class="netreviews_stars_light">
      <a href="javascript:av_widget_click()" id="AV_button">
         <div id="top">
            <div class="netreviews_review_rate_and_stars">
               {include file=$stars_dir}
            </div>
            <div id="slide">
               <span class="reviewCount">
               {$av_nb_reviews|escape:'htmlall':'UTF-8'}
               </span>
               {if $av_nb_reviews > 1}
               {l s='reviews' mod='netreviews'}
               {else}
               {l s='review' mod='netreviews'}
               {/if}
            </div>
         </div>
      </a>
   </div>
{elseif ($widgetlight eq '2')}
   <div class="netreviewsProductWidgetNew">
      <img alt="netreviews_seal" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" class="netreviewsProductWidgetNewLogo"/>
      <div class="ProductWidgetNewRatingWrapper">
            <div class="netreviews_review_rate_and_stars">
               {include file=$stars_dir}
            </div>
            <div class="netreviewsProductWidgetNewRate">
               <span class="ratingValue">{$av_rate|escape:'htmlall':'UTF-8'}</span>/<span class="bestRating">5</span>
            </div>
            <a href="javascript:av_widget_click()" id="AV_button" class="netreviewsProductWidgetNewLink">{l s='See the reviews' mod='netreviews'}
            (<span>{$av_nb_reviews|escape:'htmlall':'UTF-8'}</span>)
            </a>
      </div>
   </div>
{elseif ($widgetlight eq '3')}
   <div class="av_product_award">
      <div id="top">
            <div class="netreviews_review_rate_and_stars">
               {include file=$stars_dir}
            </div>
         <div class="ratingText">
            <span class="reviewCount">
            {$av_nb_reviews|escape:'htmlall':'UTF-8'}
            </span>
            {if $av_nb_reviews > 1}
            {l s='reviews' mod='netreviews'}
            {else}
            {l s='review' mod='netreviews'}
            {/if}
         </div>
      </div>
      <div id="bottom" {if ($use_star_format_image != '1' && $version_ps >= 1.4 )}style="background:#{$customized_star_color|escape:'htmlall':'UTF-8'}"{/if}><a href="javascript:av_widget_click()" id="AV_button">{l s='See the reviews' mod='netreviews'}</a></div>
      <img alt="netreviews_seal" id="sceau" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" />
   </div>
{/if}

{if ($snippets_complete == "1" && $snippets_type == "1")}
</div> <!-- End product -->
{/if}
