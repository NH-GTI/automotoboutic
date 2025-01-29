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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig */
class __TwigTemplate_59a8cb0da40e4dc538c2d0efa6c4b621 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'order_message_form_rest' => [$this, 'block_order_message_form_rest'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig"));

        // line 25
        echo "
";
        // line 26
        $macros["ps"] = $this->macros["ps"] = $this->loadTemplate("@PrestaShop/Admin/macros.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig", 26)->unwrap();
        // line 27
        echo "
";
        // line 28
        $context["messagesToShow"] = 4;
        // line 29
        echo "
<div class=\"card mt-2\" data-role=\"message-card\" >
  <div class=\"card-header\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <h3 class=\"card-header-title\">
          ";
        // line 35
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Messages", [], "Admin.Global"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 35, $this->source); })()), "messages", [], "any", false, false, false, 35), "total", [], "any", false, false, false, 35), "html", null, true);
        echo ")
        </h3>
      </div>
      ";
        // line 38
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 38, $this->source); })()), "messages", [], "any", false, false, false, 38), "total", [], "any", false, false, false, 38) > (isset($context["messagesToShow"]) || array_key_exists("messagesToShow", $context) ? $context["messagesToShow"] : (function () { throw new RuntimeError('Variable "messagesToShow" does not exist.', 38, $this->source); })()))) {
            // line 39
            echo "        <div class=\"col-md-6 text-right\">
          <a
            href=\"#\"
            data-toggle=\"modal\"
            data-target=\"#view_all_messages_modal\"
            class=\"d-print-none js-open-all-messages-btn\"
          >
            ";
            // line 46
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View full conversation", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
          </a>
        </div>
      ";
        }
        // line 50
        echo "    </div>
  </div>

  ";
        // line 53
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 53, $this->source); })()), "messages", [], "any", false, false, false, 53), "messages", [], "any", false, false, false, 53))) {
            // line 54
            echo "    <ul class=\"list-unstyled messages-block\">
      ";
            // line 55
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_reverse_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 55, $this->source); })()), "messages", [], "any", false, false, false, 55), "messages", [], "any", false, false, false, 55), 0, (isset($context["messagesToShow"]) || array_key_exists("messagesToShow", $context) ? $context["messagesToShow"] : (function () { throw new RuntimeError('Variable "messagesToShow" does not exist.', 55, $this->source); })()))));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 56
                echo "        ";
                $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/msg_list_item.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig", 56)->display($context);
                // line 57
                echo "      ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 58
            echo "    </ul>
  ";
        }
        // line 60
        echo "
  <div class=\"card-body d-print-none\">
    ";
        // line 62
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 62, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_send_message", ["orderId" => twig_get_attribute($this->env, $this->source,         // line 63
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 63, $this->source); })()), "id", [], "any", false, false, false, 63)])]);
        // line 64
        echo "

    ";
        // line 66
        echo twig_call_macro($macros["ps"], "macro_form_group_row", [twig_get_attribute($this->env, $this->source, (isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 66, $this->source); })()), "order_message", [], "any", false, false, false, 66), [], ["label" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Choose your order message", [], "Admin.Orderscustomers.Feature"), "label_on_top" => true, "class" => "mb-0"]], 66, $context, $this->getSourceContext());
        // line 70
        echo "

    <div class=\"js-order-messages-container d-none\">
      <div class=\"js-message-change-warning\">";
        // line 73
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Do you want to overwrite your existing message?", [], "Admin.Orderscustomers.Notification"), "html", null, true);
        echo "</div>
      ";
        // line 74
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 74, $this->source); })()), "vars", [], "any", false, false, false, 74), "messages", [], "array", false, false, false, 74));
        foreach ($context['_seq'] as $context["id"] => $context["message"]) {
            // line 75
            echo "        <div data-id=\"";
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\">
          ";
            // line 76
            echo twig_escape_filter($this->env, $context["message"], "html", null, true);
            echo "
        </div>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['id'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 79
        echo "    </div>

    <div class=\"form-group row configure\">
      <div class=\"col-sm\">
        <a href=\"";
        // line 83
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_order_messages_index");
        echo "\" class=\"configure-link\">
          ";
        // line 84
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Configure predefined messages", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
          <i class=\"material-icons rtl-flip\">arrow_right_alt</i>
        </a>
      </div>
    </div>

    ";
        // line 90
        echo twig_call_macro($macros["ps"], "macro_form_group_row", [twig_get_attribute($this->env, $this->source, (isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 90, $this->source); })()), "is_displayed_to_customer", [], "any", false, false, false, 90), ["material_design" => true]], 90, $context, $this->getSourceContext());
        echo "

    ";
        // line 92
        echo twig_call_macro($macros["ps"], "macro_form_group_row", [twig_get_attribute($this->env, $this->source, (isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 92, $this->source); })()), "message", [], "any", false, false, false, 92), ["attr" => ["cols" => 30, "rows" => 3]], ["label" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Message", [], "Admin.Global"), "label_on_top" => true, "class" => "js-text-with-length-counter"]], 92, $context, $this->getSourceContext());
        // line 96
        echo "

    ";
        // line 98
        $this->displayBlock('order_message_form_rest', $context, $blocks);
        // line 101
        echo "
    <div class=\"text-right\">
      <button type=\"submit\" class=\"btn btn-primary\">";
        // line 103
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Send message", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</button>
    </div>

    ";
        // line 106
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 106, $this->source); })()), 'form_end');
        echo "
  </div>
