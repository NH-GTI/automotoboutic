{include file='./process-steps.tpl'}

<div class="block_head_SM" {* style="background-image:url('{$module_media_base_url}img/img_surmesure.jpg')" *}
>
	<div class="container">
		<div class="content_head_block text-center">
    		{if $step == 1}
                <h1>{l s='Tapis auto sur-mesure' d='Modules.Surmesure.Shop'}</h1>
    			{if !isset($marque_selected) || $marque_selected == 0}
                <p>{l s='Personnalisez vos tapis en 4 étapes' d='Modules.Surmesure.Shop'}</p>
		<p style="color:#FFFFFF;font-size: 19px;font-weight: 900;background-color:#156f8a;padding:8px;margin:0 0 3em 0;letter-spacing:1px;">Pour toute commande contenant un tapis sur mesure, le délai de livraison sera de 8 à 12 jours ouvrés.</p>
    			{else}
                <p>{$marque_selected_nom}</p>
    			{/if}
    		{elseif $step == 2}
                <h1>{l s='Tapis' d='Modules.Surmesure.Shop'} {$marque_selected_nom|@ucfirst} {$family_selected_nom|@ucfirst}</h1>
            {elseif $step > 2}
                <h1>{l s='Tapis' d='Modules.Surmesure.Shop'} {$model_name_for_title|@ucfirst}</h1>
                {* {$marque_selected_nom}  *}
    			<p>{$modele_selected_nom}</p> 
    		{/if}
		</div>
	</div>
</div>
	
<div class="block_main_SM">
    {* {block name='process_steps'} *}
        {* {include file='./process-steps.tpl'} *}
    {* {/block} *}
    
    {if $step == 1}
    {block name='process_step_1'}
        {include file='./process-step-1.tpl'}
    {/block}
    {elseif $step == 2}
    {block name='process_step_2'}
        {include file='./process-step-2.tpl'}
    {/block}
    {elseif $step == 3}
    {block name='process_step_3'}
        {include file='./process-step-3.tpl'}
    {/block}
    {elseif $step == 4}
    {block name='process_step_4'}
        {include file='./process-step-4.tpl'}
    {/block}
    {elseif $step == 5}
    {block name='process_step_4'}
        {* {include file='./process-step-4.tpl'} *}
        {include file='./process-step-5.tpl'}
    {/block}
    {elseif $step == 6}
    {block name='process_step_4'}
        {include file='./process-step-5.tpl'}
        {include file='./process-step-6.tpl'}
    {/block}
    {/if}
</div>
