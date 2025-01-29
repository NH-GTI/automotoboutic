<?php
/* Smarty version 4.3.4, created on 2025-01-27 17:29:33
  from 'module:ps_imagesliderviewstemplateshookslider.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6797b46d9426c8_86945744',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6c2108a17c7103b6e203f4f0621d4645b56b0114' => 
    array (
      0 => 'module:ps_imagesliderviewstemplateshookslider.tpl',
      1 => 1736237617,
      2 => 'module',
    ),
  ),
  'cache_lifetime' => 31536000,
),true)) {
function content_6797b46d9426c8_86945744 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="background-color: #FFFFFF;margin: 1.3em 0;">     <p style="color: #D62C2C;text-align: center;font-size:1.2em;font-weight:700;"></p></div>
				  <div id="carousel" data-ride="carousel" class="carousel slide" data-interval="5000" data-wrap="true" data-pause="hover" data-touch="true">
		    <ol class="carousel-indicators">
	            <li data-target="#carousel" data-slide-to="0" class="active"></li>
          </ol>
    <ul class="carousel-inner" role="listbox" aria-label="Conteneur carrousel">
              <li class="carousel-item active" role="option" aria-hidden="false">
                      <figure>
              <img src="https://www.automotoboutic.com/modules/ps_imageslider/images/728acbf20afdb6af80344f8d5375f29059adae80_Banniere-2.jpg" alt="" loading="lazy" width="1110" height="340">
                          </figure>
                  </li>
          </ul>
    <div class="direction" aria-label="Boutons du carrousel">
      <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" aria-label="Précédent">
        <span class="icon-prev hidden-xs" aria-hidden="true">
          <i class="material-icons">&#xE5CB;</i>
        </span>
      </a>
      <a class="right carousel-control" href="#carousel" role="button" data-slide="next" aria-label="Suivant">
        <span class="icon-next" aria-hidden="true">
          <i class="material-icons">&#xE5CC;</i>
        </span>
      </a>
    </div>
  </div>
<?php }
}
