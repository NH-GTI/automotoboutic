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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig */
class __TwigTemplate_cc69b25652240dda261928a61c612686 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig"));

        // line 25
        echo "
";
        // line 26
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 26, $this->source); })()), "virtual", [], "any", false, false, false, 26)) {
            // line 27
            echo "
    ";
            // line 28
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 28, $this->source); })()), "shipping", [], "any", false, false, false, 28), "giftMessage", [], "any", false, false, false, 28)) {
                // line 29
                echo "      <div>
        <label>
          ";
                // line 31
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Gift message:", [], "Admin.Global"), "html", null, true);
                echo "
        </label>
        <div id=\"gift-message\">
           ";
                // line 34
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 34, $this->source); })()), "shipping", [], "any", false, false, false, 34), "giftMessage", [], "any", false, false, false, 34), "html", null, true);
                echo "
        </div>
      </div>
    ";
            }
            // line 38
            echo "
    <table class=\"table\" id=\"shipping-grid-table\">
    <thead>
      <tr>
        <th>";
            // line 42
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Date", [], "Admin.Global"), "html", null, true);
            echo "</th>
        <th>&nbsp;</th>
        <th>";
            // line 44
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Carrier", [], "Admin.Shipping.Feature"), "html", null, true);
            echo "</th>
        <th>";
            // line 45
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Weight", [], "Admin.Global"), "html", null, true);
            echo "</th>
        <th>";
            // line 46
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Shipping cost", [], "Admin.Shipping.Feature"), "html", null, true);
            echo "</th>
        <th>";
            // line 47
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Tracking number", [], "Admin.Shipping.Feature"), "html", null, true);
            echo "</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      ";
            // line 52
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 52, $this->source); })()), "shipping", [], "any", false, false, false, 52), "carriers", [], "any", false, false, false, 52));
            foreach ($context['_seq'] as $context["_key"] => $context["carrier"]) {
                // line 53
                echo "        <tr>
          <td class=\"date\">";
                // line 54
                echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatLite(twig_get_attribute($this->env, $this->source, $context["carrier"], "date", [], "any", false, false, false, 54)), "html", null, true);
                echo "</td>
          <td>&nbsp;</td>
          <td class=\"carrier-name\">";
                // line 56
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "name", [], "any", false, false, false, 56), "html", null, true);
                echo "</td>
          <td class=\"carrier-weight\">";
                // line 57
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "weight", [], "any", false, false, false, 57), "html", null, true);
                echo "</td>
          <td class=\"carrier-price\">";
                // line 58
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "price", [], "any", false, false, false, 58), "html", null, true);
                echo "</td>
          <td class=\"carrier-tracking-number\">
            ";
                // line 60
                if (twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingNumber", [], "any", false, false, false, 60)) {
                    // line 61
                    echo "              ";
                    if (twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingUrl", [], "any", false, false, false, 61)) {
                        // line 62
                        echo "                <a href=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingUrl", [], "any", false, false, false, 62), "html", null, true);
                        echo "\" target=\"_blank\" rel=\"noopener noreferrer nofollow\">";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingNumber", [], "any", false, false, false, 62), "html", null, true);
                        echo "</a>
              ";
                    } else {
                        // line 64
                        echo "                ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingNumber", [], "any", false, false, false, 64), "html", null, true);
                        echo "
              ";
                    }
                    // line 66
                    echo "            ";
                }
                // line 67
                echo "          </td>
          <td class=\"text-right\">
            ";
                // line 69
                if (twig_get_attribute($this->env, $this->source, $context["carrier"], "canEdit", [], "any", false, false, false, 69)) {
                    // line 70
                    echo "              <a href=\"#\"
                 class=\"js-update-shipping-btn d-print-none\"
                 data-toggle=\"modal\"
                 data-target=\"#updateOrderShippingModal\"
                 data-order-carrier-id=\"";
                    // line 74
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "orderCarrierId", [], "any", false, false, false, 74), "html", null, true);
                    echo "\"
                 data-order-tracking-number=\"";
                    // line 75
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["carrier"], "trackingNumber", [], "any", false, false, false, 75), "html", null, true);
                    echo "\"
              >
                ";
                    // line 77
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit", [], "Admin.Actions"), "html", null, true);
                    echo "
              </a>
            ";
                }
                // line 80
                echo "          </td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['carrier'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 83
            echo "    </tbody>
  </table>

  ";
            // line 86
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 86, $this->source); })()), "shipping", [], "any", false, false, false, 86), "carrierModuleInfo", [], "any", false, false, false, 86)) {
                // line 87
                echo "    ";
                echo twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 87, $this->source); })()), "shipping", [], "any", false, false, false, 87), "carrierModuleInfo", [], "any", false, false, false, 87);
                echo "
  ";
            }
        } else {
            // line 90
            echo "  <p class=\"text-center mb-0\">
    ";
            // line 91
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Shipping does not apply to virtual orders", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
  </p>
";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  200 => 91,  197 => 90,  190 => 87,  188 => 86,  183 => 83,  175 => 80,  169 => 77,  164 => 75,  160 => 74,  154 => 70,  152 => 69,  148 => 67,  145 => 66,  139 => 64,  131 => 62,  128 => 61,  126 => 60,  121 => 58,  117 => 57,  113 => 56,  108 => 54,  105 => 53,  101 => 52,  93 => 47,  89 => 46,  85 => 45,  81 => 44,  76 => 42,  70 => 38,  63 => 34,  57 => 31,  53 => 29,  51 => 28,  48 => 27,  46 => 26,  43 => 25,);
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

{% if not orderForViewing.virtual %}

    {% if orderForViewing.shipping.giftMessage %}
      <div>
        <label>
          {{ 'Gift message:'|trans({}, 'Admin.Global') }}
        </label>
        <div id=\"gift-message\">
           {{ orderForViewing.shipping.giftMessage }}
        </div>
      </div>
    {% endif %}

    <table class=\"table\" id=\"shipping-grid-table\">
    <thead>
      <tr>
        <th>{{ 'Date'|trans({}, 'Admin.Global') }}</th>
        <th>&nbsp;</th>
        <th>{{ 'Carrier'|trans({}, 'Admin.Shipping.Feature') }}</th>
        <th>{{ 'Weight'|trans({}, 'Admin.Global') }}</th>
        <th>{{ 'Shipping cost'|trans({}, 'Admin.Shipping.Feature') }}</th>
        <th>{{ 'Tracking number'|trans({}, 'Admin.Shipping.Feature') }}</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      {% for carrier in orderForViewing.shipping.carriers %}
        <tr>
          <td class=\"date\">{{ carrier.date|date_format_lite }}</td>
          <td>&nbsp;</td>
          <td class=\"carrier-name\">{{ carrier.name }}</td>
          <td class=\"carrier-weight\">{{ carrier.weight }}</td>
          <td class=\"carrier-price\">{{ carrier.price }}</td>
          <td class=\"carrier-tracking-number\">
            {% if carrier.trackingNumber %}
              {% if carrier.trackingUrl %}
                <a href=\"{{ carrier.trackingUrl }}\" target=\"_blank\" rel=\"noopener noreferrer nofollow\">{{ carrier.trackingNumber }}</a>
              {% else %}
                {{ carrier.trackingNumber }}
              {% endif %}
            {% endif %}
          </td>
          <td class=\"text-right\">
            {% if carrier.canEdit %}
              <a href=\"#\"
                 class=\"js-update-shipping-btn d-print-none\"
                 data-toggle=\"modal\"
                 data-target=\"#updateOrderShippingModal\"
                 data-order-carrier-id=\"{{ carrier.orderCarrierId }}\"
                 data-order-tracking-number=\"{{ carrier.trackingNumber }}\"
              >
                {{ 'Edit'|trans({}, 'Admin.Actions') }}
              </a>
            {% endif %}
          </td>
        </tr>
      {% endfor %}
    </tbody>
  </table>

  {% if orderForViewing.shipping.carrierModuleInfo %}
    {{ orderForViewing.shipping.carrierModuleInfo|raw }}
  {% endif %}
{% else %}
  <p class=\"text-center mb-0\">
    {{ 'Shipping does not apply to virtual orders'|trans({}, 'Admin.Orderscustomers.Feature') }}
  </p>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/shipping.html.twig");
    }
}
