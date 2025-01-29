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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig */
class __TwigTemplate_e3904b8023a0f6a42c8e1ed8162a92dc extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig"));

        // line 25
        echo "
<div class=\"card mt-2\" id=\"view_order_payments_block\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      ";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Payment", [], "Admin.Global"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 29, $this->source); })()), "payments", [], "any", false, false, false, 29), "payments", [], "any", false, false, false, 29)), "html", null, true);
        echo ")
    </h3>
  </div>

  <div class=\"card-body\">
    ";
        // line 34
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments_alert.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig", 34)->display(twig_array_merge($context, ["payments" => twig_get_attribute($this->env, $this->source,         // line 35
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 35, $this->source); })()), "payments", [], "any", false, false, false, 35), "linkedOrders" => twig_get_attribute($this->env, $this->source,         // line 36
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 36, $this->source); })()), "linkedOrders", [], "any", false, false, false, 36)]));
        // line 38
        echo "
    <table class=\"table\" data-role=\"payments-grid-table\">
      <thead>
        <tr>
          <th class=\"table-head-date\">";
        // line 42
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Date", [], "Admin.Global"), "html", null, true);
        echo "</th>
          <th class=\"table-head-payment\">";
        // line 43
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Payment method", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</th>
          <th class=\"table-head-transaction\">";
        // line 44
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Transaction ID", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "</th>
          <th class=\"table-head-amount\">";
        // line 45
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Amount", [], "Admin.Global"), "html", null, true);
        echo "</th>
          <th class=\"table-head-invoice\">";
        // line 46
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Invoice", [], "Admin.Global"), "html", null, true);
        echo "</th>
          <th class=\"table-head-employee\">";
        // line 47
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Employee", [], "Admin.Global"), "html", null, true);
        echo "</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      ";
        // line 52
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 52, $this->source); })()), "payments", [], "any", false, false, false, 52), "payments", [], "any", false, false, false, 52));
        foreach ($context['_seq'] as $context["_key"] => $context["payment"]) {
            // line 53
            echo "        <tr>
          <td data-role=\"date-column\">";
            // line 54
            echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatFull(twig_get_attribute($this->env, $this->source, $context["payment"], "date", [], "any", false, false, false, 54)), "html", null, true);
            echo "</td>
          <td data-role=\"payment-method-column\">";
            // line 55
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "paymentMethod", [], "any", false, false, false, 55), "html", null, true);
            echo "</td>
          <td data-role=\"transaction-id-column\">";
            // line 56
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "transactionId", [], "any", false, false, false, 56), "html", null, true);
            echo "</td>
          <td data-role=\"amount-column\">";
            // line 57
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "amount", [], "any", false, false, false, 57), "html", null, true);
            echo "</td>
          <td data-role=\"invoice-column\">";
            // line 58
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "invoiceNumber", [], "any", false, false, false, 58)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "invoiceNumber", [], "any", false, false, false, 58), "html", null, true);
            }
            echo "</td>
          <td data-role=\"invoice-column\">";
            // line 59
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "employeeName", [], "any", false, false, false, 59)) {
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "employeeName", [], "any", false, false, false, 59), "html", null, true);
            } else {
                echo "-";
            }
            echo "</td>
          <td class=\"text-right\">
            <button class=\"btn btn-sm btn-outline-secondary js-payment-details-btn\">
              ";
            // line 62
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Details", [], "Admin.Global"), "html", null, true);
            echo "
            </button>
          </td>
        </tr>
        <tr class=\"d-none\" data-role=\"payment-details\">
          <td colspan=\"6\">
            <p class=\"mb-0\">
              <strong>";
            // line 69
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Card number", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>
              ";
            // line 70
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "cardNumber", [], "any", false, false, false, 70)) {
                // line 71
                echo "                ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "cardNumber", [], "any", false, false, false, 71), "html", null, true);
                echo "
              ";
            } else {
                // line 73
                echo "                ";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Not defined", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
              ";
            }
            // line 75
            echo "            </p>
            <p class=\"mb-0\">
              <strong>";
            // line 77
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Card type", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>&nbsp;
              ";
            // line 78
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "cardBrand", [], "any", false, false, false, 78)) {
                // line 79
                echo "                ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "cardBrand", [], "any", false, false, false, 79), "html", null, true);
                echo "
              ";
            } else {
                // line 81
                echo "                ";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Not defined", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
              ";
            }
            // line 83
            echo "            </p>
            <p class=\"mb-0\">
              <strong>";
            // line 85
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Expiration date", [], "Admin.Catalog.Feature"), "html", null, true);
            echo "</strong>&nbsp;
              ";
            // line 86
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "cardExpiration", [], "any", false, false, false, 86)) {
                // line 87
                echo "                ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "cardExpiration", [], "any", false, false, false, 87), "html", null, true);
                echo "
              ";
            } else {
                // line 89
                echo "                ";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Not defined", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
              ";
            }
            // line 91
            echo "            </p>
            <p class=\"mb-0\">
              <strong>";
            // line 93
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cardholder name", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</strong>&nbsp;
              ";
            // line 94
            if (twig_get_attribute($this->env, $this->source, $context["payment"], "cardHolder", [], "any", false, false, false, 94)) {
                // line 95
                echo "                ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["payment"], "cardHolder", [], "any", false, false, false, 95), "html", null, true);
                echo "
              ";
            } else {
                // line 97
                echo "                ";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Not defined", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
              ";
            }
            // line 99
            echo "            </p>
          </td>
        </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['payment'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 103
        echo "      <tr class=\"d-print-none\">
        ";
        // line 104
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 104, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_add_payment", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 104, $this->source); })()), "id", [], "any", false, false, false, 104)])]);
        echo "
          <td>
            ";
        // line 106
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 106, $this->source); })()), "date", [], "any", false, false, false, 106), 'widget');
        echo "
          </td>
          <td>
            ";
        // line 109
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 109, $this->source); })()), "payment_method", [], "any", false, false, false, 109), 'widget');
        echo "
          </td>
          <td>
            ";
        // line 112
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 112, $this->source); })()), "transaction_id", [], "any", false, false, false, 112), 'widget');
        echo "
          </td>
          <td>
            ";
        // line 115
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 115, $this->source); })()), "amount_currency", [], "any", false, false, false, 115), 'widget');
        echo "
          </td>
          <td>
            <div ";
        // line 118
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 118, $this->source); })()), "id_invoice", [], "any", false, false, false, 118), "vars", [], "any", false, false, false, 118), "choices", [], "any", false, false, false, 118))) {
            echo "class=\"d-none\"";
        }
        echo ">
              ";
        // line 119
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 119, $this->source); })()), "id_invoice", [], "any", false, false, false, 119), 'widget');
        echo "
            </div>
          </td>
          <td></td>
          <td>
            <button type=\"submit\" class=\"btn btn-primary btn-sm\">";
        // line 124
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add", [], "Admin.Actions"), "html", null, true);
        echo "</button>
          </td>
        ";
        // line 126
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["addOrderPaymentForm"]) || array_key_exists("addOrderPaymentForm", $context) ? $context["addOrderPaymentForm"] : (function () { throw new RuntimeError('Variable "addOrderPaymentForm" does not exist.', 126, $this->source); })()), 'form_end');
        echo "
      </tr>
      </tbody>
    </table>

    ";
        // line 131
        if (( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 131, $this->source); })()), "valid", [], "any", false, false, false, 131) && twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["changeOrderCurrencyForm"]) || array_key_exists("changeOrderCurrencyForm", $context) ? $context["changeOrderCurrencyForm"] : (function () { throw new RuntimeError('Variable "changeOrderCurrencyForm" does not exist.', 131, $this->source); })()), "new_currency_id", [], "any", false, false, false, 131), "vars", [], "any", false, false, false, 131), "choices", [], "any", false, false, false, 131)))) {
            // line 132
            echo "      ";
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["changeOrderCurrencyForm"]) || array_key_exists("changeOrderCurrencyForm", $context) ? $context["changeOrderCurrencyForm"] : (function () { throw new RuntimeError('Variable "changeOrderCurrencyForm" does not exist.', 132, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_change_currency", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 132, $this->source); })()), "id", [], "any", false, false, false, 132)])]);
            echo "
        <div class=\"form-group row d-print-none\">
          <label class=\"form-control-label\">";
            // line 134
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Change currency", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "</label>
          <div class=\"col-sm\">
            <div class=\"input-group\">
              ";
            // line 137
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["changeOrderCurrencyForm"]) || array_key_exists("changeOrderCurrencyForm", $context) ? $context["changeOrderCurrencyForm"] : (function () { throw new RuntimeError('Variable "changeOrderCurrencyForm" does not exist.', 137, $this->source); })()), "new_currency_id", [], "any", false, false, false, 137), 'widget');
            echo "
              <button class=\"btn btn-outline-secondary btn-sm ml-2\">
                ";
            // line 139
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Change", [], "Admin.Actions"), "html", null, true);
            echo "
              </button>
            </div>

            <div class=\"d-none\">
              ";
            // line 144
            echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["changeOrderCurrencyForm"]) || array_key_exists("changeOrderCurrencyForm", $context) ? $context["changeOrderCurrencyForm"] : (function () { throw new RuntimeError('Variable "changeOrderCurrencyForm" does not exist.', 144, $this->source); })()), 'rest');
            echo "
            </div>

            <small class=\"text-muted\">
              ";
            // line 148
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Do not forget to update your exchange rate before making this change.", [], "Admin.Orderscustomers.Help"), "html", null, true);
            echo "
            </small>
          </div>
        </div>
      ";
            // line 152
            echo             $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["changeOrderCurrencyForm"]) || array_key_exists("changeOrderCurrencyForm", $context) ? $context["changeOrderCurrencyForm"] : (function () { throw new RuntimeError('Variable "changeOrderCurrencyForm" does not exist.', 152, $this->source); })()), 'form_end');
            echo "
    ";
        }
        // line 154
        echo "  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  344 => 154,  339 => 152,  332 => 148,  325 => 144,  317 => 139,  312 => 137,  306 => 134,  300 => 132,  298 => 131,  290 => 126,  285 => 124,  277 => 119,  271 => 118,  265 => 115,  259 => 112,  253 => 109,  247 => 106,  242 => 104,  239 => 103,  230 => 99,  224 => 97,  218 => 95,  216 => 94,  212 => 93,  208 => 91,  202 => 89,  196 => 87,  194 => 86,  190 => 85,  186 => 83,  180 => 81,  174 => 79,  172 => 78,  168 => 77,  164 => 75,  158 => 73,  152 => 71,  150 => 70,  146 => 69,  136 => 62,  126 => 59,  120 => 58,  116 => 57,  112 => 56,  108 => 55,  104 => 54,  101 => 53,  97 => 52,  89 => 47,  85 => 46,  81 => 45,  77 => 44,  73 => 43,  69 => 42,  63 => 38,  61 => 36,  60 => 35,  59 => 34,  49 => 29,  43 => 25,);
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

