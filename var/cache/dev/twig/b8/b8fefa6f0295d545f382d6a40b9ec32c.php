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

/* @PrestaShop/Admin/Sell/Order/Order/view.html.twig */
class __TwigTemplate_88981e3e2e7f979a7b6e136fd614a0c0 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'content' => [$this, 'block_content'],
            'javascripts' => [$this, 'block_javascripts'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 31
        return "@PrestaShop/Admin/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig"));

        // line 26
        $context["use_regular_h1_structure"] = false;
        // line 27
        ob_start();
        // line 28
        echo "  ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 28)->display($context);
        $context["layoutTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 109
        $context["js_translatable"] = twig_array_merge(["The product was successfully added." => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("The product was successfully added.", [], "Admin.Notifications.Success"), "The product was successfully removed." => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("The product was successfully removed.", [], "Admin.Notifications.Success"), "[1] products were successfully added." => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("[1] products were successfully added.", [], "Admin.Notifications.Success"), "[1] products were successfully removed." => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("[1] products were successfully removed.", [], "Admin.Notifications.Success")],         // line 114
(isset($context["js_translatable"]) || array_key_exists("js_translatable", $context) ? $context["js_translatable"] : (function () { throw new RuntimeError('Variable "js_translatable" does not exist.', 114, $this->source); })()));
        // line 31
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/layout.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 31);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 33
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 34
        echo "  ";
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
  <link rel=\"stylesheet\" href=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl((("themes/new-theme/public/orders" . (isset($context["rtl_suffix"]) || array_key_exists("rtl_suffix", $context) ? $context["rtl_suffix"] : (function () { throw new RuntimeError('Variable "rtl_suffix" does not exist.', 35, $this->source); })())) . ".css")), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\">
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 38
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 39
        echo "  <div id=\"order-view-page\" data-order-title=\"";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Order", [], "Admin.Global"), "html", null, true);
        echo " #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 39, $this->source); })()), "id", [], "any", false, false, false, 39), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 39, $this->source); })()), "reference", [], "any", false, false, false, 39), "html", null, true);
        echo "\">
    <div class=\"d-print-none\">
      ";
        // line 41
        $context["displayAdminOrderTopHookContent"] = $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderTop", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 41, $this->source); })()), "id", [], "any", false, false, false, 41)]);
        // line 42
        echo "      ";
        if (((isset($context["displayAdminOrderTopHookContent"]) || array_key_exists("displayAdminOrderTopHookContent", $context) ? $context["displayAdminOrderTopHookContent"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTopHookContent" does not exist.', 42, $this->source); })()) != "")) {
            // line 43
            echo "        ";
            echo (isset($context["displayAdminOrderTopHookContent"]) || array_key_exists("displayAdminOrderTopHookContent", $context) ? $context["displayAdminOrderTopHookContent"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTopHookContent" does not exist.', 43, $this->source); })());
            echo "
      ";
        }
        // line 45
        echo "      <div class=\"order-actions\">
        ";
        // line 46
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 46)->display($context);
        // line 47
        echo "      </div>
    </div>

    <div class=\"d-none d-print-block mb-4\">
      ";
        // line 51
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 51)->display($context);
        // line 52
        echo "    </div>

    <div id=\"orderProductsModificationPosition\" class=\"d-none mb-4\"></div>

    <div class=\"d-none d-print-block mb-4\">
      ";
        // line 57
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_title.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 57)->display($context);
        // line 58
        echo "    </div>

    <div class=\"product-row row\">
      <div class=\"col-md-4 left-column\">
        ";
        // line 62
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 62)->display($context);
        // line 63
        echo "        ";
        echo $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderSide", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 63, $this->source); })()), "id", [], "any", false, false, false, 63)]);
        echo "
        ";
        // line 64
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 64)->display($context);
        // line 65
        echo "        ";
        echo $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderSideBottom", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 65, $this->source); })()), "id", [], "any", false, false, false, 65)]);
        echo "
      </div>

      <div class=\"col-md-8 d-print-block right-column\">
        <div id=\"orderProductsOriginalPosition\">
          ";
        // line 70
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 70)->display($context);
        // line 71
        echo "        </div>
        ";
        // line 72
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 72)->display($context);
        // line 73
        echo "        ";
        echo $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderMain", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 73, $this->source); })()), "id", [], "any", false, false, false, 73)]);
        echo "
        ";
        // line 74
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 74)->display($context);
        // line 75
        echo "        ";
        echo $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderMainBottom", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 75, $this->source); })()), "id", [], "any", false, false, false, 75)]);
        echo "
      </div>
    </div>

    ";
        // line 79
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 79, $this->source); })()), "sources", [], "any", false, false, false, 79), "sources", [], "any", false, false, false, 79))) {
            // line 80
            echo "      <div class=\"product-row\">
        <div class=\"left-column\">
          ";
            // line 82
            $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 82)->display($context);
            // line 83
            echo "        </div>
      </div>
    ";
        }
        // line 86
        echo "
    ";
        // line 87
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 87, $this->source); })()), "linkedOrders", [], "any", false, false, false, 87), "linkedOrders", [], "any", false, false, false, 87))) {
            // line 88
            echo "      ";
            $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/linked_orders.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 88)->display($context);
            // line 89
            echo "    ";
        }
        // line 90
        echo "
    ";
        // line 91
        echo $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrder", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 91, $this->source); })()), "id", [], "any", false, false, false, 91)]);
        echo "

    ";
        // line 93
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 93)->display($context);
        // line 94
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 94)->display($context);
        // line 95
        echo "    ";
        if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 95, $this->source); })()), "customer", [], "any", false, false, false, 95)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 95, $this->source); })()), "customer", [], "any", false, false, false, 95), "id", [], "any", false, false, false, 95) != 0))) {
            // line 96
            echo "      ";
            $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 96)->display($context);
            // line 97
            echo "    ";
        }
        // line 98
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_all_messages_modal.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 98)->display($context);
        // line 99
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", 99)->display($context);
        // line 100
        echo "  </div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 103
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 104
        echo "  ";
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "

  <script src=\"";
        // line 106
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("themes/new-theme/public/order_view.bundle.js"), "html", null, true);
        echo "\"></script>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  277 => 106,  271 => 104,  261 => 103,  250 => 100,  247 => 99,  244 => 98,  241 => 97,  238 => 96,  235 => 95,  232 => 94,  230 => 93,  225 => 91,  222 => 90,  219 => 89,  216 => 88,  214 => 87,  211 => 86,  206 => 83,  204 => 82,  200 => 80,  198 => 79,  190 => 75,  188 => 74,  183 => 73,  181 => 72,  178 => 71,  176 => 70,  167 => 65,  165 => 64,  160 => 63,  158 => 62,  152 => 58,  150 => 57,  143 => 52,  141 => 51,  135 => 47,  133 => 46,  130 => 45,  124 => 43,  121 => 42,  119 => 41,  109 => 39,  99 => 38,  87 => 35,  82 => 34,  72 => 33,  61 => 31,  59 => 114,  58 => 109,  54 => 28,  52 => 27,  50 => 26,  37 => 31,);
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

