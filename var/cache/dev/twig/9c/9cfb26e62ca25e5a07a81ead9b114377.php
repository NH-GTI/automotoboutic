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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig */
class __TwigTemplate_6a61ae396a19cce45bf3f169ee2294e4 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig"));

        // line 25
        echo "
  ";
        // line 26
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 26, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_update_status", ["orderId" => twig_get_attribute($this->env, $this->source,         // line 27
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 27, $this->source); })()), "id", [], "any", false, false, false, 27)]), "attr" => ["id" => "update_order_status_action_form"]]);
        // line 31
        echo "

  <div class=\"input-group\">
    ";
        // line 34
        $context["backgroundColor"] = "#ffffff";
        // line 35
        echo "    ";
        $context["isBright"] = true;
        // line 36
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 36, $this->source); })()), "new_order_status_id", [], "any", false, false, false, 36), "vars", [], "any", false, false, false, 36), "choices", [], "any", false, false, false, 36));
        foreach ($context['_seq'] as $context["_key"] => $context["choice"]) {
            // line 37
            echo "      ";
            if ((twig_get_attribute($this->env, $this->source, $context["choice"], "value", [], "any", false, false, false, 37) == twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 37, $this->source); })()), "new_order_status_id", [], "any", false, false, false, 37), "vars", [], "any", false, false, false, 37), "data", [], "any", false, false, false, 37))) {
                // line 38
                echo "        ";
                $context["backgroundColor"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["choice"], "attr", [], "any", false, false, false, 38), "data-background-color", [], "array", false, false, false, 38);
                // line 39
                echo "        ";
                $context["isBright"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["choice"], "attr", [], "any", false, false, false, 39), "data-is-bright", [], "array", false, false, false, 39);
                // line 40
                echo "      ";
            }
            // line 41
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['choice'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "    <div class=\"select-status";
        if ((isset($context["isBright"]) || array_key_exists("isBright", $context) ? $context["isBright"] : (function () { throw new RuntimeError('Variable "isBright" does not exist.', 42, $this->source); })())) {
            echo " is-bright";
        }
        echo "\" id=\"update_order_status_action_input_wrapper\" style=\"background-color:";
        echo twig_escape_filter($this->env, (isset($context["backgroundColor"]) || array_key_exists("backgroundColor", $context) ? $context["backgroundColor"] : (function () { throw new RuntimeError('Variable "backgroundColor" does not exist.', 42, $this->source); })()), "html", null, true);
        echo ";\">
        ";
        // line 43
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 43, $this->source); })()), "new_order_status_id", [], "any", false, false, false, 43), 'widget', ["attr" => ["class" => "select-status-colored"], "id" => "update_order_status_action_input"]);
        // line 48
        echo "
    </div>

    <button class=\"btn btn-action ml-2\"
            id=\"update_order_status_action_btn\"
            disabled
            data-order-status-id=\"";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 54, $this->source); })()), "history", [], "any", false, false, false, 54), "currentOrderStatusId", [], "any", false, false, false, 54), "html", null, true);
        echo "\"
    >
      ";
        // line 56
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Update status", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
    </button>
  </div>

  <div class=\"d-none\">
    ";
        // line 61
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 61, $this->source); })()), 'rest');
        echo "
  </div>
  ";
        // line 63
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderStatusActionBarForm"]) || array_key_exists("updateOrderStatusActionBarForm", $context) ? $context["updateOrderStatusActionBarForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusActionBarForm" does not exist.', 63, $this->source); })()), 'form_end');
        echo "

  ";
        // line 65
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 65, $this->source); })()), "documents", [], "any", false, false, false, 65), "canGenerateInvoice", [], "any", false, false, false, 65)) {
            // line 66
            echo "    <form class=\"order-actions-invoice\">
      <div class=\"input-group\">
        <a href=\"";
            // line 68
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_generate_invoice_pdf", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 68, $this->source); })()), "id", [], "any", false, false, false, 68)]), "html", null, true);
            echo "\"
           class=\"btn btn-action\" data-role=\"view-invoice\">
          <i class=\"material-icons\">receipt</i>
          ";
            // line 71
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View invoice", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        </a>
      </div>
    </form>
  ";
        }
        // line 76
        echo "
  <form class=\"order-actions-print\">
    <div class=\"input-group\">
      <button type=\"button\" class=\"btn btn-action js-print-order-view-page\">
        <i class=\"material-icons\" aria-hidden=\"true\">print</i>
        ";
        // line 81
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Print order", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
      </button>
    </div>
  </form>

  ";
        // line 86
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 86, $this->source); })()), "documents", [], "any", false, false, false, 86), "canGenerateDeliverySlip", [], "any", false, false, false, 86)) {
            // line 87
            echo "    <form class=\"order-actions-delivery\">
      <div class=\"input-group\">
        <a href=\"";
            // line 89
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_generate_delivery_slip_pdf", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 89, $this->source); })()), "id", [], "any", false, false, false, 89)]), "html", null, true);
            echo "\"
           class=\"btn btn-action\" data-role=\"view-delivery-slip\">
          <i class=\"material-icons\">local_shipping</i>
          ";
            // line 92
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View delivery slip", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        </a>
      </div>
    </form>
  ";
        }
        // line 97
        echo "
  ";
        // line 98
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig", 98)->display($context);
        // line 99
        echo "
  ";
        // line 100
        if ((isset($context["merchandiseReturnEnabled"]) || array_key_exists("merchandiseReturnEnabled", $context) ? $context["merchandiseReturnEnabled"] : (function () { throw new RuntimeError('Variable "merchandiseReturnEnabled" does not exist.', 100, $this->source); })())) {
            // line 101
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 101, $this->source); })()), "isDelivered", [], "method", false, false, false, 101)) {
                // line 102
                echo "      <button class=\"btn btn-action return-product-display\" type=\"button\"";
                if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 102, $this->source); })()), "isRefundable", [], "method", false, false, false, 102)) {
                    echo " disabled";
                }
                echo ">
        <i class=\"material-icons\">swap_horiz</i>
        ";
                // line 104
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Return products", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
      </button>
    ";
            } elseif ((twig_get_attribute($this->env, $this->source,             // line 106
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 106, $this->source); })()), "hasBeenPaid", [], "method", false, false, false, 106) || twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 106, $this->source); })()), "hasPayments", [], "method", false, false, false, 106))) {
                // line 107
                echo "      <button class=\"btn btn-action standard-refund-display\" type=\"button\"";
                if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 107, $this->source); })()), "isRefundable", [], "method", false, false, false, 107)) {
                    echo " disabled";
                }
                echo ">
        <i class=\"material-icons\">swap_horiz</i>
        ";
                // line 109
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Standard refund", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
      </button>
    ";
            } else {
                // line 112
                echo "      <button class=\"btn btn-action cancel-product-display\" type=\"button\">
        ";
                // line 113
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel products", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
      </button>
    ";
            }
            // line 116
            echo "  ";
        }
        // line 117
        echo "
  ";
        // line 118
        if ((twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 118, $this->source); })()), "hasBeenPaid", [], "method", false, false, false, 118) || twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 118, $this->source); })()), "hasPayments", [], "method", false, false, false, 118))) {
            // line 119
            echo "      <button class=\"btn btn-action partial-refund-display\" type=\"button\"";
            if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 119, $this->source); })()), "isRefundable", [], "method", false, false, false, 119)) {
                echo " disabled";
            }
            echo ">
        <i class=\"material-icons\">swap_horiz</i>
        ";
            // line 121
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Partial refund", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
      </button>
  ";
        }
        // line 124
        echo "
  ";
        // line 125
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig", 125)->display($context);
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  255 => 125,  252 => 124,  246 => 121,  238 => 119,  236 => 118,  233 => 117,  230 => 116,  224 => 113,  221 => 112,  215 => 109,  207 => 107,  205 => 106,  200 => 104,  192 => 102,  189 => 101,  187 => 100,  184 => 99,  182 => 98,  179 => 97,  171 => 92,  165 => 89,  161 => 87,  159 => 86,  151 => 81,  144 => 76,  136 => 71,  130 => 68,  126 => 66,  124 => 65,  119 => 63,  114 => 61,  106 => 56,  101 => 54,  93 => 48,  91 => 43,  82 => 42,  76 => 41,  73 => 40,  70 => 39,  67 => 38,  64 => 37,  59 => 36,  56 => 35,  54 => 34,  49 => 31,  47 => 27,  46 => 26,  43 => 25,);
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

  {{ form_start(updateOrderStatusActionBarForm, {
    'action': path('admin_orders_update_status', {'orderId': orderForViewing.id}),
    'attr': {
      'id': 'update_order_status_action_form'
    }
  }) }}

  <div class=\"input-group\">
    {% set backgroundColor = '#ffffff' %}
    {% set isBright = true %}
    {% for choice in updateOrderStatusActionBarForm.new_order_status_id.vars.choices %}
      {% if choice.value == updateOrderStatusActionBarForm.new_order_status_id.vars.data %}
        {% set backgroundColor = choice.attr['data-background-color'] %}
        {% set isBright = choice.attr['data-is-bright'] %}
      {% endif %}
    {% endfor %}
    <div class=\"select-status{% if isBright %} is-bright{% endif %}\" id=\"update_order_status_action_input_wrapper\" style=\"background-color:{{ backgroundColor }};\">
        {{ form_widget(updateOrderStatusActionBarForm.new_order_status_id, {
          'attr': {
            'class': 'select-status-colored',
          },
          'id': 'update_order_status_action_input',
        }) }}
    </div>

    <button class=\"btn btn-action ml-2\"
            id=\"update_order_status_action_btn\"
            disabled
            data-order-status-id=\"{{ orderForViewing.history.currentOrderStatusId }}\"
    >
      {{ 'Update status'|trans({}, 'Admin.Orderscustomers.Feature') }}
    </button>
  </div>

  <div class=\"d-none\">
    {{ form_rest(updateOrderStatusActionBarForm) }}
  </div>
  {{ form_end(updateOrderStatusActionBarForm) }}

  {% if orderForViewing.documents.canGenerateInvoice %}
    <form class=\"order-actions-invoice\">
      <div class=\"input-group\">
        <a href=\"{{ path('admin_orders_generate_invoice_pdf', {'orderId': orderForViewing.id}) }}\"
           class=\"btn btn-action\" data-role=\"view-invoice\">
          <i class=\"material-icons\">receipt</i>
          {{ 'View invoice'|trans({}, 'Admin.Orderscustomers.Feature') }}
        </a>
      </div>
    </form>
  {% endif %}

  <form class=\"order-actions-print\">
    <div class=\"input-group\">
      <button type=\"button\" class=\"btn btn-action js-print-order-view-page\">
        <i class=\"material-icons\" aria-hidden=\"true\">print</i>
        {{ 'Print order'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
    </div>
  </form>

  {% if orderForViewing.documents.canGenerateDeliverySlip %}
    <form class=\"order-actions-delivery\">
      <div class=\"input-group\">
        <a href=\"{{ path('admin_orders_generate_delivery_slip_pdf', {'orderId': orderForViewing.id}) }}\"
           class=\"btn btn-action\" data-role=\"view-delivery-slip\">
          <i class=\"material-icons\">local_shipping</i>
          {{ 'View delivery slip'|trans({}, 'Admin.Orderscustomers.Feature') }}
        </a>
      </div>
    </form>
  {% endif %}

  {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig' %}

  {% if merchandiseReturnEnabled %}
    {% if orderForViewing.isDelivered() %}
      <button class=\"btn btn-action return-product-display\" type=\"button\"{% if not orderForViewing.isRefundable() %} disabled{% endif %}>
        <i class=\"material-icons\">swap_horiz</i>
        {{ 'Return products'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
    {% elseif orderForViewing.hasBeenPaid() or orderForViewing.hasPayments() %}
      <button class=\"btn btn-action standard-refund-display\" type=\"button\"{% if not orderForViewing.isRefundable() %} disabled{% endif %}>
        <i class=\"material-icons\">swap_horiz</i>
        {{ 'Standard refund'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
    {% else %}
      <button class=\"btn btn-action cancel-product-display\" type=\"button\">
        {{ 'Cancel products'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
    {% endif %}
  {% endif %}

  {% if orderForViewing.hasBeenPaid() or orderForViewing.hasPayments() %}
      <button class=\"btn btn-action partial-refund-display\" type=\"button\"{% if not orderForViewing.isRefundable() %} disabled{% endif %}>
        <i class=\"material-icons\">swap_horiz</i>
        {{ 'Partial refund'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
  {% endif %}

  {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig' %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig");
    }
}
