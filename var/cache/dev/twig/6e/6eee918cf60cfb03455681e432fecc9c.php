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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/product.html.twig */
class __TwigTemplate_3050e22aaff37b940503cf11e2e9dbe3 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product.html.twig"));

        // line 25
        echo "
";
        // line 26
        $context["rowIsDisplayed"] = ((array_key_exists("productIndex", $context) && array_key_exists("paginationNum", $context)) && ((isset($context["productIndex"]) || array_key_exists("productIndex", $context) ? $context["productIndex"] : (function () { throw new RuntimeError('Variable "productIndex" does not exist.', 26, $this->source); })()) > (isset($context["paginationNum"]) || array_key_exists("paginationNum", $context) ? $context["paginationNum"] : (function () { throw new RuntimeError('Variable "paginationNum" does not exist.', 26, $this->source); })())));
        // line 27
        echo "<tr id=\"orderProduct_";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 27, $this->source); })()), "orderDetailId", [], "any", false, false, false, 27), "html", null, true);
        echo "\" class=\"cellProduct";
        if ((isset($context["rowIsDisplayed"]) || array_key_exists("rowIsDisplayed", $context) ? $context["rowIsDisplayed"] : (function () { throw new RuntimeError('Variable "rowIsDisplayed" does not exist.', 27, $this->source); })())) {
            echo " d-none d-print-table-row";
        }
        echo "\">
  <td class=\"cellProductImg\">
    ";
        // line 29
        if (twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 29, $this->source); })()), "imagePath", [], "any", false, false, false, 29)) {
            // line 30
            echo "      <img src=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 30, $this->source); })()), "imagePath", [], "any", false, false, false, 30), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 30, $this->source); })()), "name", [], "any", false, false, false, 30), "html", null, true);
            echo "\" />
    ";
        }
        // line 32
        echo "  </td>
  <td class=\"cellProductName\">
    <a href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_product_form", ["id" => twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 34, $this->source); })()), "id", [], "any", false, false, false, 34)]), "html", null, true);
        echo "\">
      <p class=\"mb-0 productName\">";
        // line 35
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 35, $this->source); })()), "name", [], "any", false, false, false, 35), "html", null, true);
        echo "</p>
      ";
        // line 36
        if (twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 36, $this->source); })()), "reference", [], "any", false, false, false, 36)) {
            // line 37
            echo "        <p class=\"mb-0 productReference\">
          ";
            // line 38
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Reference number:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
          ";
            // line 39
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 39, $this->source); })()), "reference", [], "any", false, false, false, 39), "html", null, true);
            echo "
        </p>
      ";
        }
        // line 42
        echo "      ";
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 42, $this->source); })()), "supplierReference", [], "any", false, false, false, 42))) {
            // line 43
            echo "        <p class=\"mb-0 productSupplierReference\">
          ";
            // line 44
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Supplier reference:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
          ";
            // line 45
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 45, $this->source); })()), "supplierReference", [], "any", false, false, false, 45), "html", null, true);
            echo "
        </p>
      ";
        }
        // line 48
        echo "    </a>
    ";
        // line 49
        if (((twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 49, $this->source); })()), "type", [], "any", false, false, false, 49) == twig_constant("PrestaShop\\PrestaShop\\Core\\Domain\\Order\\QueryResult\\OrderProductForViewing::TYPE_PACK")) && (null === twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 49, $this->source); })()), "customizations", [], "any", false, false, false, 49)))) {
            // line 50
            echo "      <span class=\"btn-product-pack-modal d-print-none\" data-toggle=\"modal\" data-target=\"#product-pack-modal\" data-pack-items=\"";
            echo twig_escape_filter($this->env, json_encode(twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 50, $this->source); })()), "packItems", [], "any", false, false, false, 50)), "html", null, true);
            echo "\">
        <strong>";
            // line 51
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View pack content", [], "Admin.Actions"), "html", null, true);
            echo "</strong>
      </span>
    ";
        }
        // line 54
        echo "  </td>
  <td class=\"cellProductUnitPrice\">";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 55, $this->source); })()), "unitPrice", [], "any", false, false, false, 55), "html", null, true);
        echo "</td>
  <td class=\"cellProductQuantity text-center\">
    ";
        // line 57
        if ((twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 57, $this->source); })()), "quantity", [], "any", false, false, false, 57) > 1)) {
            // line 58
            echo "      <span class=\"badge badge-secondary rounded-circle\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 58, $this->source); })()), "quantity", [], "any", false, false, false, 58), "html", null, true);
            echo "</span>
    ";
        } else {
            // line 60
            echo "      ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 60, $this->source); })()), "quantity", [], "any", false, false, false, 60), "html", null, true);
            echo "
    ";
        }
        // line 62
        echo "
    <div class=\"d-none js-product-quantity\">
      <input type=\"text\" value=\"";
        // line 64
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 64, $this->source); })()), "quantity", [], "any", false, false, false, 64), "html", null, true);
        echo "\" class=\"form-control\">
    </div>
  </td>
  <td class=\"cellProductLocation";
        // line 67
        if ( !(isset($context["isColumnLocationDisplayed"]) || array_key_exists("isColumnLocationDisplayed", $context) ? $context["isColumnLocationDisplayed"] : (function () { throw new RuntimeError('Variable "isColumnLocationDisplayed" does not exist.', 67, $this->source); })())) {
            echo " d-none";
        }
        echo "\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 67, $this->source); })()), "location", [], "any", false, false, false, 67), "html", null, true);
        echo "</td>
  <td class=\"cellProductRefunded";
        // line 68
        if ( !(isset($context["isColumnRefundedDisplayed"]) || array_key_exists("isColumnRefundedDisplayed", $context) ? $context["isColumnRefundedDisplayed"] : (function () { throw new RuntimeError('Variable "isColumnRefundedDisplayed" does not exist.', 68, $this->source); })())) {
            echo " d-none";
        }
        echo "\">
    ";
        // line 69
        if ((twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 69, $this->source); })()), "quantityRefunded", [], "any", false, false, false, 69) > 0)) {
            // line 70
            echo "      ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 70, $this->source); })()), "quantityRefunded", [], "any", false, false, false, 70), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 70, $this->source); })()), "amountRefunded", [], "any", false, false, false, 70), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Refunded", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo ")
    ";
        }
        // line 72
        echo "  </td>
  <td class=\"cellProductAvailableQuantity text-center";
        // line 73
        if ( !(isset($context["isAvailableQuantityDisplayed"]) || array_key_exists("isAvailableQuantityDisplayed", $context) ? $context["isAvailableQuantityDisplayed"] : (function () { throw new RuntimeError('Variable "isAvailableQuantityDisplayed" does not exist.', 73, $this->source); })())) {
            echo " d-none";
        }
        echo "\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 73, $this->source); })()), "availableQuantity", [], "any", false, false, false, 73), "html", null, true);
        echo "</td>
  <td class=\"cellProductTotalPrice\">";
        // line 74
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 74, $this->source); })()), "totalPrice", [], "any", false, false, false, 74), "html", null, true);
        echo "</td>
  ";
        // line 75
        if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 75, $this->source); })()), "hasInvoice", [], "method", false, false, false, 75)) {
            // line 76
            echo "    <td>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 76, $this->source); })()), "orderInvoiceNumber", [], "any", false, false, false, 76), "html", null, true);
            echo "</td>
  ";
        }
        // line 78
        echo "  ";
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 78, $this->source); })()), "delivered", [], "any", false, false, false, 78)) {
            // line 79
            echo "    <td class=\"text-right cellProductActions\">
      <button
        type=\"button\"
        class=\"btn tooltip-link js-order-product-edit-btn\"
        data-toggle=\"pstooltip\"
        data-placement=\"top\"
        data-original-title=\"";
            // line 85
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit", [], "Admin.Actions"), "html", null, true);
            echo "\"
        data-order-detail-id=\"";
            // line 86
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 86, $this->source); })()), "orderDetailId", [], "any", false, false, false, 86), "html", null, true);
            echo "\"
        data-product-id=\"";
            // line 87
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 87, $this->source); })()), "id", [], "any", false, false, false, 87), "html", null, true);
            echo "\"
        data-combination-id=\"";
            // line 88
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 88, $this->source); })()), "combinationId", [], "any", false, false, false, 88), "html", null, true);
            echo "\"
        data-product-quantity=\"";
            // line 89
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 89, $this->source); })()), "quantity", [], "any", false, false, false, 89), "html", null, true);
            echo "\"
        data-product-price-tax-incl=\"";
            // line 90
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 90, $this->source); })()), "unitPriceTaxInclRaw", [], "any", false, false, false, 90), "html", null, true);
            echo "\"
        data-product-price-tax-excl=\"";
            // line 91
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 91, $this->source); })()), "unitPriceTaxExclRaw", [], "any", false, false, false, 91), "html", null, true);
            echo "\"
        data-tax-rate=\"";
            // line 92
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 92, $this->source); })()), "taxRate", [], "any", false, false, false, 92), "html", null, true);
            echo "\"
        data-location=\"";
            // line 93
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 93, $this->source); })()), "location", [], "any", false, false, false, 93), "html", null, true);
            echo "\"
        data-available-quantity=\"";
            // line 94
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 94, $this->source); })()), "availableQuantity", [], "any", false, false, false, 94), "html", null, true);
            echo "\"
        data-available-out-of-stock=\"";
            // line 95
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 95, $this->source); })()), "availableOutOfStock", [], "any", false, false, false, 95), "html", null, true);
            echo "\"
        data-order-invoice-id=\"";
            // line 96
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 96, $this->source); })()), "orderInvoiceId", [], "any", false, false, false, 96), "html", null, true);
            echo "\"
        data-is-order-tax-included=\"";
            // line 97
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 97, $this->source); })()), "isTaxIncluded", [], "any", false, false, false, 97), "html", null, true);
            echo "\">
        <i class=\"material-icons\">edit</i>
      </button>
      <button
        type=\"button\"
        class=\"btn tooltip-link js-order-product-delete-btn\"
        data-toggle=\"pstooltip\"
        data-placement=\"top\"
        data-order-id=\"";
            // line 105
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 105, $this->source); })()), "id", [], "any", false, false, false, 105), "html", null, true);
            echo "\"
        data-order-detail-id=\"";
            // line 106
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 106, $this->source); })()), "orderDetailId", [], "any", false, false, false, 106), "html", null, true);
            echo "\"
        data-delete-message=\"";
            // line 107
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Are you sure?", [], "Admin.Notifications.Warning"), "html", null, true);
            echo "\"
        data-original-title=\"";
            // line 108
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Delete", [], "Admin.Actions"), "html", null, true);
            echo "\">
        <i class=\"material-icons\">delete</i>
      </button>
    </td>
  ";
        }
        // line 113
        echo "  <td class=\"text-right cancel-product-element\">
    <div class=\"cancel-product-cell cancel-product-element";
        // line 114
        if ((twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 114, $this->source); })()), "refundable", [], "any", false, false, false, 114) == false)) {
            echo " hidden";
        }
        echo "\">
      <div class=\"cancel-product-cell-elements\">
        <div class=\"cancel-product-selector form-group\">
          ";
        // line 117
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 117, $this->source); })()), ("selected_" . twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 117, $this->source); })()), "orderDetailId", [], "any", false, false, false, 117)), [], "array", false, false, false, 117), 'widget');
        echo "
        </div>
        <div class=\"cancel-product-quantity form-group\">
          ";
        // line 120
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 120, $this->source); })()), ("quantity_" . twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 120, $this->source); })()), "orderDetailId", [], "any", false, false, false, 120)), [], "array", false, false, false, 120), 'label');
        echo "
          <div class=\"input-group\">
            ";
        // line 122
        $context["quantityInputOptions"] = ["attr" => ["data-product-price-tax-incl" => twig_get_attribute($this->env, $this->source,         // line 124
(isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 124, $this->source); })()), "unitPriceTaxInclRaw", [], "any", false, false, false, 124), "data-product-price-tax-excl" => twig_get_attribute($this->env, $this->source,         // line 125
(isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 125, $this->source); })()), "unitPriceTaxExclRaw", [], "any", false, false, false, 125), "data-amount-refundable" => twig_get_attribute($this->env, $this->source,         // line 126
(isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 126, $this->source); })()), "amountRefundableRaw", [], "any", false, false, false, 126), "data-quantity-refundable" => twig_get_attribute($this->env, $this->source,         // line 127
(isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 127, $this->source); })()), "quantityRefundable", [], "any", false, false, false, 127)]];
        // line 130
        echo "            ";
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 130, $this->source); })()), ("quantity_" . twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 130, $this->source); })()), "orderDetailId", [], "any", false, false, false, 130)), [], "array", false, false, false, 130), 'widget', (isset($context["quantityInputOptions"]) || array_key_exists("quantityInputOptions", $context) ? $context["quantityInputOptions"] : (function () { throw new RuntimeError('Variable "quantityInputOptions" does not exist.', 130, $this->source); })()));
        echo "
          </div>
        </div>
        <div class=\"cancel-product-amount form-group\">
          ";
        // line 134
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 134, $this->source); })()), ("amount_" . twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 134, $this->source); })()), "orderDetailId", [], "any", false, false, false, 134)), [], "array", false, false, false, 134), 'label');
        echo "
          <div class=\"input-group\">
            ";
        // line 136
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["cancelProductForm"]) || array_key_exists("cancelProductForm", $context) ? $context["cancelProductForm"] : (function () { throw new RuntimeError('Variable "cancelProductForm" does not exist.', 136, $this->source); })()), ("amount_" . twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 136, $this->source); })()), "orderDetailId", [], "any", false, false, false, 136)), [], "array", false, false, false, 136), 'widget');
        echo "
            <div class=\"input-group-append\"><div class=\"input-group-text\">";
        // line 137
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderCurrency"]) || array_key_exists("orderCurrency", $context) ? $context["orderCurrency"] : (function () { throw new RuntimeError('Variable "orderCurrency" does not exist.', 137, $this->source); })()), "symbol", [], "any", false, false, false, 137), "html", null, true);
        echo "</div></div>
            <small class=\"max-refund text-left\">
              ";
        // line 139
        echo $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("(Max %amount_refundable% %tax_method%)", ["%amount_refundable%" => twig_get_attribute($this->env, $this->source,         // line 140
(isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 140, $this->source); })()), "amountRefundable", [], "any", false, false, false, 140), "%tax_method%" => twig_get_attribute($this->env, $this->source,         // line 141
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 141, $this->source); })()), "taxMethod", [], "any", false, false, false, 141)], "Admin.Orderscustomers.Help");
        // line 142
        echo "
            </small>
          </div>
        </div>
      </div>
    </div>
  </td>
