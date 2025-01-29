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
    <div class="netreviews_image_stars">
        <div class="netreviews_stars netreviews_stars_bg">
            <span style="width:{$av_rate_percent_int|escape:'htmlall':'UTF-8'}%;"></span>
        </div>
    </div>
    {elseif $av_star_type == 'small'}
    <div class="netreviews_image_stars">
        <div class="netreviews_stars netreviews_stars_md">
            <span style="width:{$review.rate_percent|escape:'htmlall':'UTF-8'}%"></span>
        </div>
    </div>
    {elseif $av_star_type == 'widget'}
    <div class="netreviews_image_stars">
        <div class="netreviews_stars netreviews_stars_md">
            <span style="width:{$av_rate_percent_int|escape:'htmlall':'UTF-8'}%"></span>
        </div>
    </div>
{/if}
