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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig */
class __TwigTemplate_bd40c3668c1887ccbbd6f6af0fe8bb49 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig"));

        // line 25
        echo "
<div id=\"customerCard\" class=\"customer card\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      ";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Customer", [], "Admin.Global"), "html", null, true);
        echo "
    </h3>
  </div>

  <div class=\"card-body\">
    <div id=\"customerInfo\" class=\"info-block\">
      <div class=\"row\">
        ";
        // line 36
        if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 36, $this->source); })()), "customer", [], "any", false, false, false, 36)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 36, $this->source); })()), "customer", [], "any", false, false, false, 36), "id", [], "any", false, false, false, 36) != 0))) {
            // line 37
            echo "          <div class=\"col-xxl-7\">
            <h2 class=\"mb-0\">
              <i class=\"material-icons\">account_box</i>

              ";
            // line 41
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 41, $this->source); })()), "customer", [], "any", false, false, false, 41), "gender", [], "any", false, false, false, 41), "html", null, true);
            echo "
              ";
            // line 42
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 42, $this->source); })()), "customer", [], "any", false, false, false, 42), "firstName", [], "any", false, false, false, 42), "html", null, true);
            echo "
              ";
            // line 43
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 43, $this->source); })()), "customer", [], "any", false, false, false, 43), "lastName", [], "any", false, false, false, 43), "html", null, true);
            echo "

              <strong class=\"text-muted ml-2\">#";
            // line 45
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 45, $this->source); })()), "customer", [], "any", false, false, false, 45), "id", [], "any", false, false, false, 45), "html", null, true);
            echo "</strong>
            </h2>

          </div>
          <div id=\"viewFullDetails\" class=\"col-xxl-5 text-xxl-right\">
            <a class=\"d-print-none\" href=\"";
            // line 50
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_customers_view", ["customerId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 50, $this->source); })()), "customer", [], "any", false, false, false, 50), "id", [], "any", false, false, false, 50)]), "html", null, true);
            echo "\">
              ";
            // line 51
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View full details", [], "Admin.Actions"), "html", null, true);
            echo "
            </a>
          </div>
        ";
        } else {
            // line 55
            echo "          <div class=\"col\">
            <h2 class=\"mb-0\">";
            // line 56
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Deleted customer", [], "Admin.Global"), "html", null, true);
            echo "</h2>
          </div>
        ";
        }
        // line 59
        echo "      </div>

      ";
        // line 61
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 61, $this->source); })()), "customer", [], "any", false, false, false, 61), "groups", [], "any", false, false, false, 61)) {
            // line 62
            echo "        <div class=\"customer-groups mt-2\">
          ";
            // line 63
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 63, $this->source); })()), "customer", [], "any", false, false, false, 63), "groups", [], "any", false, false, false, 63));
            foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
                // line 64
                echo "            <span class=\"badge\">";
                echo twig_escape_filter($this->env, $context["group"], "html", null, true);
                echo "</span>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['group'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 66
            echo "        </div>
      ";
        }
        // line 68
        echo "
    </div>
    ";
        // line 70
        if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 70, $this->source); })()), "customer", [], "any", false, false, false, 70)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 70, $this->source); })()), "customer", [], "any", false, false, false, 70), "id", [], "any", false, false, false, 70) != 0))) {
            // line 71
            echo "      <div class=\"row mt-3\">
        <div id=\"customerEmail\" class=\"col-xxl-6\">
          <p class=\"mb-1\">
            <strong>";
            // line 74
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Email:", [], "Admin.Global"), "html", null, true);
            echo "</strong>
          </p>
          <p>
            <a href=\"mailto:";
            // line 77
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 77, $this->source); })()), "customer", [], "any", false, false, false, 77), "email", [], "any", false, false, false, 77), "html", null, true);
            echo "\">
              ";
            // line 78
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 78, $this->source); })()), "customer", [], "any", false, false, false, 78), "email", [], "any", false, false, false, 78), "html", null, true);
            echo "
            </a>
          </p>

          ";
            // line 82
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 82, $this->source); })()), "customer", [], "any", false, false, false, 82), "isGuest", [], "any", false, false, false, 82) === false)) {
                // line 83
                echo "            <p class=\"mb-1\">
              <strong>";
                // line 84
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Account registered:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</strong>
            </p>
            <p>";
                // line 86
                echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatFull(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 86, $this->source); })()), "customer", [], "any", false, false, false, 86), "accountRegistrationDate", [], "any", false, false, false, 86)), "html", null, true);
                echo "</p>
          ";
            }
            // line 88
            echo "
          ";
            // line 89
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 89, $this->source); })()), "customer", [], "any", false, false, false, 89), "siret", [], "any", false, false, false, 89))) {
                // line 90
                echo "            <p class=\"mb-1\">
              <strong>";
                // line 91
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("SIRET", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</strong>
            </p>
            <p>";
                // line 93
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 93, $this->source); })()), "customer", [], "any", false, false, false, 93), "siret", [], "any", false, false, false, 93), "html", null, true);
                echo "</p>
          ";
            }
            // line 95
            echo "
          ";
            // line 96
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 96, $this->source); })()), "customer", [], "any", false, false, false, 96), "ape", [], "any", false, false, false, 96))) {
                // line 97
                echo "            <p class=\"mb-1 d-block d-md-none\">
              <strong>";
                // line 98
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("APE", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</strong>
            </p>
            <p class=\"d-block d-md-none\">";
                // line 100
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 100, $this->source); })()), "customer", [], "any", false, false, false, 100), "ape", [], "any", false, false, false, 100), "html", null, true);
                echo "</p>
          ";
            }
            // line 102
            echo "        </div>
        <div id=\"validatedOrders\" class=\"col-xxl-6\">
          <p class=\"mb-1\">
            <strong>";
            // line 105
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Validated orders placed:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>
          </p>
          <p>
            <span class=\"badge rounded badge-dark\">";
            // line 108
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 108, $this->source); })()), "customer", [], "any", false, false, false, 108), "validOrdersPlaced", [], "any", false, false, false, 108), "html", null, true);
            echo "</span>
          </p>

          ";
            // line 111
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 111, $this->source); })()), "customer", [], "any", false, false, false, 111), "isGuest", [], "any", false, false, false, 111) === false)) {
                // line 112
                echo "            <p class=\"mb-1\">
              <strong>";
                // line 113
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Total spent since registration:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</strong>
            </p>
            <p>
              <span class=\"badge rounded badge-dark\">";
                // line 116
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 116, $this->source); })()), "customer", [], "any", false, false, false, 116), "totalSpentSinceRegistration", [], "any", false, false, false, 116), "html", null, true);
                echo "</span>
            </p>
          ";
            }
            // line 119
            echo "
          ";
            // line 120
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 120, $this->source); })()), "customer", [], "any", false, false, false, 120), "ape", [], "any", false, false, false, 120))) {
                // line 121
                echo "            <p class=\"mb-1 d-none d-md-block\">
              <strong>";
                // line 122
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("APE", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</strong>
            </p>
            <p class=\"d-none d-md-block\">";
                // line 124
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 124, $this->source); })()), "customer", [], "any", false, false, false, 124), "ape", [], "any", false, false, false, 124), "html", null, true);
                echo "</p>
          ";
            }
            // line 126
            echo "        </div>
      </div>
    ";
        }
        // line 129
        echo "    <div class=\"info-block mt-2\">
      <div class=\"row\">
        ";
        // line 131
        if ((twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 131, $this->source); })()), "virtual", [], "any", false, false, false, 131) === false)) {
            // line 132
            echo "          <div id=\"addressShipping\" class=\"info-block-col col-xl-6\">
            <div class=\"row justify-content-between no-gutters\">
              <strong>";
            // line 134
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Shipping address", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>
              ";
            // line 135
            if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 135, $this->source); })()), "customer", [], "any", false, false, false, 135)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 135, $this->source); })()), "customer", [], "any", false, false, false, 135), "id", [], "any", false, false, false, 135) != 0))) {
                // line 136
                echo "                <a class=\"tooltip-link d-print-none\" href=\"#\" data-toggle=\"dropdown\">
                  <i class=\"material-icons\">more_vert</i>
                </a>

                <div class=\"dropdown-menu dropdown-menu-right\">
                  <a class=\"dropdown-item\" id=\"js-delivery-address-edit-btn\"
                     href=\"";
                // line 142
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_order_addresses_edit", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 142, $this->source); })()), "id", [], "any", false, false, false, 142), "addressType" => "delivery", "liteDisplaying" => 1, "submitFormAjax" => 1]), "html", null, true);
                echo "\"
                  >
                    ";
                // line 144
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit existing address", [], "Admin.Actions"), "html", null, true);
                echo "
                  </a>

                  <a href=\"#\"
                     class=\"dropdown-item js-update-customer-address-modal-btn\"
                     data-toggle=\"modal\"
                     data-target=\"#updateCustomerAddressModal\"
                     data-address-type=\"shipping\"
                  >
                    ";
                // line 153
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Select another address", [], "Admin.Actions"), "html", null, true);
                echo "
                  </a>
                </div>
              ";
            }
            // line 157
            echo "            </div>

            ";
            // line 159
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 159, $this->source); })()), "shippingAddressFormatted", [], "any", false, false, false, 159), "
"));
            foreach ($context['_seq'] as $context["_key"] => $context["line"]) {
                // line 160
                echo "              <p class=\"mb-0\">";
                echo twig_escape_filter($this->env, $context["line"], "html", null, true);
                echo "</p>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['line'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 162
            echo "          </div>
        ";
        }
        // line 164
        echo "        <div id=\"addressInvoice\" class=\"info-block-col ";
        if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 164, $this->source); })()), "virtual", [], "any", false, false, false, 164)) {
            echo "col-md-12";
        } else {
            echo "col-xl-6";
        }
        echo "\">
          <div class=\"row justify-content-between no-gutters\">
            <strong>";
        // line 166
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Invoice address", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</strong>

            ";
        // line 168
        if (( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 168, $this->source); })()), "customer", [], "any", false, false, false, 168)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 168, $this->source); })()), "customer", [], "any", false, false, false, 168), "id", [], "any", false, false, false, 168) != 0))) {
            // line 169
            echo "              <a class=\"tooltip-link d-print-none\" href=\"#\" data-toggle=\"dropdown\">
                <i class=\"material-icons\">more_vert</i>
              </a>

              <div class=\"dropdown-menu dropdown-menu-right\">
                <a class=\"dropdown-item\" id=\"js-invoice-address-edit-btn\"
                   href=\"";
            // line 175
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_order_addresses_edit", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 175, $this->source); })()), "id", [], "any", false, false, false, 175), "addressType" => "invoice", "liteDisplaying" => 1, "submitFormAjax" => 1]), "html", null, true);
            echo "\"
                >
                  ";
            // line 177
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit existing address", [], "Admin.Actions"), "html", null, true);
            echo "
                </a>

                <a href=\"#\"
                   class=\"dropdown-item js-update-customer-address-modal-btn\"
                   data-toggle=\"modal\"
                   data-target=\"#updateCustomerAddressModal\"
                   data-address-type=\"invoice\"
                >
                  ";
            // line 186
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Select another address", [], "Admin.Actions"), "html", null, true);
            echo "
                </a>
              </div>
            ";
        }
        // line 190
        echo "          </div>

          ";
        // line 192
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_split_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 192, $this->source); })()), "invoiceAddressFormatted", [], "any", false, false, false, 192), "
"));
        foreach ($context['_seq'] as $context["_key"] => $context["line"]) {
            // line 193
            echo "            <p class=\"mb-0\">";
            echo twig_escape_filter($this->env, $context["line"], "html", null, true);
            echo "</p>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['line'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 195
        echo "        </div>
      </div>
    </div>

    ";
        // line 199
        if ((( !(null === twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 199, $this->source); })()), "customer", [], "any", false, false, false, 199)) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 199, $this->source); })()), "customer", [], "any", false, false, false, 199), "id", [], "any", false, false, false, 199) != 0)) &&  !(null === (isset($context["privateNoteForm"]) || array_key_exists("privateNoteForm", $context) ? $context["privateNoteForm"] : (function () { throw new RuntimeError('Variable "privateNoteForm" does not exist.', 199, $this->source); })())))) {
            // line 200
            echo "      <div id=\"privateNote\" class=\"mt-2 info-block\">
        <div class=\"row\">
          ";
            // line 202
            $context["isPrivateNoteOpen"] =  !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 202, $this->source); })()), "customer", [], "any", false, false, false, 202), "privateNote", [], "any", false, false, false, 202));
            // line 203
            echo "
          <div class=\"col-md-6\">
            <h3 class=\"mb-0";
            // line 205
            echo (( !(isset($context["isPrivateNoteOpen"]) || array_key_exists("isPrivateNoteOpen", $context) ? $context["isPrivateNoteOpen"] : (function () { throw new RuntimeError('Variable "isPrivateNoteOpen" does not exist.', 205, $this->source); })())) ? (" d-print-none") : (""));
            echo "\">
              ";
            // line 206
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Private note", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
            </h3>
          </div>
          <div class=\"col-md-6 text-right d-print-none\">
            <a href=\"#\"
               class=\"float-right tooltip-link js-private-note-toggle-btn ";
            // line 211
            if ((isset($context["isPrivateNoteOpen"]) || array_key_exists("isPrivateNoteOpen", $context) ? $context["isPrivateNoteOpen"] : (function () { throw new RuntimeError('Variable "isPrivateNoteOpen" does not exist.', 211, $this->source); })())) {
                echo "is-opened";
            }
            echo "\"
            >
              ";
            // line 213
            if ((isset($context["isPrivateNoteOpen"]) || array_key_exists("isPrivateNoteOpen", $context) ? $context["isPrivateNoteOpen"] : (function () { throw new RuntimeError('Variable "isPrivateNoteOpen" does not exist.', 213, $this->source); })())) {
                // line 214
                echo "                <i class=\"material-icons\">remove</i>
              ";
            } else {
                // line 216
                echo "                <i class=\"material-icons\">add</i>
              ";
            }
            // line 218
            echo "            </a>
          </div>

          <div class=\"col-md-12 mt-3 js-private-note-block ";
            // line 221
            if ( !(isset($context["isPrivateNoteOpen"]) || array_key_exists("isPrivateNoteOpen", $context) ? $context["isPrivateNoteOpen"] : (function () { throw new RuntimeError('Variable "isPrivateNoteOpen" does not exist.', 221, $this->source); })())) {
                echo "d-none";
            }
            echo "\">
            ";
            // line 222
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["privateNoteForm"]) || array_key_exists("privateNoteForm", $context) ? $context["privateNoteForm"] : (function () { throw new RuntimeError('Variable "privateNoteForm" does not exist.', 222, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_customers_set_private_note", ["customerId" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,             // line 224
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 224, $this->source); })()), "customer", [], "any", false, false, false, 224), "id", [], "any", false, false, false, 224), "back" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_view", ["orderId" => twig_get_attribute($this->env, $this->source,             // line 225
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 225, $this->source); })()), "id", [], "any", false, false, false, 225)])])]);
            // line 227
            echo "

              ";
            // line 229
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["privateNoteForm"]) || array_key_exists("privateNoteForm", $context) ? $context["privateNoteForm"] : (function () { throw new RuntimeError('Variable "privateNoteForm" does not exist.', 229, $this->source); })()), "note", [], "any", false, false, false, 229), 'widget');
            echo "
              <div class=\"d-none\">
                ";
            // line 231
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["privateNoteForm"]) || array_key_exists("privateNoteForm", $context) ? $context["privateNoteForm"] : (function () { throw new RuntimeError('Variable "privateNoteForm" does not exist.', 231, $this->source); })()), 'rest');
            echo "
              </div>

              <div class=\"mt-2 text-right\">
                <button type=\"submit\"
                        class=\"btn btn-primary btn-sm js-private-note-btn\"
                        ";
            // line 237
            if (twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 237, $this->source); })()), "customer", [], "any", false, false, false, 237), "privateNote", [], "any", false, false, false, 237))) {
                echo "disabled";
            }
            // line 238
            echo "                >
                  ";
            // line 239
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Save", [], "Admin.Actions"), "html", null, true);
            echo "
                </button>
              </div>
            ";
            // line 242
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["privateNoteForm"]) || array_key_exists("privateNoteForm", $context) ? $context["privateNoteForm"] : (function () { throw new RuntimeError('Variable "privateNoteForm" does not exist.', 242, $this->source); })()), 'form_end');
            echo "
          </div>
        </div>
      </div>
    ";
        }
        // line 247
        echo "  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  508 => 247,  500 => 242,  494 => 239,  491 => 238,  487 => 237,  478 => 231,  473 => 229,  469 => 227,  467 => 225,  466 => 224,  465 => 222,  459 => 221,  454 => 218,  450 => 216,  446 => 214,  444 => 213,  437 => 211,  429 => 206,  425 => 205,  421 => 203,  419 => 202,  415 => 200,  413 => 199,  407 => 195,  398 => 193,  393 => 192,  389 => 190,  382 => 186,  370 => 177,  365 => 175,  357 => 169,  355 => 168,  350 => 166,  340 => 164,  336 => 162,  327 => 160,  322 => 159,  318 => 157,  311 => 153,  299 => 144,  294 => 142,  286 => 136,  284 => 135,  280 => 134,  276 => 132,  274 => 131,  270 => 129,  265 => 126,  260 => 124,  255 => 122,  252 => 121,  250 => 120,  247 => 119,  241 => 116,  235 => 113,  232 => 112,  230 => 111,  224 => 108,  218 => 105,  213 => 102,  208 => 100,  203 => 98,  200 => 97,  198 => 96,  195 => 95,  190 => 93,  185 => 91,  182 => 90,  180 => 89,  177 => 88,  172 => 86,  167 => 84,  164 => 83,  162 => 82,  155 => 78,  151 => 77,  145 => 74,  140 => 71,  138 => 70,  134 => 68,  130 => 66,  121 => 64,  117 => 63,  114 => 62,  112 => 61,  108 => 59,  102 => 56,  99 => 55,  92 => 51,  88 => 50,  80 => 45,  75 => 43,  71 => 42,  67 => 41,  61 => 37,  59 => 36,  49 => 29,  43 => 25,);
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

