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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig */
class __TwigTemplate_12197782d95c2ed321ca9aaef22b981a extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig"));

        // line 25
        echo "<div class=\"col-md-12\">
  <table class=\"table discountList";
        // line 26
        if (twig_test_empty((isset($context["discounts"]) || array_key_exists("discounts", $context) ? $context["discounts"] : (function () { throw new RuntimeError('Variable "discounts" does not exist.', 26, $this->source); })()))) {
            echo " d-none";
        }
        echo "\">
    <thead>
    <tr>
      <th>";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Name", [], "Admin.Global"), "html", null, true);
        echo "</th>
      <th>";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Value", [], "Admin.Global"), "html", null, true);
        echo "</th>
      <th class=\"text-right d-print-none\">";
        // line 31
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Actions", [], "Admin.Global"), "html", null, true);
        echo "</th>
    </tr>
    </thead>
    <tbody>
    ";
        // line 35
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["discounts"]) || array_key_exists("discounts", $context) ? $context["discounts"] : (function () { throw new RuntimeError('Variable "discounts" does not exist.', 35, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["discount"]) {
            // line 36
            echo "      <tr>
        <td class=\"discountList-name\">";
            // line 37
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["discount"], "name", [], "any", false, false, false, 37), "html", null, true);
            echo "</td>
        <td data-role=\"discountList-value\">
          ";
            // line 39
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["discount"], "amountRaw", [], "any", false, false, false, 39), "greaterThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 39)) {
                echo "-";
            }
            // line 40
            echo "          ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["discount"], "amountFormatted", [], "any", false, false, false, 40), "html", null, true);
            echo "
        </td>
        <td class=\"text-right d-print-none\">
          <a class=\"delete-cart-rule btn btn-text\" href=\"";
            // line 43
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_remove_cart_rule", ["orderId" => (isset($context["orderId"]) || array_key_exists("orderId", $context) ? $context["orderId"] : (function () { throw new RuntimeError('Variable "orderId" does not exist.', 43, $this->source); })()), "orderCartRuleId" => twig_get_attribute($this->env, $this->source, $context["discount"], "orderCartRuleId", [], "any", false, false, false, 43)]), "html", null, true);
            echo "\">
            <i class=\"material-icons\">delete</i>
          </a>
        </td>
      </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['discount'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "    </tbody>
  </table>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 49,  92 => 43,  85 => 40,  81 => 39,  76 => 37,  73 => 36,  69 => 35,  62 => 31,  58 => 30,  54 => 29,  46 => 26,  43 => 25,);
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
<div class=\"col-md-12\">
  <table class=\"table discountList{% if discounts is empty %} d-none{% endif %}\">
    <thead>
    <tr>
      <th>{{ 'Name'|trans({}, 'Admin.Global') }}</th>
      <th>{{ 'Value'|trans({}, 'Admin.Global') }}</th>
      <th class=\"text-right d-print-none\">{{ 'Actions'|trans({}, 'Admin.Global') }}</th>
    </tr>
    </thead>
    <tbody>
    {% for discount in discounts %}
      <tr>
        <td class=\"discountList-name\">{{ discount.name }}</td>
        <td data-role=\"discountList-value\">
          {% if discount.amountRaw.greaterThan(number(0)) %}-{% endif %}
          {{ discount.amountFormatted }}
        </td>
        <td class=\"text-right d-print-none\">
          <a class=\"delete-cart-rule btn btn-text\" href=\"{{ path('admin_orders_remove_cart_rule', {'orderId': orderId, 'orderCartRuleId': discount.orderCartRuleId}) }}\">
            <i class=\"material-icons\">delete</i>
          </a>
        </td>
      </tr>
    {% endfor %}
    </tbody>
  </table>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig");
    }
}
