{block name='process_steps_navigation'}
    <div class="container head-selector">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="/tapis/step-1" type="button" class="btn-custom{if $step == 1} btn-custom-active{/if}">
                        {l s='1.' d='Modules.Contactform.Shop'} <span
                            class="step_name">{l s='Mon véhicule' d='Modules.Contactform.Shop'}</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="{if $step > 2}{$smarty.server.REQUEST_URI|regex_replace:"/step-[0-9]+/":"step-2"}{else}#step-2{/if}"
                        type="button" class="btn-custom{if $step == 2} btn-custom-active{/if}"
                        {if $step <= 2}disabled="disabled" {/if}>
                        {l s='2.' d='Modules.Contactform.Shop'} <span
                            class="step_name">{l s='Mon modèle' d='Modules.Contactform.Shop'}</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="{if $step > 3}{$smarty.server.REQUEST_URI|regex_replace:"/step-[0-9]+/":"step-3"}{else}#step-3{/if}"
                        type="button" class="btn-custom{if $step == 3} btn-custom-active{/if}"
                        {if $step <= 3}disabled="disabled" {/if}>
                        {l s='3.' d='Modules.Contactform.Shop'} <span
                            class="step_name">{l s='Ma finition' d='Modules.Contactform.Shop'}</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="{if $step > 4}{$smarty.server.REQUEST_URI|regex_replace:"/step-[0-9]+/":"step-4"}{else}#step-4{/if}"
                        type="button" class="btn-custom{if $step == 4} btn-custom-active{/if}"
                        {if $step <= 4}disabled="disabled" {/if}>
                        {l s='4.' d='Modules.Contactform.Shop'} <span
                            class="step_name">{l s='Ma configuration' d='Modules.Contactform.Shop'}</span>
                    </a>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-5" type="button" class="btn-custom{if $step == 5 || $step == 6} btn-custom-active{/if}"
                        {if $step <= 5}disabled="disabled" {/if}>
                        {l s='5.' d='Modules.Contactform.Shop'} {l s='Validation' d='Modules.Contactform.Shop'}
                    </a>
                </div>
            </div>
        </div>
        <!--<div>
            <button id="confidential-mode-button" class="confidential_mode_button">
                {if $smarty.cookies.confidential_mode|escape:"html" == "ON"}
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
                {else}
                    <svg fill="#00cc00" height="800px" width="800px" version="1.1" id="Layer_1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330"
                        xml:space="preserve">
                        <g id="XMLID_516_">
                            <path id="XMLID_517_" d="M15,160c8.284,0,15-6.716,15-15V85c0-30.327,24.673-55,55-55c30.327,0,55,24.673,55,55v45h-25
                        c-8.284,0-15,6.716-15,15v170c0,8.284,6.716,15,15,15h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15H170V85
                        c0-46.869-38.131-85-85-85S0,38.131,0,85v60C0,153.284,6.716,160,15,160z" />
                        </g>
                    </svg>
                {/if}
            </button>
        </div>-->
    </div><!-- end .container -->
{/block}
