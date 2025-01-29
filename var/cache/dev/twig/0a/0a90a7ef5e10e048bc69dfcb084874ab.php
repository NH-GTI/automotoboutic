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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig */
class __TwigTemplate_137c77b92db41f892bba5b039ad1b298 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        // line 25
        echo "
";
        // line 26
        ob_start();
        // line 27
        echo "  ";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Status", [], "Admin.Global"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 27, $this->source); })()), "history", [], "any", false, false, false, 27), "statuses", [], "any", false, false, false, 27)), "html", null, true);
        echo ")
";
        $context["statusTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 29
        echo "
";
        // line 30
        ob_start();
        // line 31
        echo "  ";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Documents", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo " (<span class=\"count\">";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 31, $this->source); })()), "documents", [], "any", false, false, false, 31), "documents", [], "any", false, false, false, 31)), "html", null, true);
        echo "</span>)
";
        $context["documentsTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 33
        echo "
";
        // line 34
        ob_start();
        // line 35
        echo "  ";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Carriers", [], "Admin.Shipping.Feature"), "html", null, true);
        echo " (<span class=\"count\">";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 35, $this->source); })()), "shipping", [], "any", false, false, false, 35), "carriers", [], "any", false, false, false, 35)), "html", null, true);
        echo "</span>)
";
        $context["carriersTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 37
        echo "
";
        // line 38
        ob_start();
        // line 39
        echo "  ";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Merchandise returns", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo " (<span data-role=\"count\">";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 39, $this->source); })()), "returns", [], "any", false, false, false, 39), "orderReturns", [], "any", false, false, false, 39)), "html", null, true);
        echo "</span>)
";
        $context["merchantReturnsTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 41
        echo "
<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        ";
        // line 47
        echo twig_escape_filter($this->env, (isset($context["statusTitle"]) || array_key_exists("statusTitle", $context) ? $context["statusTitle"] : (function () { throw new RuntimeError('Variable "statusTitle" does not exist.', 47, $this->source); })()), "html", null, true);
        echo "
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        ";
        // line 53
        echo twig_escape_filter($this->env, (isset($context["documentsTitle"]) || array_key_exists("documentsTitle", $context) ? $context["documentsTitle"] : (function () { throw new RuntimeError('Variable "documentsTitle" does not exist.', 53, $this->source); })()), "html", null, true);
        echo "
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        ";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["carriersTitle"]) || array_key_exists("carriersTitle", $context) ? $context["carriersTitle"] : (function () { throw new RuntimeError('Variable "carriersTitle" does not exist.', 59, $this->source); })()), "html", null, true);
        echo "
      </a>
    </li>
    ";
        // line 62
        if ((isset($context["merchandiseReturnEnabled"]) || array_key_exists("merchandiseReturnEnabled", $context) ? $context["merchandiseReturnEnabled"] : (function () { throw new RuntimeError('Variable "merchandiseReturnEnabled" does not exist.', 62, $this->source); })())) {
            // line 63
            echo "      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          ";
            // line 66
            echo twig_escape_filter($this->env, (isset($context["merchantReturnsTitle"]) || array_key_exists("merchantReturnsTitle", $context) ? $context["merchantReturnsTitle"] : (function () { throw new RuntimeError('Variable "merchantReturnsTitle" does not exist.', 66, $this->source); })()), "html", null, true);
            echo "
        </a>
      </li>
    ";
        }
        // line 70
        echo "  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      ";
        // line 74
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 74, "2014308716")->display($context);
        // line 82
        echo "    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      ";
        // line 84
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 84, "1859254594")->display($context);
        // line 92
        echo "    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      ";
        // line 94
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 94, "1444475536")->display($context);
        // line 102
        echo "    </div>
    ";
        // line 103
        if ((isset($context["merchandiseReturnEnabled"]) || array_key_exists("merchandiseReturnEnabled", $context) ? $context["merchandiseReturnEnabled"] : (function () { throw new RuntimeError('Variable "merchandiseReturnEnabled" does not exist.', 103, $this->source); })())) {
            // line 104
            echo "      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        ";
            // line 105
            $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 105, "134924896")->display($context);
            // line 113
            echo "      </div>
    ";
        }
        // line 115
        echo "
    ";
        // line 116
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 116, $this->source); })()), "shipping", [], "any", false, false, false, 116), "recycledPackaging", [], "any", false, false, false, 116)) {
            // line 117
            echo "      <span class=\"badge badge-success\">";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Recycled packaging", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</span>
    ";
        }
        // line 119
        echo "
    ";
        // line 120
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 120, $this->source); })()), "shipping", [], "any", false, false, false, 120), "giftWrapping", [], "any", false, false, false, 120)) {
            // line 121
            echo "      <span class=\"badge badge-success\">";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Gift wrapping", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</span>
    ";
        }
        // line 123
        echo "  </div>
