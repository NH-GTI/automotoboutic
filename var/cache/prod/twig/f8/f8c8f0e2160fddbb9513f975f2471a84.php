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

/* @PrestaShop/Admin/Sell/Order/Cart/Blocks/View/order_information.html.twig */
class __TwigTemplate_a7e592172dc5210db07f51e956410e93 extends Template
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
        // line 25
        echo "
<div class=\"card\" data-role=\"order-information\">
  <h3 class=\"card-header\">";
        // line 27
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cart information", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</h3>
  <div class=\"card-body\">
    <p class=\"mb-0\">
      ";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Created:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
      ";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "cartSummary", [], "any", false, false, false, 31), "date_add", [], "any", false, false, false, 31), "html", null, true);
        echo "
    </p>
    <p class=\"mb-0\">
      ";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Last updated:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
      ";
        // line 35
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "cartSummary", [], "any", false, false, false, 35), "date_upd", [], "any", false, false, false, 35), "html", null, true);
        echo "
    </p>
    <hr class=\"mt-2 mb-2\">
    ";
        // line 38
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "orderInformation", [], "any", false, false, false, 38), "id", [], "any", false, false, false, 38))) {
            // line 39
            echo "
      <p>";
            // line 40
            echo twig_replace_filter($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Order %orderid% was created from this cart on %orderdate%.", [], "Admin.Orderscustomers.Feature"), ["%orderid%" => (((("<a href=\"" . $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_view", ["orderId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 41
($context["cartView"] ?? null), "orderInformation", [], "any", false, false, false, 41), "id", [], "any", false, false, false, 41)])) . "\">#") . twig_sprintf(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "orderInformation", [], "any", false, false, false, 41), "id", [], "any", false, false, false, 41), "\"%06d")) . "</a>"), "%orderdate%" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 42
($context["cartView"] ?? null), "orderInformation", [], "any", false, false, false, 42), "placed_date", [], "any", false, false, false, 42)]);
            // line 43
            echo "</p>

      <a href=\"";
            // line 45
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_view", ["orderId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "orderInformation", [], "any", false, false, false, 45), "id", [], "any", false, false, false, 45)]), "html", null, true);
            echo "\" class=\"btn btn-primary\">
        <i class=\"material-icons\">remove_red_eye</i>
        ";
            // line 47
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View order", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
      </a>
    ";
        } else {
            // line 50
            echo "      <p class=\"mb-0\">";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("The customer has not proceeded to checkout yet.", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</p>

      ";
            // line 52
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 52), "id", [], "any", false, false, false, 52)) {
                // line 53
                echo "        <a href=\"";
                echo twig_escape_filter($this->env, ($context["createOrderFromCartLink"] ?? null), "html", null, true);
                echo "\" class=\"btn btn-primary mt-3\" id=\"create-order-from-cart\">
          <i class=\"material-icons\">add_circle_outline</i>
          ";
                // line 55
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Create an order from this cart", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
        </a>
      ";
            }
            // line 58
            echo "    ";
        }
        // line 59
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/order_information.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 59,  111 => 58,  105 => 55,  99 => 53,  97 => 52,  91 => 50,  85 => 47,  80 => 45,  76 => 43,  74 => 42,  73 => 41,  72 => 40,  69 => 39,  67 => 38,  61 => 35,  57 => 34,  51 => 31,  47 => 30,  41 => 27,  37 => 25,);
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/order_information.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Cart/Blocks/View/order_information.html.twig");
    }
}
