<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig */
class __TwigTemplate_5d5b3760c8b2a25df1414130bb1fc26e extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig"));

        // line 25
        echo "
";
        // line 26
        if ( !(null === (isset($context["changeOrderAddressForm"]) || array_key_exists("changeOrderAddressForm", $context) ? $context["changeOrderAddressForm"] : (function () { throw new RuntimeError('Variable "changeOrderAddressForm" does not exist.', 26, $this->source); })()))) {
            // line 27
            echo "  <div class=\"modal fade\" id=\"updateCustomerAddressModal\">
      <div class=\"modal-dialog\" role=\"document\">
        ";
            // line 29
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["changeOrderAddressForm"]) || array_key_exists("changeOrderAddressForm", $context) ? $context["changeOrderAddressForm"] : (function () { throw new RuntimeError('Variable "changeOrderAddressForm" does not exist.', 29, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_change_customer_address", ["orderId" => twig_get_attribute($this->env, $this->source,             // line 30
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 30, $this->source); })()), "id", [], "any", false, false, false, 30), "customerId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 30, $this->source); })()), "customer", [], "any", false, false, false, 30), "id", [], "any", false, false, false, 30)])]);
            echo "
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h5 class=\"modal-title\">
                ";
            // line 34
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Select another address", [], "Admin.Actions"), "html", null, true);
            echo "
              </h5>

              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"";
            // line 37
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Close", [], "Admin.Actions"), "html", null, true);
            echo "\">
                <span aria-hidden=\"true\">×</span>
              </button>
            </div>

            <div class=\"modal-body\">
              <div class=\"form-group m-0\">
                ";
            // line 44
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["changeOrderAddressForm"]) || array_key_exists("changeOrderAddressForm", $context) ? $context["changeOrderAddressForm"] : (function () { throw new RuntimeError('Variable "changeOrderAddressForm" does not exist.', 44, $this->source); })()), "new_address_id", [], "any", false, false, false, 44), 'widget');
            echo "
              </div>
            </div>

            ";
            // line 48
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["changeOrderAddressForm"]) || array_key_exists("changeOrderAddressForm", $context) ? $context["changeOrderAddressForm"] : (function () { throw new RuntimeError('Variable "changeOrderAddressForm" does not exist.', 48, $this->source); })()), "address_type", [], "any", false, false, false, 48), 'widget');
            echo "

            <div class=\"modal-footer\">
              <button id=\"change-address-cancel-button\" type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
                ";
            // line 52
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "html", null, true);
            echo "
              </button>

              <button id=\"change-address-submit-button\" type=\"submit\" class=\"btn btn-primary\">
                ";
            // line 56
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Select", [], "Admin.Actions"), "html", null, true);
            echo "
              </button>
            </div>
          </div>
        ";
            // line 60
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["changeOrderAddressForm"]) || array_key_exists("changeOrderAddressForm", $context) ? $context["changeOrderAddressForm"] : (function () { throw new RuntimeError('Variable "changeOrderAddressForm" does not exist.', 60, $this->source); })()), 'form_end');
            echo "
      </div>
  </div>
";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 60,  97 => 56,  90 => 52,  83 => 48,  76 => 44,  66 => 37,  60 => 34,  53 => 30,  52 => 29,  48 => 27,  46 => 26,  43 => 25,);
    }

    public function getSourceContext()
    {
        return new Source("{#**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *#}

{% if changeOrderAddressForm is not null %}
  <div class=\"modal fade\" id=\"updateCustomerAddressModal\">
      <div class=\"modal-dialog\" role=\"document\">
        {{ form_start(changeOrderAddressForm,
          {'action': path('admin_orders_change_customer_address', {'orderId': orderForViewing.id, 'customerId': orderForViewing.customer.id})}) }}
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h5 class=\"modal-title\">
                {{ 'Select another address'|trans({}, 'Admin.Actions') }}
              </h5>

              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"{{ 'Close'|trans({}, 'Admin.Actions') }}\">
                <span aria-hidden=\"true\">×</span>
              </button>
            </div>

            <div class=\"modal-body\">
              <div class=\"form-group m-0\">
                {{ form_widget(changeOrderAddressForm.new_address_id) }}
              </div>
            </div>

            {{ form_widget(changeOrderAddressForm.address_type) }}

            <div class=\"modal-footer\">
              <button id=\"change-address-cancel-button\" type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
                {{ 'Cancel'|trans({}, 'Admin.Actions') }}
              </button>

              <button id=\"change-address-submit-button\" type=\"submit\" class=\"btn btn-primary\">
                {{ 'Select'|trans({}, 'Admin.Actions') }}
              </button>
            </div>
          </div>
        {{ form_end(changeOrderAddressForm) }}
      </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig");
    }
}
