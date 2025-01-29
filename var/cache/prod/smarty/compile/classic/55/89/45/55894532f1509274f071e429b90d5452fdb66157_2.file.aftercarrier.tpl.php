<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:47:06
  from '/var/www/html/automotoboutic/modules/dpdfrance/views/templates/front/aftercarrier.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67978e5a6b48f5_92377645',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55894532f1509274f071e429b90d5452fdb66157' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/dpdfrance/views/templates/front/aftercarrier.tpl',
      1 => 1730819392,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67978e5a6b48f5_92377645 (Smarty_Internal_Template $_smarty_tpl) {
?>
<noscript>
    <tr>
        <td colspan="5">
            <div class="dpdfrance_relais_error">
                <strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'It seems that your browser doesn\'t allow Javascript execution, therefore DPD Relais is not available. Please change browser settings, or try another browser.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</strong>
            </div>
        </td>
    </tr>
    <br/>
    <div style="display:none;">
</noscript>

<div id="dpdfrance_relais_filter" onclick="hideGreyFilterAndDpdRelaisDetails()"></div>

<table id="dpdfrance_relais_point_table" class="dpdfrance_fo" style="display:none;">
    <?php if ((isset($_smarty_tpl->tpl_vars['error']->value))) {?>
        <tr>
            <td colspan="5">
                <div class="dpdfrance_relais_error"> <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['error']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 </div>
            </td>
        </tr>
    <?php } else { ?>
        <?php if ($_smarty_tpl->tpl_vars['dpdfrance_relais_status']->value == 'error') {?>
            <tr>
                <td colspan="5" class="p-0">
                    <div class="dpdfrance_relais_error">
                        <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'It seems that you haven\'t selected a DPD Pickup point, please pick one from this list','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</p>
                    </div>
                </td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="5" class="p-0">
                <div id="dpdfrance_div_relais_header">
                    <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please select your DPD Relais parcelshop among this list','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</p></div>
                <?php if ($_smarty_tpl->tpl_vars['ssl']->value == 0 || $_smarty_tpl->tpl_vars['ssl_everywhere']->value == 1) {?>
                <div id="dpdfrance_div_relais_srch_link">
                    <span onMouseOver="javascript:this.style.cursor='pointer';javascript:this.style.textDecoration='underline';"
                          onMouseOut="javascript:this.style.cursor='auto';javascript:this.style.textDecoration='none';"
                          onClick="$('#dpdfrance_div_relais_srch_panel').slideToggle();"
                    >
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search for Pickup points near another address','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                    </span>
                    <div id="dpdfrance_div_relais_srch_panel" style="display:none;">
                        <input type="text" id="dpdfrance_search_address" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Address','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
"/><br/>
                        <input type="text" id="dpdfrance_search_zipcode" maxlength="5" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Postcode','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
"/>
                        <input type="text" id="dpdfrance_search_city" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'City','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
"/>
                        <button type="button" id="dpdfrance_search_submit" name="dpdfrance_search_submit"
                                onclick="dpdFranceRelaisAjaxUpdate($('#dpdfrance_search_address').val(), $('#dpdfrance_search_zipcode').val(), $('#dpdfrance_search_city').val(), 'search', dpdfrance_cart_id);"
                        >
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                        </button>
                        <button type="button" id="dpdfrance_reset_submit" name="dpdfrance_reset_submit"
                                onclick="dpdFranceRelaisAjaxUpdate($('#dpdfrance_search_address').val(), $('#dpdfrance_search_zipcode').val(), $('#dpdfrance_search_city').val(), 'reset', dpdfrance_cart_id);"
                        >
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Reset','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                        </button>
                    </div>
                    <?php }?>
            </td>
        </tr>
        <?php if ((isset($_smarty_tpl->tpl_vars['dpdfrance_relais_empty']->value))) {?>
            <tr>
                <td colspan="5" class="p-0">
                    <div class="dpdfrance_relais_error">
                        <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'There are no Pickup points near this address, please modify it.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</p>
                    </div>
                </td>
            </tr>
        <?php }?>

        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['dpdfrance_relais_points']->value, 'points', false, NULL, 'dpdfranceRelaisLoop', array (
  'index' => true,
  'first' => true,
));
$_smarty_tpl->tpl_vars['points']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['points']->value) {
$_smarty_tpl->tpl_vars['points']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'];
?>
            <tr class="dpdfrance_lignepr" data-relay-id="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" onclick="document.getElementById('<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
').checked=true;">

                                <td class="dpdfrance_logorelais"></td>

                                <td class="dpdfrance_adressepr">
                    <b><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['shop_name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</b><br/><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['address1'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                    <br/><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['postal_code'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['city'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
<br/>
                </td>

                                <td class="dpdfrance_distancepr"><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['distance'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 km</td>

                                <td class="dpdfrance_popinpr">
                    <span onMouseOver="javascript:this.style.cursor='pointer';"
                          onMouseOut="javascript:this.style.cursor='auto';"
                          onClick="openDpdFranceDialog('dpdfrance_relaydetail<?php echo htmlspecialchars((string) (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'] : null)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
','map_canvas<?php echo htmlspecialchars((string) (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'] : null)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
',<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['coord_lat'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
,<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['coord_long'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
,'<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_base_dir']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
')">
                        <u><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'More details','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</u>
                    </span>
                </td>

                                <td class="dpdfrance_radiopr">
                    <?php if ($_smarty_tpl->tpl_vars['dpdfrance_selectedrelay']->value == $_smarty_tpl->tpl_vars['points']->value['relay_id']) {?>
                        <input type="radio" name="dpdfrance_relay_id"
                               id="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                               value="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                               checked="checked"
                        >
                    <?php } else { ?>
                        <input type="radio" name="dpdfrance_relay_id"
                               id="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                               value="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                                <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['first'] : null)) {?>
                                    checked="checked"
                                <?php }?>
                        >
                    <?php }?>
                    <label for="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                        <span><span></span></span>
                        <b>ICI</b>
                    </label>
                </td>
            </tr>

                        <div id="dpdfrance_relaydetail<?php echo htmlspecialchars((string) (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'] : null)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
                 class="dpdfrance_relaisbox" style="display:none;">

                <div class="dpdfrance_relaisboxclose" onclick="
                        document.getElementById('dpdfrance_relaydetail<?php echo htmlspecialchars((string) (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'] : null)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
').style.display='none';
                        document.getElementById('dpdfrance_relais_filter').style.display='none'">
                    <img src="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_base_dir']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
/views/img/front/relais/box-close.png"/>
                </div>

                <div class="dpdfrance_relaisboxcarto"
                     id="map_canvas<?php echo htmlspecialchars((string) (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dpdfranceRelaisLoop']->value['index'] : null)+call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( 1,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"></div>

                <div id="relaisboxbottom" class="dpdfrance_relaisboxbottom">
                    <div id="relaisboxadresse" class="dpdfrance_relaisboxadresse">
                        <div class="dpdfrance_relaisboxadresseheader"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your DPD Pickup point','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</div>
                        <br/>
                        <b><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['shop_name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</b><br/>
                        <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['address1'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
<br/>
                        <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['address2']))) {?>
                            <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['address2'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                            <br/>
                        <?php }?>
                        <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['postal_code'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['city'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
<br/>
                        <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['local_hint']))) {?>
                            <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Landmark','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['local_hint'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</p>
                        <?php }?>
                    </div>

                    <div class="dpdfrance_relaisboxhoraires">
                        <div class="dpdfrance_relaisboxhorairesheader"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Opening hours','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</div>
                        <br/>
                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Monday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['monday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['monday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['monday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['monday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['monday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tuesday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['tuesday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['tuesday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['tuesday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['tuesday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['tuesday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Wednesday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['wednesday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['wednesday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['wednesday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['wednesday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['wednesday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Thursday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['thursday']))) {?> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['thursday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['thursday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['thursday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['thursday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Friday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['friday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['friday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['friday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['friday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['friday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Saturday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['saturday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['saturday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['saturday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['saturday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['saturday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>

                        <p>
                            <span class="dpdfrance_relaisboxjour"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sunday','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </span>
                            <?php if (!(isset($_smarty_tpl->tpl_vars['points']->value['sunday']))) {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closed','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

                            <?php } else { ?>
                                <?php if ($_smarty_tpl->tpl_vars['points']->value['sunday'][0]) {?>
                                    <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['sunday'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['sunday'][1]))) {?>
                                        & <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['sunday'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </p>
                    </div>

                    <div id="relaisboxinfos" class="dpdfrance_relaisboxinfos">
                        <div class="dpdfrance_relaisboxinfosheader"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'More info','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</div>
                        <br/>
                        <h5><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Distance in km','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </h5><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['distance'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 km
                        <br/>
                        <h5><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'DPD Relais code','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 : </h5><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['relay_id'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                        <br/>
                        <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['closing_period'][0]))) {?>
                            <h4>
                                <img src="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_base_dir']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
/views/img/front/relais/warning.png" alt="warning"/>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Closing period','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
:
                            </h4>
                            <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['closing_period'][0],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                            <br/>
                        <?php }?>
                        <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['closing_period'][1]))) {?>
                            <h4></h4>
                            <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['closing_period'][1],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                            <br/>
                        <?php }?>
                        <?php if ((isset($_smarty_tpl->tpl_vars['points']->value['closing_period'][2]))) {?>
                            <h4></h4>
                            <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['points']->value['closing_period'][2],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

                            <br/>
                        <?php }?>
                    </div>
                </div>
            </div>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php }?>
</table>
<noscript></div></noscript>



<div id="div_dpdfrance_predict_block" class="dpdfrance_fo" style="display:none;">
    <div id="div_dpdfrance_predict_header">
        <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Your order will be delivered by DPD with Predict service','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</p>
    </div>
    <div class="module" id="predict">
        <div id="div_dpdfrance_predict_logo"></div>
        <div class="copy">
            <p><h2><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'With Predict, be independent with your deliveries !','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
 :</h2></p><br/>

            <p><h2><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'How it works:','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</h2></p>
            <ul>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'A first SMS will be sent to you when your parcel is taken in charge by DPD France. This SMS will tell you the expected delivery date.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'On the day of delivery, you will receive an SMS and an email indicating the delivery time slot.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Upon delivery, transmit your code to the DPD France delivery person. Your parcel will be delivered to you against signature.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
            </ul><br/>

            <p><h2><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'The Predict service allows you to be autonomous on your delivery thanks to a wide choice of reprogramming options until the day before or in case of absence :','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</h2></p>
            <ul>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery of your parcel at pudo’s','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery to your workplace','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery to another address','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery to a neighbour','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Withdrawal in a DPD France depot','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
                <li><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Delivery to another date','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</li>
            </ul>
            <p><h2><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Benefit from the advantages of Predict delivery !','mod'=>'dpdfrance'),$_smarty_tpl ) );?>
</h2></p>
        </div><br/>
        <div id="div_dpdfrance_dpd_logo"></div>
    </div>

    <div id="div_dpdfrance_predict_gsm">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Get all the advantages of DPD\'s Predict service by providing a french GSM number here ','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

        <input type="text" name="dpdfrance_predict_gsm_dest" id="input_dpdfrance_predict_gsm_dest" maxlength="17"
               value="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_predict_gsm_dest']->value,'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
        <div id="dpdfrance_predict_gsm_button">></div>
    </div>

    <div id="dpdfrance_predict_error" class="warnmsg" style="display:none;">
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'It seems that the GSM number you provided is incorrect. Please provide a french GSM number, starting with 06 or 07, on 10 consecutive digits.','mod'=>'dpdfrance'),$_smarty_tpl ) );?>

    </div>
</div>
<?php }
}
