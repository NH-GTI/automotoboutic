<?php
/* Smarty version 4.3.4, created on 2025-01-29 09:29:31
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-steps.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6799e6eb7f45d4_28503551',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '85a3d881dec4f1601693447afb4e829b78d66b32' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-steps.tpl',
      1 => 1732526536,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6799e6eb7f45d4_28503551 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16199782386799e6eb7e7884_97219161', 'process_steps_navigation');
?>

<?php }
/* {block 'process_steps_navigation'} */
class Block_16199782386799e6eb7e7884_97219161 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_steps_navigation' => 
  array (
    0 => 'Block_16199782386799e6eb7e7884_97219161',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/automotoboutic/vendor/smarty/smarty/libs/plugins/modifier.regex_replace.php','function'=>'smarty_modifier_regex_replace',),));
?>

    <div class="container head-selector">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="/tapis/step-1" type="button" class="btn-custom<?php if ($_smarty_tpl->tpl_vars['step']->value == 1) {?> btn-custom-active<?php }?>">
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'1.','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
 <span
                            class="step_name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Mon véhicule','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="<?php if ($_smarty_tpl->tpl_vars['step']->value > 2) {
echo htmlspecialchars((string) smarty_modifier_regex_replace($_SERVER['REQUEST_URI'],"/step-[0-9]+/","step-2"), ENT_QUOTES, 'UTF-8');
} else { ?>#step-2<?php }?>"
                        type="button" class="btn-custom<?php if ($_smarty_tpl->tpl_vars['step']->value == 2) {?> btn-custom-active<?php }?>"
                        <?php if ($_smarty_tpl->tpl_vars['step']->value <= 2) {?>disabled="disabled" <?php }?>>
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'2.','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
 <span
                            class="step_name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Mon modèle','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="<?php if ($_smarty_tpl->tpl_vars['step']->value > 3) {
echo htmlspecialchars((string) smarty_modifier_regex_replace($_SERVER['REQUEST_URI'],"/step-[0-9]+/","step-3"), ENT_QUOTES, 'UTF-8');
} else { ?>#step-3<?php }?>"
                        type="button" class="btn-custom<?php if ($_smarty_tpl->tpl_vars['step']->value == 3) {?> btn-custom-active<?php }?>"
                        <?php if ($_smarty_tpl->tpl_vars['step']->value <= 3) {?>disabled="disabled" <?php }?>>
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'3.','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
 <span
                            class="step_name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ma finition','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="<?php if ($_smarty_tpl->tpl_vars['step']->value > 4) {
echo htmlspecialchars((string) smarty_modifier_regex_replace($_SERVER['REQUEST_URI'],"/step-[0-9]+/","step-4"), ENT_QUOTES, 'UTF-8');
} else { ?>#step-4<?php }?>"
                        type="button" class="btn-custom<?php if ($_smarty_tpl->tpl_vars['step']->value == 4) {?> btn-custom-active<?php }?>"
                        <?php if ($_smarty_tpl->tpl_vars['step']->value <= 4) {?>disabled="disabled" <?php }?>>
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'4.','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
 <span
                            class="step_name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ma configuration','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-5" type="button" class="btn-custom<?php if ($_smarty_tpl->tpl_vars['step']->value == 5 || $_smarty_tpl->tpl_vars['step']->value == 6) {?> btn-custom-active<?php }?>"
                        <?php if ($_smarty_tpl->tpl_vars['step']->value <= 5) {?>disabled="disabled" <?php }?>>
                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'5.','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Validation','d'=>'Modules.Contactform.Shop'),$_smarty_tpl ) );?>

                    </a>
                </div>
            </div>
        </div>
        <!--<div>
            <button id="confidential-mode-button" class="confidential_mode_button">
                <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_COOKIE['confidential_mode'],"html" )) == "ON") {?>
                    <svg fill=" #ff0000" height="800px" width="800px" version="1.1" id="Layer_1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330"
                        xml:space="preserve">
                        <g id="XMLID_509_">
                            <path id="XMLID_510_" d="M65,330h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15h-15V85c0-46.869-38.131-85-85-85
                S80,38.131,80,85v45H65c-8.284,0-15,6.716-15,15v170C50,323.284,56.716,330,65,330z M180,234.986V255c0,8.284-6.716,15-15,15
                s-15-6.716-15-15v-20.014c-6.068-4.565-10-11.824-10-19.986c0-13.785,11.215-25,25-25s25,11.215,25,25
            C190,223.162,186.068,230.421,180,234.986z M110,85c0-30.327,24.673-55,55-55s55,24.673,55,55v45H110V85z" />
                        </g>
                    </svg>
                <?php } else { ?>
                    <svg fill="#00cc00" height="800px" width="800px" version="1.1" id="Layer_1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330"
                        xml:space="preserve">
                        <g id="XMLID_516_">
                            <path id="XMLID_517_" d="M15,160c8.284,0,15-6.716,15-15V85c0-30.327,24.673-55,55-55c30.327,0,55,24.673,55,55v45h-25
                        c-8.284,0-15,6.716-15,15v170c0,8.284,6.716,15,15,15h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15H170V85
                        c0-46.869-38.131-85-85-85S0,38.131,0,85v60C0,153.284,6.716,160,15,160z" />
                        </g>
                    </svg>
                <?php }?>
            </button>
        </div>-->
    </div><!-- end .container -->
<?php
}
}
/* {/block 'process_steps_navigation'} */
}
