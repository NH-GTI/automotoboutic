{*
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
 *  @package    ecordersandstock
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 *}

<input type="hidden" name="ecoas_token" value="{$ecoas_token}" id="ecoas_token" />
<input type="hidden" name="ecoas_baseDir" value="{$baseDir}" id="ecoas_baseDir" />
<div id="display_message">
</div>

<div class="panel col-lg-12">
    <h3>
        {l s='Paramétrage du module Orders And Stock' mod='ecordersandstock'}
    </h3>
    <div class="panel col-lg-12" style="box-shadow:3px 3px grey;">
        <h3>
            {l s='Récupération des commandes' mod='ecordersandstock'}
        </h3>
        <div class="form-group">
            <div class="col-lg-1"><span class="pull-right"></span></div>
            <label class="control-label col-lg-3">{l s='Mail pour les commandes' mod='ecordersandstock'} :</label>
            <div class="input-group col-lg-4">
                <input type="text" value="{$ecoas_mailorders}" name="ecoas_mailorders" class="ecordersandstock_conf">
            </div>
        </div>
    </div>
    <div class="btnCon">
        <a class="" href="javascript:save_parameter_ecordersandstock();">
            <button type="submit" value="1" id="configuration_form_submit_btn" name="submit" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='ecordersandstock'}
            </button>
        </a>
    </div>
</div>

<div class="panel col-lg-12">
    <h3>
        {l s='Actions' mod='ecordersandstock'}
    </h3>
    <div class="panel col-lg-12" style="background-color: #FFF3CD;">
        <h3>{l s='Exporter les commandes AMB' mod='ecordersandstock'}</h3>
        <div class="col-lg-1">
            <div class="btn btn-default">
                <a class="" href="javascript:send_orders();">
                    <i class="process-icon-upload"></i> {l s='Send orders by mail' mod='ecordersandstock'}
                </a>
            </div>
        </div>
        <div class="col-lg-3"></div>
        <div class="margin-form col-lg-8">
            <input type="text" value="{$ordersLinkAMB}" readonly style="cursor:text;"/>
        </div>
    </div>
    <div class="panel col-lg-12" style="background-color: #CFF4FC;">
        <h3>{l s='Exporter les commandes Marketplace' mod='ecordersandstock'}</h3>
        <div class="col-lg-1">
            <div class="btn btn-default">
                <a class="" href="javascript:send_orders_marketplace();">
                    <i class="process-icon-upload"></i> {l s='Send orders by mail' mod='ecordersandstock'}
                </a>
            </div>
        </div>
        <div class="col-lg-3"></div>
        <div class="margin-form col-lg-8">
            <input type="text" value="{$ordersLinkMarketplace}" readonly style="cursor:text;"/>
        </div>
    </div>
</div>

<div class="panel col-lg-12">
    <div class="panel col-lg-12">
        {l s='Voici le tableau de contrôle pour lancer et suivre les tâches planifiées.' mod='ecordersandstock'}
        <br>
        {l s='Ces tâches peuvent être automatisées en plaçant les liens correspondants dans un planificateur de tâches.' mod='ecordersandstock'}
    </div>
    <div class="panel col-lg-12" id="fileblock">
        <h3>{l s='Uploader le fichier de stock' mod='ecordersandstock'}</h3>
        <form action="{$baseDir}ajax.php" method="post" enctype="multipart/form-data" class="form-horizontal" name="ecoas_file_form">
            <p class="alert alert-info">
                {l s='Le fichier doit être un .txt encodé UTF8' mod='ecordersandstock'}
            </p>
            <div class="form-group">
                <label for="ecoas_stockfile" class="control-label col-lg-3">
                    <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="{l s='Fichier de stock au format habituel' mod='ecordersandstock'}">
                        {l s='Fichier de stock' mod='ecordersandstock'}
                    </span>
                </label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-lg-7">
                            <input type="hidden" name="ecoas_token" value="{$ecoas_token}">
                            <input type="hidden" name="majsel" value="99">
                            <input id="ecoas_stockfile" name="ecoas_stockfile" class="hide" type="file">
                            <div class="dummyfile input-group">
                                <span class="input-group-addon"><i class="icon-file"></i></span>
                                <input id="file-name" class="disabled" name="filename" readonly="" type="text">
                                <span class="input-group-btn">
                                    <button id="file-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                            <i class="icon-folder-open"></i> {l s='Choisissez un fichier' mod='ecordersandstock'}
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-9 col-lg-push-3">
                    <button class="btn btn-default" type="submit" name="download">
                            <i class="icon-upload-alt"></i>
                            {l s='Charger le fichier' mod='ecordersandstock'}
                    </button>
                </div>
            </div>
        </form>
    </div>
    {foreach $tasks as $task}
        <div class="panel col-lg-12 ecoas_cpanel" data-id_cpanel="{$task.id_cpanel}" data-prefix="{$task.prefix}" data-suffix="{$task.suffix}" data-position="{$task.position}" style="box-shadow:3px 3px grey;">
            <h3>
                <div class="col-lg-12">
                    <label class="control-label col-lg-3">{l s='Control panel for' mod='ecordersandstock'} "{l s=$task.name mod='ecordersandstock'}"</label>
                    <div class="col-lg-1">
                        <button class="btn btn-success col-lg-6" onclick="javascript:$.post('{$task.link}');confirm_task_start();return false;">
                            {l s='Start' mod='ecordersandstock'}
                        </button>
                        <button class="btn btn-danger col-lg-6" onclick="javascript:$.post('{$task.link|cat:'&kill'}');confirm_task_stop();return false;">
                            {l s='Stop' mod='ecordersandstock'}
                        </button>
                    </div>
                    <div class="margin-form col-lg-8">
                        <input type="text" value="{$task.link}" readonly style="cursor:text;"/>
                    </div>
                </div>
            </h3>
            <i>{l s='Keep the cursor inside this frame to update it' mod='ecordersandstock'}</i>
            <div class="ecoas_vpanel" id="ecoas_vpanel"></div>
        </div>
    {/foreach}
</div>

<div style="clear:both"></div>