</tr>
";
        // line 150
        if ( !(null === twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 150, $this->source); })()), "customizations", [], "any", false, false, false, 150))) {
            // line 151
            echo "  <tr class=\"order-product-customization";
            if ((isset($context["rowIsDisplayed"]) || array_key_exists("rowIsDisplayed", $context) ? $context["rowIsDisplayed"] : (function () { throw new RuntimeError('Variable "rowIsDisplayed" does not exist.', 151, $this->source); })())) {
                echo " d-none";
            }
            echo "\">
    <td class=\"border-top-0\"></td>
    ";
            // line 153
            $context["colspan"] = 8;
            // line 154
            echo "    ";
            $context["colspan"] = ((twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 154, $this->source); })()), "hasInvoice", [], "method", false, false, false, 154)) ? (((isset($context["colspan"]) || array_key_exists("colspan", $context) ? $context["colspan"] : (function () { throw new RuntimeError('Variable "colspan" does not exist.', 154, $this->source); })()) + 1)) : ((isset($context["colspan"]) || array_key_exists("colspan", $context) ? $context["colspan"] : (function () { throw new RuntimeError('Variable "colspan" does not exist.', 154, $this->source); })())));
            // line 155
            echo "    ";
            $context["colspan"] = (( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 155, $this->source); })()), "delivered", [], "any", false, false, false, 155)) ? (((isset($context["colspan"]) || array_key_exists("colspan", $context) ? $context["colspan"] : (function () { throw new RuntimeError('Variable "colspan" does not exist.', 155, $this->source); })()) + 1)) : ((isset($context["colspan"]) || array_key_exists("colspan", $context) ? $context["colspan"] : (function () { throw new RuntimeError('Variable "colspan" does not exist.', 155, $this->source); })())));
            // line 156
            echo "    <td colspan=\"";
            echo twig_escape_filter($this->env, (isset($context["colspan"]) || array_key_exists("colspan", $context) ? $context["colspan"] : (function () { throw new RuntimeError('Variable "colspan" does not exist.', 156, $this->source); })()), "html", null, true);
            echo "\" class=\"border-top-0 text-muted\">
      ";
            // line 157
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 157, $this->source); })()), "customizations", [], "any", false, false, false, 157), "fileCustomizations", [], "any", false, false, false, 157)) {
                // line 158
                echo "        <div class=\"d-flex flex-row flex-wrap\">
          ";
                // line 159
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 159, $this->source); })()), "customizations", [], "any", false, false, false, 159), "fileCustomizations", [], "any", false, false, false, 159));
                foreach ($context['_seq'] as $context["_key"] => $context["customization"]) {
                    // line 160
                    echo "              <div class=\"mr-4\">
                <p><strong>";
                    // line 161
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["customization"], "name", [], "any", false, false, false, 161), "html", null, true);
                    echo "</strong></p>
                <p>
                  <a href=\"";
                    // line 163
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_display_customization_image", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 163, $this->source); })()), "id", [], "any", false, false, false, 163), "value" => twig_get_attribute($this->env, $this->source, $context["customization"], "value", [], "any", false, false, false, 163)]), "html", null, true);
                    echo "\" download>
                    <img src=\"";
                    // line 164
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["customization"], "image", [], "any", false, false, false, 164), "html", null, true);
                    echo "\" alt=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["customization"], "name", [], "any", false, false, false, 164), "html", null, true);
                    echo "\">
                  </a>
                </p>
              </div>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['customization'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 169
                echo "        </div>
      ";
            }
            // line 171
            echo "      ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 171, $this->source); })()), "customizations", [], "any", false, false, false, 171), "textCustomizations", [], "any", false, false, false, 171));
            foreach ($context['_seq'] as $context["_key"] => $context["customization"]) {
                // line 172
                echo "        <p><strong>";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["customization"], "name", [], "any", false, false, false, 172), "html", null, true);
                echo " :</strong> ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["customization"], "value", [], "any", false, false, false, 172), "html", null, true);
                echo "</p>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['customization'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 174
            echo "      ";
            if ((twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 174, $this->source); })()), "type", [], "any", false, false, false, 174) == twig_constant("PrestaShop\\PrestaShop\\Core\\Domain\\Order\\QueryResult\\OrderProductForViewing::TYPE_PACK"))) {
                // line 175
                echo "        <div class=\"btn-product-pack-modal mb-3 d-print-none\" data-toggle=\"modal\" data-target=\"#product-pack-modal\" data-pack-items=\"";
                echo twig_escape_filter($this->env, json_encode(twig_get_attribute($this->env, $this->source, (isset($context["product"]) || array_key_exists("product", $context) ? $context["product"] : (function () { throw new RuntimeError('Variable "product" does not exist.', 175, $this->source); })()), "packItems", [], "any", false, false, false, 175)), "html", null, true);
                echo "\">
          <strong>";
                // line 176
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("View pack content", [], "Admin.Actions"), "html", null, true);
                echo "</strong>
        </div>
      ";
            }
            // line 179
            echo "    </td>
  </tr>