</div>

";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 98
    public function block_order_message_form_rest($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "order_message_form_rest"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "order_message_form_rest"));

        // line 99
        echo "      ";
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["orderMessageForm"]) || array_key_exists("orderMessageForm", $context) ? $context["orderMessageForm"] : (function () { throw new RuntimeError('Variable "orderMessageForm" does not exist.', 99, $this->source); })()), 'rest');
        echo "
    ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  243 => 99,  233 => 98,  218 => 106,  212 => 103,  208 => 101,  206 => 98,  202 => 96,  200 => 92,  195 => 90,  186 => 84,  182 => 83,  176 => 79,  167 => 76,  162 => 75,  158 => 74,  154 => 73,  149 => 70,  147 => 66,  143 => 64,  141 => 63,  140 => 62,  136 => 60,  132 => 58,  118 => 57,  115 => 56,  98 => 55,  95 => 54,  93 => 53,  88 => 50,  81 => 46,  72 => 39,  70 => 38,  62 => 35,  54 => 29,  52 => 28,  49 => 27,  47 => 26,  44 => 25,);
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

{% import '@PrestaShop/Admin/macros.html.twig' as ps %}

{% set messagesToShow = 4 %}

<div class=\"card mt-2\" data-role=\"message-card\" >
  <div class=\"card-header\">
    <div class=\"row\">
      <div class=\"col-md-6\">
        <h3 class=\"card-header-title\">
          {{ 'Messages'|trans({}, 'Admin.Global') }} ({{ orderForViewing.messages.total }})
        </h3>
      </div>
      {% if orderForViewing.messages.total > messagesToShow %}
        <div class=\"col-md-6 text-right\">
          <a
            href=\"#\"
            data-toggle=\"modal\"
            data-target=\"#view_all_messages_modal\"
            class=\"d-print-none js-open-all-messages-btn\"
          >
            {{ 'View full conversation'|trans({}, 'Admin.Orderscustomers.Feature') }}
          </a>
        </div>
      {% endif %}
    </div>
  </div>

  {% if orderForViewing.messages.messages is not empty %}
    <ul class=\"list-unstyled messages-block\">
      {% for message in orderForViewing.messages.messages|slice(0, messagesToShow)|reverse %}
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/msg_list_item.html.twig' %}
      {% endfor %}
    </ul>
  {% endif %}

  <div class=\"card-body d-print-none\">
    {{ form_start(orderMessageForm, {
      'action': path('admin_orders_send_message', { orderId: orderForViewing.id })
    }) }}

    {{ ps.form_group_row(orderMessageForm.order_message, {}, {
      'label': 'Choose your order message'|trans({}, 'Admin.Orderscustomers.Feature'),
      'label_on_top': true,
      'class': 'mb-0'
    }) }}

    <div class=\"js-order-messages-container d-none\">
      <div class=\"js-message-change-warning\">{{ 'Do you want to overwrite your existing message?'|trans({}, 'Admin.Orderscustomers.Notification') }}</div>
      {% for id, message in orderMessageForm.vars['messages'] %}
        <div data-id=\"{{ id }}\">
          {{ message }}
        </div>
      {% endfor %}
    </div>

    <div class=\"form-group row configure\">
      <div class=\"col-sm\">
        <a href=\"{{ path('admin_order_messages_index') }}\" class=\"configure-link\">
          {{ 'Configure predefined messages'|trans({}, 'Admin.Orderscustomers.Feature') }}
          <i class=\"material-icons rtl-flip\">arrow_right_alt</i>
        </a>
      </div>
    </div>

    {{ ps.form_group_row(orderMessageForm.is_displayed_to_customer, {'material_design': true}) }}

    {{ ps.form_group_row(orderMessageForm.message, {'attr': { 'cols':30, 'rows':3 }}, {
      'label': 'Message'|trans({}, 'Admin.Global'),
      'label_on_top': true,
      'class': 'js-text-with-length-counter'
    }) }}

    {% block order_message_form_rest %}
      {{ form_rest(orderMessageForm) }}
    {% endblock %}

    <div class=\"text-right\">
      <button type=\"submit\" class=\"btn btn-primary\">{{ 'Send message'|trans({}, 'Admin.Orderscustomers.Feature') }}</button>
    </div>

    {{ form_end(orderMessageForm) }}
  </div>
</div>

", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/messages.html.twig");
    }
}
