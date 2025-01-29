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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig */
class __TwigTemplate_fba6cbd901eb80f8621ccf924eae3992 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig"));

        // line 25
        echo "
  ";
        // line 26
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 26, $this->source); })()), "sources", [], "any", false, false, false, 26), "sources", [], "any", false, false, false, 26))) {
            // line 27
            echo "    <div class=\"card mt-2 d-print-none\">
      <div class=\"card-header\">
        <div class=\"row\">
          <div class=\"col-md-6\">
            <i class=\"material-icons\">public</i>
            ";
            // line 32
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Sources", [], "Admin.Orderscustomers.Feature"), "html", null, true);
            echo "
            <span class=\"badge badge-primary rounded\">";
            // line 33
            echo twig_escape_filter($this->env, twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 33, $this->source); })()), "sources", [], "any", false, false, false, 33), "sources", [], "any", false, false, false, 33)), "html", null, true);
            echo "</span>
          </div>

          <ul id=\"order-sources-list\">
            ";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["orderForViewing"]) || array_key_exists("orderForViewing", $context) ? $context["orderForViewing"] : (function () { throw new RuntimeError('Variable "orderForViewing" does not exist.', 37, $this->source); })()), "sources", [], "any", false, false, false, 37), "sources", [], "any", false, false, false, 37));
            foreach ($context['_seq'] as $context["_key"] => $context["source"]) {
                // line 38
                echo "              <li>
                ";
                // line 39
                echo twig_escape_filter($this->env, $this->extensions['PrestaShopBundle\Twig\Extension\LocalizationExtension']->dateFormatFull(twig_get_attribute($this->env, $this->source, $context["source"], "addedAt", [], "any", false, false, false, 39)), "html", null, true);
                echo "
                <br/>
                <b>";
                // line 41
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("From", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</b>
                ";
                // line 42
                if ((twig_get_attribute($this->env, $this->source, $context["source"], "httpReferer", [], "any", false, false, false, 42) != "")) {
                    // line 43
                    echo "                  <a href=\"";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["source"], "httpReferer", [], "any", false, false, false, 43), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["source"], "httpReferer", [], "any", false, false, false, 43), "html", null, true);
                    echo "</a>
                ";
                } else {
                    // line 45
                    echo "                  -
                ";
                }
                // line 47
                echo "                <br/>
                <b>";
                // line 48
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("To", [], "Admin.Orderscustomers.Feature"), "html", null, true);
                echo "</b>
                <a href=\"http://";
                // line 49
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["source"], "requestUri", [], "any", false, false, false, 49), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->source, $context["source"], "requestUri", [], "any", false, false, false, 49), 0, 100), "html", null, true);
                echo "</a>
                <br/>
                ";
                // line 51
                if ((twig_get_attribute($this->env, $this->source, $context["source"], "keywords", [], "any", false, false, false, 51) != "")) {
                    // line 52
                    echo "                  <b>";
                    echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Keywords", [], "Admin.Global"), "html", null, true);
                    echo "</b>
                  <br/>
                  ";
                    // line 54
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["source"], "keywords", [], "any", false, false, false, 54), "html", null, true);
                    echo "
                ";
                }
                // line 56
                echo "                <br/>
              </li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['source'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 59
            echo "          </ul>
        </div>
      </div>
    </div>
  ";
        }
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 59,  123 => 56,  118 => 54,  112 => 52,  110 => 51,  103 => 49,  99 => 48,  96 => 47,  92 => 45,  84 => 43,  82 => 42,  78 => 41,  73 => 39,  70 => 38,  66 => 37,  59 => 33,  55 => 32,  48 => 27,  46 => 26,  43 => 25,);
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

  {% if orderForViewing.sources.sources is not empty %}
    <div class=\"card mt-2 d-print-none\">
      <div class=\"card-header\">
        <div class=\"row\">
          <div class=\"col-md-6\">
            <i class=\"material-icons\">public</i>
            {{ 'Sources'|trans({}, 'Admin.Orderscustomers.Feature') }}
            <span class=\"badge badge-primary rounded\">{{ orderForViewing.sources.sources|length }}</span>
          </div>

          <ul id=\"order-sources-list\">
            {% for source in orderForViewing.sources.sources %}
              <li>
                {{ source.addedAt|date_format_full }}
                <br/>
                <b>{{ 'From'|trans({}, 'Admin.Orderscustomers.Feature') }}</b>
                {% if source.httpReferer != '' %}
                  <a href=\"{{ source.httpReferer }}\">{{ source.httpReferer }}</a>
                {% else %}
                  -
                {% endif %}
                <br/>
                <b>{{ 'To'|trans({}, 'Admin.Orderscustomers.Feature') }}</b>
                <a href=\"http://{{ source.requestUri }}\">{{ source.requestUri | slice(0,100) }}</a>
                <br/>
                {% if source.keywords != '' %}
                  <b>{{ 'Keywords'|trans({}, 'Admin.Global') }}</b>
                  <br/>
                  {{ source.keywords }}
                {% endif %}
                <br/>
              </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
  {% endif %}
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/sources.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/sources.html.twig");
    }
}