";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  453 => 179,  447 => 176,  442 => 175,  439 => 174,  428 => 172,  423 => 171,  419 => 169,  406 => 164,  402 => 163,  397 => 161,  394 => 160,  390 => 159,  387 => 158,  385 => 157,  380 => 156,  377 => 155,  374 => 154,  372 => 153,  364 => 151,  362 => 150,  352 => 142,  350 => 141,  349 => 140,  348 => 139,  343 => 137,  339 => 136,  334 => 134,  326 => 130,  324 => 127,  323 => 126,  322 => 125,  321 => 124,  320 => 122,  315 => 120,  309 => 117,  301 => 114,  298 => 113,  290 => 108,  286 => 107,  282 => 106,  278 => 105,  267 => 97,  263 => 96,  259 => 95,  255 => 94,  251 => 93,  247 => 92,  243 => 91,  239 => 90,  235 => 89,  231 => 88,  227 => 87,  223 => 86,  219 => 85,  211 => 79,  208 => 78,  202 => 76,  200 => 75,  196 => 74,  188 => 73,  185 => 72,  175 => 70,  173 => 69,  167 => 68,  159 => 67,  153 => 64,  149 => 62,  143 => 60,  137 => 58,  135 => 57,  130 => 55,  127 => 54,  121 => 51,  116 => 50,  114 => 49,  111 => 48,  105 => 45,  101 => 44,  98 => 43,  95 => 42,  89 => 39,  85 => 38,  82 => 37,  80 => 36,  76 => 35,  72 => 34,  68 => 32,  60 => 30,  58 => 29,  48 => 27,  46 => 26,  43 => 25,);
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