<div class=\"card mt-2\" id=\"view_order_payments_block\">
  <div class=\"card-header\">
    <h3 class=\"card-header-title\">
      {{ 'Payment'|trans({}, 'Admin.Global') }} ({{ orderForViewing.payments.payments|length }})
    </h3>
  </div>

  <div class=\"card-body\">
    {% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments_alert.html.twig' with {
        'payments': orderForViewing.payments,
        'linkedOrders': orderForViewing.linkedOrders
    } %}

    <table class=\"table\" data-role=\"payments-grid-table\">
      <thead>
        <tr>
          <th class=\"table-head-date\">{{ 'Date'|trans({}, 'Admin.Global') }}</th>
          <th class=\"table-head-payment\">{{ 'Payment method'|trans({}, 'Admin.Orderscustomers.Feature') }}</th>
          <th class=\"table-head-transaction\">{{ 'Transaction ID'|trans({}, 'Admin.Orderscustomers.Feature') }}</th>
          <th class=\"table-head-amount\">{{ 'Amount'|trans({}, 'Admin.Global') }}</th>
          <th class=\"table-head-invoice\">{{ 'Invoice'|trans({}, 'Admin.Global') }}</th>
          <th class=\"table-head-employee\">{{ 'Employee'|trans({}, 'Admin.Global') }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      {% for payment in orderForViewing.payments.payments %}
        <tr>
          <td data-role=\"date-column\">{{ payment.date|date_format_full }}</td>
          <td data-role=\"payment-method-column\">{{ payment.paymentMethod }}</td>
          <td data-role=\"transaction-id-column\">{{ payment.transactionId }}</td>
          <td data-role=\"amount-column\">{{ payment.amount }}</td>
          <td data-role=\"invoice-column\">{% if payment.invoiceNumber %}{{ payment.invoiceNumber }}{% endif %}</td>
          <td data-role=\"invoice-column\">{% if payment.employeeName %}{{ payment.employeeName }}{% else %}-{% endif %}</td>
          <td class=\"text-right\">
            <button class=\"btn btn-sm btn-outline-secondary js-payment-details-btn\">
              {{ 'Details'|trans({}, 'Admin.Global') }}
            </button>
          </td>
        </tr>
        <tr class=\"d-none\" data-role=\"payment-details\">
          <td colspan=\"6\">
            <p class=\"mb-0\">
              <strong>{{ 'Card number'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>
              {% if payment.cardNumber %}
                {{ payment.cardNumber }}
              {% else %}
                {{ 'Not defined'|trans({}, 'Admin.Orderscustomers.Feature') }}
              {% endif %}
            </p>
            <p class=\"mb-0\">
              <strong>{{ 'Card type'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>&nbsp;
              {% if payment.cardBrand %}
                {{ payment.cardBrand }}
              {% else %}
                {{ 'Not defined'|trans({}, 'Admin.Orderscustomers.Feature') }}
              {% endif %}
            </p>
            <p class=\"mb-0\">
              <strong>{{ 'Expiration date'|trans({}, 'Admin.Catalog.Feature') }}</strong>&nbsp;
              {% if payment.cardExpiration %}
                {{ payment.cardExpiration }}
              {% else %}
                {{ 'Not defined'|trans({}, 'Admin.Orderscustomers.Feature') }}
              {% endif %}
            </p>
            <p class=\"mb-0\">
              <strong>{{ 'Cardholder name'|trans({}, 'Admin.Orderscustomers.Feature') }}</strong>&nbsp;
              {% if payment.cardHolder %}
                {{ payment.cardHolder }}
              {% else %}
                {{ 'Not defined'|trans({}, 'Admin.Orderscustomers.Feature') }}
              {% endif %}
            </p>
          </td>
        </tr>
      {% endfor %}
      <tr class=\"d-print-none\">
        {{ form_start(addOrderPaymentForm, {'action': path('admin_orders_add_payment', {'orderId': orderForViewing.id})}) }}
          <td>
            {{ form_widget(addOrderPaymentForm.date) }}
          </td>
          <td>
            {{ form_widget(addOrderPaymentForm.payment_method) }}
          </td>
          <td>
            {{ form_widget(addOrderPaymentForm.transaction_id) }}
          </td>
          <td>
            {{ form_widget(addOrderPaymentForm.amount_currency) }}
          </td>
          <td>
            <div {% if addOrderPaymentForm.id_invoice.vars.choices is empty %}class=\"d-none\"{% endif %}>
              {{ form_widget(addOrderPaymentForm.id_invoice) }}
            </div>
          </td>
          <td></td>
          <td>
            <button type=\"submit\" class=\"btn btn-primary btn-sm\">{{ 'Add'|trans({}, 'Admin.Actions') }}</button>
          </td>
        {{ form_end(addOrderPaymentForm) }}
      </tr>
      </tbody>
    </table>

    {% if not orderForViewing.valid and changeOrderCurrencyForm.new_currency_id.vars.choices|length %}
      {{ form_start(changeOrderCurrencyForm, {'action': path('admin_orders_change_currency', {'orderId': orderForViewing.id})}) }}
        <div class=\"form-group row d-print-none\">
          <label class=\"form-control-label\">{{ 'Change currency'|trans({}, 'Admin.Orderscustomers.Feature') }}</label>
          <div class=\"col-sm\">
            <div class=\"input-group\">
              {{ form_widget(changeOrderCurrencyForm.new_currency_id) }}
              <button class=\"btn btn-outline-secondary btn-sm ml-2\">
                {{ 'Change'|trans({}, 'Admin.Actions') }}
              </button>
            </div>

            <div class=\"d-none\">
              {{ form_rest(changeOrderCurrencyForm) }}
            </div>

            <small class=\"text-muted\">
              {{ 'Do not forget to update your exchange rate before making this change.'|trans({}, 'Admin.Orderscustomers.Help') }}
            </small>
          </div>
        </div>
      {{ form_end(changeOrderCurrencyForm) }}
    {% endif %}
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/payments.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/payments.html.twig");
    }
}
