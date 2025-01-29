<?php
/* Smarty version 4.3.4, created on 2025-01-29 12:09:38
  from 'module:ps_crosssellingviewstemplateshookps_crossselling.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a0c72164830_65755641',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e421d796c01b1c87b479bce6a64b1b0f280dab9' => 
    array (
      0 => 'module:ps_crosssellingviewstemplateshookps_crossselling.tpl',
      1 => 1708963242,
      2 => 'module',
    ),
    '4ad82d2a90256dbb7e79b1bca26f905ba3cd9852' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/catalog/_partials/productlist.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
    'd38304c4461928a311d790eccf50195e34c85d12' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/catalog/_partials/miniatures/product.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
    '13b288c25dbc1999c70730166b3d7c45eaadd16c' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/catalog/_partials/product-flags.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 31536000,
),true)) {
function content_679a0c72164830_65755641 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'renderLogo' => 
  array (
    'compiled_filepath' => '/var/www/html/automotoboutic/var/cache/dev/smarty/compile/classiclayouts_layout_full_width_tpl/88/36/ac/8836ac7944680434878ef424cff9658cbf5943bc_2.file.helpers.tpl.php',
    'uid' => '8836ac7944680434878ef424cff9658cbf5943bc',
    'call_name' => 'smarty_template_function_renderLogo_607760535679a084a771728_26037970',
  ),
));
?><!-- begin /var/www/html/automotoboutic/themes/classic/modules/ps_crossselling/views/templates/hook/ps_crossselling.tpl -->
<section class="featured-products clearfix mt-3">
  <h2>Les clients qui ont acheté ce produit ont également acheté :</h2>
  

<div class="products">
            
<div class="js-product product col-xs-12 col-sm-6 col-lg-4 col-xl-3">
  <article class="product-miniature js-product-miniature" data-id-product="7992" data-id-product-attribute="159">
    <div class="thumbnail-container">
      <div class="thumbnail-top">
        
                      <a href="https://www.automotoboutic.com/nouveautes/7992-159-housses-siege-voiture-messina-complet.html#" class="thumbnail product-thumbnail">
              <picture>
                                                <img
                  src="https://www.automotoboutic.com/21244-home_default/housses-siege-voiture-messina-complet.jpg"
                  alt="Housse siege auto bleu jeans"
                  loading="lazy"
                  data-full-size-image-url="https://www.automotoboutic.com/21244-large_default/housses-siege-voiture-messina-complet.jpg"
                  width="263"
                  height="292"
                />
              </picture>
            </a>
                  

        <div class="highlighted-informations no-variants">
          
            <a class="quick-view js-quick-view" href="#" data-link-action="quickview">
              <i class="material-icons search">&#xE8B6;</i> Aperçu rapide
            </a>
          

          
                      
        </div>
      </div>

      <div class="product-description">
        
                      <h2 class="h3 product-title"><a href="https://www.automotoboutic.com/nouveautes/7992-159-housses-siege-voiture-messina-complet.html#" content="https://www.automotoboutic.com/nouveautes/7992-159-housses-siege-voiture-messina-complet.html#">Housses de siege auto...</a></h2>
                  

        
                      <div class="product-price-and-shipping">
              
              

              <span class="price" aria-label="Prix">
                                                  0,00 €
                              </span>

              

              
            </div>
                  

        
          
<!-- begin module:productcomments/views/templates/hook/product-list-reviews.tpl -->
<!-- begin /var/www/html/automotoboutic/modules/productcomments/views/templates/hook/product-list-reviews.tpl -->

<div class="product-list-reviews" data-id="7992" data-url="https://www.automotoboutic.com/module/productcomments/CommentGrade">
  <div class="grade-stars small-stars"></div>
  <div class="comments-nb"></div>
</div>
<!-- end /var/www/html/automotoboutic/modules/productcomments/views/templates/hook/product-list-reviews.tpl -->
<!-- end module:productcomments/views/templates/hook/product-list-reviews.tpl -->

        
      </div>

      
    <ul class="product-flags js-product-flags">
        <br />
<b>Warning</b>:  foreach() argument must be of type array|object, bool given in <b>/var/www/html/automotoboutic/src/Adapter/Presenter/Product/ProductLazyArray.php</b> on line <b>667</b><br />
            <li class="product-flag out_of_stock">Rupture de stock</li>
            </ul>

    </div>
  </article>
</div>

    </div>
</section>
<!-- end /var/www/html/automotoboutic/themes/classic/modules/ps_crossselling/views/templates/hook/ps_crossselling.tpl --><?php }
}
