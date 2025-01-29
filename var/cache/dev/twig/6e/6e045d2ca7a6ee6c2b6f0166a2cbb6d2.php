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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig */
class __TwigTemplate_f27218699e4e9c85e93956497c3ae9ff extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig"));

        // line 25
        echo "
";
        // line 26
        $context["isColumnLocationDisplayed"] = false;
        // line 27
        $context["isColumnRefundedDisplayed"] = false;
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_slice($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 28, $this->source); })()), "products", [], "any", false, false, false, 28), "products", [], "any", false, false, false, 28), 0, (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 28, $this->source); })())));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 29
            echo "  ";
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["product"], "location", [], "any", false, false, false, 29))) {
                // line 30
                echo "    ";
                $context["isColumnLocationDisplayed"] = true;
                // line 31
                echo "  ";
            }
            // line 32
            echo "  ";
            if ((twig_get_attribute($this->env, $this->source, $context["product"], "quantityRefunded", [], "any", false, false, false, 32) > 0)) {
                // line 33
                echo "    ";
                $context["isColumnRefundedDisplayed"] = true;
                // line 34
                echo "  ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "
<div class=\"card\" id=\"orderProductsPanel\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      ";
        // line 40
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Products", [], "Admin.Global"), "html", null, true);
        echo " (<span id=\"orderProductsPanelCount\">";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 40, $this->source); })()), "products", [], "any", false, false, false, 40), "products", [], "any", false, false, false, 40)), "html", null, true);
        echo "</span>)
    </h3>
  </div>

  <div class=\"card-body\">
    <div class=\"spinner-order-products-container\" id=\"orderProductsLoading\">
      <div class=\"spinner spinner-primary\"></div>
    </div>

    ";
        // line 49
        $context["formOptions"] = ["attr" => ["data-order-id" => twig_get_attribute($this->env, $this->source,         // line 51
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 51, $this->source); })()), "id", [], "any", false, false, false, 51), "data-is-delivered" => twig_get_attribute($this->env, $this->source,         // line 52
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 52, $this->source); })()), "isDelivered", [], "any", false, false, false, 52), "data-is-tax-included" => twig_get_attribute($this->env, $this->source,         // line 53
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 53, $this->source); })()), "isTaxIncluded", [], "any", false, false, false, 53), "data-discounts-amount" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 54
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 54, $this->source); })()), "prices", [], "any", false, false, false, 54), "discountsAmountRaw", [], "any", false, false, false, 54), "data-price-specification" => json_encode(        // line 55
(isset($context["priceSpecification"]) || array_key_exists("priceSpecification", $context) ? $context["priceSpecification"] : (function () { throw new RuntimeError('Variable "priceSpecification" does not exist.', 55, $this->source); })()))]];
        // line 58
        echo "    ";
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 58, $this->source); })()), 'form_start', (isset($context["formOptions"]) || array_key_exists("formOptions", $context) ? $context["formOptions"] : (function () { throw new RuntimeError('Variable "formOptions" does not exist.', 58, $this->source); })()));
        echo "

    ";
        // line 61
        echo "    <table class=\"table\" id=\"orderProductsTable\" data-currency-precision=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderCurrency"]) || array_key_exists("orderCurrency", $context) ? $context["orderCurrency"] : (function () { throw new RuntimeError('Variable "orderCurrency" does not exist.', 61, $this->source); })()), "precision", [], "any", false, false, false, 61), "html", null, true);
        echo "\">
      <thead>
        <tr>
          <th>
            <p>";
        // line 65
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Product", [], "Admin.Global"), "html", null, true);
        echo "</p>
          </th>
          <th></th>
          <th>
            <p class=\"mb-0\">";
        // line 69
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Price per unit", [], "Admin.Advparameters.Feature"), "html", null, true);
        echo "</p>
            <small class=\"text-muted\">";
        // line 70
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 70, $this->source); })()), "taxMethod", [], "any", false, false, false, 70), "html", null, true);
        echo "</small>
          </th>
          <th>
            <p>";
        // line 73
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Quantity", [], "Admin.Global"), "html", null, true);
        echo "</p>
          </th>
          <th class=\"cellProductLocation";
        // line 75
        if ( !(isset($context["isColumnLocationDisplayed"]) || array_key_exists("isColumnLocationDisplayed", $context) ? $context["isColumnLocationDisplayed"] : (function () { throw new RuntimeError('Variable "isColumnLocationDisplayed" does not exist.', 75, $this->source); })())) {
            echo " d-none";
        }
        echo "\">
            <p>";
        // line 76
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Stock location", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</p>
          </th>
          <th class=\"cellProductRefunded";
        // line 78
        if ( !(isset($context["isColumnRefundedDisplayed"]) || array_key_exists("isColumnRefundedDisplayed", $context) ? $context["isColumnRefundedDisplayed"] : (function () { throw new RuntimeError('Variable "isColumnRefundedDisplayed" does not exist.', 78, $this->source); })())) {
            echo " d-none";
        }
        echo "\">
            <p>";
        // line 79
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Refunded", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</p>
          </th>
          <th ";
        // line 81
        if ( !(isset($context["isAvailableQuantityDisplayed"]) || array_key_exists("isAvailableQuantityDisplayed", $context) ? $context["isAvailableQuantityDisplayed"] : (function () { throw new RuntimeError('Variable "isAvailableQuantityDisplayed" does not exist.', 81, $this->source); })())) {
            echo " class=\"d-none\" ";
        }
        echo ">
            <p>";
        // line 82
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Available", [], "Admin.Global"), "html", null, true);
        echo "</p>
          </th>
          <th>
            <p class=\"mb-0\">";
        // line 85
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Total", [], "Admin.Global"), "html", null, true);
        echo "</p>
            <small class=\"text-muted\">";
        // line 86
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 86, $this->source); })()), "taxMethod", [], "any", false, false, false, 86), "html", null, true);
        echo "</small>
          </th>
          ";
        // line 88
        if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 88, $this->source); })()), "hasInvoice", [], "method", false, false, false, 88)) {
            // line 89
            echo "            <th>
              <p>";
            // line 90
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Invoice", [], "Admin.Global"), "html", null, true);
            echo "</p>
            </th>
          ";
        }
        // line 93
        echo "          ";
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 93, $this->source); })()), "delivered", [], "any", false, false, false, 93)) {
            // line 94
            echo "            <th class=\"text-right product_actions d-print-none\">
              <p>";
            // line 95
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Actions", [], "Admin.Global"), "html", null, true);
            echo "</p>
            </th>
          ";
        }
        // line 98
        echo "          <th class=\"text-center cancel-product-element\">
            <p>";
        // line 99
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Partial refund", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</p>
          </th>
        </tr>
      </thead>
      <tbody>
        ";
        // line 104
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product_list.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", 104)->display($context);
        // line 105
        echo "        ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", 105)->display($context);
        // line 106
        echo "        ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", 106)->display($context);
        // line 107
        echo "      </tbody>
    </table>


    ";
        // line 112
        echo "    <div class=\"row mb-3\">
      <div class=\"col-xl-6 d-print-none order-product-pagination\">
        <div class=\"form-group\">
          <label for=\"paginator_select_page_limit\" class=\"col-form-label ml-3\">";
        // line 115
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Items per page:", [], "Admin.Catalog.Feature"), "html", null, true);
        echo "</label>
          <select id=\"orderProductsTablePaginationNumberSelector\" class=\"pagination-link custom-select\">
            ";
        // line 117
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["paginationNumOptions"]) || array_key_exists("paginationNumOptions", $context) ? $context["paginationNumOptions"] : (function () { throw new RuntimeError('Variable "paginationNumOptions" does not exist.', 117, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["numPageOption"]) {
            // line 118
            echo "              <option value=\"";
            echo twig_escape_filter($this->env, $context["numPageOption"], "html", null, true);
            echo "\" ";
            if (($context["numPageOption"] == (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 118, $this->source); })()))) {
                echo " selected ";
            }
            echo ">";
            echo twig_escape_filter($this->env, $context["numPageOption"], "html", null, true);
            echo "</option>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['numPageOption'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 120
        echo "          </select>
        </div>

        ";
        // line 123
        $context["numPages"] = twig_round(max((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 123, $this->source); })()), "products", [], "any", false, false, false, 123), "products", [], "any", false, false, false, 123)) / (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 123, $this->source); })())), 1), 0, "ceil");
        // line 124
        echo "        <nav aria-label=\"Products Navigation\"";
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 124, $this->source); })()), "products", [], "any", false, false, false, 124), "products", [], "any", false, false, false, 124)) <= (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 124, $this->source); })()))) {
            echo " class=\"d-none\"";
        }
        echo " id=\"orderProductsNavPagination\">
          <ul class=\"pagination\" id=\"orderProductsTablePagination\" data-num-per-page=\"";
        // line 125
        echo twig_escape_filter($this->env, (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 125, $this->source); })()), "html", null, true);
        echo "\" data-num-pages=\"";
        echo twig_escape_filter($this->env, (isset($context["numPages"]) || array_key_exists("numPages", $context) ? $context["numPages"] : (function () { throw new RuntimeError('Variable "numPages" does not exist.', 125, $this->source); })()), "html", null, true);
        echo "\">
            <li class=\"page-item disabled\" id=\"orderProductsTablePaginationPrev\">
              <a class=\"page-link\" href=\"javascript:void(0);\" aria-label=\"Previous\">
                <span aria-hidden=\"true\">&laquo;</span>
                <span class=\"sr-only\">Previous</span>
              </a>
            </li>
            ";
        // line 132
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["numPages"]) || array_key_exists("numPages", $context) ? $context["numPages"] : (function () { throw new RuntimeError('Variable "numPages" does not exist.', 132, $this->source); })())));
        foreach ($context['_seq'] as $context["_key"] => $context["numPage"]) {
            // line 133
            echo "              <li class=\"page-item";
            if (($context["numPage"] == 1)) {
                echo " active";
            }
            echo "\">
                <span class=\"page-link\" data-order-id=\"";
            // line 134
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 134, $this->source); })()), "id", [], "any", false, false, false, 134), "html", null, true);
            echo "\" data-page=\"";
            echo twig_escape_filter($this->env, $context["numPage"], "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $context["numPage"], "html", null, true);
            echo "</span>
              </li>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['numPage'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 137
        echo "            <li class=\"page-item d-none\">
              <span class=\"page-link\" data-order-id=\"";
        // line 138
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 138, $this->source); })()), "id", [], "any", false, false, false, 138), "html", null, true);
        echo "\" data-page=\"\"></span>
            </li>
            <li class=\"page-item\" id=\"orderProductsTablePaginationNext\">
              <a class=\"page-link\" href=\"javascript:void(0);\" aria-label=\"Next\">
                <span aria-hidden=\"true\">&raquo;</span>
                <span class=\"sr-only\">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>

      <div class=\"col-xl-6 text-xl-right discount-action\">
        ";
        // line 151
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 151, $this->source); })()), "delivered", [], "any", false, false, false, 151)) {
            // line 152
            echo "          <button type=\"button\" class=\"btn btn-outline-secondary js-product-action-btn mr-3\" id=\"addProductBtn\">
            <i class=\"material-icons\">add_circle_outline</i>
            ";
            // line 154
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add a product", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
          </button>
        ";
        }
        // line 157
        echo "        <button type=\"button\" class=\"btn btn-outline-secondary js-product-action-btn\" data-toggle=\"modal\" data-target=\"#addOrderDiscountModal\">
          <i class=\"material-icons\">confirmation_number</i>
          ";
        // line 159
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add a discount", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
        </button>
      </div>

    </div>

    ";
        // line 166
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", 166)->display(twig_array_merge($context, ["discounts" => twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 167
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 167, $this->source); })()), "discounts", [], "any", false, false, false, 167), "discounts", [], "any", false, false, false, 167), "orderId" => twig_get_attribute($this->env, $this->source,         // line 168
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 168, $this->source); })()), "id", [], "any", false, false, false, 168)]));
        // line 170
        echo "

    ";
        // line 173
        echo "
    <div class=\"info-block\">
      <div class=\"row\">

        <div class=\"col-sm text-center\">
          <p class=\"text-muted mb-0\">
            <strong>";
        // line 179
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Products", [], "Admin.Global"), "html", null, true);
        echo "</strong>
          </p>
          <strong id=\"orderProductsTotal\">";
        // line 181
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 181, $this->source); })()), "prices", [], "any", false, false, false, 181), "productsPriceFormatted", [], "any", false, false, false, 181), "html", null, true);
        echo "</strong>
        </div>

        <div id=\"order-discounts-total-container\" class=\"col-sm text-center";
        // line 184
        if ( !twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 184, $this->source); })()), "prices", [], "any", false, false, false, 184), "discountsAmountRaw", [], "any", false, false, false, 184), "greaterThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 184)) {
            echo " d-none";
        }
        echo "\">
          <p class=\"text-muted mb-0\">
            <strong>";
        // line 186
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Discounts", [], "Admin.Global"), "html", null, true);
        echo "</strong>
          </p>
          <strong id=\"orderDiscountsTotal\">-";
        // line 188
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 188, $this->source); })()), "prices", [], "any", false, false, false, 188), "discountsAmountFormatted", [], "any", false, false, false, 188), "html", null, true);
        echo "</strong>
        </div>

        ";
        // line 191
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 191, $this->source); })()), "prices", [], "any", false, false, false, 191), "wrappingPriceRaw", [], "any", false, false, false, 191), "greaterThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 191)) {
            // line 192
            echo "          <div class=\"col-sm text-center\">
            <p class=\"text-muted mb-0\">
              <strong>";
            // line 194
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Wrapping", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>
            </p>
            <strong id=\"orderWrappingTotal\">";
            // line 196
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 196, $this->source); })()), "prices", [], "any", false, false, false, 196), "wrappingPriceFormatted", [], "any", false, false, false, 196), "html", null, true);
            echo "</strong>
          </div>
        ";
        }
        // line 199
        echo "
        <div id=\"order-shipping-total-container\" class=\"col-sm text-center";
        // line 200
        if ( !twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 200, $this->source); })()), "prices", [], "any", false, false, false, 200), "shippingPriceRaw", [], "any", false, false, false, 200), "greaterThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 200)) {
            echo " d-none";
        }
        echo "\">
          <p class=\"text-muted mb-0\">
            <strong>";
        // line 202
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Shipping", [], "Admin.Catalog.Feature"), "html", null, true);
        echo "</strong>
          </p>
          <div class=\"shipping-price\">
            <strong id=\"orderShippingTotal\">";
        // line 205
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 205, $this->source); })()), "prices", [], "any", false, false, false, 205), "shippingPriceFormatted", [], "any", false, false, false, 205), "html", null, true);
        echo "</strong>
            <div class=\"cancel-product-element shipping-refund-amount";
        // line 206
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 206, $this->source); })()), "prices", [], "any", false, false, false, 206), "shippingRefundableAmountRaw", [], "any", false, false, false, 206), "lowerOrEqualThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 206)) {
            echo " hidden";
        }
        echo "\">
              <div class=\"input-group\">
                ";
        // line 208
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 208, $this->source); })()), "shipping_amount", [], "any", false, false, false, 208), 'widget');
        echo "
                <div class=\"input-group-append\">
                  <div class=\"input-group-text\">";
        // line 210
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderCurrency"]) || array_key_exists("orderCurrency", $context) ? $context["orderCurrency"] : (function () { throw new RuntimeError('Variable "orderCurrency" does not exist.', 210, $this->source); })()), "symbol", [], "any", false, false, false, 210), "html", null, true);
        echo "</div>
                </div>
              </div>
              <p class=\"text-center\">(max
                ";
        // line 214
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 214, $this->source); })()), "prices", [], "any", false, false, false, 214), "shippingRefundableAmountFormatted", [], "any", false, false, false, 214), "html", null, true);
        echo "
                tax included)</p>
            </div>
          </div>
        </div>

        ";
        // line 220
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 220, $this->source); })()), "taxIncluded", [], "any", false, false, false, 220)) {
            // line 221
            echo "          <div class=\"col-sm text-center\">
            <p class=\"text-muted mb-0\">
              <strong>";
            // line 223
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Taxes", [], "Admin.Global"), "html", null, true);
            echo "</strong>
            </p>
            <strong id=\"orderTaxesTotal\">";
            // line 225
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 225, $this->source); })()), "prices", [], "any", false, false, false, 225), "taxesAmountFormatted", [], "any", false, false, false, 225), "html", null, true);
            echo "</strong>
          </div>
        ";
        }
        // line 228
        echo "
        <div class=\"col-sm text-center\">
          <p class=\"text-muted mb-0\">
            <strong>";
        // line 231
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Total", [], "Admin.Global"), "html", null, true);
        echo "</strong>
          </p>
          <span class=\"badge rounded badge-dark font-size-100\" id=\"orderTotal\">";
        // line 233
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 233, $this->source); })()), "prices", [], "any", false, false, false, 233), "totalAmountFormatted", [], "any", false, false, false, 233), "html", null, true);
        echo "</span>
        </div>

      </div>
    </div>

    ";
        // line 240
        echo "    <p class=\"mb-0 mt-1 text-center text-muted\">
      <small>
        ";
        // line 242
        echo $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("For this customer group, prices are displayed as: [1]%tax_method%[/1]", ["%tax_method%" => twig_get_attribute($this->env, $this->source,         // line 243
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 243, $this->source); })()), "taxMethod", [], "any", false, false, false, 243), "[1]" => "<strong>", "[/1]" => "</strong>"], "Admin.Orderscustomers.Notification");
        // line 246
        echo ".

        ";
        // line 248
        if ( !$this->extensions['PrestaShopBundle\Twig\LayoutExtension']->getConfiguration("PS_ORDER_RETURN")) {
            // line 249
            echo "          <strong>";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Merchandise returns are disabled", [], "Admin.Orderscustomers.Notification"), "html", null, true);
            echo "</strong>
        ";
        }
        // line 251
        echo "      </small>
    </p>

    ";
        // line 255
        echo "    <div class=\"cancel-product-element refund-checkboxes-container\">
      <div class=\"cancel-product-element form-group restock-products\">
        ";
        // line 257
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 257, $this->source); })()), "restock", [], "any", false, false, false, 257), 'widget');
        echo "
      </div>
      <div class=\"cancel-product-element form-group refund-credit-slip\">
        ";
        // line 260
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 260, $this->source); })()), "credit_slip", [], "any", false, false, false, 260), 'widget');
        echo "
      </div>
      <div class=\"cancel-product-element form-group refund-voucher\">
        ";
        // line 263
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 263, $this->source); })()), "voucher", [], "any", false, false, false, 263), 'widget');
        echo "
      </div>
      <div class=\"cancel-product-element shipping-refund";
        // line 265
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 265, $this->source); })()), "prices", [], "any", false, false, false, 265), "shippingRefundableAmountRaw", [], "any", false, false, false, 265), "lowerOrEqualThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 265)) {
            echo " hidden";
        }
        echo "\">
        <div class=\"form-group\">
          ";
        // line 267
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 267, $this->source); })()), "shipping", [], "any", false, false, false, 267), 'widget');
        echo "
          <small class=\"shipping-refund-amount\">(";
        // line 268
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 268, $this->source); })()), "prices", [], "any", false, false, false, 268), "shippingRefundableAmountFormatted", [], "any", false, false, false, 268), "html", null, true);
        echo ")</small>
        </div>
      </div>
      <div class=\"cancel-product-element form-group voucher-refund-type";
        // line 271
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 271, $this->source); })()), "prices", [], "any", false, false, false, 271), "discountsAmountRaw", [], "any", false, false, false, 271), "lowerOrEqualThan", [0 => $this->extensions['PrestaShopBundle\Twig\Extension\NumberExtension']->createNumber(0)], "method", false, false, false, 271)) {
            echo " hidden";
        }
        echo "\">
        ";
        // line 272
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("This order has been partially paid by voucher. Choose the amount you want to refund:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
        ";
        // line 273
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 273, $this->source); })()), "voucher_refund_type", [], "any", false, false, false, 273), 'widget');
        echo "
        <div class=\"voucher-refund-type-negative-error\">
          ";
        // line 275
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Error. You cannot refund a negative amount.", [], "Admin.Orderscustomers.Notification"), "html", null, true);
        echo "
        </div>
      </div>
    </div>
    <div class=\"cancel-product-element form-submit text-right\">
      ";
        // line 280
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 280, $this->source); })()), "cancel", [], "any", false, false, false, 280), 'widget');
        echo "
      ";
        // line 281
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 281, $this->source); })()), "save", [], "any", false, false, false, 281), 'widget');
        echo "
    </div>

    ";
        // line 284
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 284, $this->source); })()), 'form_end');
        echo "
  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  595 => 284,  589 => 281,  585 => 280,  577 => 275,  572 => 273,  568 => 272,  562 => 271,  556 => 268,  552 => 267,  545 => 265,  540 => 263,  534 => 260,  528 => 257,  524 => 255,  519 => 251,  513 => 249,  511 => 248,  507 => 246,  505 => 243,  504 => 242,  500 => 240,  491 => 233,  486 => 231,  481 => 228,  475 => 225,  470 => 223,  466 => 221,  464 => 220,  455 => 214,  448 => 210,  443 => 208,  436 => 206,  432 => 205,  426 => 202,  419 => 200,  416 => 199,  410 => 196,  405 => 194,  401 => 192,  399 => 191,  393 => 188,  388 => 186,  381 => 184,  375 => 181,  370 => 179,  362 => 173,  358 => 170,  356 => 168,  355 => 167,  353 => 166,  344 => 159,  340 => 157,  334 => 154,  330 => 152,  328 => 151,  312 => 138,  309 => 137,  296 => 134,  289 => 133,  285 => 132,  273 => 125,  266 => 124,  264 => 123,  259 => 120,  244 => 118,  240 => 117,  235 => 115,  230 => 112,  224 => 107,  221 => 106,  218 => 105,  216 => 104,  208 => 99,  205 => 98,  199 => 95,  196 => 94,  193 => 93,  187 => 90,  184 => 89,  182 => 88,  177 => 86,  173 => 85,  167 => 82,  161 => 81,  156 => 79,  150 => 78,  145 => 76,  139 => 75,  134 => 73,  128 => 70,  124 => 69,  117 => 65,  109 => 61,  103 => 58,  101 => 55,  100 => 54,  99 => 53,  98 => 52,  97 => 51,  96 => 49,  82 => 40,  76 => 36,  69 => 34,  66 => 33,  63 => 32,  60 => 31,  57 => 30,  54 => 29,  50 => 28,  48 => 27,  46 => 26,  43 => 25,);
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

