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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig */
class __TwigTemplate_fdb561ee6972c5e1aec1b39981dc7e18 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig"));

        // line 25
        echo "
<tr id=\"addProductTableRow\" class=\"add-product d-none\" data-is-order-tax-included=\"";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 26, $this->source); })()), "isTaxIncluded", [], "any", false, false, false, 26), "html", null, true);
        echo "\">
  <td colspan=\"2\" class=\"pr-2\">
    ";
        // line 28
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 28, $this->source); })()), "product_id", [], "any", false, false, false, 28), 'row');
        echo "
    ";
        // line 29
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 29, $this->source); })()), "tax_rate", [], "any", false, false, false, 29), 'row');
        echo "
    ";
        // line 30
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 30, $this->source); })()), "search", [], "any", false, false, false, 30), 'label');
        echo "
    ";
        // line 31
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 31, $this->source); })()), "search", [], "any", false, false, false, 31), 'widget');
        echo "
    <div class=\"dropdown\">
      <div class=\"dropdown-menu\"></div>
    </div>
    <div class=\"input-group mt-2 d-none\" id=\"addProductCombinations\">
      <div class=\"input-group-prepend\">
        <div class=\"input-group-text\">";
        // line 37
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Combinations", [], "Admin.Global"), "html", null, true);
        echo "</div>
      </div>
      <select id=\"addProductCombinationId\" class=\"custom-select\"></select>
    </div>
  </td>
  <td class=\"pr-2\">
   <div class=\"row add-product-inputs\">
     <div class=\"col-sm-6\">
      ";
        // line 45
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 45, $this->source); })()), "price_tax_excluded", [], "any", false, false, false, 45), 'widget');
        echo "
     </div>

     <div class=\"col-sm-6\">
      ";
        // line 49
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 49, $this->source); })()), "price_tax_included", [], "any", false, false, false, 49), 'widget');
        echo "
     </div>
   </div>
  </td>
  <td class=\"pr-2 add-product-quantity\">
    ";
        // line 54
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 54, $this->source); })()), "quantity", [], "any", false, false, false, 54), 'row', ["type" => "number"]);
        echo "
  </td>
  <td id=\"addProductLocation\" class=\"cellProductLocation\"></td>
  <td id=\"addProductRefunded\" class=\"cellProductRefunded\"></td>
  <td id=\"addProductAvailable\"";
        // line 58
        if ( !(isset($context["isAvailableQuantityDisplayed"]) || array_key_exists("isAvailableQuantityDisplayed", $context) ? $context["isAvailableQuantityDisplayed"] : (function () { throw new RuntimeError('Variable "isAvailableQuantityDisplayed" does not exist.', 58, $this->source); })())) {
            echo " class=\"d-none\"";
        }
        echo "></td>
  <td id=\"addProductTotalPrice\"></td>
  ";
        // line 60
        if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 60, $this->source); })()), "hasInvoice", [], "method", false, false, false, 60)) {
            // line 61
            echo "    <td class=\"addProductInvoice pr-2\">
      ";
            // line 62
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 62, $this->source); })()), "invoice", [], "any", false, false, false, 62), 'row', ["attr" => ["data-modal-title" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Create a new invoice", [], "Admin.Orderscustomers.Feature"), "data-modal-apply" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Create", [], "Admin.Actions"), "data-modal-cancel" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "data-modal-body" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Are you sure you want to create a new invoice?", [], "Admin.Orderscustomers.Notification"), "data-modal-edit-price-title" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit the price", [], "Admin.Orderscustomers.Feature"), "data-modal-edit-price-apply" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Update", [], "Admin.Actions"), "data-modal-edit-price-cancel" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "data-modal-edit-price-body" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Are you sure you want to edit this product price? It will be applied to all invoices of this order.", [], "Admin.Orderscustomers.Notification")]]);
            // line 73
            echo "
    </td>
  ";
        }
        // line 76
        echo "  <td class=\"text-right add-product-buttons\">
    <div class=\"btn-group\">
      ";
        // line 78
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 78, $this->source); })()), "cancel", [], "any", false, false, false, 78), 'row');
        echo "
      ";
        // line 79
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 79, $this->source); })()), "add", [], "any", false, false, false, 79), 'row');
        echo "
    </div>
  </td>
