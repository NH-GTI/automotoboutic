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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig */
class __TwigTemplate_8e77f14e7a8ecd418f1c45c731441947 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig"));

        // line 25
        echo "
<table class=\"table\" data-role=\"history-grid-table\">
  <tbody>
    ";
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 28, $this->source); })()), "history", [], "any", false, false, false, 28), "statuses", [], "any", false, false, false, 28));
        foreach ($context['_seq'] as $context["_key"] => $context["status"]) {
            // line 29
            echo "    <tr>
      <td data-role=\"status-column\">
        <span class=\"badge rounded badge-print-light\"
              style=\"background-color: ";
            // line 32
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["status"], "color", [], "any", false, false, false, 32), "html", null, true);
            echo "; color: ";
            echo (($this->env->getFunction('is_color_bright')->getCallable()(twig_get_attribute($this->env, $this->source, $context["status"], "color", [], "any", false, false, false, 32))) ? ("black") : ("white"));
            echo ";\"
        >
          ";
            // line 34
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["status"], "name", [], "any", false, false, false, 34), "html", null, true);
            echo "
        </span>
      </td>
      <td class=\"text-right\" data-role=\"employee-column\">
        ";
            // line 38
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["status"], "employeeLastName", [], "any", false, false, false, 38))) {
                // line 39
                echo "          ";
                echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, $context["status"], "employeeFirstName", [], "any", false, false, false, 39) . " ") . twig_get_attribute($this->env, $this->source, $context["status"], "employeeLastName", [], "any", false, false, false, 39)), "html", null, true);
                echo "
        ";
            }
            // line 41
            echo "      </td>
      <td class=\"text-right\" data-role=\"date-column\">
        ";
            // line 43
            echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatFull(twig_get_attribute($this->env, $this->source, $context["status"], "createdAt", [], "any", false, false, false, 43)), "html", null, true);
            echo "
      </td>
      <td class=\"text-right\">
        ";
            // line 46
            if (twig_get_attribute($this->env, $this->source, $context["status"], "withEmail", [], "any", false, false, false, 46)) {
                // line 47
                echo "          <form method=\"post\" 
                action=\"";
                // line 48
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_resend_email", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 48, $this->source); })()), "id", [], "any", false, false, false, 48), "orderHistoryId" => twig_get_attribute($this->env, $this->source, $context["status"], "orderHistoryId", [], "any", false, false, false, 48), "orderStatusId" => twig_get_attribute($this->env, $this->source, $context["status"], "orderStatusId", [], "any", false, false, false, 48)]), "html", null, true);
                echo "\">
            <button class=\"btn btn-link pt-0 pb-0\">
              ";
                // line 50
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Resend email", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "
            </button>
          </form>
        ";
            }
            // line 54
            echo "      </td>
    </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['status'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 57
        echo "  </tbody>
</table>

<div class=\"d-flex justify-content-end\">
  ";
        // line 61
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderStatusForm"]) || array_key_exists("updateOrderStatusForm", $context) ? $context["updateOrderStatusForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusForm" does not exist.', 61, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_update_status", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 61, $this->source); })()), "id", [], "any", false, false, false, 61)]), "attr" => ["class" => "card-details-form"]]);
        echo "
    <div class=\"form-group card-details-actions\">
      ";
        // line 63
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["updateOrderStatusForm"]) || array_key_exists("updateOrderStatusForm", $context) ? $context["updateOrderStatusForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusForm" does not exist.', 63, $this->source); })()), "new_order_status_id", [], "any", false, false, false, 63), 'widget');
        echo "

      <button class=\"btn btn-primary update-status mt-3 mt-md-0 ml-0 ml-md-3\">
        ";
        // line 66
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Update status", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
      </button>
    </div>

    <div class=\"d-none\">
      ";
        // line 71
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["updateOrderStatusForm"]) || array_key_exists("updateOrderStatusForm", $context) ? $context["updateOrderStatusForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusForm" does not exist.', 71, $this->source); })()), 'rest');
        echo "
    </div>
  ";
        // line 73
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["updateOrderStatusForm"]) || array_key_exists("updateOrderStatusForm", $context) ? $context["updateOrderStatusForm"] : (function () { throw new RuntimeError('Variable "updateOrderStatusForm" does not exist.', 73, $this->source); })()), 'form_end');
        echo "
</div>

";
        // line 76
        $this->loadTemplate("@PrestaShop/Admin/Sell/Order/Order/Blocks/View/internal_note.html.twig", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig", 76)->display($context);
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 76,  144 => 73,  139 => 71,  131 => 66,  125 => 63,  120 => 61,  114 => 57,  106 => 54,  99 => 50,  94 => 48,  91 => 47,  89 => 46,  83 => 43,  79 => 41,  73 => 39,  71 => 38,  64 => 34,  57 => 32,  52 => 29,  48 => 28,  43 => 25,);
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

<table class=\"table\" data-role=\"history-grid-table\">
  <tbody>
    {% for status in orderForViewing.history.statuses %}
    <tr>
      <td data-role=\"status-column\">
        <span class=\"badge rounded badge-print-light\"
              style=\"background-color: {{ status.color }}; color: {{ is_color_bright(status.color) ? 'black' : 'white' }};\"
        >
          {{ status.name }}
        </span>
      </td>
      <td class=\"text-right\" data-role=\"employee-column\">
        {% if status.employeeLastName is not empty %}
          {{ status.employeeFirstName ~ ' ' ~ status.employeeLastName }}
        {% endif %}
      </td>
      <td class=\"text-right\" data-role=\"date-column\">
        {{ status.createdAt|date_format_full }}
      </td>
      <td class=\"text-right\">
        {% if status.withEmail %}
          <form method=\"post\" 
                action=\"{{ path('admin_orders_resend_email', {'orderId': orderForViewing.id, 'orderHistoryId': status.orderHistoryId, 'orderStatusId': status.orderStatusId}) }}\">
            <button class=\"btn btn-link pt-0 pb-0\">
              {{ 'Resend email'|trans({}, 'Admin.Orderscustomers.Feature') }}
            </button>
          </form>
        {% endif %}
      </td>
    </tr>
    {% endfor %}
  </tbody>
</table>

<div class=\"d-flex justify-content-end\">
  {{ form_start(updateOrderStatusForm, {'action': path('admin_orders_update_status',  {'orderId': orderForViewing.id}), 'attr': {'class': 'card-details-form'}, }) }}
    <div class=\"form-group card-details-actions\">
      {{ form_widget(updateOrderStatusForm.new_order_status_id) }}

      <button class=\"btn btn-primary update-status mt-3 mt-md-0 ml-0 ml-md-3\">
        {{ 'Update status'|trans({}, 'Admin.Orderscustomers.Feature') }}
      </button>
    </div>

    <div class=\"d-none\">
      {{ form_rest(updateOrderStatusForm) }}
    </div>
  {{ form_end(updateOrderStatusForm) }}
</div>

{% include '@PrestaShop/Admin/Sell/Order/Order/Blocks/View/internal_note.html.twig' %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/history.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/history.html.twig");
    }
}