{% set isColumnLocationDisplayed = false %}
{% set isColumnRefundedDisplayed = false %}
{% for product in orderForViewing.products.products|slice(0, paginationNum) %}
  {% if product.location is not empty %}
    {% set isColumnLocationDisplayed = true %}
  {% endif %}
  {% if product.quantityRefunded > 0 %}
    {% set isColumnRefundedDisplayed = true %}
  {% endif %}
{% endfor %}

<div class=\"card\" id=\"orderProductsPanel\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      {{ 'Products'|trans({}, 'Admin.Global') }} (<span id=\"orderProductsPanelCount\">{{ orderForViewing.products.products|length }}</span>)
    </h3>
  </div>

  <div class=\"card-body\">
    <div class=\"spinner-order-products-container\" id=\"orderProductsLoading\">
      <div class=\"spinner spinner-primary\"></div>
    </div>

    {% set formOptions = {
          'attr': {
            'data-order-id': orderForViewing.id,
            'data-is-delivered': orderForViewing.isDelivered,
            'data-is-tax-included': orderForViewing.isTaxIncluded,
            'data-discounts-amount': orderForViewing.prices.discountsAmountRaw,
            'data-price-specification': priceSpecification|json_encode
          }
        } %}
    {{ form_start(cancelProductForm, formOptions) }}

    {# PRODUCT TABLE #}
    <table class=\"table\" id=\"orderProductsTable\" data-currency-precision=\"{{ orderCurrency.precision }}\">
      <thead>
        <tr>
          <th>
            <p>{{ 'Product'|trans({}, 'Admin.Global') }}</p>
          </th>
          <th></th>
          <th>
            <p class=\"mb-0\">{{ 'Price per unit'|trans({}, 'Admin.Advparameters.Feature') }}</p>
            <small class=\"text-muted\">{{ orderForViewing.taxMethod }}</small>
          </th>
          <th>
            <p>{{ 'Quantity'|trans({}, 'Admin.Global') }}</p>
          </th>
          <th class=\"cellProductLocation{% if not isColumnLocationDisplayed %} d-none{% endif %}\">
            <p>{{ 'Stock location'|trans({}, 'Admin.Orderscustomers.Feature') }}</p>
          </th>
          <th class=\"cellProductRefunded{% if not isColumnRefundedDisplayed %} d-none{% endif %}\">
            <p>{{ 'Refunded'|trans({}, 'Admin.Orderscustomers.Feature') }}</p>
          </th>
          <th {% if not isAvailableQuantityDisplayed %} class=\"d-none\" {% endif %}>
            <p>{{ 'Available'|trans({}, 'Admin.Global') }}</p>
          </th>
          <th>
            <p class=\"mb-0\">{{ 'Total'|trans({}, 'Admin.Global') }}</p>
            <small class=\"text-muted\">{{ orderForViewing.taxMethod }}</small>
          </th>
          {% if orderForViewing.hasInvoice() %}
            <th>
              <p>{{ 'Invoice'|trans({}, 'Admin.Global') }}</p>
            </th>
          {% endif %}
          {% if not orderForViewing.delivered %}
            <th class=\"text-right product_actions d-print-none\">
              <p>{{ 'Actions'|trans({}, 'Admin.Global') }}</p>
            </th>
          {% endif %}
          <th class=\"text-center cancel-product-element\">
            <p>{{ 'Partial refund'|trans({}, 'Admin.Orderscustomers.Feature') }}</p>
          </th>
        </tr>
      </thead>
      <tbody>
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product_list.html.twig' %}
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig' %}
        {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig' %}
      </tbody>
    </table>


    {# PAGINATION AND ADD NEW PRODUCT/DISCOUNT #}
    <div class=\"row mb-3\">
      <div class=\"col-xl-6 d-print-none order-product-pagination\">
        <div class=\"form-group\">
          <label for=\"paginator_select_page_limit\" class=\"col-form-label ml-3\">{{ \"Items per page:\"|trans({}, 'Admin.Catalog.Feature') }}</label>
          <select id=\"orderProductsTablePaginationNumberSelector\" class=\"pagination-link custom-select\">
            {% for numPageOption in paginationNumOptions %}
              <option value=\"{{ numPageOption }}\" {% if numPageOption == paginationNum %} selected {% endif %}>{{ numPageOption }}</option>
            {% endfor %}
          </select>
        </div>

        {% set numPages = max(orderForViewing.products.products|length / paginationNum, 1)|round(0, 'ceil') %}
        <nav aria-label=\"Products Navigation\"{% if orderForViewing.products.products|length <= paginationNum %} class=\"d-none\"{% endif %} id=\"orderProductsNavPagination\">
          <ul class=\"pagination\" id=\"orderProductsTablePagination\" data-num-per-page=\"{{ paginationNum }}\" data-num-pages=\"{{ numPages }}\">
            <li class=\"page-item disabled\" id=\"orderProductsTablePaginationPrev\">
              <a class=\"page-link\" href=\"javascript:void(0);\" aria-label=\"Previous\">
                <span aria-hidden=\"true\">&laquo;</span>
                <span class=\"sr-only\">Previous</span>
              </a>
            </li>
            {% for numPage in 1..numPages %}
              <li class=\"page-item{% if numPage==1 %} active{% endif %}\">
                <span class=\"page-link\" data-order-id=\"{{ orderForViewing.id }}\" data-page=\"{{ numPage }}\">{{ numPage }}</span>
              </li>
            {% endfor %}
            <li class=\"page-item d-none\">
              <span class=\"page-link\" data-order-id=\"{{ orderForViewing.id }}\" data-page=\"\"></span>
            </li>
            <li class=\"page-item\" id=\"orderProductsTablePaginationNext\">
              <a class=\"page-link\" href=\"javascript:void(0);\" aria-label=\"Next\">
                <span aria-hidden=\"true\">&raquo;</span>
                <span class=\"sr-only\">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>

      <div class=\"col-xl-6 text-xl-right discount-action\">
        {% if not orderForViewing.delivered %}
          <button type=\"button\" class=\"btn btn-outline-secondary js-product-action-btn mr-3\" id=\"addProductBtn\">
            <i class=\"material-icons\">add_circle_outline</i>
            {{ 'Add a product'|trans({}, 'Admin.Orderscustomers.Feature') }}
          </button>
        {% endif %}
        <button type=\"button\" class=\"btn btn-outline-secondary js-product-action-btn\" data-toggle=\"modal\" data-target=\"#addOrderDiscountModal\">
          <i class=\"material-icons\">confirmation_number</i>
          {{ 'Add a discount'|trans({}, 'Admin.Orderscustomers.Feature') }}
        </button>
      </div>

    </div>

    {# DISCOUNT LIST #}
    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/discount_list.html.twig' with {
        'discounts': orderForViewing.discounts.discounts,
        'orderId': orderForViewing.id
    } %}


    {# ORDER TOTALS #}

    <div class=\"info-block\">
      <div class=\"row\">

        <div class=\"col-sm text-center\">
          <p class=\"text-muted mb-0\">
            <strong>{{ 'Products'|trans({}, 'Admin.Global') }}</strong>
          </p>
          <strong id=\"orderProductsTotal\">{{ orderForViewing.prices.productsPriceFormatted }}</strong>
        </div>

        <div id=\"order-discounts-total-container\" class=\"col-sm text-center{% if not orderForViewing.prices.discountsAmountRaw.greaterThan((number(0))) %} d-none{% endif %}\">
          <p class=\"text-muted mb-0\">
            <strong>{{ 'Discounts'|trans({}, 'Admin.Global') }}</strong>
          </p>
          <strong id=\"orderDiscountsTotal\">-{{ orderForViewing.prices.discountsAmountFormatted }}</strong>
        </div>

        {% if orderForViewing.prices.wrappingPriceRaw.greaterThan(number(0)) %}
          <div class=\"col-sm text-center\">
            <p class=\"text-muted mb-0\">
              <strong>{{ 'Wrapping'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
            </p>
            <strong id=\"orderWrappingTotal\">{{ orderForViewing.prices.wrappingPriceFormatted }}</strong>
          </div>
        {% endif %}

        <div id=\"order-shipping-total-container\" class=\"col-sm text-center{% if not orderForViewing.prices.shippingPriceRaw.greaterThan((number(0))) %} d-none{% endif %}\">
          <p class=\"text-muted mb-0\">
            <strong>{{ 'Shipping'|trans({}, 'Admin.Catalog.Feature') }}</strong>
          </p>
          <div class=\"shipping-price\">
            <strong id=\"orderShippingTotal\">{{ orderForViewing.prices.shippingPriceFormatted }}</strong>
            <div class=\"cancel-product-element shipping-refund-amount{% if orderForViewing.prices.shippingRefundableAmountRaw.lowerOrEqualThan(number(0)) %} hidden{% endif %}\">
              <div class=\"input-group\">
                {{ form_widget(cancelProductForm.shipping_amount) }}
                <div class=\"input-group-append\">
                  <div class=\"input-group-text\">{{ orderCurrency.symbol }}</div>
                </div>
              </div>
              <p class=\"text-center\">(max
                {{ orderForViewing.prices.shippingRefundableAmountFormatted }}
                tax included)</p>
            </div>
          </div>
        </div>

        {% if not orderForViewing.taxIncluded %}
          <div class=\"col-sm text-center\">
            <p class=\"text-muted mb-0\">
              <strong>{{ 'Taxes'|trans({}, 'Admin.Global') }}</strong>
            </p>
            <strong id=\"orderTaxesTotal\">{{ orderForViewing.prices.taxesAmountFormatted }}</strong>
          </div>
        {% endif %}

        <div class=\"col-sm text-center\">
          <p class=\"text-muted mb-0\">
            <strong>{{ 'Total'|trans({}, 'Admin.Global') }}</strong>
          </p>
          <span class=\"badge rounded badge-dark font-size-100\" id=\"orderTotal\">{{ orderForViewing.prices.totalAmountFormatted }}</span>
        </div>

      </div>
    </div>

    {# PRICE DISPLAY #}
    <p class=\"mb-0 mt-1 text-center text-muted\">
      <small>
        {{ 'For this customer group, prices are displayed as: [1]%tax_method%[/1]'|trans({
          '%tax_method%': orderForViewing.taxMethod,
          '[1]': '<strong>',
          '[/1]': '</strong>'
        }, 'Admin.Orderscustomers.Notification')|raw }}.

        {% if not configuration('PS_ORDER_RETURN') %}
          <strong>{{ 'Merchandise returns are disabled'|trans({}, 'Admin.Orderscustomers.Notification') }}</strong>
        {% endif %}
      </small>
    </p>

    {# PRODUCT CANCEL #}
    <div class=\"cancel-product-element refund-checkboxes-container\">
      <div class=\"cancel-product-element form-group restock-products\">
        {{ form_widget(cancelProductForm.restock) }}
      </div>
      <div class=\"cancel-product-element form-group refund-credit-slip\">
        {{ form_widget(cancelProductForm.credit_slip) }}
      </div>
      <div class=\"cancel-product-element form-group refund-voucher\">
        {{ form_widget(cancelProductForm.voucher) }}
      </div>
      <div class=\"cancel-product-element shipping-refund{% if orderForViewing.prices.shippingRefundableAmountRaw.lowerOrEqualThan(number(0)) %} hidden{% endif %}\">
        <div class=\"form-group\">
          {{ form_widget(cancelProductForm.shipping) }}
          <small class=\"shipping-refund-amount\">({{ orderForViewing.prices.shippingRefundableAmountFormatted }})</small>
        </div>
      </div>
      <div class=\"cancel-product-element form-group voucher-refund-type{% if orderForViewing.prices.discountsAmountRaw.lowerOrEqualThan(number(0)) %} hidden{% endif %}\">
        {{ 'This order has been partially paid by voucher. Choose the amount you want to refund:'|trans({}, 'Admin.Orderscustomers.Feature') }}
        {{ form_widget(cancelProductForm.voucher_refund_type) }}
        <div class=\"voucher-refund-type-negative-error\">
          {{ 'Error. You cannot refund a negative amount.'|trans({}, 'Admin.Orderscustomers.Notification') }}
        </div>
      </div>
    </div>
    <div class=\"cancel-product-element form-submit text-right\">
      {{ form_widget(cancelProductForm.cancel) }}
      {{ form_widget(cancelProductForm.save) }}
    </div>

    {{ form_end(cancelProductForm) }}
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/products.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/products.html.twig");
    }
}
