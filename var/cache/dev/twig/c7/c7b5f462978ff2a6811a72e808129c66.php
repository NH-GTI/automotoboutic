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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig */
class __TwigTemplate_57836077e8274a6c7e28347050238e7c extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig"));

        // line 25
        echo "
<table id=\"documents-grid-table\" class=\"table mb-3";
        // line 26
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 26, $this->source); })()), "documents", [], "any", false, false, false, 26), "documents", [], "any", false, false, false, 26))) {
            echo " table-empty";
        }
        echo "\">
  <thead>
  <tr>
    <th>
      ";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Date", [], "Admin.Global"), "html", null, true);
        echo "
    </th>
    <th>
      ";
        // line 33
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Document", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
    </th>
    <th>
      ";
        // line 36
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Number", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
    </th>
    <th>
      ";
        // line 39
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Amount", [], "Admin.Global"), "html", null, true);
        echo "
    </th>
    <th class=\"text-right d-print-none\">
      ";
        // line 42
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Actions", [], "Admin.Global"), "html", null, true);
        echo "
    </th>
  </tr>
  </thead>
  <tbody>
  ";
        // line 47
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 47, $this->source); })()), "documents", [], "any", false, false, false, 47), "documents", [], "any", false, false, false, 47))) {
            // line 48
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 48, $this->source); })()), "documents", [], "any", false, false, false, 48), "documents", [], "any", false, false, false, 48));
            foreach ($context['_seq'] as $context["_key"] => $context["document"]) {
                // line 49
                echo "      <tr>
        <td class=\"documents-table-column-date\">
          ";
                // line 51
                echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatLite(twig_get_attribute($this->env, $this->source, $context["document"], "createdAt", [], "any", false, false, false, 51)), "html", null, true);
                echo "
        </td>
        <td class=\"documents-table-column-type\">
          ";
                // line 54
                if ((twig_get_attribute($this->env, $this->source, $context["document"], "type", [], "any", false, false, false, 54) == "invoice")) {
                    // line 55
                    echo "            ";
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Invoice", [], "Admin.Global"), "html", null, true);
                    echo "
          ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 56
$context["document"], "type", [], "any", false, false, false, 56) == "credit_slip")) {
                    // line 57
                    echo "            ";
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Credit slip", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                    echo "
          ";
                } else {
                    // line 59
                    echo "            ";
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Delivery slip", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                    echo "
          ";
                }
                // line 61
                echo "        </td>
        <td class=\"documents-table-column-download-link\">
          ";
                // line 63
                if ((twig_get_attribute($this->env, $this->source, $context["document"], "type", [], "any", false, false, false, 63) == "invoice")) {
                    // line 64
                    echo "            <a target=\"_blank\" rel=\"noopener noreferrer nofollow\"
               href=\"";
                    // line 65
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_generate_invoice_pdf", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 65, $this->source); })()), "id", [], "any", false, false, false, 65)]), "html", null, true);
                    echo "\"
            >
              ";
                    // line 67
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "referenceNumber", [], "any", false, false, false, 67), "html", null, true);
                    echo "
            </a>
          ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 69
