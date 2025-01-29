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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig */
class __TwigTemplate_1a38076971f2e1a325e797390f7bc4e3 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig"));

        // line 25
        echo "<div class=\"card\">
  <div class=\"card-body\">
    <div class=\"row\">
      <div class=\"col-sm\">
        <p>
          <strong>";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Date", [], "Admin.Global"), "html", null, true);
        echo "</strong>
        </p>
        <strong>";
        // line 32
        echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatFull(twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 32, $this->source); })()), "createdAt", [], "any", false, false, false, 32)), "html", null, true);
        echo "</strong>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>";
        // line 36
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Total", [], "Admin.Global"), "html", null, true);
        echo "</strong>
        </p>
        <p>
          <span class=\"badge rounded font-size-100\">
            ";
        // line 40
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 40, $this->source); })()), "prices", [], "any", false, false, false, 40), "totalAmountFormatted", [], "any", false, false, false, 40), "html", null, true);
        echo "
          </span>
        </p>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>
            ";
        // line 47
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Message", [], "Admin.Global"), "html", null, true);
        echo "
          </strong>
        </p>
        <strong>
          ";
        // line 51
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 51, $this->source); })()), "messages", [], "any", false, false, false, 51), "total", [], "any", false, false, false, 51), "html", null, true);
        echo "
        </strong>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>
            ";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Products", [], "Admin.Global"), "html", null, true);
        echo "
          </strong>
        </p>
        <strong>
          ";
        // line 61
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 61, $this->source); })()), "products", [], "any", false, false, false, 61), "products", [], "any", false, false, false, 61)), "html", null, true);
        echo "
        </strong>
      </div>
    </div>
  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 61,  95 => 57,  86 => 51,  79 => 47,  69 => 40,  62 => 36,  55 => 32,  50 => 30,  43 => 25,);
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
<div class=\"card\">
  <div class=\"card-body\">
    <div class=\"row\">
      <div class=\"col-sm\">
        <p>
          <strong>{{ 'Date'|trans({}, 'Admin.Global') }}</strong>
        </p>
        <strong>{{ orderForViewing.createdAt|date_format_full }}</strong>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>{{ 'Total'|trans({}, 'Admin.Global') }}</strong>
        </p>
        <p>
          <span class=\"badge rounded font-size-100\">
            {{ orderForViewing.prices.totalAmountFormatted }}
          </span>
        </p>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>
            {{ 'Message'|trans({}, 'Admin.Global') }}
          </strong>
        </p>
        <strong>
          {{ orderForViewing.messages.total }}
        </strong>
      </div>
      <div class=\"col-sm\">
        <p>
          <strong>
            {{ 'Products'|trans({}, 'Admin.Global') }}
          </strong>
        </p>
        <strong>
          {{ orderForViewing.products.products|length }}
        </strong>
      </div>
    </div>
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/print_order_statistics.html.twig");
    }
}