</tr>
<tr id=\"addProductNewInvoiceInfo\" class=\"d-none\">
  <td colspan=\"12\">
    <div>
      <h4>";
        // line 86
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("New invoice information", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</h4>
      <p data-role=\"carrier-name\"><b>";
        // line 87
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Carrier", [], "Admin.Shipping.Feature"), "html", null, true);
        echo " :</b> ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 87, $this->source); })()), "carrierName", [], "any", false, false, false, 87), "html", null, true);
        echo "</p>
      ";
        // line 88
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addProductRowForm"]) || array_key_exists("addProductRowForm", $context) ? $context["addProductRowForm"] : (function () { throw new RuntimeError('Variable "addProductRowForm" does not exist.', 88, $this->source); })()), "free_shipping", [], "any", false, false, false, 88), 'row');
        echo "
    </div>
  </td>
</tr>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 88,  146 => 87,  142 => 86,  132 => 79,  128 => 78,  124 => 76,  119 => 73,  117 => 62,  114 => 61,  112 => 60,  105 => 58,  98 => 54,  90 => 49,  83 => 45,  72 => 37,  63 => 31,  59 => 30,  55 => 29,  51 => 28,  46 => 26,  43 => 25,);
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

<tr id=\"addProductTableRow\" class=\"add-product d-none\" data-is-order-tax-included=\"{{ orderForViewing.isTaxIncluded }}\">
  <td colspan=\"2\" class=\"pr-2\">
    {{ form_row(addProductRowForm.product_id) }}
    {{ form_row(addProductRowForm.tax_rate) }}
    {{ form_label(addProductRowForm.search) }}
    {{ form_widget(addProductRowForm.search) }}
    <div class=\"dropdown\">
      <div class=\"dropdown-menu\"></div>
    </div>
    <div class=\"input-group mt-2 d-none\" id=\"addProductCombinations\">
      <div class=\"input-group-prepend\">
        <div class=\"input-group-text\">{{ 'Combinations'|trans({}, 'Admin.Global') }}</div>
      </div>
      <select id=\"addProductCombinationId\" class=\"custom-select\"></select>
    </div>
  </td>
  <td class=\"pr-2\">
   <div class=\"row add-product-inputs\">
     <div class=\"col-sm-6\">
      {{ form_widget(addProductRowForm.price_tax_excluded) }}
     </div>

     <div class=\"col-sm-6\">
      {{ form_widget(addProductRowForm.price_tax_included) }}
     </div>
   </div>
  </td>
  <td class=\"pr-2 add-product-quantity\">
    {{ form_row(addProductRowForm.quantity, {'type':'number'}) }}
  </td>
  <td id=\"addProductLocation\" class=\"cellProductLocation\"></td>
  <td id=\"addProductRefunded\" class=\"cellProductRefunded\"></td>
  <td id=\"addProductAvailable\"{% if not isAvailableQuantityDisplayed %} class=\"d-none\"{% endif %}></td>
  <td id=\"addProductTotalPrice\"></td>
  {% if orderForViewing.hasInvoice() %}
    <td class=\"addProductInvoice pr-2\">
      {{ form_row(addProductRowForm.invoice, {
        'attr': {
          'data-modal-title': 'Create a new invoice'|trans({}, 'Admin.Orderscustomers.Feature'),
          'data-modal-apply': 'Create'|trans({}, 'Admin.Actions'),
          'data-modal-cancel': 'Cancel'|trans({}, 'Admin.Actions'),
          'data-modal-body': 'Are you sure you want to create a new invoice?'|trans({}, 'Admin.Orderscustomers.Notification'),
          'data-modal-edit-price-title': 'Edit the price'|trans({}, 'Admin.Orderscustomers.Feature'),
          'data-modal-edit-price-apply': 'Update'|trans({}, 'Admin.Actions'),
          'data-modal-edit-price-cancel': 'Cancel'|trans({}, 'Admin.Actions'),
          'data-modal-edit-price-body': 'Are you sure you want to edit this product price? It will be applied to all invoices of this order.'|trans({}, 'Admin.Orderscustomers.Notification'),
        }})
      }}
    </td>
  {% endif %}
  <td class=\"text-right add-product-buttons\">
    <div class=\"btn-group\">
      {{ form_row(addProductRowForm.cancel) }}
      {{ form_row(addProductRowForm.add) }}
    </div>
  </td>
</tr>
<tr id=\"addProductNewInvoiceInfo\" class=\"d-none\">
  <td colspan=\"12\">
    <div>
      <h4>{{ 'New invoice information'|trans({}, 'Admin.Orderscustomers.Feature') }}</h4>
      <p data-role=\"carrier-name\"><b>{{ 'Carrier'|trans({}, 'Admin.Shipping.Feature') }} :</b> {{ orderForViewing.carrierName }}</p>
      {{ form_row(addProductRowForm.free_shipping) }}
    </div>
  </td>
</tr>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/add_product_row.html.twig");
    }
}