$context["document"], "type", [], "any", false, false, false, 69) == "delivery_slip")) {
                    // line 70
                    echo "            <a target=\"_blank\" rel=\"noopener noreferrer nofollow\"
               href=\"";
                    // line 71
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_generate_delivery_slip_pdf", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 71, $this->source); })()), "id", [], "any", false, false, false, 71)]), "html", null, true);
                    echo "\"
            >
              ";
                    // line 73
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "referenceNumber", [], "any", false, false, false, 73), "html", null, true);
                    echo "
            </a>
          ";
                } elseif ((twig_get_attribute($this->env, $this->source,                 // line 75
$context["document"], "type", [], "any", false, false, false, 75) == "credit_slip")) {
                    // line 76
                    echo "            <a href=\"";
                    echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\LayoutExtension']->getAdminLink("AdminPdf", true, ["submitAction" => "generateOrderSlipPDF", "id_order_slip" => twig_get_attribute($this->env, $this->source, $context["document"], "id", [], "any", false, false, false, 76)]), "html", null, true);
                    echo "\">
              ";
                    // line 77
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "referenceNumber", [], "any", false, false, false, 77), "html", null, true);
                    echo "
            </a>
          ";
                }
                // line 80
                echo "        </td>
        <td class=\"documents-table-column-amount\">
          ";
                // line 82
                if (twig_get_attribute($this->env, $this->source, $context["document"], "amount", [], "any", false, false, false, 82)) {
                    // line 83
                    echo "            <p class=\"mb-0\">
              ";
                    // line 84
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "amount", [], "any", false, false, false, 84), "html", null, true);
                    echo "

              ";
                    // line 86
                    if (twig_get_attribute($this->env, $this->source, $context["document"], "amountMismatch", [], "any", false, false, false, 86)) {
                        // line 87
                        echo "                (";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "amountMismatch", [], "any", false, false, false, 87), "html", null, true);
                        echo ")
              ";
                    }
                    // line 89
                    echo "            </p>
          ";
                } else {
                    // line 91
                    echo "            --
          ";
                }
                // line 93
                echo "        </td>
        <td class=\"text-right documents-table-column-actions\">
          ";
                // line 95
                if ((twig_get_attribute($this->env, $this->source, $context["document"], "type", [], "any", false, false, false, 95) == "invoice")) {
                    // line 96
                    echo "            ";
                    if ((twig_get_attribute($this->env, $this->source, $context["document"], "isAddPaymentAllowed", [], "any", false, false, false, 96) && twig_get_attribute($this->env, $this->source, $context["document"], "amount", [], "any", false, false, false, 96))) {
                        // line 97
                        echo "              <button href=\"#\" class=\"js-enter-payment-btn btn btn-primary btn-sm\" data-payment-amount=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "numericalAmount", [], "any", false, false, false, 97), "html", null, true);
                        echo "\">
                ";
                        // line 98
                        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Enter payment", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                        echo "
              </button>
            ";
                    }
                    // line 101
                    echo "            ";
                    if (twig_test_empty(twig_get_attribute($this->env, $this->source, $context["document"], "note", [], "any", false, false, false, 101))) {
                        // line 102
                        echo "              <button href=\"#\" class=\"js-open-invoice-note-btn btn btn-primary btn-sm\">
                ";
                        // line 103
                        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add note", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                        echo "
              </button>
            ";
                    } else {
                        // line 106
                        echo "              <button href=\"#\" class=\"js-open-invoice-note-btn btn btn-primary btn-sm btn-edit\">
                ";
                        // line 107
                        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Edit note", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                        echo "
              </button>
            ";
                    }
                    // line 110
                    echo "          ";
                }
                // line 111
                echo "        </td>
      </tr>
      ";
                // line 113
                if ((twig_get_attribute($this->env, $this->source, $context["document"], "type", [], "any", false, false, false, 113) == "invoice")) {
                    // line 114
                    echo "        <tr class=\"d-none\">
          <td colspan=\"5\">
            <form action=\"";
                    // line 116
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_update_invoice_note", ["orderId" => twig_get_attribute($this->env, $this->source,                     // line 117
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 117, $this->source); })()), "id", [], "any", false, false, false, 117), "orderInvoiceId" => twig_get_attribute($this->env, $this->source,                     // line 118
$context["document"], "id", [], "any", false, false, false, 118)]), "html", null, true);
                    // line 119
                    echo "\" method=\"post\">
              <div class=\"input-group\">
                <input type=\"text\" class=\"form-control invoice-note\" name=\"invoice_note\" value=\"";
                    // line 121
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["document"], "note", [], "any", false, false, false, 121), "html", null, true);
                    echo "\">
                <button class=\"btn btn-secondary ml-2 btn-sm js-cancel-invoice-note-btn\" type=\"button\">
                  ";
                    // line 123
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "html", null, true);
                    echo "
                </button>
                <button class=\"btn btn-primary ml-2 btn-sm js-save-invoice-note-btn\" type=\"submit\">
                  ";
                    // line 126
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Save", [], "Admin.Actions"), "html", null, true);
                    echo "
                </button>
              </div>
            </form>
          </td>
        </tr>
      ";
                }
                // line 133
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['document'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 134
            echo "  ";
        } else {
            // line 135
            echo "    <tr>
      <td colspan=\"5\" class=\"text-center alert-available\">
        ";
            // line 137
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("There is no available document", [], "Admin.Orderscustomers.Notification"), "html", null, true);
            echo "
      </td>
    </tr>
  ";
        }
        // line 141
        echo "  </tbody>
