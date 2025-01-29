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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig */
class __TwigTemplate_75d479d0d35258c894844a1cba9a8a1e extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig"));

        // line 25
        echo "
<div class=\"title-content\">
  <h1 class=\"d-inline\">
    <strong class=\"text-muted\" data-role=\"order-id\">#";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 28, $this->source); })()), "id", [], "any", false, false, false, 28), "html", null, true);
        echo "</strong>
    <strong data-role=\"order-reference\">";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 29, $this->source); })()), "reference", [], "any", false, false, false, 29), "html", null, true);
        echo "</strong>
  </h1>

  <p class=\"lead d-inline\">
    ";
        // line 33
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("from", [], "Admin.Global"), "html", null, true);
        echo "

    ";
        // line 35
        if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 35, $this->source); })()), "customer", [], "any", false, false, false, 35)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 35, $this->source); })()), "customer", [], "any", false, false, false, 35), "id", [], "any", false, false, false, 35) != 0))) {
            // line 36
            echo "      ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 36, $this->source); })()), "customer", [], "any", false, false, false, 36), "firstName", [], "any", false, false, false, 36), "html", null, true);
            echo "
      ";
            // line 37
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 37, $this->source); })()), "customer", [], "any", false, false, false, 37), "lastName", [], "any", false, false, false, 37), "html", null, true);
            echo "
    ";
        } else {
            // line 39
            echo "      ";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Deleted customer", [], "Admin.Global"), "html", null, true);
            echo "
    ";
        }
        // line 41
        echo "  </p>

  <span class=\"badge rounded badge-dark ml-2 mr-2 font-size-100\">
    ";
        // line 44
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 44, $this->source); })()), "prices", [], "any", false, false, false, 44), "totalAmountFormatted", [], "any", false, false, false, 44), "html", null, true);
        echo "
  </span>

  <p class=\"lead d-inline\">
    ";
        // line 48
        echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatLite(twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 48, $this->source); })()), "createdAt", [], "any", false, false, false, 48)), "html", null, true);
        echo "
    <span class=\"text-muted\">
      ";
        // line 50
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("at", [], "Admin.Global"), "html", null, true);
        echo "

      ";
        // line 52
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 52, $this->source); })()), "createdAt", [], "any", false, false, false, 52), "H:i:s"), "html", null, true);
        echo "
    </span>
  </p>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 52,  99 => 50,  94 => 48,  87 => 44,  82 => 41,  76 => 39,  71 => 37,  66 => 36,  64 => 35,  59 => 33,  52 => 29,  48 => 28,  43 => 25,);
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

<div class=\"title-content\">
  <h1 class=\"d-inline\">
    <strong class=\"text-muted\" data-role=\"order-id\">#{{ orderForViewing.id }}</strong>
    <strong data-role=\"order-reference\">{{ orderForViewing.reference }}</strong>
  </h1>

  <p class=\"lead d-inline\">
    {{ 'from'|trans({}, 'Admin.Global') }}

    {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
      {{ orderForViewing.customer.firstName }}
      {{ orderForViewing.customer.lastName }}
    {% else %}
      {{ 'Deleted customer'|trans({}, 'Admin.Global') }}
    {% endif %}
  </p>

  <span class=\"badge rounded badge-dark ml-2 mr-2 font-size-100\">
    {{ orderForViewing.prices.totalAmountFormatted }}
  </span>

  <p class=\"lead d-inline\">
    {{ orderForViewing.createdAt|date_format_lite }}
    <span class=\"text-muted\">
      {{ 'at'|trans({}, 'Admin.Global') }}

      {{ orderForViewing.createdAt|date('H:i:s') }}
    </span>
  </p>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/header.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/header.html.twig");
    }
}
