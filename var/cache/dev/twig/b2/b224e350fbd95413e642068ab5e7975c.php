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

/* @PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig */
class __TwigTemplate_5492647472f573ba62145c2a6e719e23 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig"));

        // line 25
        echo "
<div class=\"modal fade\" id=\"product-pack-modal\" tabindex=\"-1\" role=\"dialog\">
  <div class=\"modal-dialog modal-lg\" role=\"document\">
    <div class=\"modal-content\">
      <div class=\"card-header\">
        <div class=\"row\">
          <div class=\"col\">
            <h3 class=\"card-header-title\">
              ";
        // line 33
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Products in pack", [], "Admin.Global"), "html", null, true);
        echo "
            </h3>
          </div>
          <div class=\"col\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
              <i class=\"material-icons\">
                close
              </i>
            </button>
          </div>
        </div>
      </div>
      <div class=\"modal-body\">
        <table class=\"table\" id=\"product-pack-modal-table\">
          <thead>
          <tr>
            <th colspan=\"3\">";
        // line 49
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Product", [], "Admin.Global"), "html", null, true);
        echo "</th>
            <th>";
        // line 50
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Quantity", [], "Admin.Global"), "html", null, true);
        echo "</th>
            <th>";
        // line 51
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Available", [], "Admin.Global"), "html", null, true);
        echo "</th>
          </tr>
          </thead>
          <tbody>
          <tr class=\"d-none\" id=\"template-pack-table-row\">
            <td>";
        // line 56
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Package item", [], "Admin.Global"), "html", null, true);
        echo "</td>
            <td class=\"cell-product-img\">
              <img alt=\"\"/>
            </td>
            <td class=\"cell-product-name\">
              <a href=\"\">
                <p class=\"mb-0 product-name\"></p>
                <p class=\"mb-0 product-reference\">
                  ";
        // line 64
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Reference number:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
                </p>
                <p class=\"mb-0 product-supplier-reference\">
                  ";
        // line 67
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Supplier reference:", [], "Admin.Orderscustomers.Feature"), "html", null, true);
        echo "
                </p>
              </a>
            </td>
            <td class=\"cell-product-quantity\">
              <span class=\"badge badge-secondary rounded-circle\"></span>
            </td>
            <td class=\"cell-product-available-quantity\"></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 67,  99 => 64,  88 => 56,  80 => 51,  76 => 50,  72 => 49,  53 => 33,  43 => 25,);
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

<div class=\"modal fade\" id=\"product-pack-modal\" tabindex=\"-1\" role=\"dialog\">
  <div class=\"modal-dialog modal-lg\" role=\"document\">
    <div class=\"modal-content\">
      <div class=\"card-header\">
        <div class=\"row\">
          <div class=\"col\">
            <h3 class=\"card-header-title\">
              {{ 'Products in pack'|trans({}, 'Admin.Global') }}
            </h3>
          </div>
          <div class=\"col\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
              <i class=\"material-icons\">
                close
              </i>
            </button>
          </div>
        </div>
      </div>
      <div class=\"modal-body\">
        <table class=\"table\" id=\"product-pack-modal-table\">
          <thead>
          <tr>
            <th colspan=\"3\">{{ 'Product'|trans({}, 'Admin.Global') }}</th>
            <th>{{ 'Quantity'|trans({}, 'Admin.Global') }}</th>
            <th>{{ 'Available'|trans({}, 'Admin.Global') }}</th>
          </tr>
          </thead>
          <tbody>
          <tr class=\"d-none\" id=\"template-pack-table-row\">
            <td>{{ 'Package item'|trans({}, 'Admin.Global') }}</td>
            <td class=\"cell-product-img\">
              <img alt=\"\"/>
            </td>
            <td class=\"cell-product-name\">
              <a href=\"\">
                <p class=\"mb-0 product-name\"></p>
                <p class=\"mb-0 product-reference\">
                  {{ 'Reference number:'|trans({}, 'Admin.Orderscustomers.Feature') }}
                </p>
                <p class=\"mb-0 product-supplier-reference\">
                  {{ 'Supplier reference:'|trans({}, 'Admin.Orderscustomers.Feature') }}
                </p>
              </a>
            </td>
            <td class=\"cell-product-quantity\">
              <span class=\"badge badge-secondary rounded-circle\"></span>
            </td>
            <td class=\"cell-product-available-quantity\"></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
", "@PrestaShop/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig", "/var/www/html/automotoboutic/src/PrestaShopBundle/Resources/views/Admin/Sell/Order/Order/Blocks/View/Modal/view_product_pack_modal.html.twig");
    }
}