</table>

";
        // line 144
        if ((twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 144, $this->source); })()), "documents", [], "any", false, false, false, 144), "documents", [], "any", false, false, false, 144)) && twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 144, $this->source); })()), "invoiceManagementIsEnabled", [], "any", false, false, false, 144))) {
            // line 145
            echo "  <form action=\"";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_generate_invoice", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 145, $this->source); })()), "id", [], "any", false, false, false, 145)]), "html", null, true);
            echo "\" method=\"POST\">
    <button class=\"btn btn-primary\">
      <i class=\"material-icons\">autorenew</i>
      ";
            // line 148
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Generate invoice", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
    </button>
  </form>
";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  319 => 148,  312 => 145,  310 => 144,  305 => 141,  298 => 137,  294 => 135,  291 => 134,  285 => 133,  275 => 126,  269 => 123,  264 => 121,  260 => 119,  258 => 118,  257 => 117,  256 => 116,  252 => 114,  250 => 113,  246 => 111,  243 => 110,  237 => 107,  234 => 106,  228 => 103,  225 => 102,  222 => 101,  216 => 98,  211 => 97,  208 => 96,  206 => 95,  202 => 93,  198 => 91,  194 => 89,  188 => 87,  186 => 86,  181 => 84,  178 => 83,  176 => 82,  172 => 80,  166 => 77,  161 => 76,  159 => 75,  154 => 73,  149 => 71,  146 => 70,  144 => 69,  139 => 67,  134 => 65,  131 => 64,  129 => 63,  125 => 61,  119 => 59,  113 => 57,  111 => 56,  106 => 55,  104 => 54,  98 => 51,  94 => 49,  89 => 48,  87 => 47,  79 => 42,  73 => 39,  67 => 36,  61 => 33,  55 => 30,  46 => 26,  43 => 25,);
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

<table id=\"documents-grid-table\" class=\"table mb-3{% if orderForViewing.documents.documents is empty %} table-empty{% endif %}\">
  <thead>
  <tr>
    <th>
      {{ 'Date'|trans({}, 'Admin.Global') }}
    </th>
    <th>
      {{ 'Document'|trans({}, 'Admin.Orderscustomers.Feature') }}
    </th>
    <th>
      {{ 'Number'|trans({}, 'Admin.Orderscustomers.Feature') }}
    </th>
    <th>
      {{ 'Amount'|trans({}, 'Admin.Global') }}
    </th>
    <th class=\"text-right d-print-none\">
      {{ 'Actions'|trans({}, 'Admin.Global') }}
    </th>
  </tr>
  </thead>
  <tbody>
  {% if  orderForViewing.documents.documents is not empty %}
    {% for document in orderForViewing.documents.documents %}
      <tr>
        <td class=\"documents-table-column-date\">
          {{ document.createdAt|date_format_lite }}
        </td>
        <td class=\"documents-table-column-type\">
          {% if document.type == 'invoice' %}
            {{ 'Invoice'|trans({}, 'Admin.Global') }}
          {% elseif document.type == 'credit_slip' %}
            {{ 'Credit slip'|trans({}, 'Admin.Orderscustomers.Feature') }}
          {% else %}
            {{ 'Delivery slip'|trans({}, 'Admin.Orderscustomers.Feature') }}
          {% endif %}
        </td>
        <td class=\"documents-table-column-download-link\">
          {% if document.type == 'invoice' %}
            <a target=\"_blank\" rel=\"noopener noreferrer nofollow\"
               href=\"{{ path('admin_orders_generate_invoice_pdf', {'orderId': orderForViewing.id}) }}\"
            >
              {{ document.referenceNumber }}
            </a>
          {% elseif document.type == 'delivery_slip' %}
            <a target=\"_blank\" rel=\"noopener noreferrer nofollow\"
               href=\"{{ path('admin_orders_generate_delivery_slip_pdf', {'orderId': orderForViewing.id}) }}\"
            >
              {{ document.referenceNumber }}
            </a>
          {% elseif document.type == 'credit_slip' %}
            <a href=\"{{ getAdminLink('AdminPdf', true, {'submitAction': 'generateOrderSlipPDF', 'id_order_slip': document.id}) }}\">
              {{ document.referenceNumber }}
            </a>
          {% endif %}
        </td>
        <td class=\"documents-table-column-amount\">
          {% if document.amount %}
            <p class=\"mb-0\">
              {{ document.amount }}

              {% if document.amountMismatch %}
                ({{ document.amountMismatch }})
              {% endif %}
            </p>
          {% else %}
            --
          {% endif %}
        </td>
        <td class=\"text-right documents-table-column-actions\">
          {% if document.type == 'invoice' %}
            {% if (document.isAddPaymentAllowed and document.amount) %}
              <button href=\"#\" class=\"js-enter-payment-btn btn btn-primary btn-sm\" data-payment-amount=\"{{ document.numericalAmount }}\">
                {{ 'Enter payment'|trans({}, 'Admin.Orderscustomers.Feature') }}
              </button>
            {% endif %}
            {% if document.note is empty %}
              <button href=\"#\" class=\"js-open-invoice-note-btn btn btn-primary btn-sm\">
                {{ 'Add note'|trans({}, 'Admin.Orderscustomers.Feature') }}
              </button>
            {% else %}
              <button href=\"#\" class=\"js-open-invoice-note-btn btn btn-primary btn-sm btn-edit\">
                {{ 'Edit note'|trans({}, 'Admin.Orderscustomers.Feature') }}
              </button>
            {% endif %}
          {% endif %}
        </td>
      </tr>
      {% if document.type == 'invoice' %}
        <tr class=\"d-none\">
          <td colspan=\"5\">
            <form action=\"{{ path('admin_orders_update_invoice_note', {
              'orderId': orderForViewing.id,
              'orderInvoiceId': document.id
            }) }}\" method=\"post\">
              <div class=\"input-group\">
                <input type=\"text\" class=\"form-control invoice-note\" name=\"invoice_note\" value=\"{{ document.note }}\">
                <button class=\"btn btn-secondary ml-2 btn-sm js-cancel-invoice-note-btn\" type=\"button\">
                  {{ 'Cancel'|trans({}, 'Admin.Actions') }}
                </button>
                <button class=\"btn btn-primary ml-2 btn-sm js-save-invoice-note-btn\" type=\"submit\">
                  {{ 'Save'|trans({}, 'Admin.Actions') }}
                </button>
              </div>
            </form>
          </td>
        </tr>
      {% endif %}
    {% endfor %}
  {% else %}
    <tr>
      <td colspan=\"5\" class=\"text-center alert-available\">
        {{ 'There is no available document'|trans({}, 'Admin.Orderscustomers.Notification') }}
      </td>
    </tr>
  {% endif %}
  </tbody>
</table>

{% if  orderForViewing.documents.documents is empty and orderForViewing.invoiceManagementIsEnabled %}
  <form action=\"{{ path('admin_orders_generate_invoice', {'orderId': orderForViewing.id}) }}\" method=\"POST\">
    <button class=\"btn btn-primary\">
      <i class=\"material-icons\">autorenew</i>
      {{ 'Generate invoice'|trans({}, 'Admin.Orderscustomers.Feature') }}
    </button>
  </form>
{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/documents.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/documents.html.twig");
    }
}
