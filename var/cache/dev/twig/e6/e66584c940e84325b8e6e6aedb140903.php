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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig */
class __TwigTemplate_4ff655a800217f1ea36849290ece5f17 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig"));

        // line 25
        echo "
";
        // line 27
        if ((twig_get_attribute($this->env, $this->source, (isset($context["backOfficeOrderButtons"]) || array_key_exists("backOfficeOrderButtons", $context) ? $context["backOfficeOrderButtons"] : (function () { throw new RuntimeError('Variable "backOfficeOrderButtons" does not exist.', 27, $this->source); })()), "isEmpty", [], "method", false, false, false, 27) != true)) {
            // line 28
            echo "
  ";
            // line 29
            $context["buttons"] = twig_get_attribute($this->env, $this->source, (isset($context["backOfficeOrderButtons"]) || array_key_exists("backOfficeOrderButtons", $context) ? $context["backOfficeOrderButtons"] : (function () { throw new RuntimeError('Variable "backOfficeOrderButtons" does not exist.', 29, $this->source); })()), "toArray", [], "method", false, false, false, 29);
            // line 30
            echo "  ";
            if ((twig_length_filter($this->env, (isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 30, $this->source); })())) > 2)) {
                // line 31
                echo "
    ";
                // line 32
                $context["firstButton"] = twig_first($this->env, (isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 32, $this->source); })()));
                // line 33
                echo "    <div class=\"d-inline-block ml-2\">
      <div class=\"input-group\">
        <a class=\"btn ";
                // line 35
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["firstButton"]) || array_key_exists("firstButton", $context) ? $context["firstButton"] : (function () { throw new RuntimeError('Variable "firstButton" does not exist.', 35, $this->source); })()), "class", [], "any", false, false, false, 35), "html", null, true);
                echo "\"
        ";
                // line 36
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["firstButton"]) || array_key_exists("firstButton", $context) ? $context["firstButton"] : (function () { throw new RuntimeError('Variable "firstButton" does not exist.', 36, $this->source); })()), "properties", [], "any", false, false, false, 36));
                foreach ($context['_seq'] as $context["propertyName"] => $context["propertyContent"]) {
                    // line 37
                    echo "          ";
                    echo twig_escape_filter($this->env, $context["propertyName"], "html", null, true);
                    echo "=\"";
                    echo twig_escape_filter($this->env, $context["propertyContent"], "html", null, true);
                    echo "\"
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['propertyName'], $context['propertyContent'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 39
                echo "        >
        ";
                // line 40
                echo twig_get_attribute($this->env, $this->source, (isset($context["firstButton"]) || array_key_exists("firstButton", $context) ? $context["firstButton"] : (function () { throw new RuntimeError('Variable "firstButton" does not exist.', 40, $this->source); })()), "content", [], "any", false, false, false, 40);
                echo "
        </a>
      </div>
    </div>

    ";
                // line 46
                echo "    ";
                $context["buttons"] = twig_slice($this->env, (isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 46, $this->source); })()), 1, twig_length_filter($this->env, (isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 46, $this->source); })())));
                // line 47
                echo "
    <div class=\"d-inline-block ml-2\">
      <div class=\"input-group\">
        <div class=\"dropdown\">
          <button class=\"btn btn-action dropdown-toggle\" type=\"button\" id=\"dropdownOrderActionBar\"
                  data-toggle=\"dropdown\"
                  aria-haspopup=\"true\" aria-expanded=\"false\">
            <i class=\"material-icons form-error-icon\">more_horiz</i>

            ";
                // line 56
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("More actions", [], "Admin.Global"), "html", null, true);
                echo "
          </button>

          <div class=\"dropdown-menu\" aria-labelledby=\"dropdownOrderActionBar\">
            ";
                // line 60
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 60, $this->source); })()));
                foreach ($context['_seq'] as $context["_key"] => $context["button"]) {
                    // line 61
                    echo "              <a class=\"dropdown-item btn ";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["button"], "class", [], "any", false, false, false, 61), "html", null, true);
                    echo "\"
              ";
                    // line 62
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["button"], "properties", [], "any", false, false, false, 62));
                    foreach ($context['_seq'] as $context["propertyName"] => $context["propertyContent"]) {
                        // line 63
                        echo "                ";
                        echo twig_escape_filter($this->env, $context["propertyName"], "html", null, true);
                        echo "=\"";
                        echo twig_escape_filter($this->env, $context["propertyContent"], "html", null, true);
                        echo "\"
              ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['propertyName'], $context['propertyContent'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 64
                    echo ">
              ";
                    // line 65
                    echo twig_get_attribute($this->env, $this->source, $context["button"], "content", [], "any", false, false, false, 65);
                    echo "
              </a>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['button'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 68
                echo "          </div>
        </div>
      </div>
    </div>
  ";
            } else {
                // line 73
                echo "    ";
                // line 74
                echo "
    ";
                // line 75
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["buttons"]) || array_key_exists("buttons", $context) ? $context["buttons"] : (function () { throw new RuntimeError('Variable "buttons" does not exist.', 75, $this->source); })()));
                foreach ($context['_seq'] as $context["_key"] => $context["button"]) {
                    // line 76
                    echo "      <div class=\"d-inline-block ml-2\">
        <div class=\"input-group\">
          <a class=\"btn ";
                    // line 78
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["button"], "class", [], "any", false, false, false, 78), "html", null, true);
                    echo "\"
          ";
                    // line 79
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["button"], "properties", [], "any", false, false, false, 79));
                    foreach ($context['_seq'] as $context["propertyName"] => $context["propertyContent"]) {
                        // line 80
                        echo "            ";
                        echo twig_escape_filter($this->env, $context["propertyName"], "html", null, true);
                        echo "=\"";
                        echo twig_escape_filter($this->env, $context["propertyContent"], "html", null, true);
                        echo "\"
          ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['propertyName'], $context['propertyContent'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 82
                    echo "          >
          ";
                    // line 83
                    echo twig_get_attribute($this->env, $this->source, $context["button"], "content", [], "any", false, false, false, 83);
                    echo "
          </a>
        </div>
      </div>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['button'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 88
                echo "  ";
            }
            // line 89
            echo "
";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  208 => 89,  205 => 88,  194 => 83,  191 => 82,  180 => 80,  176 => 79,  172 => 78,  168 => 76,  164 => 75,  161 => 74,  159 => 73,  152 => 68,  143 => 65,  140 => 64,  129 => 63,  125 => 62,  120 => 61,  116 => 60,  109 => 56,  98 => 47,  95 => 46,  87 => 40,  84 => 39,  73 => 37,  69 => 36,  65 => 35,  61 => 33,  59 => 32,  56 => 31,  53 => 30,  51 => 29,  48 => 28,  46 => 27,  43 => 25,);
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

{# Rendering of hook actionGetAdminOrderButtons, see ActionsBarButtonsCollection #}
{% if (backOfficeOrderButtons.isEmpty() != true) %}

  {% set buttons = backOfficeOrderButtons.toArray() %}
  {% if buttons|length > 2 %}

    {% set firstButton = buttons|first %}
    <div class=\"d-inline-block ml-2\">
      <div class=\"input-group\">
        <a class=\"btn {{ firstButton.class }}\"
        {% for propertyName, propertyContent in firstButton.properties %}
          {{ propertyName }}=\"{{ propertyContent }}\"
        {% endfor %}
        >
        {{ firstButton.content|raw }}
        </a>
      </div>
    </div>

    {# Display other buttons into a dropdown #}
    {% set buttons = buttons|slice(1, buttons|length) %}

    <div class=\"d-inline-block ml-2\">
      <div class=\"input-group\">
        <div class=\"dropdown\">
          <button class=\"btn btn-action dropdown-toggle\" type=\"button\" id=\"dropdownOrderActionBar\"
                  data-toggle=\"dropdown\"
                  aria-haspopup=\"true\" aria-expanded=\"false\">
            <i class=\"material-icons form-error-icon\">more_horiz</i>

            {{ 'More actions'|trans({}, 'Admin.Global') }}
          </button>

          <div class=\"dropdown-menu\" aria-labelledby=\"dropdownOrderActionBar\">
            {% for button in buttons %}
              <a class=\"dropdown-item btn {{ button.class }}\"
              {% for propertyName, propertyContent in button.properties %}
                {{ propertyName }}=\"{{ propertyContent }}\"
              {% endfor %}>
              {{ button.content|raw }}
              </a>
            {% endfor %}
          </div>
        </div>
      </div>
    </div>
  {% else %}
    {# If only 2 buttons, display them like this #}

    {% for button in buttons %}
      <div class=\"d-inline-block ml-2\">
        <div class=\"input-group\">
          <a class=\"btn {{ button.class }}\"
          {% for propertyName, propertyContent in button.properties %}
            {{ propertyName }}=\"{{ propertyContent }}\"
          {% endfor %}
          >
          {{ button.content|raw }}
          </a>
        </div>
      </div>
    {% endfor %}
  {% endif %}

{% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/extra_order_button_actions.html.twig");
    }
}
