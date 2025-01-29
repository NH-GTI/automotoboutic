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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig */
class __TwigTemplate_a171ee2c66f1f836e3aaf90c4eefcef2 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig"));

        // line 25
        echo "
<tr id=\"editProductTableRowTemplate\" class=\"d-none editProductRow\">
  <td class=\"cellProductImg\"></td>
  <td class=\"cellProductName\"></td>
  <td class=\"cellProductTaxes pr-5\">
    ";
        // line 30
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 30, $this->source); })()), "price_tax_excluded", [], "any", false, false, false, 30), 'row', ["attr" => ["data-modal-edit-price-title" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit the price", [], "Admin.Orderscustomers.Feature"), "data-modal-edit-price-apply" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit", [], "Admin.Actions"), "data-modal-edit-price-cancel" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "data-modal-edit-price-body" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Are you sure you want to edit this product price? It will be applied to all identical products in this order.", [], "Admin.Orderscustomers.Notification")]]);
        // line 37
        echo "
    ";
        // line 38
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 38, $this->source); })()), "price_tax_included", [], "any", false, false, false, 38), 'row');
        echo "
  </td>
  <td class=\"cellProductQuantity pr-5\">
    ";
        // line 41
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 41, $this->source); })()), "quantity", [], "any", false, false, false, 41), 'row', ["type" => "number"]);
        echo "
  </td>
  <td class=\"editProductLocation cellProductLocation\"></td>
  <td class=\"editProductRefunded cellProductRefunded\"></td>
  <td class=\"editProductAvailable";
        // line 45
        if ( !(isset($context["isAvailableQuantityDisplayed"]) || array_key_exists("isAvailableQuantityDisplayed", $context) ? $context["isAvailableQuantityDisplayed"] : (function () { throw new RuntimeError('Variable "isAvailableQuantityDisplayed" does not exist.', 45, $this->source); })())) {
            echo " d-none";
        }
        echo "\"></td>
  <td class=\"editProductTotalPrice\"></td>
  ";
        // line 47
        if (twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 47, $this->source); })()), "hasInvoice", [], "method", false, false, false, 47)) {
            // line 48
            echo "    <td class=\"cellProductInvoice\">
      ";
            // line 49
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 49, $this->source); })()), "invoice", [], "any", false, false, false, 49), 'row', ["attr" => ["data-modal-edit-price-title" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit the price", [], "Admin.Orderscustomers.Feature"), "data-modal-edit-price-apply" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit", [], "Admin.Actions"), "data-modal-edit-price-cancel" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "data-modal-edit-price-body" => $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Are you sure you want to edit this product price? It will be applied to all invoices of this order.", [], "Admin.Orderscustomers.Notification")]]);
            // line 56
            echo "
    </td>
  ";
        }
        // line 59
        echo "  <td class=\"editProductActions text-right\">
    <div class=\"editProductActions-container btn-group\">
      ";
        // line 61
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 61, $this->source); })()), "cancel", [], "any", false, false, false, 61), 'row');
        echo "
      ";
        // line 62
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["editProductRowForm"]) || array_key_exists("editProductRowForm", $context) ? $context["editProductRowForm"] : (function () { throw new RuntimeError('Variable "editProductRowForm" does not exist.', 62, $this->source); })()), "save", [], "any", false, false, false, 62), 'row');
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
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  95 => 62,  91 => 61,  87 => 59,  82 => 56,  80 => 49,  77 => 48,  75 => 47,  68 => 45,  61 => 41,  55 => 38,  52 => 37,  50 => 30,  43 => 25,);
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

<tr id=\"editProductTableRowTemplate\" class=\"d-none editProductRow\">
  <td class=\"cellProductImg\"></td>
  <td class=\"cellProductName\"></td>
  <td class=\"cellProductTaxes pr-5\">
    {{ form_row(editProductRowForm.price_tax_excluded, {
      'attr': {
        'data-modal-edit-price-title': 'Edit the price'|trans({}, 'Admin.Orderscustomers.Feature'),
        'data-modal-edit-price-apply': 'Edit'|trans({}, 'Admin.Actions'),
        'data-modal-edit-price-cancel': 'Cancel'|trans({}, 'Admin.Actions'),
        'data-modal-edit-price-body': 'Are you sure you want to edit this product price? It will be applied to all identical products in this order.'|trans({}, 'Admin.Orderscustomers.Notification'),
      }})
    }}
    {{ form_row(editProductRowForm.price_tax_included) }}
  </td>
  <td class=\"cellProductQuantity pr-5\">
    {{ form_row(editProductRowForm.quantity, {'type':'number'}) }}
  </td>
  <td class=\"editProductLocation cellProductLocation\"></td>
  <td class=\"editProductRefunded cellProductRefunded\"></td>
  <td class=\"editProductAvailable{% if not isAvailableQuantityDisplayed %} d-none{% endif %}\"></td>
  <td class=\"editProductTotalPrice\"></td>
  {% if orderForViewing.hasInvoice() %}
    <td class=\"cellProductInvoice\">
      {{ form_row(editProductRowForm.invoice, {
        'attr': {
          'data-modal-edit-price-title': 'Edit the price'|trans({}, 'Admin.Orderscustomers.Feature'),
          'data-modal-edit-price-apply': 'Edit'|trans({}, 'Admin.Actions'),
          'data-modal-edit-price-cancel': 'Cancel'|trans({}, 'Admin.Actions'),
          'data-modal-edit-price-body': 'Are you sure you want to edit this product price? It will be applied to all invoices of this order.'|trans({}, 'Admin.Orderscustomers.Notification'),
        }})
      }}
    </td>
  {% endif %}
  <td class=\"editProductActions text-right\">
    <div class=\"editProductActions-container btn-group\">
      {{ form_row(editProductRowForm.cancel) }}
      {{ form_row(editProductRowForm.save) }}
    </div>
  </td>
</tr>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/edit_product_row.html.twig");
    }
}
