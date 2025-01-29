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
{if ($snippets_complete == "1")}
   <div itemscope itemtype="http://schema.org/Product">
      <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" />
      <meta itemprop="description" content="{$product_description|escape:'htmlall':'UTF-8'|strip_tags}">
      <meta itemprop="image" content="{$url_image|escape:'htmlall':'UTF-8'}" />
      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
         <meta itemprop="price" content="{$product_price|escape:'htmlall':'UTF-8'}">
         <meta itemprop="priceCurrency" content="{$product_currency|escape:'htmlall':'UTF-8'}">
         <link itemprop="availability" href="http://schema.org/InStock" />
         {if $product_url}
            <meta itemprop="url" content="{$product_url|escape:'htmlall':'UTF-8'}" />
         {/if}
      </span>
      {if $product_url}
         <meta itemprop="url" content="{$product_url|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $product_id}
         <meta itemprop="productID" content="{$product_id|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $sku}
         <meta itemprop="sku" content="{$sku|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $brand_name}
         <meta itemprop="brand" content="{$brand_name|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $mpn}
         <meta itemprop="mpn" content="{$mpn|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $gtin_ean}
         <meta itemprop="gtin13" content="{$gtin_ean|escape:'htmlall':'UTF-8'}" />
      {/if}
      {if $gtin_upc}
         <meta itemprop="gtin12" content="{$gtin_upc|escape:'htmlall':'UTF-8'}" />
      {/if}
{/if}

<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
   <meta itemprop="ratingValue" content="{$av_rate|escape:'htmlall':'UTF-8'}"/>
   <meta itemprop="worstRating" content="1"/>
   <meta itemprop="bestRating" content="5"/>
   <meta itemprop="ratingCount " content="{$av_nb_reviews|escape:'htmlall':'UTF-8'}"/>
</div>

{if $detailsReviews}
   {foreach from=$detailsReviews item=review}
      <span itemprop="review" itemscope itemtype="https://schema.org/Review">
         <meta itemprop="reviewBody" content="{$review.review|urldecode|escape:'htmlall':'UTF-8'}" />
         <span itemprop="author" itemscope itemtype="https://schema.org/Person">
            <span itemprop="name" content="{$review.customer_name|urldecode|escape:'htmlall':'UTF-8'}"></span>
         </span>
         <meta itemprop="datePublished" itemtype="https://schema.org/datePublished" content="{$review.horodate|date_format:'%Y/%m/%d'|escape:'htmlall':'UTF-8'}" />
         <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
            <meta itemprop="ratingValue" content="{$review.rate|escape:'htmlall':'UTF-8'}" />
         </span>
      </span>
   {/foreach}
{/if}