<div id=\"customerCard\" class=\"customer card\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      {{ 'Customer'|trans({}, 'Admin.Global') }}
    </h3>
  </div>

  <div class=\"card-body\">
    <div id=\"customerInfo\" class=\"info-block\">
      <div class=\"row\">
        {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
          <div class=\"col-xxl-7\">
            <h2 class=\"mb-0\">
              <i class=\"material-icons\">account_box</i>

              {{ orderForViewing.customer.gender }}
              {{ orderForViewing.customer.firstName }}
              {{ orderForViewing.customer.lastName }}

              <strong class=\"text-muted ml-2\">#{{ orderForViewing.customer.id }}</strong>
            </h2>

          </div>
          <div id=\"viewFullDetails\" class=\"col-xxl-5 text-xxl-right\">
            <a class=\"d-print-none\" href=\"{{ path('admin_customers_view', {'customerId': orderForViewing.customer.id }) }}\">
              {{ 'View full details'|trans({}, 'Admin.Actions') }}
            </a>
          </div>
        {% else %}
          <div class=\"col\">
            <h2 class=\"mb-0\">{{ 'Deleted customer'|trans({}, 'Admin.Global') }}</h2>
          </div>
        {% endif %}
      </div>

      {% if orderForViewing.customer.groups %}
        <div class=\"customer-groups mt-2\">
          {% for group in orderForViewing.customer.groups %}
            <span class=\"badge\">{{ group }}</span>
          {% endfor %}
        </div>
      {% endif %}

    </div>
    {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
      <div class=\"row mt-3\">
        <div id=\"customerEmail\" class=\"col-xxl-6\">
          <p class=\"mb-1\">
            <strong>{{ 'Email:'|trans({}, 'Admin.Global') }}</strong>
          </p>
          <p>
            <a href=\"mailto:{{ orderForViewing.customer.email }}\">
              {{ orderForViewing.customer.email }}
            </a>
          </p>

          {%  if orderForViewing.customer.isGuest is same as(false) %}
            <p class=\"mb-1\">
              <strong>{{ 'Account registered:'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <p>{{ orderForViewing.customer.accountRegistrationDate|date_format_full }}</p>
          {% endif %}

          {%  if orderForViewing.customer.siret is not empty %}
            <p class=\"mb-1\">
              <strong>{{ 'SIRET'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <p>{{ orderForViewing.customer.siret }}</p>
          {% endif %}

          {%  if orderForViewing.customer.ape is not empty %}
            <p class=\"mb-1 d-block d-md-none\">
              <strong>{{ 'APE'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <p class=\"d-block d-md-none\">{{ orderForViewing.customer.ape }}</p>
          {% endif %}
        </div>
        <div id=\"validatedOrders\" class=\"col-xxl-6\">
          <p class=\"mb-1\">
            <strong>{{ 'Validated orders placed:'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
          </p>
          <p>
            <span class=\"badge rounded badge-dark\">{{ orderForViewing.customer.validOrdersPlaced }}</span>
          </p>

          {%  if orderForViewing.customer.isGuest is same as(false) %}
            <p class=\"mb-1\">
              <strong>{{ 'Total spent since registration:'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <p>
              <span class=\"badge rounded badge-dark\">{{ orderForViewing.customer.totalSpentSinceRegistration }}</span>
            </p>
          {% endif %}

          {%  if orderForViewing.customer.ape is not empty %}
            <p class=\"mb-1 d-none d-md-block\">
              <strong>{{ 'APE'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <p class=\"d-none d-md-block\">{{ orderForViewing.customer.ape }}</p>
          {% endif %}
        </div>
      </div>
    {% endif %}
    <div class=\"info-block mt-2\">
      <div class=\"row\">
        {% if orderForViewing.virtual is same as(false) %}
          <div id=\"addressShipping\" class=\"info-block-col col-xl-6\">
            <div class=\"row justify-content-between no-gutters\">
              <strong>{{ 'Shipping address'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
              {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
                <a class=\"tooltip-link d-print-none\" href=\"#\" data-toggle=\"dropdown\">
                  <i class=\"material-icons\">more_vert</i>
                </a>

                <div class=\"dropdown-menu dropdown-menu-right\">
                  <a class=\"dropdown-item\" id=\"js-delivery-address-edit-btn\"
                     href=\"{{ path('admin_order_addresses_edit', {'orderId': orderForViewing.id, 'addressType': 'delivery', 'liteDisplaying': 1, 'submitFormAjax': 1}) }}\"
                  >
                    {{ 'Edit existing address'|trans({}, 'Admin.Actions') }}
                  </a>

                  <a href=\"#\"
                     class=\"dropdown-item js-update-customer-address-modal-btn\"
                     data-toggle=\"modal\"
                     data-target=\"#updateCustomerAddressModal\"
                     data-address-type=\"shipping\"
                  >
                    {{ 'Select another address'|trans({}, 'Admin.Actions') }}
                  </a>
                </div>
              {% endif %}
            </div>

            {% for line in orderForViewing.shippingAddressFormatted|split(\"\\n\") %}
              <p class=\"mb-0\">{{ line }}</p>
            {% endfor %}
          </div>
        {% endif %}
        <div id=\"addressInvoice\" class=\"info-block-col {% if orderForViewing.virtual %}col-md-12{% else %}col-xl-6{% endif %}\">
          <div class=\"row justify-content-between no-gutters\">
            <strong>{{ 'Invoice address'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>

            {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 %}
              <a class=\"tooltip-link d-print-none\" href=\"#\" data-toggle=\"dropdown\">
                <i class=\"material-icons\">more_vert</i>
              </a>

              <div class=\"dropdown-menu dropdown-menu-right\">
                <a class=\"dropdown-item\" id=\"js-invoice-address-edit-btn\"
                   href=\"{{ path('admin_order_addresses_edit', {'orderId': orderForViewing.id, 'addressType': 'invoice', 'liteDisplaying': 1, 'submitFormAjax': 1}) }}\"
                >
                  {{ 'Edit existing address'|trans({}, 'Admin.Actions') }}
                </a>

                <a href=\"#\"
                   class=\"dropdown-item js-update-customer-address-modal-btn\"
                   data-toggle=\"modal\"
                   data-target=\"#updateCustomerAddressModal\"
                   data-address-type=\"invoice\"
                >
                  {{ 'Select another address'|trans({}, 'Admin.Actions') }}
                </a>
              </div>
            {% endif %}
          </div>

          {% for line in orderForViewing.invoiceAddressFormatted|split(\"\\n\") %}
            <p class=\"mb-0\">{{ line }}</p>
          {% endfor %}
        </div>
      </div>
    </div>

    {% if orderForViewing.customer is not null and orderForViewing.customer.id != 0 and privateNoteForm is not null %}
      <div id=\"privateNote\" class=\"mt-2 info-block\">
        <div class=\"row\">
          {% set isPrivateNoteOpen = not orderForViewing.customer.privateNote is empty %}

          <div class=\"col-md-6\">
            <h3 class=\"mb-0{{ not isPrivateNoteOpen ? ' d-print-none': '' }}\">
              {{ 'Private note'|trans({}, 'Admin.Orderscustomers.Feature') }}
            </h3>
          </div>
          <div class=\"col-md-6 text-right d-print-none\">
            <a href=\"#\"
               class=\"float-right tooltip-link js-private-note-toggle-btn {% if isPrivateNoteOpen %}is-opened{% endif %}\"
            >
              {% if isPrivateNoteOpen %}
                <i class=\"material-icons\">remove</i>
              {% else %}
                <i class=\"material-icons\">add</i>
              {% endif %}
            </a>
          </div>

          <div class=\"col-md-12 mt-3 js-private-note-block {% if not isPrivateNoteOpen %}d-none{% endif %}\">
            {{ form_start(privateNoteForm, {
              'action': path('admin_customers_set_private_note', {
                'customerId': orderForViewing.customer.id,
                'back': path('admin_orders_view', {'orderId': orderForViewing.id})
              })
              }) }}

              {{ form_widget(privateNoteForm.note) }}
              <div class=\"d-none\">
                {{ form_rest(privateNoteForm) }}
              </div>

              <div class=\"mt-2 text-right\">
                <button type=\"submit\"
                        class=\"btn btn-primary btn-sm js-private-note-btn\"
                        {% if orderForViewing.customer.privateNote is empty %}disabled{% endif %}
                >
                  {{ 'Save'|trans({}, 'Admin.Actions') }}
                </button>
              </div>
            {{ form_end(privateNoteForm) }}
          </div>
        </div>
      </div>
    {% endif %}
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/customer.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/customer.html.twig");
    }
}
