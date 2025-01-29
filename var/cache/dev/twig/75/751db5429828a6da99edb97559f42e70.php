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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig */
class __TwigTemplate_d2eaaa0b77010c4f2ba66f3d4c7a23e2 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig"));

        // line 25
        echo "
<div class=\"modal fade\" id=\"updateOrderShippingModal\" tabindex=\"-1\" role=\"dialog\">
  <div class=\"modal-dialog\" role=\"document\">
    ";
        // line 28
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 28, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_update_shipping", ["orderId" => twig_get_attribute($this->env, $this->source,         // line 29
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 29, $this->source); })()), "id", [], "any", false, false, false, 29)])]);
        // line 30
        echo "
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <h5 class=\"modal-title\">";
        // line 33
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit shipping details", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</h5>
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Close", [], "Admin.Actions"), "html", null, true);
        echo "\">
            <span aria-hidden=\"true\">×</span>
          </button>
        </div>
        <div class=\"modal-body\">
          ";
        // line 39
        if ( !$this->extensions['PrestaShopBundle\Twig\LayoutExtension']->getConfiguration("PS_ORDER_RECALCULATE_SHIPPING")) {
            // line 40
            echo "            <div class=\"alert alert-info\" role=\"alert\">
              <p class=\"alert-text\">
                ";
            // line 42
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Please note that carrier change will not recalculate your shipping costs, if you want to change this please visit Shop Parameters > Order Settings", [], "Admin.Orderscustomers.Notification"), "html", null, true);
            echo "
              </p>
            </div>
          ";
        }
        // line 46
        echo "
          <div class=\"form-group\">
            <label class=\"form-control-label\" for=\"";
        // line 48
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 48, $this->source); })()), "tracking_number", [], "any", false, false, false, 48), "vars", [], "any", false, false, false, 48), "id", [], "any", false, false, false, 48), "html", null, true);
        echo "\">
              ";
        // line 49
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Tracking number", [], "Admin.Shipping.Feature"), "html", null, true);
        echo "
            </label>

            ";
        // line 52
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 52, $this->source); })()), "tracking_number", [], "any", false, false, false, 52), 'widget');
        echo "
          </div>

          <div class=\"form-group\">
            <label class=\"form-control-label\" for=\"";
        // line 56
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 56, $this->source); })()), "new_carrier_id", [], "any", false, false, false, 56), "vars", [], "any", false, false, false, 56), "id", [], "any", false, false, false, 56), "html", null, true);
        echo "\">
              ";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Carrier", [], "Admin.Global"), "html", null, true);
        echo "
            </label>

            ";
        // line 60
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 60, $this->source); })()), "new_carrier_id", [], "any", false, false, false, 60), 'widget');
        echo "
          </div>

          <div class=\"d-none\">
            ";
        // line 64
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 64, $this->source); })()), 'rest');
        echo "
          </div>
        </div>
        <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
            ";
        // line 69
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "html", null, true);
        echo "
          </button>
          <button type=\"submit\" class=\"btn btn-primary\">
            ";
        // line 72
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Update", [], "Admin.Actions"), "html", null, true);
        echo "
          </button>
        </div>
      </div>
    ";
        // line 76
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderShippingForm"]) || array_key_exists("updateOrderShippingForm", $context) ? $context["updateOrderShippingForm"] : (function () { throw new RuntimeError('Variable "updateOrderShippingForm" does not exist.', 76, $this->source); })()), 'form_end');
        echo "
  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  140 => 76,  133 => 72,  127 => 69,  119 => 64,  112 => 60,  106 => 57,  102 => 56,  95 => 52,  89 => 49,  85 => 48,  81 => 46,  74 => 42,  70 => 40,  68 => 39,  60 => 34,  56 => 33,  51 => 30,  49 => 29,  48 => 28,  43 => 25,);
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

<div class=\"modal fade\" id=\"updateOrderShippingModal\" tabindex=\"-1\" role=\"dialog\">
  <div class=\"modal-dialog\" role=\"document\">
    {{ form_start(updateOrderShippingForm, {
      'action': path('admin_orders_update_shipping', {'orderId': orderForViewing.id})
    }) }}
      <div class=\"modal-content\">
        <div class=\"modal-header\">
          <h5 class=\"modal-title\">{{ 'Edit shipping details'|trans({}, 'Admin.Orderscustomers.Feature') }}</h5>
          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"{{ 'Close'|trans({}, 'Admin.Actions') }}\">
            <span aria-hidden=\"true\">×</span>
          </button>
        </div>
        <div class=\"modal-body\">
          {% if not configuration('PS_ORDER_RECALCULATE_SHIPPING') %}
            <div class=\"alert alert-info\" role=\"alert\">
              <p class=\"alert-text\">
                {{ 'Please note that carrier change will not recalculate your shipping costs, if you want to change this please visit Shop Parameters > Order Settings'|trans({}, 'Admin.Orderscustomers.Notification') }}
              </p>
            </div>
          {% endif %}

          <div class=\"form-group\">
            <label class=\"form-control-label\" for=\"{{ updateOrderShippingForm.tracking_number.vars.id }}\">
              {{ 'Tracking number'|trans({}, 'Admin.Shipping.Feature') }}
            </label>

            {{ form_widget(updateOrderShippingForm.tracking_number) }}
          </div>

          <div class=\"form-group\">
            <label class=\"form-control-label\" for=\"{{ updateOrderShippingForm.new_carrier_id.vars.id }}\">
              {{ 'Carrier'|trans({}, 'Admin.Global') }}
            </label>

            {{ form_widget(updateOrderShippingForm.new_carrier_id) }}
          </div>

          <div class=\"d-none\">
            {{ form_rest(updateOrderShippingForm) }}
          </div>
        </div>
        <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
            {{ 'Cancel'|trans({}, 'Admin.Actions') }}
          </button>
          <button type=\"submit\" class=\"btn btn-primary\">
            {{ 'Update'|trans({}, 'Admin.Actions') }}
          </button>
        </div>
      </div>
    {{ form_end(updateOrderShippingForm) }}
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig");
    }
}
