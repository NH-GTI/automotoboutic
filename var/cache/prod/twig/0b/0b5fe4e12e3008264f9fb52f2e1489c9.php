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

/* @PrestaShop/Admin/Sell/Order/Cart/Blocks/View/customer_information.html.twig */
class __TwigTemplate_1a1b035869d41905a9abac1adcd3b32a extends Template
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
<div class=\"card\" data-role=\"customer-information\">
  <h3 class=\"card-header\">
    ";
        // line 28
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Customer information", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
  </h3>
  <div class=\"card-body\">
    ";
        // line 31
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 31), "id", [], "any", false, false, false, 31)) {
            // line 32
            echo "      <p class=\"mb-0\">
        ";
            // line 33
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Customer ID:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        ";
            // line 34
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 34), "id", [], "any", false, false, false, 34), "html", null, true);
            echo "
      </p>

      <p class=\"mb-0\">
        ";
            // line 38
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Name:", [], "Admin.Global"), "html", null, true);
            echo "
        ";
            // line 39
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 39), "gender", [], "any", false, false, false, 39)) {
                // line 40
                echo "          ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 40), "gender", [], "any", false, false, false, 40), "html", null, true);
                echo "
        ";
            }
            // line 42
            echo "        ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 42), "first_name", [], "any", false, false, false, 42), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 42), "last_name", [], "any", false, false, false, 42), "html", null, true);
            echo "
      </p>

      <p class=\"mb-0\">
        ";
            // line 46
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Email:", [], "Admin.Global"), "html", null, true);
            echo "
        ";
            // line 47
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 47), "email", [], "any", false, false, false, 47), "html", null, true);
            echo "
      </p>

      <p class=\"mb-0\">
        ";
            // line 51
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Account creation date:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        ";
            // line 52
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 52), "registration_date", [], "any", false, false, false, 52), "html", null, true);
            echo "
      </p>

      <p class=\"mb-0\">
        ";
            // line 56
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Number of orders:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        ";
            // line 57
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 57), "valid_orders_count", [], "any", false, false, false, 57), "html", null, true);
            echo "
      </p>

      <p>
        ";
            // line 61
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Amount spent:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
        ";
            // line 62
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 62), "total_spent_since_registration", [], "any", false, false, false, 62), "html", null, true);
            echo "
      </p>

      <a href=\"";
            // line 65
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_customers_view", ["customerId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 65), "id", [], "any", false, false, false, 65)]), "html", null, true);
            echo "\" class=\"btn btn-primary\">
        <i class=\"material-icons\">remove_red_eye</i>
        ";
            // line 67
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View customer", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
      </a>
      <a href=\"mailto:";
            // line 69
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["cartView"] ?? null), "customerInformation", [], "any", false, false, false, 69), "email", [], "any", false, false, false, 69), "html", null, true);
            echo "\" class=\"btn btn-secondary\">
        <i class=\"material-icons\">mail</i>
        ";
            // line 71
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Write an email", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
      </a>

    ";
        } else {
            // line 75
            echo "      <p class=\"mb-0\">";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("No customer information available yet.", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</h2>
    ";
        }
        // line 77
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/customer_information.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  157 => 77,  151 => 75,  144 => 71,  139 => 69,  134 => 67,  129 => 65,  123 => 62,  119 => 61,  112 => 57,  108 => 56,  101 => 52,  97 => 51,  90 => 47,  86 => 46,  76 => 42,  70 => 40,  68 => 39,  64 => 38,  57 => 34,  53 => 33,  50 => 32,  48 => 31,  42 => 28,  37 => 25,);
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Sell/Order/Cart/Blocks/View/customer_information.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Cart/Blocks/View/customer_information.html.twig");
    }
}
