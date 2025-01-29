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
{if $av_star_type == 'big'}
<div class="netreviews_bg_stars_big">
   <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color|escape:'htmlall':'UTF-8'}">
      {for $av_star=0 to $average_rate_percent.floor}<span class="nr-icon nr-star"></span>{/for}{if $average_rate_percent.decimals}<span class="nr-icon nr-star" style="width:{$average_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
   </div>
</div>

{elseif $av_star_type == 'small'}
<div class="netreviews_font_stars">
    <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color|escape:'htmlall':'UTF-8'}">
      {for $av_star=1 to $review.rate}<span class="nr-icon nr-star"></span>{/for}
   </div>
</div>

{elseif $av_star_type == 'widget'}
<div class="netreviews_font_stars">
    <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color|escape:'htmlall':'UTF-8'}">
      {for $av_star=0 to $average_rate_percent.floor}<span class="nr-icon nr-star"></span>{/for}{if $average_rate_percent.decimals}<span class="nr-icon nr-star" style="width:{$average_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
   </div>
</div>
 {/if}
