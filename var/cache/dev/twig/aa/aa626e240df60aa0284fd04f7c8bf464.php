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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig */
class __TwigTemplate_f7063e002fb8f62ebac3fdd7a945106e extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig"));

        // line 25
        echo "
<div class=\"order-navigation\">
  <div class=\"order-navigation-left\">
      <a
        href=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_view", ["orderId" => (isset($context["previousOrderId"]) || array_key_exists("previousOrderId", $context) ? $context["previousOrderId"] : (function () { throw new RuntimeError('Variable "previousOrderId" does not exist.', 29, $this->source); })())]), "html", null, true);
        echo "\"
        role=\"button\"
        class=\"btn btn-action ";
        // line 31
        if ( !(isset($context["previousOrderId"]) || array_key_exists("previousOrderId", $context) ? $context["previousOrderId"] : (function () { throw new RuntimeError('Variable "previousOrderId" does not exist.', 31, $this->source); })())) {
            echo " disabled ";
        }
        echo "\"
       >
        <span class=\"material-icons rtl-flip\">arrow_back</span>
      </a>
  </div>

  <div class=\"order-navigation-right\">
      <a
        href=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_view", ["orderId" => (isset($context["nextOrderId"]) || array_key_exists("nextOrderId", $context) ? $context["nextOrderId"] : (function () { throw new RuntimeError('Variable "nextOrderId" does not exist.', 39, $this->source); })())]), "html", null, true);
        echo "\"
        role=\"button\"
        class=\"btn btn-action ";
        // line 41
        if ( !(isset($context["nextOrderId"]) || array_key_exists("nextOrderId", $context) ? $context["nextOrderId"] : (function () { throw new RuntimeError('Variable "nextOrderId" does not exist.', 41, $this->source); })())) {
            echo " disabled ";
        }
        echo "\"
      >
        <span class=\"material-icons rtl-flip\">arrow_forward</span>
      </a>
  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 41,  67 => 39,  54 => 31,  49 => 29,  43 => 25,);
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

<div class=\"order-navigation\">
  <div class=\"order-navigation-left\">
      <a
        href=\"{{ path('admin_orders_view', {'orderId': previousOrderId}) }}\"
        role=\"button\"
        class=\"btn btn-action {% if not previousOrderId %} disabled {% endif %}\"
       >
        <span class=\"material-icons rtl-flip\">arrow_back</span>
      </a>
  </div>

  <div class=\"order-navigation-right\">
      <a
        href=\"{{ path('admin_orders_view', {'orderId': nextOrderId}) }}\"
        role=\"button\"
        class=\"btn btn-action {% if not nextOrderId %} disabled {% endif %}\"
      >
        <span class=\"material-icons rtl-flip\">arrow_forward</span>
      </a>
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/order_navigation.html.twig");
    }
}