{% set rowIsDisplayed =  (productIndex is defined and paginationNum is defined and productIndex > paginationNum) %}
<tr id=\"orderProduct_{{ product.orderDetailId }}\" class=\"cellProduct{% if rowIsDisplayed %} d-none d-print-table-row{% endif %}\">
  <td class=\"cellProductImg\">
    {% if product.imagePath %}
      <img src=\"{{ product.imagePath }}\" alt=\"{{ product.name }}\" />
    {% endif %}
  </td>
  <td class=\"cellProductName\">
    <a href=\"{{ path('admin_product_form', {'id': product.id}) }}\">
      <p class=\"mb-0 productName\">{{ product.name }}</p>
      {% if product.reference %}
        <p class=\"mb-0 productReference\">
          {{ 'Reference number:'|trans({}, 'Admin.Orderscustomers.Feature') }}
          {{ product.reference }}
        </p>
      {% endif %}
      {% if product.supplierReference is not empty %}
        <p class=\"mb-0 productSupplierReference\">
          {{ 'Supplier reference:'|trans({}, 'Admin.Orderscustomers.Feature') }}
          {{ product.supplierReference }}
        </p>
      {% endif %}
    </a>
    {% if product.type == constant('PrestaShop\\\\PrestaShop\\\\Core\\\\Domain\\\\Order\\\\QueryResult\\\\OrderProductForViewing::TYPE_PACK') and product.customizations is null %}
      <span class=\"btn-product-pack-modal d-print-none\" data-toggle=\"modal\" data-target=\"#product-pack-modal\" data-pack-items=\"{{ product.packItems|json_encode }}\">
        <strong>{{ 'View pack content'|trans({}, 'Admin.Actions') }}</strong>
      </span>
    {% endif %}
  </td>
  <td class=\"cellProductUnitPrice\">{{ product.unitPrice }}</td>
  <td class=\"cellProductQuantity text-center\">
    {% if product.quantity > 1 %}
      <span class=\"badge badge-secondary rounded-circle\">{{ product.quantity }}</span>
    {% else %}
      {{ product.quantity }}
    {% endif %}

    <div class=\"d-none js-product-quantity\">
      <input type=\"text\" value=\"{{ product.quantity }}\" class=\"form-control\">
    </div>
  </td>
  <td class=\"cellProductLocation{% if not isColumnLocationDisplayed %} d-none{% endif %}\">{{ product.location }}</td>
  <td class=\"cellProductRefunded{% if not isColumnRefundedDisplayed %} d-none{% endif %}\">
    {% if product.quantityRefunded > 0 %}
      {{ product.quantityRefunded }} ({{ product.amountRefunded }} {{ 'Refunded'|trans({}, 'Admin.Orderscustomers.Feature') }})
    {% endif %}
  </td>
  <td class=\"cellProductAvailableQuantity text-center{% if not isAvailableQuantityDisplayed %} d-none{% endif %}\">{{ product.availableQuantity }}</td>
  <td class=\"cellProductTotalPrice\">{{ product.totalPrice }}</td>
  {% if orderForViewing.hasInvoice() %}
    <td>{{ product.orderInvoiceNumber }}</td>
  {% endif %}
  {% if not orderForViewing.delivered %}
    <td class=\"text-right cellProductActions\">
      <button
        type=\"button\"
        class=\"btn tooltip-link js-order-product-edit-btn\"
        data-toggle=\"pstooltip\"
        data-placement=\"top\"
        data-original-title=\"{{ 'Edit'|trans({}, 'Admin.Actions') }}\"
        data-order-detail-id=\"{{ product.orderDetailId }}\"
        data-product-id=\"{{ product.id }}\"
        data-combination-id=\"{{ product.combinationId }}\"
        data-product-quantity=\"{{ product.quantity }}\"
        data-product-price-tax-incl=\"{{ product.unitPriceTaxInclRaw }}\"
        data-product-price-tax-excl=\"{{ product.unitPriceTaxExclRaw }}\"
        data-tax-rate=\"{{ product.taxRate }}\"
        data-location=\"{{ product.location }}\"
        data-available-quantity=\"{{ product.availableQuantity }}\"
        data-available-out-of-stock=\"{{ product.availableOutOfStock }}\"
        data-order-invoice-id=\"{{ product.orderInvoiceId }}\"
        data-is-order-tax-included=\"{{ orderForViewing.isTaxIncluded }}\">
        <i class=\"material-icons\">edit</i>
      </button>
      <button
        type=\"button\"
        class=\"btn tooltip-link js-order-product-delete-btn\"
        data-toggle=\"pstooltip\"
        data-placement=\"top\"
        data-order-id=\"{{ orderForViewing.id }}\"
        data-order-detail-id=\"{{ product.orderDetailId }}\"
        data-delete-message=\"{{ 'Are you sure?'|trans({}, 'Admin.Notifications.Warning') }}\"
        data-original-title=\"{{ 'Delete'|trans({}, 'Admin.Actions') }}\">
        <i class=\"material-icons\">delete</i>
      </button>
    </td>
  {% endif %}
  <td class=\"text-right cancel-product-element\">
    <div class=\"cancel-product-cell cancel-product-element{% if product.refundable == false %} hidden{% endif %}\">
      <div class=\"cancel-product-cell-elements\">
        <div class=\"cancel-product-selector form-group\">
          {{ form_widget(cancelProductForm['selected_' ~ product.orderDetailId]) }}
        </div>
        <div class=\"cancel-product-quantity form-group\">
          {{ form_label(cancelProductForm['quantity_' ~ product.orderDetailId]) }}
          <div class=\"input-group\">
            {% set quantityInputOptions = {
              'attr': {
                'data-product-price-tax-incl': product.unitPriceTaxInclRaw,
                'data-product-price-tax-excl': product.unitPriceTaxExclRaw,
                'data-amount-refundable': product.amountRefundableRaw,
                'data-quantity-refundable': product.quantityRefundable,
              }
            } %}
            {{ form_widget(cancelProductForm['quantity_' ~ product.orderDetailId], quantityInputOptions) }}
          </div>
        </div>
        <div class=\"cancel-product-amount form-group\">
          {{ form_label(cancelProductForm['amount_' ~ product.orderDetailId]) }}
          <div class=\"input-group\">
            {{ form_widget(cancelProductForm['amount_' ~ product.orderDetailId]) }}
            <div class=\"input-group-append\"><div class=\"input-group-text\">{{ orderCurrency.symbol }}</div></div>
            <small class=\"max-refund text-left\">
              {{ '(Max %amount_refundable% %tax_method%)'|trans({
                '%amount_refundable%': product.amountRefundable,
                '%tax_method%': orderForViewing.taxMethod
              }, 'Admin.Orderscustomers.Help')|raw }}
            </small>
          </div>
        </div>
      </div>
    </div>
  </td>
