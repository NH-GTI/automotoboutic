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

/* @PrestaShop/Admin/Sell/Order/Cart/view.html.twig */
class __TwigTemplate_2d2adca41505fecb5dcadb869d008305 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'cart_kpis' => [$this, 'block_cart_kpis'],
            'cart_order_information' => [$this, 'block_cart_order_information'],
            'cart_customer_information' => [$this, 'block_cart_customer_information'],
            'cart_summary' => [$this, 'block_cart_summary'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 26
        return "@PrestaShop/Admin/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/layout.html.twig", "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig", 26);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 28
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 29
        echo "  ";
        $this->displayBlock('cart_kpis', $context, $blocks);
        // line 35
        echo "
  <div class=\"row\">
    <div class=\"col-md-6\">
      ";
        // line 38
        $this->displayBlock('cart_order_information', $context, $blocks);
        // line 41
        echo "    </div>
    <div class=\"col-md-6\">
      ";
        // line 43
        $this->displayBlock('cart_customer_information', $context, $blocks);
        // line 46
        echo "    </div>
  </div>

  ";
        // line 49
        $this->displayBlock('cart_summary', $context, $blocks);
        // line 52
        echo "
  ";
        // line 53
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "cartSummary", [], "any", false, false, false, 53), "cart_link", [], "any", false, false, false, 53))) {
            // line 54
            echo "    <div class=\"card\" data-role=\"customer-information\">
      <h3 class=\"card-header\">
        <i class=\"material-icons\">mail</i>
        ";
            // line 57
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Share this cart", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
      </h3>
      <div class=\"card-body\">
        <p>";
            // line 60
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Thanks to this link, your customer can open the cart, check its contents and validate the order, if it suits them.", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</p>
        <div class=\"card bg-light p-2 mb-0\">
        ";
            // line 62
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "cartSummary", [], "any", false, false, false, 62), "cart_link", [], "any", false, false, false, 62), "html", null, true);
            echo "
        </div>
      </div>
    </div>
  ";
        }
        // line 67
        echo "  
";
    }

    // line 29
    public function block_cart_kpis($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 30
        echo "    ";
        echo $this->env->getRuntime('Symfony\Bridge\Twig\Extension\HttpKernelRuntime')->renderFragment(Symfony\Bridge\Twig\Extension\HttpKernelExtension::controller("PrestaShopBundle:Admin\\Common:renderKpiRow", ["kpiRow" =>         // line 32
($context["cartKpi"] ?? null)]));
        // line 33
        echo "
  ";
    }

    // line 38
    public function block_cart_order_information($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 39
        echo "        ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/order_information.html.twig", "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig", 39)->display($context);
        // line 40
        echo "      ";
    }

    // line 43
    public function block_cart_customer_information($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 44
        echo "        ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/customer_information.html.twig", "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig", 44)->display($context);
        // line 45
        echo "      ";
    }

    // line 49
    public function block_cart_summary($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 50
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/cart_summary.html.twig", "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig", 50)->display($context);
        // line 51
        echo "  ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 51,  150 => 50,  146 => 49,  142 => 45,  139 => 44,  135 => 43,  131 => 40,  128 => 39,  124 => 38,  119 => 33,  117 => 32,  115 => 30,  111 => 29,  106 => 67,  98 => 62,  93 => 60,  87 => 57,  82 => 54,  80 => 53,  77 => 52,  75 => 49,  70 => 46,  68 => 43,  64 => 41,  62 => 38,  57 => 35,  54 => 29,  50 => 28,  39 => 26,);
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Sell/Order/Cart/view.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Cart/view.html.twig");
    }
}
