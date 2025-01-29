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

{assign var="av_star_type" value="small"}
<div class="netreviews_reviews_section">
   <div class="loader_av"></div>
      {foreach from=$reviews key=k_review item=review}
         <div class="netreviews_review_part{if $k_review == '0'} first-child{/if}" >

          <div class="netreviews_stars_rate">
             <div class="netreviews_review_rate_and_stars">
               {include file=$stars_dir}
            </div>
            <div class="nrRate">{$review.rate|escape:'htmlall':'UTF-8'}/5</div>
         </div>

         <p class="netreviews_customer_review">
            {$review.avis|escape:'htmlall':'UTF-8'}
         </p>

       {if ($hidemedia != '1')}
        <!-- Media part -->
         {if $review.media_content}
        <ul class="netreviews_media_part">
              {foreach from=$review.media_content key=k_media_content item=media}
                <li>
                    <a data-type="{$media.type|escape:'htmlall':'UTF-8'}"
                       data-src="{$media.large|escape:'htmlall':'UTF-8'}"
                       class="netreviews_image_thumb" href="javascript:"
                       style="background-image:url('{$media.small|escape:'htmlall':'UTF-8'}');">&nbsp;</a>
                </li>
              {/foreach}
        </ul>
        <div class="netreviews_clear">&nbsp;</div>
        {/if}
            <!-- End media part -->
        {/if}

            <div class="netreviews_customer_name">
               {if $review.customer_name_anonymous}
                   {l s='Anonymous customer' mod='netreviews'}
               {else}
                   {$review.customer_name|escape:'htmlall':'UTF-8'}
               {/if}
               <span>{if $old_lang}{$av_ajax_translation.a|escape:'htmlall':'UTF-8'} {$av_ajax_translation.b|escape:'htmlall':'UTF-8'}{else}{l s='published' mod='netreviews'} {l s='the' mod='netreviews'}{/if} {$review.horodate|escape:'htmlall':'UTF-8'}</span>
               <span class="order_date">{if $old_lang}{$av_ajax_translation.c|escape:'htmlall':'UTF-8'}{else}{l s='following an order made on' mod='netreviews'}{/if} {$review.horodate_order|escape:'htmlall':'UTF-8'}</span>
            </div>


      {if $review.discussion}
            {foreach from=$review.discussion key=k_discussion item=discussion}
            <div class="netreviews_website_answer" {if $k_discussion > 0} review_number={$review.id_product_av|escape:'htmlall':'UTF-8'} style= "display: none" {/if}>
                  <span class="netreviews_answer_title">
                  {if $old_lang}{$av_ajax_translation.d|escape:'htmlall':'UTF-8'}{else}{l s='Comment from' mod='netreviews'}{/if} {$discussion.origine|escape:'htmlall':'UTF-8'} {if $old_lang}{$av_ajax_translation.b|escape:'htmlall':'UTF-8'}{else}{l s='the' mod='netreviews'}{/if} {$discussion.horodate|escape:'htmlall':'UTF-8'}
                  </span>
                  <br>
                  {$discussion.commentaire|escape:'htmlall':'UTF-8'}
            </div>
          {/foreach}
           {if $review.discussion|count - 1 > 0}
              <div class="netreviews_discussion">
                 <a class="netreviews_button_comment active" href="javascript:switchCommentsVisibility('{$review.id_product_av|escape:'htmlall':'UTF-8'}',1)" id="display{$review.id_product_av|escape:'htmlall':'UTF-8'}" review_number={$review.id_product_av|escape:'htmlall':'UTF-8'}>
                 <div class="nr-icon nr-comment"></div>
                 <span>
                     {if $old_lang}{$av_ajax_translation.e|escape:'htmlall':'UTF-8'}{else}{l s='Show exchanges' mod='netreviews'}{/if}
                     </span>
                 </a>

                 <a class="netreviews_button_comment" href="javascript:switchCommentsVisibility('{$review.id_product_av|escape:'htmlall':'UTF-8'}',0)" id="hide{$review.id_product_av|escape:'htmlall':'UTF-8'}" review_number={$review.id_product_av|escape:'htmlall':'UTF-8'} >
                 <div class="nr-icon nr-comment"></div>
                 <span>
                     {if $old_lang}{$av_ajax_translation.f|escape:'htmlall':'UTF-8'}{else}{l s='Hide exchanges' mod='netreviews'}{/if}
                     </span>
                 </a>
              </div>
           {/if}
      {/if}

            {if (!$hidehelpful)}
            <!-- helpful START -->
            <p class="netreviews_helpful_block">
                        {if $old_lang}{$av_ajax_translation.g|escape:'htmlall':'UTF-8'}{else}{l s='Did you find this helpful?' mod='netreviews'}{/if}
                        <a href="javascript:" onclick="javascript:avHelpfulClick('{$review.id_product_av|escape:'htmlall':'UTF-8'}','1','{$review.sign|escape:'htmlall':'UTF-8'}')" class="netreviewsVote" data-review-id="{$review.id_product_av|escape:'htmlall':'UTF-8'}" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_1">{if $old_lang}{$av_ajax_translation.h|escape:'htmlall':'UTF-8'}{else}{l s='Yes' mod='netreviews'}{/if} <span>{$review.helpful|escape:'htmlall':'UTF-8'}</span></a>
                        <a href="javascript:" onclick="javascript:avHelpfulClick('{$review.id_product_av|escape:'htmlall':'UTF-8'}','0','{$review.sign|escape:'htmlall':'UTF-8'}')" class="netreviewsVote" data-review-id="{$review.id_product_av|escape:'htmlall':'UTF-8'}" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_0">{if $old_lang}{$av_ajax_translation.i|escape:'htmlall':'UTF-8'}{else}{l s='No' mod='netreviews'}{/if} <span>{$review.helpless|escape:'htmlall':'UTF-8'}</span></a>
                    </p>
                    <p class="netreviews_helpfulmsg" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_msg"></p>
            <!-- helpful END-->
            {/if}
</div>
{/foreach}

  {if $reviews_max_pages > 1 && $reviews_max_pages > $current_page}
   <div id="netreviews_button_more_reviews">
       <div onclick="javascript:netreviewsFilter('more');" class="netreviews_button" id="av_load_next_page">
       {if $old_lang}{$av_ajax_translation.j|escape:'htmlall':'UTF-8'}{else}
       <span class="display">{l s='More reviews...' mod='netreviews'}</span>
       <i class="netreviews_arrow_down"></i>
       {/if}
       </div>
    </div>

   {/if}

</div>