{% set use_regular_h1_structure = false %}
{% set layoutTitle %}
  {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig' %}
{% endset %}

{% extends '@PrestaShop/Admin/layout.html.twig' %}

{% block stylesheets %}
  {{ parent() }}
  <link rel=\"stylesheet\" href=\"{{ asset('themes/new-theme/public/orders' ~ rtl_suffix ~ '.css') }}\" type=\"text/css\" media=\"all\">
{% endblock %}

{% block content %}
  <div id=\"order-view-page\" data-order-title=\"{{ 'Order'|trans({}, 'Admin.Global') }} #{{ orderForViewing.id }} {{ orderForViewing.reference }}\">
    <div class=\"d-print-none\">
      {% set displayAdminOrderTopHookContent = renderhook('displayAdminOrderTop', {'id_order': orderForViewing.id}) %}
      {% if displayAdminOrderTopHookContent != '' %}
        {{ displayAdminOrderTopHookContent|raw }}
      {% endif %}
      <div class=\"order-actions\">
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_actions.html.twig' %}
      </div>
    </div>

    <div class=\"d-none d-print-block mb-4\">
      {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig' %}
    </div>

    <div id=\"orderProductsModificationPosition\" class=\"d-none mb-4\"></div>

    <div class=\"d-none d-print-block mb-4\">
      {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_title.html.twig' %}
    </div>

    <div class=\"product-row row\">
      <div class=\"col-md-4 left-column\">
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig' %}
        {{ renderhook('displayAdminOrderSide', {'id_order': orderForViewing.id}) }}
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/messages.html.twig' %}
        {{ renderhook('displayAdminOrderSideBottom', {'id_order': orderForViewing.id}) }}
      </div>

      <div class=\"col-md-8 d-print-block right-column\">
        <div id=\"orderProductsOriginalPosition\">
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig' %}
        </div>
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig' %}
        {{ renderhook('displayAdminOrderMain', {'id_order': orderForViewing.id}) }}
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig' %}
        {{ renderhook('displayAdminOrderMainBottom', {'id_order': orderForViewing.id}) }}
      </div>
    </div>

    {% if orderForViewing.sources.sources is not empty %}
      <div class=\"product-row\">
        <div class=\"left-column\">
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig' %}
        </div>
      </div>
    {% endif %}

    {% if orderForViewing.linkedOrders.linkedOrders is not empty %}
      {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/linked_orders.html.twig' %}
    {% endif %}

    {{ renderhook('displayAdminOrder', {'id_order': orderForViewing.id}) }}

    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig' %}
    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_shipping_modal.html.twig' %}
    {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
      {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/update_customer_address_modal.html.twig' %}
    {% endif %}
    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_all_messages_modal.html.twig' %}
    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig' %}
  </div>
{% endblock %}

{% block javascripts %}
  {{ parent() }}

  <script src=\"{{ asset('themes/new-theme/public/order_view.bundle.js') }}\"></script>
{% endblock %}

{% set js_translatable = {
  \"The product was successfully added.\": 'The product was successfully added.'|trans({}, 'Admin.Notifications.Success'),
  \"The product was successfully removed.\": 'The product was successfully removed.'|trans({}, 'Admin.Notifications.Success'),
  \"[1] products were successfully added.\": '[1] products were successfully added.'|trans({}, 'Admin.Notifications.Success'),
  \"[1] products were successfully removed.\": '[1] products were successfully removed.'|trans({}, 'Admin.Notifications.Success'),
}|merge(js_translatable)
%}
", "@PrestaShop/Admin/Sell/Order/Order/view.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/view.html.twig");
    }
}