</div>

";
        // line 126
        $context["displayAdminOrderTabLink"] = $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderTabLink", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 126, $this->source); })()), "id", [], "any", false, false, false, 126)]);
        // line 127
        $context["displayAdminOrderTabContent"] = $this->extensions['PrestaShopBundle\Twig\HookExtension']->renderHook("displayAdminOrderTabContent", ["id_order" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 127, $this->source); })()), "id", [], "any", false, false, false, 127)]);
        // line 128
        if (( !twig_test_empty((isset($context["displayAdminOrderTabLink"]) || array_key_exists("displayAdminOrderTabLink", $context) ? $context["displayAdminOrderTabLink"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTabLink" does not exist.', 128, $this->source); })())) ||  !twig_test_empty((isset($context["displayAdminOrderTabContent"]) || array_key_exists("displayAdminOrderTabContent", $context) ? $context["displayAdminOrderTabContent"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTabContent" does not exist.', 128, $this->source); })())))) {
            // line 129
            echo "  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      ";
            // line 132
            echo "      ";
            echo (isset($context["displayAdminOrderTabLink"]) || array_key_exists("displayAdminOrderTabLink", $context) ? $context["displayAdminOrderTabLink"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTabLink" does not exist.', 132, $this->source); })());
            echo "
    </ul>

    <div class=\"tab-content\">
      ";
            // line 137
            echo "      ";
            echo (isset($context["displayAdminOrderTabContent"]) || array_key_exists("displayAdminOrderTabContent", $context) ? $context["displayAdminOrderTabContent"] : (function () { throw new RuntimeError('Variable "displayAdminOrderTabContent" does not exist.', 137, $this->source); })());
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
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  220 => 137,  212 => 132,  208 => 129,  206 => 128,  204 => 127,  202 => 126,  197 => 123,  191 => 121,  189 => 120,  186 => 119,  180 => 117,  178 => 116,  175 => 115,  171 => 113,  169 => 105,  166 => 104,  164 => 103,  161 => 102,  159 => 94,  155 => 92,  153 => 84,  149 => 82,  147 => 74,  141 => 70,  134 => 66,  129 => 63,  127 => 62,  121 => 59,  112 => 53,  103 => 47,  95 => 41,  87 => 39,  85 => 38,  82 => 37,  74 => 35,  72 => 34,  69 => 33,  61 => 31,  59 => 30,  56 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set statusTitle %}
  {{ 'Status'|trans({}, 'Admin.Global') }} ({{ orderForViewing.history.statuses|length }})
{% endset %}

{% set documentsTitle %}
  {{ 'Documents'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span class=\"count\">{{ orderForViewing.documents.documents|length }}</span>)
{% endset %}

{% set carriersTitle %}
  {{ 'Carriers'|trans({}, 'Admin.Shipping.Feature') }} (<span class=\"count\">{{ orderForViewing.shipping.carriers|length }}</span>)
{% endset %}

{% set merchantReturnsTitle %}
  {{ 'Merchandise returns'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span data-role=\"count\">{{ orderForViewing.returns.orderReturns|length }}</span>)
{% endset %}

<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        {{ statusTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        {{ documentsTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        {{ carriersTitle }}
      </a>
    </li>
    {% if merchandiseReturnEnabled %}
      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          {{ merchantReturnsTitle }}
        </a>
      </li>
    {% endif %}
  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ statusTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ documentsTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ carriersTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    {% if merchandiseReturnEnabled %}
      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
          {% block header %}
            {{ merchantReturnsTitle }}
          {% endblock %}
          {% block body %}
            {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig' %}
          {% endblock %}
        {% endembed %}
      </div>
    {% endif %}

    {% if orderForViewing.shipping.recycledPackaging %}
      <span class=\"badge badge-success\">{{ 'Recycled packaging'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}

    {% if orderForViewing.shipping.giftWrapping %}
      <span class=\"badge badge-success\">{{ 'Gift wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}
  </div>
</div>

{% set displayAdminOrderTabLink = renderhook('displayAdminOrderTabLink', {'id_order': orderForViewing.id}) %}
{% set displayAdminOrderTabContent = renderhook('displayAdminOrderTabContent', {'id_order': orderForViewing.id}) %}
{% if displayAdminOrderTabLink is not empty or displayAdminOrderTabContent is not empty%}
  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      {# Rendering of hook displayAdminOrderTabLink, we expect tab links #}
      {{ displayAdminOrderTabLink|raw }}
    </ul>

    <div class=\"tab-content\">
      {# Rendering of hook displayAdminOrderTabContent, we expect tab contents #}
      {{ displayAdminOrderTabContent|raw }}
    </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/details.html.twig");
    }
}


/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig */
class __TwigTemplate_137c77b92db41f892bba5b039ad1b298___2014308716 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header' => [$this, 'block_header'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 74
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 74);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 75
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        // line 76
        echo "          ";
        echo twig_escape_filter($this->env, (isset($context["statusTitle"]) || array_key_exists("statusTitle", $context) ? $context["statusTitle"] : (function () { throw new RuntimeError('Variable "statusTitle" does not exist.', 76, $this->source); })()), "html", null, true);
        echo "
        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 78
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 79
        echo "          ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 79)->display($context);
        // line 80
        echo "        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  477 => 80,  474 => 79,  464 => 78,  451 => 76,  441 => 75,  418 => 74,  220 => 137,  212 => 132,  208 => 129,  206 => 128,  204 => 127,  202 => 126,  197 => 123,  191 => 121,  189 => 120,  186 => 119,  180 => 117,  178 => 116,  175 => 115,  171 => 113,  169 => 105,  166 => 104,  164 => 103,  161 => 102,  159 => 94,  155 => 92,  153 => 84,  149 => 82,  147 => 74,  141 => 70,  134 => 66,  129 => 63,  127 => 62,  121 => 59,  112 => 53,  103 => 47,  95 => 41,  87 => 39,  85 => 38,  82 => 37,  74 => 35,  72 => 34,  69 => 33,  61 => 31,  59 => 30,  56 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set statusTitle %}
  {{ 'Status'|trans({}, 'Admin.Global') }} ({{ orderForViewing.history.statuses|length }})
{% endset %}

{% set documentsTitle %}
  {{ 'Documents'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span class=\"count\">{{ orderForViewing.documents.documents|length }}</span>)
{% endset %}

{% set carriersTitle %}
  {{ 'Carriers'|trans({}, 'Admin.Shipping.Feature') }} (<span class=\"count\">{{ orderForViewing.shipping.carriers|length }}</span>)
{% endset %}

{% set merchantReturnsTitle %}
  {{ 'Merchandise returns'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span data-role=\"count\">{{ orderForViewing.returns.orderReturns|length }}</span>)
{% endset %}

<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        {{ statusTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        {{ documentsTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        {{ carriersTitle }}
      </a>
    </li>
    {% if merchandiseReturnEnabled %}
      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          {{ merchantReturnsTitle }}
        </a>
      </li>
    {% endif %}
  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ statusTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ documentsTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ carriersTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    {% if merchandiseReturnEnabled %}
      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
          {% block header %}
            {{ merchantReturnsTitle }}
          {% endblock %}
          {% block body %}
            {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig' %}
          {% endblock %}
        {% endembed %}
      </div>
    {% endif %}

    {% if orderForViewing.shipping.recycledPackaging %}
      <span class=\"badge badge-success\">{{ 'Recycled packaging'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}

    {% if orderForViewing.shipping.giftWrapping %}
      <span class=\"badge badge-success\">{{ 'Gift wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}
  </div>
</div>

{% set displayAdminOrderTabLink = renderhook('displayAdminOrderTabLink', {'id_order': orderForViewing.id}) %}
{% set displayAdminOrderTabContent = renderhook('displayAdminOrderTabContent', {'id_order': orderForViewing.id}) %}
{% if displayAdminOrderTabLink is not empty or displayAdminOrderTabContent is not empty%}
  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      {# Rendering of hook displayAdminOrderTabLink, we expect tab links #}
      {{ displayAdminOrderTabLink|raw }}
    </ul>

    <div class=\"tab-content\">
      {# Rendering of hook displayAdminOrderTabContent, we expect tab contents #}
      {{ displayAdminOrderTabContent|raw }}
    </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/details.html.twig");
    }
}


/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig */
class __TwigTemplate_137c77b92db41f892bba5b039ad1b298___1859254594 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header' => [$this, 'block_header'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 84
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 84);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 85
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        // line 86
        echo "          ";
        echo twig_escape_filter($this->env, (isset($context["documentsTitle"]) || array_key_exists("documentsTitle", $context) ? $context["documentsTitle"] : (function () { throw new RuntimeError('Variable "documentsTitle" does not exist.', 86, $this->source); })()), "html", null, true);
        echo "
        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 88
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 89
        echo "          ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 89)->display($context);
        // line 90
        echo "        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  728 => 90,  725 => 89,  715 => 88,  702 => 86,  692 => 85,  669 => 84,  477 => 80,  474 => 79,  464 => 78,  451 => 76,  441 => 75,  418 => 74,  220 => 137,  212 => 132,  208 => 129,  206 => 128,  204 => 127,  202 => 126,  197 => 123,  191 => 121,  189 => 120,  186 => 119,  180 => 117,  178 => 116,  175 => 115,  171 => 113,  169 => 105,  166 => 104,  164 => 103,  161 => 102,  159 => 94,  155 => 92,  153 => 84,  149 => 82,  147 => 74,  141 => 70,  134 => 66,  129 => 63,  127 => 62,  121 => 59,  112 => 53,  103 => 47,  95 => 41,  87 => 39,  85 => 38,  82 => 37,  74 => 35,  72 => 34,  69 => 33,  61 => 31,  59 => 30,  56 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set statusTitle %}
  {{ 'Status'|trans({}, 'Admin.Global') }} ({{ orderForViewing.history.statuses|length }})
{% endset %}

{% set documentsTitle %}
  {{ 'Documents'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span class=\"count\">{{ orderForViewing.documents.documents|length }}</span>)
{% endset %}

{% set carriersTitle %}
  {{ 'Carriers'|trans({}, 'Admin.Shipping.Feature') }} (<span class=\"count\">{{ orderForViewing.shipping.carriers|length }}</span>)
{% endset %}

{% set merchantReturnsTitle %}
  {{ 'Merchandise returns'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span data-role=\"count\">{{ orderForViewing.returns.orderReturns|length }}</span>)
{% endset %}

<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        {{ statusTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        {{ documentsTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        {{ carriersTitle }}
      </a>
    </li>
    {% if merchandiseReturnEnabled %}
      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          {{ merchantReturnsTitle }}
        </a>
      </li>
    {% endif %}
  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ statusTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ documentsTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ carriersTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    {% if merchandiseReturnEnabled %}
      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
          {% block header %}
            {{ merchantReturnsTitle }}
          {% endblock %}
          {% block body %}
            {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig' %}
          {% endblock %}
        {% endembed %}
      </div>
    {% endif %}

    {% if orderForViewing.shipping.recycledPackaging %}
      <span class=\"badge badge-success\">{{ 'Recycled packaging'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}

    {% if orderForViewing.shipping.giftWrapping %}
      <span class=\"badge badge-success\">{{ 'Gift wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}
  </div>
</div>

{% set displayAdminOrderTabLink = renderhook('displayAdminOrderTabLink', {'id_order': orderForViewing.id}) %}
{% set displayAdminOrderTabContent = renderhook('displayAdminOrderTabContent', {'id_order': orderForViewing.id}) %}
{% if displayAdminOrderTabLink is not empty or displayAdminOrderTabContent is not empty%}
  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      {# Rendering of hook displayAdminOrderTabLink, we expect tab links #}
      {{ displayAdminOrderTabLink|raw }}
    </ul>

    <div class=\"tab-content\">
      {# Rendering of hook displayAdminOrderTabContent, we expect tab contents #}
      {{ displayAdminOrderTabContent|raw }}
    </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/details.html.twig");
    }
}


/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig */
class __TwigTemplate_137c77b92db41f892bba5b039ad1b298___1444475536 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header' => [$this, 'block_header'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 94
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 94);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 95
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        // line 96
        echo "          ";
        echo twig_escape_filter($this->env, (isset($context["carriersTitle"]) || array_key_exists("carriersTitle", $context) ? $context["carriersTitle"] : (function () { throw new RuntimeError('Variable "carriersTitle" does not exist.', 96, $this->source); })()), "html", null, true);
        echo "
        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 98
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 99
        echo "          ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 99)->display($context);
        // line 100
        echo "        ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  979 => 100,  976 => 99,  966 => 98,  953 => 96,  943 => 95,  920 => 94,  728 => 90,  725 => 89,  715 => 88,  702 => 86,  692 => 85,  669 => 84,  477 => 80,  474 => 79,  464 => 78,  451 => 76,  441 => 75,  418 => 74,  220 => 137,  212 => 132,  208 => 129,  206 => 128,  204 => 127,  202 => 126,  197 => 123,  191 => 121,  189 => 120,  186 => 119,  180 => 117,  178 => 116,  175 => 115,  171 => 113,  169 => 105,  166 => 104,  164 => 103,  161 => 102,  159 => 94,  155 => 92,  153 => 84,  149 => 82,  147 => 74,  141 => 70,  134 => 66,  129 => 63,  127 => 62,  121 => 59,  112 => 53,  103 => 47,  95 => 41,  87 => 39,  85 => 38,  82 => 37,  74 => 35,  72 => 34,  69 => 33,  61 => 31,  59 => 30,  56 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set statusTitle %}
  {{ 'Status'|trans({}, 'Admin.Global') }} ({{ orderForViewing.history.statuses|length }})
{% endset %}

{% set documentsTitle %}
  {{ 'Documents'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span class=\"count\">{{ orderForViewing.documents.documents|length }}</span>)
{% endset %}

{% set carriersTitle %}
  {{ 'Carriers'|trans({}, 'Admin.Shipping.Feature') }} (<span class=\"count\">{{ orderForViewing.shipping.carriers|length }}</span>)
{% endset %}

{% set merchantReturnsTitle %}
  {{ 'Merchandise returns'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span data-role=\"count\">{{ orderForViewing.returns.orderReturns|length }}</span>)
{% endset %}

<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        {{ statusTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        {{ documentsTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        {{ carriersTitle }}
      </a>
    </li>
    {% if merchandiseReturnEnabled %}
      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          {{ merchantReturnsTitle }}
        </a>
      </li>
    {% endif %}
  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ statusTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ documentsTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ carriersTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    {% if merchandiseReturnEnabled %}
      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
          {% block header %}
            {{ merchantReturnsTitle }}
          {% endblock %}
          {% block body %}
            {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig' %}
          {% endblock %}
        {% endembed %}
      </div>
    {% endif %}

    {% if orderForViewing.shipping.recycledPackaging %}
      <span class=\"badge badge-success\">{{ 'Recycled packaging'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}

    {% if orderForViewing.shipping.giftWrapping %}
      <span class=\"badge badge-success\">{{ 'Gift wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}
  </div>
</div>

{% set displayAdminOrderTabLink = renderhook('displayAdminOrderTabLink', {'id_order': orderForViewing.id}) %}
{% set displayAdminOrderTabContent = renderhook('displayAdminOrderTabContent', {'id_order': orderForViewing.id}) %}
{% if displayAdminOrderTabLink is not empty or displayAdminOrderTabContent is not empty%}
  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      {# Rendering of hook displayAdminOrderTabLink, we expect tab links #}
      {{ displayAdminOrderTabLink|raw }}
    </ul>

    <div class=\"tab-content\">
      {# Rendering of hook displayAdminOrderTabContent, we expect tab contents #}
      {{ displayAdminOrderTabContent|raw }}
    </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/details.html.twig");
    }
}


/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig */
class __TwigTemplate_137c77b92db41f892bba5b039ad1b298___134924896 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'header' => [$this, 'block_header'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 105
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig"));

        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 105);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 106
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "header"));

        // line 107
        echo "            ";
        echo twig_escape_filter($this->env, (isset($context["merchantReturnsTitle"]) || array_key_exists("merchantReturnsTitle", $context) ? $context["merchantReturnsTitle"] : (function () { throw new RuntimeError('Variable "merchantReturnsTitle" does not exist.', 107, $this->source); })()), "html", null, true);
        echo "
          ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 109
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 110
        echo "            ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", 110)->display($context);
        // line 111
        echo "          ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1230 => 111,  1227 => 110,  1217 => 109,  1204 => 107,  1194 => 106,  1171 => 105,  979 => 100,  976 => 99,  966 => 98,  953 => 96,  943 => 95,  920 => 94,  728 => 90,  725 => 89,  715 => 88,  702 => 86,  692 => 85,  669 => 84,  477 => 80,  474 => 79,  464 => 78,  451 => 76,  441 => 75,  418 => 74,  220 => 137,  212 => 132,  208 => 129,  206 => 128,  204 => 127,  202 => 126,  197 => 123,  191 => 121,  189 => 120,  186 => 119,  180 => 117,  178 => 116,  175 => 115,  171 => 113,  169 => 105,  166 => 104,  164 => 103,  161 => 102,  159 => 94,  155 => 92,  153 => 84,  149 => 82,  147 => 74,  141 => 70,  134 => 66,  129 => 63,  127 => 62,  121 => 59,  112 => 53,  103 => 47,  95 => 41,  87 => 39,  85 => 38,  82 => 37,  74 => 35,  72 => 34,  69 => 33,  61 => 31,  59 => 30,  56 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set statusTitle %}
  {{ 'Status'|trans({}, 'Admin.Global') }} ({{ orderForViewing.history.statuses|length }})
{% endset %}

{% set documentsTitle %}
  {{ 'Documents'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span class=\"count\">{{ orderForViewing.documents.documents|length }}</span>)
{% endset %}

{% set carriersTitle %}
  {{ 'Carriers'|trans({}, 'Admin.Shipping.Feature') }} (<span class=\"count\">{{ orderForViewing.shipping.carriers|length }}</span>)
{% endset %}

{% set merchantReturnsTitle %}
  {{ 'Merchandise returns'|trans({}, 'Admin.Orderscustomers.Feature') }} (<span data-role=\"count\">{{ orderForViewing.returns.orderReturns|length }}</span>)
{% endset %}

<div class=\"mt-2\">
  <ul class=\"nav nav nav-tabs d-print-none\" role=\"tablist\">
    <li class=\"nav-item\">
      <a class=\"nav-link active show\" id=\"historyTab\" data-toggle=\"tab\" href=\"#historyTabContent\" role=\"tab\" aria-controls=\"historyTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">history</i>
        {{ statusTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderDocumentsTab\" data-toggle=\"tab\" href=\"#orderDocumentsTabContent\" role=\"tab\" aria-controls=\"orderDocumentsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">note</i>
        {{ documentsTitle }}
      </a>
    </li>
    <li class=\"nav-item\">
      <a class=\"nav-link\" id=\"orderShippingTab\" data-toggle=\"tab\" href=\"#orderShippingTabContent\" role=\"tab\" aria-controls=\"orderShippingTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
        <i class=\"material-icons\">local_shipping</i>
        {{ carriersTitle }}
      </a>
    </li>
    {% if merchandiseReturnEnabled %}
      <li class=\"nav-item\">
        <a class=\"nav-link\" id=\"orderReturnsTab\" data-toggle=\"tab\" href=\"#orderReturnsTabContent\" role=\"tab\" aria-controls=\"orderReturnsTabContent\" aria-expanded=\"true\" aria-selected=\"false\">
          <i class=\"material-icons\">replay</i>
          {{ merchantReturnsTitle }}
        </a>
      </li>
    {% endif %}
  </ul>

  <div class=\"tab-content\">
    <div class=\"tab-pane d-print-block fade show active\" id=\"historyTabContent\" role=\"tabpanel\" aria-labelledby=\"historyTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ statusTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderDocumentsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderDocumentsTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ documentsTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    <div class=\"tab-pane d-print-block fade\" id=\"orderShippingTabContent\" role=\"tabpanel\" aria-labelledby=\"orderShippingTab\">
      {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
        {% block header %}
          {{ carriersTitle }}
        {% endblock %}
        {% block body %}
          {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig' %}
        {% endblock %}
      {% endembed %}
    </div>
    {% if merchandiseReturnEnabled %}
      <div class=\"tab-pane d-print-block fade\" id=\"orderReturnsTabContent\" role=\"tabpanel\" aria-labelledby=\"orderReturnsTab\">
        {% embed '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details_card.html.twig' %}
          {% block header %}
            {{ merchantReturnsTitle }}
          {% endblock %}
          {% block body %}
            {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/merchandise_returns.html.twig' %}
          {% endblock %}
        {% endembed %}
      </div>
    {% endif %}

    {% if orderForViewing.shipping.recycledPackaging %}
      <span class=\"badge badge-success\">{{ 'Recycled packaging'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}

    {% if orderForViewing.shipping.giftWrapping %}
      <span class=\"badge badge-success\">{{ 'Gift wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</span>
    {% endif %}
  </div>
</div>

{% set displayAdminOrderTabLink = renderhook('displayAdminOrderTabLink', {'id_order': orderForViewing.id}) %}
{% set displayAdminOrderTabContent = renderhook('displayAdminOrderTabContent', {'id_order': orderForViewing.id}) %}
{% if displayAdminOrderTabLink is not empty or displayAdminOrderTabContent is not empty%}
  <div class=\"mt-2\" id=\"order_hook_tabs\">
    <ul class=\"nav nav nav-tabs\" role=\"tablist\">
      {# Rendering of hook displayAdminOrderTabLink, we expect tab links #}
      {{ displayAdminOrderTabLink|raw }}
    </ul>

    <div class=\"tab-content\">
      {# Rendering of hook displayAdminOrderTabContent, we expect tab contents #}
      {{ displayAdminOrderTabContent|raw }}
    </div>
  </div>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/details.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/details.html.twig");
    }
}
