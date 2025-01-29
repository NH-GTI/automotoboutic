<?php
/* Smarty version 4.3.4, created on 2025-01-29 09:29:31
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/surmesure.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6799e6eb7e4523_31278502',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9d42645001969873e94af6d3fe7b75aa0f73308d' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/surmesure.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./process-steps.tpl' => 1,
    'file:./process-step-1.tpl' => 1,
    'file:./process-step-2.tpl' => 1,
    'file:./process-step-3.tpl' => 1,
    'file:./process-step-4.tpl' => 1,
    'file:./process-step-5.tpl' => 2,
    'file:./process-step-6.tpl' => 1,
  ),
),false)) {
function content_6799e6eb7e4523_31278502 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->_subTemplateRender('file:./process-steps.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="block_head_SM" >
	<div class="container">
		<div class="content_head_block text-center">
    		<?php if ($_smarty_tpl->tpl_vars['step']->value == 1) {?>
                <h1><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tapis auto sur-mesure','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</h1>
    			<?php if (!(isset($_smarty_tpl->tpl_vars['marque_selected']->value)) || $_smarty_tpl->tpl_vars['marque_selected']->value == 0) {?>
                <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Personnalisez vos tapis en 4 étapes','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
		<p style="color:#FFFFFF;font-size: 19px;font-weight: 900;background-color:#156f8a;padding:8px;margin:0 0 3em 0;letter-spacing:1px;">Pour toute commande contenant un tapis sur mesure, le délai de livraison sera de 8 à 12 jours ouvrés.</p>
    			<?php } else { ?>
                <p><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</p>
    			<?php }?>
    		<?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 2) {?>
                <h1><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tapis','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['marque_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['family_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
</h1>
            <?php } elseif ($_smarty_tpl->tpl_vars['step']->value > 2) {?>
                <h1><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tapis','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['model_name_for_title']->value )), ENT_QUOTES, 'UTF-8');?>
</h1>
                    			<p><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</p> 
    		<?php }?>
		</div>
	</div>
</div>
	
<div class="block_main_SM">
                    
    <?php if ($_smarty_tpl->tpl_vars['step']->value == 1) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1160679666799e6eb7de358_72921381', 'process_step_1');
?>

    <?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 2) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12697304026799e6eb7df410_86907459', 'process_step_2');
?>

    <?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 3) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4921356336799e6eb7e03f8_63743731', 'process_step_3');
?>

    <?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 4) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4559538296799e6eb7e1365_62874039', 'process_step_4');
?>

    <?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 5) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8206497936799e6eb7e22c9_64321675', 'process_step_4');
?>

    <?php } elseif ($_smarty_tpl->tpl_vars['step']->value == 6) {?>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10667245356799e6eb7e32f8_65685233', 'process_step_4');
?>

    <?php }?>
</div>
<?php }
/* {block 'process_step_1'} */
class Block_1160679666799e6eb7de358_72921381 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_1' => 
  array (
    0 => 'Block_1160679666799e6eb7de358_72921381',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-1.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_1'} */
/* {block 'process_step_2'} */
class Block_12697304026799e6eb7df410_86907459 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_2' => 
  array (
    0 => 'Block_12697304026799e6eb7df410_86907459',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-2.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_2'} */
/* {block 'process_step_3'} */
class Block_4921356336799e6eb7e03f8_63743731 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_3' => 
  array (
    0 => 'Block_4921356336799e6eb7e03f8_63743731',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-3.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_3'} */
/* {block 'process_step_4'} */
class Block_4559538296799e6eb7e1365_62874039 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_4' => 
  array (
    0 => 'Block_4559538296799e6eb7e1365_62874039',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-4.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_4'} */
/* {block 'process_step_4'} */
class Block_8206497936799e6eb7e22c9_64321675 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_4' => 
  array (
    0 => 'Block_8206497936799e6eb7e22c9_64321675',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

                <?php $_smarty_tpl->_subTemplateRender('file:./process-step-5.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_4'} */
/* {block 'process_step_4'} */
class Block_10667245356799e6eb7e32f8_65685233 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_4' => 
  array (
    0 => 'Block_10667245356799e6eb7e32f8_65685233',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-5.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
        <?php $_smarty_tpl->_subTemplateRender('file:./process-step-6.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php
}
}
/* {/block 'process_step_4'} */
}
