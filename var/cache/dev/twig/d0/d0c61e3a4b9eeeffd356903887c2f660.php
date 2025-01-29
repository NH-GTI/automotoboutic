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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig */
class __TwigTemplate_c7c3eafadaa61a8536b2af2619618d15 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig"));

        // line 25
        echo "
<div class=\"modal fade\" id=\"addOrderDiscountModal\" tabindex=\"-1\" role=\"dialog\">
    <div class=\"modal-dialog\" role=\"document\">
      ";
        // line 28
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 28, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("admin_orders_add_cart_rule", ["orderId" => twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 28, $this->source); })()), "id", [], "any", false, false, false, 28)])]);
        echo "
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <h5 class=\"modal-title\">
              ";
        // line 32
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add new cart rule", [], "Admin.Catalog.Feature"), "html", null, true);
        echo "
            </h5>
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Close", [], "Admin.Actions"), "html", null, true);
        echo "\">
              <span aria-hidden=\"true\">×</span>
            </button>
          </div>
          <div class=\"modal-body\">

            <div class=\"form-group\">
              <label class=\"form-control-label\" for=\"";
        // line 41
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 41, $this->source); })()), "name", [], "any", false, false, false, 41), "vars", [], "any", false, false, false, 41), "id", [], "any", false, false, false, 41), "html", null, true);
        echo "\">
                ";
        // line 42
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Name", [], "Admin.Global"), "html", null, true);
        echo "
              </label>
              ";
        // line 44
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 44, $this->source); })()), "name", [], "any", false, false, false, 44), 'widget');
        echo "
            </div>

            <div class=\"row\">
              <div class=\"col\">
                <div class=\"form-group\">
                  <label class=\"form-control-label\" for=\"";
        // line 50
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 50, $this->source); })()), "type", [], "any", false, false, false, 50), "vars", [], "any", false, false, false, 50), "id", [], "any", false, false, false, 50), "html", null, true);
        echo "\">
                    ";
        // line 51
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Type", [], "Admin.Global"), "html", null, true);
        echo "
                  </label>
                  ";
        // line 53
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 53, $this->source); })()), "type", [], "any", false, false, false, 53), 'widget');
        echo "
                </div>
              </div>
              <div class=\"col\">
                <div class=\"form-group mb-0\">
                  <label class=\"form-control-label\" for=\"";
        // line 58
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 58, $this->source); })()), "value", [], "any", false, false, false, 58), "vars", [], "any", false, false, false, 58), "id", [], "any", false, false, false, 58), "html", null, true);
        echo "\">
                    ";
        // line 59
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Value", [], "Admin.Global"), "html", null, true);
        echo "
                  </label>

                  <div class=\"input-group\">
                    <div class=\"input-group-prepend\">
                      <div class=\"input-group-text\" id=\"add_order_cart_rule_value_unit\" data-currency-symbol=\"";
        // line 64
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["orderCurrency"]) || array_key_exists("orderCurrency", $context) ? $context["orderCurrency"] : (function () { throw new RuntimeError('Variable "orderCurrency" does not exist.', 64, $this->source); })()), "symbol", [], "any", false, false, false, 64), "html", null, true);
        echo "\">%</div>
                    </div>
                    ";
        // line 66
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 66, $this->source); })()), "value", [], "any", false, false, false, 66), 'widget');
        echo "
                  </div>
                  <small class=\"text-muted js-cart-rule-value-help d-none\">
                    ";
        // line 69
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("This value must include taxes.", [], "Admin.Orderscustomers.Notification"), "html", null, true);
        echo "
                  </small>
                </div>
              </div>
            </div>

            <div class=\"form-group ";
        // line 75
        if ( !twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 75, $this->source); })()), "hasInvoice", [], "any", false, false, false, 75)) {
            echo "d-none";
        }
        echo "\">
              <label class=\"form-control-label\" for=\"";
        // line 76
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 76, $this->source); })()), "invoice_id", [], "any", false, false, false, 76), "vars", [], "any", false, false, false, 76), "id", [], "any", false, false, false, 76), "html", null, true);
        echo "\">
                ";
        // line 77
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Invoice", [], "Admin.Global"), "html", null, true);
        echo "
              </label>
              ";
        // line 79
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(twig_get_attribute($this->env, $this->source, (isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 79, $this->source); })()), "invoice_id", [], "any", false, false, false, 79), 'widget', ["attr" => ["disabled" =>  !twig_get_attribute($this->env, $this->source,         // line 80
(isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 80, $this->source); })()), "hasInvoice", [], "any", false, false, false, 80)]]);
        // line 81
        echo "
            </div>
            
          </div>
          <div class=\"modal-footer\">
            <button type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
              ";
        // line 87
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Cancel", [], "Admin.Actions"), "html", null, true);
        echo "
            </button>
            <button type=\"submit\" class=\"btn btn-primary\" id=\"add_order_cart_rule_submit\">
              ";
        // line 90
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Add", [], "Admin.Actions"), "html", null, true);
        echo "
            </button>
          </div>
        </div>
      ";
        // line 94
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["addOrderCartRuleForm"]) || array_key_exists("addOrderCartRuleForm", $context) ? $context["addOrderCartRuleForm"] : (function () { throw new RuntimeError('Variable "addOrderCartRuleForm" does not exist.', 94, $this->source); })()), 'form_end');
        echo "
    </div>