</tr>
{% if product.customizations is not null %}
  <tr class=\"order-product-customization{% if rowIsDisplayed %} d-none{% endif %}\">
    <td class=\"border-top-0\"></td>
    {% set colspan = 8 %}
    {% set colspan = (orderForViewing.hasInvoice() ? colspan + 1 : colspan) %}
    {% set colspan = (not orderForViewing.delivered ? colspan + 1 : colspan) %}
    <td colspan=\"{{ colspan }}\" class=\"border-top-0 text-muted\">
      {% if product.customizations.fileCustomizations %}
        <div class=\"d-flex flex-row flex-wrap\">
          {% for customization in product.customizations.fileCustomizations %}
              <div class=\"mr-4\">
                <p><strong>{{ customization.name }}</strong></p>
                <p>
                  <a href=\"{{ path('admin_orders_display_customization_image', {'orderId': orderForViewing.id, \"value\": customization.value})}}\" download>
                    <img src=\"{{ customization.image }}\" alt=\"{{ customization.name }}\">
                  </a>
                </p>
              </div>
          {% endfor %}
        </div>
      {% endif %}
      {% for customization in product.customizations.textCustomizations %}
        <p><strong>{{ customization.name }} :</strong> {{ customization.value }}</p>
      {% endfor %}
      {% if product.type == constant('PrestaShop\\\\PrestaShop\\\\Core\\\\Domain\\\\Order\\\\QueryResult\\\\OrderProductForViewing::TYPE_PACK') %}
        <div class=\"btn-product-pack-modal mb-3 d-print-none\" data-toggle=\"modal\" data-target=\"#product-pack-modal\" data-pack-items=\"{{ product.packItems|json_encode }}\">
          <strong>{{ 'View pack content'|trans({}, 'Admin.Actions') }}</strong>
        </div>
      {% endif %}
    </td>
  </tr>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/product.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/product.html.twig");
    }
}
