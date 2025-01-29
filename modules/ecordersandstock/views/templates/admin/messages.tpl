{**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL Ether Création
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL Ether Création is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ethercreation.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe Ether Création
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL Ether Création est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la SARL Ether Création a l'adresse: contact@ethercreation.com
 * ...........................................................................
 *
 *  @package   ecordersandstock
 *  @author    Alec Page
 *  @copyright Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license   Commercial license
 *}

{if $etat|escape:'htmlall':'UTF-8' == 'message'}
    {if $message|escape:'htmlall':'UTF-8' == '1'}
        {l s='Message 1' mod='ecordersandstock'}
    {/if}
{else}
    <div class="alert {if $etat|escape:'htmlall':'UTF-8' == 'success'}alert-success{else}alert-danger{/if}">
        <button class="close" data-dismiss="alert" type="button">×</button>
        {if $message|escape:'htmlall':'UTF-8' == '6'}
            {l s='Parameters successfully saved' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '7'}
            {l s='An error occurred while trying to save parameters.' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '12'}
            {l s='Task launched. You can follow it from the control panel at the bottom of the page' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '13'}
            {l s='Task stopped' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '20'}
            {l s='File successfully saved.' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '21'}
            {l s='An error occurred while transferring the file.' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '30'}
            {l s='Orders file successfully sent.' mod='ecordersandstock'}
        {elseif $message|escape:'htmlall':'UTF-8' == '31'}
            {l s='An error occurred while building the orders file : see logs.' mod='ecordersandstock'}
        {/if}
    </div>
{/if}