</div>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 94,  169 => 90,  163 => 87,  155 => 81,  153 => 80,  152 => 79,  147 => 77,  143 => 76,  137 => 75,  128 => 69,  122 => 66,  117 => 64,  109 => 59,  105 => 58,  97 => 53,  92 => 51,  88 => 50,  79 => 44,  74 => 42,  70 => 41,  60 => 34,  55 => 32,  48 => 28,  43 => 25,);
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

<div class=\"modal fade\" id=\"addOrderDiscountModal\" tabindex=\"-1\" role=\"dialog\">
    <div class=\"modal-dialog\" role=\"document\">
      {{ form_start(addOrderCartRuleForm, {'action': path('admin_orders_add_cart_rule', {'orderId': orderForViewing.id})}) }}
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <h5 class=\"modal-title\">
              {{ 'Add new cart rule'|trans({}, 'Admin.Catalog.Feature') }}
            </h5>
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"{{ 'Close'|trans({}, 'Admin.Actions') }}\">
              <span aria-hidden=\"true\">×</span>
            </button>
          </div>
          <div class=\"modal-body\">

            <div class=\"form-group\">
              <label class=\"form-control-label\" for=\"{{ addOrderCartRuleForm.name.vars.id }}\">
                {{ 'Name'|trans({}, 'Admin.Global') }}
              </label>
              {{ form_widget(addOrderCartRuleForm.name) }}
            </div>

            <div class=\"row\">
              <div class=\"col\">
                <div class=\"form-group\">
                  <label class=\"form-control-label\" for=\"{{ addOrderCartRuleForm.type.vars.id }}\">
                    {{ 'Type'|trans({}, 'Admin.Global') }}
                  </label>
                  {{ form_widget(addOrderCartRuleForm.type) }}
                </div>
              </div>
              <div class=\"col\">
                <div class=\"form-group mb-0\">
                  <label class=\"form-control-label\" for=\"{{ addOrderCartRuleForm.value.vars.id }}\">
                    {{ 'Value'|trans({}, 'Admin.Global') }}
                  </label>

                  <div class=\"input-group\">
                    <div class=\"input-group-prepend\">
                      <div class=\"input-group-text\" id=\"add_order_cart_rule_value_unit\" data-currency-symbol=\"{{ orderCurrency.symbol }}\">%</div>
                    </div>
                    {{ form_widget(addOrderCartRuleForm.value) }}
                  </div>
                  <small class=\"text-muted js-cart-rule-value-help d-none\">
                    {{ 'This value must include taxes.'|trans({}, 'Admin.Orderscustomers.Notification') }}
                  </small>
                </div>
              </div>
            </div>

            <div class=\"form-group {% if not orderForViewing.hasInvoice %}d-none{% endif %}\">
              <label class=\"form-control-label\" for=\"{{ addOrderCartRuleForm.invoice_id.vars.id }}\">
                {{ 'Invoice'|trans({}, 'Admin.Global') }}
              </label>
              {{ form_widget(addOrderCartRuleForm.invoice_id, {'attr': {
                'disabled': not orderForViewing.hasInvoice
              }}) }}
            </div>
            
          </div>
          <div class=\"modal-footer\">
            <button type=\"button\" class=\"btn btn-outline-secondary\" data-dismiss=\"modal\">
              {{ 'Cancel'|trans({}, 'Admin.Actions') }}
            </button>
            <button type=\"submit\" class=\"btn btn-primary\" id=\"add_order_cart_rule_submit\">
              {{ 'Add'|trans({}, 'Admin.Actions') }}
            </button>
          </div>
        </div>
      {{ form_end(addOrderCartRuleForm) }}
    </div>
</div>", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/Modal/add_order_discount_modal.html.twig");
    }
}
