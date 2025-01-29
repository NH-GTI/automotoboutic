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

/* __string_template__6ff6a4c8c8f28c4dc040ac27da5cf7fe */
class __TwigTemplate_0d8073434b829db97b9f6a141dcd48ea extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'extra_stylesheets' => [$this, 'block_extra_stylesheets'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
            'content_footer' => [$this, 'block_content_footer'],
            'sidebar_right' => [$this, 'block_sidebar_right'],
            'javascripts' => [$this, 'block_javascripts'],
            'extra_javascripts' => [$this, 'block_extra_javascripts'],
            'translate_javascripts' => [$this, 'block_translate_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "__string_template__6ff6a4c8c8f28c4dc040ac27da5cf7fe"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "__string_template__6ff6a4c8c8f28c4dc040ac27da5cf7fe"));

        // line 1
        echo "<!DOCTYPE html>
<html lang=\"fr\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/img/app_icon.png\" />

<title>Commandes > Commande MNESEJNYZ de Florence belz • Automotoboutic - Spécialiste accessoire auto</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminOrders';
    var iso_user = 'fr';
    var lang_is_rtl = '0';
    var full_language_code = 'fr';
    var full_cldr_language_code = 'fr-FR';
    var country_iso_code = 'FR';
    var _PS_VERSION_ = '8.1.7';
    var roundMode = 2;
    var youEditFieldFor = '';
        var new_order_msg = 'Une nouvelle commande a été passée sur votre boutique.';
    var order_number_msg = 'Numéro de commande : ';
    var total_msg = 'Total : ';
    var from_msg = 'Du ';
    var see_order_msg = 'Afficher cette commande';
    var new_customer_msg = 'Un nouveau client s\\'est inscrit sur votre boutique.';
    var customer_name_msg = 'Nom du client : ';
    var new_msg = 'Un nouveau message a été posté sur votre boutique.';
    var see_msg = 'Lire le message';
    var token = '88599b772276cbeae7f8b178c9ab0f43';
    var currentIndex = 'index.php?controller=AdminOrders';
    var employee_token = 'c1bf15cd704edad3fa9a6a94e64c9df0';
    var choose_language_translate = 'Choisissez la langue :';
    var default_language = '1';
    var admin_modules_link = '/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ';
    var admin_notification_get_link = '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ';
    var admin_notification_push_link = adminNotificationPushLink = '/admin919wlkwpjawfriiadmx/index.php/common/notifications/ack?_token=ZyOZJRc7EyFYm";
        // line 40
        echo "2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ';
    var tab_modules_list = '';
    var update_success_msg = 'Mise à jour réussie';
    var search_product_msg = 'Rechercher un produit';
  </script>



<link
      rel=\"preload\"
      href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/2d8017489da689caedc1.preload..woff2\"
      as=\"font\"
      crossorigin
    >
      <link href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/create_product_default_theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/blockwishlist/public/backoffice.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin919wlkwpjawfriiadmx/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/export/views/css/export.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\/admin919wlkwpjawfriiadmx\\/\";
var baseDir = \"\\/\";
var changeFormLanguageUrl = \"\\/admin919wlkwpjawfriiadmx\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\";
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\u20ac\",\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.00\\u00a0\\u00a4\",\"negativePattern\":\"-#,##0.00\\u00a0\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var number_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\"";
        // line 68
        echo ",\"NaN\"],\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var prestashop = {\"debug\":true};
var show_new_customers = \"1\";
var show_new_messages = \"1\";
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/fancybox/jquery.fancybox.js\"></script>
<script type=\"text/javascript\" src=\"/js/admin.js?v=8.1.7\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/cldr.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/tools.js?v=8.1.7\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/create_product.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/modules/blockwishlist/public/vendors.js\"></script>
<script type=\"text/javascript\" src=\"/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/default/js/vendor/nv.d3.min.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_emailalerts/js/admin/ps_emailalerts.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/favico.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/ps_faviconnotificationbo.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ecordersandstock/views/js/ecoas_resetbutton.js\"></script>

  <script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: '#DF0067',
      textColor: '#FFFFFF',
      notificationGetUrl: '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=ZyOZJ";
        // line 94
        echo "Rc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120000, // Refresh every 2 minutes
    });
  }
</script>
<script type=\"application/javascript\">
                        \$(document).ready(function() {
                            \$.get(\"/modules/dpdfrance/cron.php?token=9a2ee22765afa7292bf4ea3c63088764&employee=2\");
                        });
                    </script><input type=\"hidden\" name=\"ecoas_token\" value=\"677f4d48d1c27922c3ee392280812350\" id=\"ecoas_token\" />
<input type=\"hidden\" name=\"ecoas_id_order\" value=\"174913\" id=\"ecoas_id_order\" />
<script>
    var nb_sellermania_orders_in_error = 0;
    var txt_sellermania_orders_in_error = \"commandes Sellermania n'ont pas pu être importées. Pour plus de détails, veuillez vous rendre dans la section de configuration du module.\";
    var sellermania_invoice_url = 'https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminModules&token=b11cd68c830cf10cf3ae98ef17c13061&configure=sellermania&module_name=sellermania&display=invoice';

    var sellermania_admin_orders_url = '/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ';
    var sellermania_default_carrier = '';
    var txt_sellermania_confirm_orders = \"Confirm selected Sellermania orders\";
    var txt_sellermania_send_orders = \"Set selected Sellermania orders as sent\";
    var txt_sellermania_select_at_least_one_order = \"You have to select at least one order\";
    var txt_sellermania_error_occured = \"An error occured\";
    var txt_sellermania_carrier_selection = \"Which carrier do you want to use?\";
    var txt_sellermania_orders_updated = \"Orders were successfully updated\";
    var txt_sellermania_select_all = \"Select all orders\";
    var txt_sellermania_unselect_all = \"Unselect all orders\";
    var txt_sellermania_timeout_exception = \"Sellermania rejected the request (too many requests has been made), ple";
        // line 123
        echo "ase wait a few seconds and try again.\";
</script>
<script type=\"text/javascript\" src=\"/modules/sellermania/views/js/displayBackOfficeHeader.js\"></script>
    <script type=\"text/javascript\" src=\"/modules/sellermania/views/js/displayBackOfficeHeader-16.js\"></script>



";
        // line 130
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>";
        echo "

<body
  class=\"lang-fr adminorders developer-mode\"
  data-base-url=\"/admin919wlkwpjawfriiadmx/index.php\"  data-token=\"ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">

  <header id=\"header\" class=\"d-print-none\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\"></a>
      <span id=\"shop_version\">8.1.7</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Accès rapide
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Commandes\"
      >Commandes</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\"
                 data-item=\"Évaluation du catalogue\"
      >Évaluation du catalogue</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Modules installés\"
      >Modules installés</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=9bb2e23170e48f";
        // line 164
        echo "0d1e550d08ddad34df\"
                 data-item=\"Nouveau bon de réduction\"
      >Nouveau bon de réduction</a>
          <a class=\"dropdown-item quick-row-link new-product-button\"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Nouveau produit\"
      >Nouveau produit</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Nouvelle catégorie\"
      >Nouvelle catégorie</a>
        <div class=\"dropdown-divider\"></div>
          <a id=\"quick-add-link\"
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"89\"
        data-icon=\"icon-AdminParentOrders\"
        data-method=\"add\"
        data-url=\"index.php/sell/orders/174913/view\"
        data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
        data-prompt-text=\"Veuillez nommer ce raccourci :\"
        data-link=\"Commandes - Liste\"
      >
        <i class=\"material-icons\">add_circle</i>
        Ajouter la page actuelle à l'accès rapide
      </a>
        <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\">
      <i class=\"material-icons\">settings</i>
      Gérez vos accès rapides
    </a>
  </div>
</div>
      </div>
      <div class=\"component component-search\" id=\"header-search-container\">
        <div class=\"component-search-body\">
          <div class=\"component-search-top\">
            <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/admin919wlkwpjawfriiadmx/index.ph";
        // line 203
        echo "p?controller=AdminSearch&amp;token=955d533f2e37cfbd97a2aad4499c30ae\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_query\" name=\"bo_query\" value=\"\" placeholder=\"Rechercher (ex. : référence produit, nom du client, etc.)\" aria-label=\"Barre de recherche\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        Partout
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"Partout\" href=\"#\" data-value=\"0\" data-placeholder=\"Que souhaitez-vous trouver ?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> Partout</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catalogue\" href=\"#\" data-value=\"1\" data-placeholder=\"Nom du produit, référence, etc.\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catalogue</a>
        <a class=\"dropdown-item\" data-item=\"Clients par nom\" href=\"#\" data-value=\"2\" data-placeholder=\"Nom\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clients par nom</a>
        <a class=\"dropdown-item\" data-item=\"Clients par adresse IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clients par adresse IP</a>
        <a class=\"dropdown-item\" data-item=\"Commandes\" href=\"#\" data-value=\"3\" data-placeholder=\"ID commande\" data-icon=\"icon-credit-card\"><i class=\"material-icons\">shopping_basket</i> Commandes</a>
        <a class=\"dropdown-item\" data-item=\"Factures\" href=\"#\" data-value=\"4\" data-placeholder=\"Numéro de facture\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Factures</a>
        <a class=\"dropdown-item\" data-item=\"Paniers";
        // line 220
        echo "\" href=\"#\" data-value=\"5\" data-placeholder=\"ID panier\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Paniers</a>
        <a class=\"dropdown-item\" data-item=\"Modules\" href=\"#\" data-value=\"7\" data-placeholder=\"Nom du module\" data-icon=\"icon-puzzle-piece\"><i class=\"material-icons\">extension</i> Modules</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">RECHERCHE</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$('#bo_query').one('click', function() {
    \$(this).closest('form').removeClass('collapsed');
  });
});
</script>
            <button class=\"component-search-cancel d-none\">Annuler</button>
          </div>

          <div class=\"component-search-quickaccess d-none\">
  <p class=\"component-search-title\">Accès rapide</p>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Commandes\"
    >Commandes</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\"
             data-item=\"Évaluation du catalogue\"
    >Évaluation du catalogue</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Modules installés\"
    >Modules installés</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=9bb2e23170e48f0d1e550d08ddad34df\"
             data-item=\"Nouveau bon de réduction\"
    >Nouveau bon de réduction</a>
      <a cl";
        // line 256
        echo "ass=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Nouveau produit\"
    >Nouveau produit</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Nouvelle catégorie\"
    >Nouvelle catégorie</a>
    <div class=\"dropdown-divider\"></div>
      <a id=\"quick-add-link\"
      class=\"dropdown-item js-quick-link\"
      href=\"#\"
      data-rand=\"45\"
      data-icon=\"icon-AdminParentOrders\"
      data-method=\"add\"
      data-url=\"index.php/sell/orders/174913/view\"
      data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
      data-prompt-text=\"Veuillez nommer ce raccourci :\"
      data-link=\"Commandes - Liste\"
    >
      <i class=\"material-icons\">add_circle</i>
      Ajouter la page actuelle à l'accès rapide
    </a>
    <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\">
    <i class=\"material-icons\">settings</i>
    Gérez vos accès rapides
  </a>
</div>
        </div>

        <div class=\"component-search-background d-none\"></div>
      </div>

              <div class=\"component hide-mobile-sm\" id=\"header-debug-mode-container\">
          <a class=\"link shop-state\"
             id=\"debug-mode\"
             data-toggle=\"pstooltip\"
             data-placement=\"bottom\"
             data-html=\"true\"
             title=\"<p class=&quot;text-left&quot;><strong>Votre boutique est en mode debug.</strong></p><p class=&quot;text-left&quot;>Tous les messages et erreurs PHP sont affichés. Lorsque vous n&#039;en avez plus besoin";
        // line 295
        echo ", &lt;strong&gt;désactivez&lt;/strong&gt; ce mode.</p>\"
             href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
          >
            <i class=\"material-icons\">bug_report</i>
            <span>Mode debug</span>
          </a>
        </div>
      
      
      <div class=\"header-right\">
                  <div class=\"component\" id=\"header-shop-list-container\">
              <div class=\"shop-list\">
    <a class=\"link\" id=\"header_shopname\" href=\"https://www.automotoboutic.com/\" target= \"_blank\">
      <i class=\"material-icons\">visibility</i>
      <span>Voir ma boutique</span>
    </a>
  </div>
          </div>
                          <div class=\"component header-right-component\" id=\"header-notifications-container\">
            <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Commandes<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notifications\"
              role=\"tab\"
            >
              Clients<span id=\"_nb_new_customers_\"></span>
            </a>
   ";
        // line 345
        echo "       </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Messages<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouvelle commande pour le moment :(<br>
              Avez-vous consulté vos <strong><a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=834e3a686d344a1706c4a67ec05b6623\">paniers abandonnés</a></strong> ?<br> Votre prochaine commande s'y trouve peut-être !
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Aucun nouveau client pour l'instant :(<br>
              Êtes-vous actifs sur les réseaux sociaux en ce moment ?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouveau message pour l'instant.<br>
              On dirait que vos clients sont satisfaits :)
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"order-notification-template\">
    <a class=\"notif\" href='order_url'>
      #_id_order_ -
      de <stro";
        // line 391
        echo "ng>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href='customer_url'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - enregistré le <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href='message_url'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
          </div>
        
        <div class=\"component\" id=\"header-employee-container\">
          <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      <div class=\"employee-top\">
        <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"https://www.automotoboutic.com/img/pr/default.jpg\" alt=\"Stella\" /></span>
        <span class=\"employee_profile\">Ravi de vous revoir Stella</span>
      </div>

      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/2/edit?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">
      <i class=\"material-icons\">edit</i>
      <span>Votre profil</span>
    </a>
    </div>

    <p class=\"divider\"></p>

    
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLogin&amp;logout=1&amp;token=9e4d3e7d81b16309f09a2bc140ca6b8e\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Déconnexion</span>
  ";
        // line 436
        echo "  </a>
  </div>
</div>
        </div>
              </div>
    </nav>
  </header>

  <nav class=\"nav-bar d-none d-print-none d-md-block\">
  <span class=\"menu-collapse\" data-toggle-url=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/toggle-navigation?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">
    <i class=\"material-icons rtl-flip\">chevron_left</i>
    <i class=\"material-icons rtl-flip\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <div class=\"logo-container\">
          <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\"></a>
          <span id=\"shop_version\" class=\"header-version\">8.1.7</span>
      </div>

      <ul class=\"main-menu\">
              
                    
                    
          
            <li class=\"link-levelone\" data-submenu=\"1\" id=\"tab-AdminDashboard\">
              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\" class=\"link\" >
                <i class=\"material-icons\">trending_up</i> <span>Tableau de bord</span>
              </a>
            </li>

          
                      
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vendre</span>
            </li>

                              
                  
                                                      
                                                          
                  <li class=\"link-levelone has_submenu link-active open ul-open\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i clas";
        // line 482
        echo "s=\"material-icons mi-shopping_basket\">shopping_basket</i>
                      <span>
                      Commandes
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_up
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo link-active\" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/invoices/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Factures
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/credit-slips/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">";
        // line 511
        echo " Avoirs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/delivery-slips/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Bons de livraison
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&amp;token=834e3a686d344a1706c4a67ec05b6623\" class=\"link\"> Paniers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"159\" id=\"subtab-AdminExport\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminExport&amp;token=20f84f531efc6f605f211317fcae655e\" class=\"link\"> Ordre de fabrication
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
       ";
        // line 545
        echo "           <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catalogue
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Catégories
                                </a>
                              </li>

                                                                                  
                              
                                                            
 ";
        // line 575
        echo "                             <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/monitoring/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Suivi
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminAttributesGroups&amp;token=be4c4788bf886173fae54dcf8c61f39e\" class=\"link\"> Attributs &amp; caractéristiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/brands/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Marques et fournisseurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/attachments/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Fichiers
                                </a>
                     ";
        // line 602
        echo "         </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;token=9bb2e23170e48f0d1e550d08ddad34df\" class=\"link\"> Réductions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/stocks/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Stock
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"132\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"161\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                  ";
        // line 632
        echo "              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-account_circle\">account_circle</i>
                      <span>
                      Clients
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                      ";
        // line 663
        echo "                                      
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/addresses/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Adresses
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link\">
                      <i class=\"material-icons mi-chat\">chat</i>
                      <span>
                      SAV
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link";
        // line 690
        echo "\"> SAV
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customer-service/order-messages/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Messages prédéfinis
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminReturn&amp;token=190a9a687cc07ca77eeb2905f7869399\" class=\"link\"> Retours produits
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\" class=\"link\">
                      <i class=\"material-icons mi-assessment\">assessment</i>
                      <span>
                      Statistiques
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
             ";
        // line 723
        echo "                                                       keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"37\" id=\"tab-IMPROVE\">
                <span class=\"title\">Personnaliser</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"38\" id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Modules
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-38\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"39\" id=\"subtab-AdminModulesSf\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Gestionnaire de modules 
                                </a>
                              </li>

               ";
        // line 760
        echo "                                                                                                                         
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"152\" id=\"subtab-AdminStoreCommander\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStoreCommander&amp;token=708b65e8897d2ff790b42e749972a549\" class=\"link\"> Store Commander
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"158\" id=\"subtab-AdminEcOrdersAndStock\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminEcOrdersAndStock&amp;token=e3b89f80542b8c82297670f705d4028e\" class=\"link\"> Ec Order And Stock
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                      <span>
                      Apparence
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                       ";
        // line 789
        echo "                                             keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"126\" id=\"subtab-AdminThemesParent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Thème et logo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"45\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/mail_theme/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Thème d&#039;e-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"47\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/cms-pages/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Pages
                                </a>
                              </li>

                                                                                  
                              
                         ";
        // line 819
        echo "                                   
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/modules/positions/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Positions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"49\" id=\"subtab-AdminImages\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminImages&amp;token=712d746201c76d7e50af65ac157342fb\" class=\"link\"> Images
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/link-widget/list?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Liste de liens
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"50\" id=\"subtab-AdminParentShipping\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a022";
        // line 848
        echo "5b7d4d47c6edfa1955516f73\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Livraison
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-50\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"51\" id=\"subtab-AdminCarriers\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a0225b7d4d47c6edfa1955516f73\" class=\"link\"> Transporteurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"52\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/shipping/preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" ";
        // line 880
        echo "data-submenu=\"53\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Paiement
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-53\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"54\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Moyens de paiement
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                         ";
        // line 909
        echo "     
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"56\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      International
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-56\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Localisation
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/zones/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Zones géographiques
                                </a>
                            ";
        // line 938
        echo "  </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"66\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/taxes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Taxes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"69\" id=\"subtab-AdminTranslations\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/translations/settings?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Traductions
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"70\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurer</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"71\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                  ";
        // line 975
        echo "    <span>
                      Paramètres de la boutique
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-71\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminParentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Paramètres généraux
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"75\" id=\"subtab-AdminParentOrderPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/order-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"78\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/product-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSD";
        // line 1003
        echo "lDPUwW3BdOKJYvQ\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/customer-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"83\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/contacts/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Contact
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"86\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/seo-urls/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Trafic et SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminPa";
        // line 1034
        echo "rentSearchConf\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSearchConf&amp;token=d4d5d68441ea494769eed19066d6bbfe\" class=\"link\"> Rechercher
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"92\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                      <span>
                      Paramètres avancés
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-92\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"93\" id=\"subtab-AdminInformation\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Informations
                                </a>
                              </li>

                                       ";
        // line 1064
        echo "                                           
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"94\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Performances
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"95\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/administration/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Administration
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/emails/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> E-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"97\" id=\"subtab-AdminImport\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/import/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3Bd";
        // line 1092
        echo "OKJYvQ\" class=\"link\"> Importer
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"98\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Équipe
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"102\" id=\"subtab-AdminParentRequestSql\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/sql-requests/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Base de données
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/logs/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminWebservice\">
      ";
        // line 1124
        echo "                          <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/webservice-keys/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Webservice
                                </a>
                              </li>

                                                                                                                                                                                                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"110\" id=\"subtab-AdminFeatureFlag\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/feature-flags/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Fonctionnalités nouvelles et expérimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"111\" id=\"subtab-AdminParentSecurity\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/security/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Security
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"153\" id=\"tab-Sellermania\">
                <span class=\"title\">Sellermania</span>
            </li>

                              
          ";
        // line 1157
        echo "        
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminSellermaniaSettings\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaSettings&amp;token=8d53b28207eeebb79d42845b22550410\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"155\" id=\"subtab-AdminSellermaniaDiagnostics\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaDiagnostics&amp;token=2b18c4445a756b41ff945e8407af624a\" class=\"link\">
                      <i class=\"material-icons mi-star_border\">star_border</i>
                      <span>
                      Diagnostic
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"categ";
        // line 1192
        echo "ory-title\" data-submenu=\"171\" id=\"tab-NHBusinessCentral\">
                <span class=\"title\">NHBusinessCentral</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"172\" id=\"subtab-ConfigurationController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/configuration?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"173\" id=\"subtab-TransactionGridController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/transactiongrid?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-refresh\">refresh</i>
                      <span>
                      Transactions
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
    ";
        // line 1227
        echo "      
                  </ul>
  </div>
  
</nav>


<div class=\"header-toolbar d-print-none\">
    
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Commandes</li>
          
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
  
        
<div class=\"title-content\">
  <h1 class=\"d-inline\">
    <strong class=\"text-muted\" data-role=\"order-id\">#174913</strong>
    <strong data-role=\"order-reference\">MNESEJNYZ</strong>
  </h1>

  <p class=\"lead d-inline\">
    de

          Florence
      belz
      </p>

  <span class=\"badge rounded badge-dark ml-2 mr-2 font-size-100\">
    58,32 €
  </span>

  <p class=\"lead d-inline\">
    28/01/2025
    <span class=\"text-muted\">
      à

      16:52:55
    </span>
  </p>
</div>

  

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                        
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Aide\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminOrders%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
                   id=\"product_form_open_help\"
                >
                  Aide
                </a>
                                    </div>
        </div>

      
    </div>
  </div>

  
  
  <div class=\"btn-floating\">
    <button class=\"btn btn-primary collapsed\" data-toggle=\"collapse\" data-target=\".btn-floating-container\" aria-expanded=\"false\">
      <i class=\"material-icons\">add</i>
    </button>
    <div class=\"btn-floating-container collapse\">
      <div class=\"btn-floating-menu\">
        
        
                   ";
        // line 1312
        echo "           <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Aide\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminOrders%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
            >
              Aide
            </a>
                        </div>
    </div>
  </div>
  
</div>

<div id=\"main-div\">
          
      <div class=\"content-div  \">

        

                                                        
        <div id=\"ajax_confirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>
<div id=\"content-message-box\"></div>


  ";
        // line 1337
        $this->displayBlock('content_header', $context, $blocks);
        $this->displayBlock('content', $context, $blocks);
        $this->displayBlock('content_footer', $context, $blocks);
        $this->displayBlock('sidebar_right', $context, $blocks);
        echo "

        

      </div>
    </div>

  <div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>Oh non !</h1>
  <p class=\"mt-3\">
    La version mobile de cette page n'est pas encore disponible.
  </p>
  <p class=\"mt-2\">
    Cette page n'est pas encore disponible sur mobile, merci de la consulter sur ordinateur.
  </p>
  <p class=\"mt-2\">
    Merci.
  </p>
  <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons rtl-flip\">arrow_back</i>
    Précédent
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    <script type=\"text/javascript\">
  \$(document).ready(function(){
\$(\"#order_grid_panel\").before(\"<div id=\\\"sc_message\\\" class=\\\"alert alert-info\\\"><p><b>Store Commander<\\/b> est install\\u00e9 sur votre boutique. Saviez-vous que vous pouvez :<\\/p>\\n                        <ul style=\\\"list-style-type: disc;padding-left:40px\\\">\\n                        <li>Filtrer plus finement vos derni\\u00e8res commandes pour changer leurs statuts en masse<\\/li>\\n                        <li>Cr\\u00e9er des commandes pour vos clients \\u00e0 partir d'un simple appel t\\u00e9l\\u00e9phonique<\\/li>\\n                        <li>Rechercher, trouver et imprimer les factures\\/avoirs\\/bons de livraison plus facilement<\\/li>\\n                        <li>Analyser pr\\u00e9cis\\u00e9ment l'origine de vos commandes pour prendre des d\\u00e9cisions sur vos diff\\u00e9rents canaux d'acquisition<\\/li>\\n                        <li>Exporter les donn\\u00e9es pour faciliter la gestion de votre comptabilit\\u00e9 (ventillation TVA, Pays, Mini-guichet...)<\\/li>\\n                        <\\/ul><br\\/>\\n                        <p>D\\u00e9marrez Store Commander pour g\\u00e9rer vos commandes plus efficacement, en <b><a href=\\\"https:\\/\\/www.automotoboutic.com\\/admin919wlkwpjawfriiadmx\\/index.php?controller=AdminStoreCommander&toke";
        // line 1365
        echo "n=708b65e8897d2ff790b42e749972a549\\\">cliquant ici<\\/a><\\/b> ou depuis le menu Module > Store Commander<\\/p>\\n                        <p style=\\\"text-align:right\\\"><a href=\\\"\\/admin919wlkwpjawfriiadmx\\/index.php\\/sell\\/orders\\/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ&sc_no_msg_order=1\\\">Ne plus afficher ce message<\\/a><\\/p><\\/a><\\/div>\");

  });
</script>

</div>
  

      <div class=\"bootstrap\">
      
    </div>
  
";
        // line 1377
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>";
        echo "
</html>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 130
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_extra_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "extra_stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "extra_stylesheets"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 1337
    public function block_content_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content_header"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content_header"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_content_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content_footer"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content_footer"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_sidebar_right($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar_right"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "sidebar_right"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 1377
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_extra_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "extra_javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "extra_javascripts"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function block_translate_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "translate_javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "translate_javascripts"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "__string_template__6ff6a4c8c8f28c4dc040ac27da5cf7fe";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1638 => 1377,  1569 => 1337,  1534 => 130,  1519 => 1377,  1505 => 1365,  1471 => 1337,  1444 => 1312,  1357 => 1227,  1320 => 1192,  1283 => 1157,  1248 => 1124,  1214 => 1092,  1184 => 1064,  1152 => 1034,  1119 => 1003,  1089 => 975,  1050 => 938,  1019 => 909,  988 => 880,  954 => 848,  923 => 819,  891 => 789,  860 => 760,  821 => 723,  786 => 690,  757 => 663,  724 => 632,  692 => 602,  663 => 575,  631 => 545,  595 => 511,  564 => 482,  516 => 436,  469 => 391,  421 => 345,  369 => 295,  328 => 256,  290 => 220,  271 => 203,  230 => 164,  191 => 130,  182 => 123,  151 => 94,  123 => 68,  93 => 40,  52 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{{ '<!DOCTYPE html>
<html lang=\"fr\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/img/app_icon.png\" />

<title>Commandes > Commande MNESEJNYZ de Florence belz • Automotoboutic - Spécialiste accessoire auto</title>

  <script type=\"text/javascript\">
    var help_class_name = \\'AdminOrders\\';
    var iso_user = \\'fr\\';
    var lang_is_rtl = \\'0\\';
    var full_language_code = \\'fr\\';
    var full_cldr_language_code = \\'fr-FR\\';
    var country_iso_code = \\'FR\\';
    var _PS_VERSION_ = \\'8.1.7\\';
    var roundMode = 2;
    var youEditFieldFor = \\'\\';
        var new_order_msg = \\'Une nouvelle commande a été passée sur votre boutique.\\';
    var order_number_msg = \\'Numéro de commande : \\';
    var total_msg = \\'Total : \\';
    var from_msg = \\'Du \\';
    var see_order_msg = \\'Afficher cette commande\\';
    var new_customer_msg = \\'Un nouveau client s\\\\\\'est inscrit sur votre boutique.\\';
    var customer_name_msg = \\'Nom du client : \\';
    var new_msg = \\'Un nouveau message a été posté sur votre boutique.\\';
    var see_msg = \\'Lire le message\\';
    var token = \\'88599b772276cbeae7f8b178c9ab0f43\\';
    var currentIndex = \\'index.php?controller=AdminOrders\\';
    var employee_token = \\'c1bf15cd704edad3fa9a6a94e64c9df0\\';
    var choose_language_translate = \\'Choisissez la langue :\\';
    var default_language = \\'1\\';
    var admin_modules_link = \\'/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\\';
    var admin_notification_get_link = \\'/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\\';
    var admin_notification_push_link = adminNotificationPushLink = \\'/admin919wlkwpjawfriiadmx/index.php/common/notifications/ack?_token=ZyOZJRc7EyFYm' | raw }}{{ '2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\\';
    var tab_modules_list = \\'\\';
    var update_success_msg = \\'Mise à jour réussie\\';
    var search_product_msg = \\'Rechercher un produit\\';
  </script>



<link
      rel=\"preload\"
      href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/2d8017489da689caedc1.preload..woff2\"
      as=\"font\"
      crossorigin
    >
      <link href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/create_product_default_theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/js/jquery/plugins/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/blockwishlist/public/backoffice.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/admin919wlkwpjawfriiadmx/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/modules/export/views/css/export.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\\\/admin919wlkwpjawfriiadmx\\\\/\";
var baseDir = \"\\\\/\";
var changeFormLanguageUrl = \"\\\\/admin919wlkwpjawfriiadmx\\\\/index.php\\\\/configure\\\\/advanced\\\\/employees\\\\/change-form-language?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\";
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\\\u00d7\",\"\\\\u2030\",\"\\\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\\\u20ac\",\"numberSymbols\":[\",\",\"\\\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\\\u00d7\",\"\\\\u2030\",\"\\\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.00\\\\u00a0\\\\u00a4\",\"negativePattern\":\"-#,##0.00\\\\u00a0\\\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var number_specifications = {\"symbol\":[\",\",\"\\\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\\\u00d7\",\"\\\\u2030\",\"\\\\u221e\"' | raw }}{{ ',\"NaN\"],\"numberSymbols\":[\",\",\"\\\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\\\u00d7\",\"\\\\u2030\",\"\\\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var prestashop = {\"debug\":true};
var show_new_customers = \"1\";
var show_new_messages = \"1\";
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery/plugins/fancybox/jquery.fancybox.js\"></script>
<script type=\"text/javascript\" src=\"/js/admin.js?v=8.1.7\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/cldr.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/js/tools.js?v=8.1.7\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/new-theme/public/create_product.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/modules/blockwishlist/public/vendors.js\"></script>
<script type=\"text/javascript\" src=\"/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/admin919wlkwpjawfriiadmx/themes/default/js/vendor/nv.d3.min.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_emailalerts/js/admin/ps_emailalerts.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/favico.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ps_faviconnotificationbo/views/js/ps_faviconnotificationbo.js\"></script>
<script type=\"text/javascript\" src=\"/modules/ecordersandstock/views/js/ecoas_resetbutton.js\"></script>

  <script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: \\'#DF0067\\',
      textColor: \\'#FFFFFF\\',
      notificationGetUrl: \\'/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=ZyOZJ' | raw }}{{ 'Rc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\\',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120000, // Refresh every 2 minutes
    });
  }
</script>
<script type=\"application/javascript\">
                        \$(document).ready(function() {
                            \$.get(\"/modules/dpdfrance/cron.php?token=9a2ee22765afa7292bf4ea3c63088764&employee=2\");
                        });
                    </script><input type=\"hidden\" name=\"ecoas_token\" value=\"677f4d48d1c27922c3ee392280812350\" id=\"ecoas_token\" />
<input type=\"hidden\" name=\"ecoas_id_order\" value=\"174913\" id=\"ecoas_id_order\" />
<script>
    var nb_sellermania_orders_in_error = 0;
    var txt_sellermania_orders_in_error = \"commandes Sellermania n\\'ont pas pu être importées. Pour plus de détails, veuillez vous rendre dans la section de configuration du module.\";
    var sellermania_invoice_url = \\'https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminModules&token=b11cd68c830cf10cf3ae98ef17c13061&configure=sellermania&module_name=sellermania&display=invoice\\';

    var sellermania_admin_orders_url = \\'/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\\';
    var sellermania_default_carrier = \\'\\';
    var txt_sellermania_confirm_orders = \"Confirm selected Sellermania orders\";
    var txt_sellermania_send_orders = \"Set selected Sellermania orders as sent\";
    var txt_sellermania_select_at_least_one_order = \"You have to select at least one order\";
    var txt_sellermania_error_occured = \"An error occured\";
    var txt_sellermania_carrier_selection = \"Which carrier do you want to use?\";
    var txt_sellermania_orders_updated = \"Orders were successfully updated\";
    var txt_sellermania_select_all = \"Select all orders\";
    var txt_sellermania_unselect_all = \"Unselect all orders\";
    var txt_sellermania_timeout_exception = \"Sellermania rejected the request (too many requests has been made), ple' | raw }}{{ 'ase wait a few seconds and try again.\";
</script>
<script type=\"text/javascript\" src=\"/modules/sellermania/views/js/displayBackOfficeHeader.js\"></script>
    <script type=\"text/javascript\" src=\"/modules/sellermania/views/js/displayBackOfficeHeader-16.js\"></script>



' | raw }}{% block stylesheets %}{% endblock %}{% block extra_stylesheets %}{% endblock %}</head>{{ '

<body
  class=\"lang-fr adminorders developer-mode\"
  data-base-url=\"/admin919wlkwpjawfriiadmx/index.php\"  data-token=\"ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">

  <header id=\"header\" class=\"d-print-none\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\"></a>
      <span id=\"shop_version\">8.1.7</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Accès rapide
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Commandes\"
      >Commandes</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\"
                 data-item=\"Évaluation du catalogue\"
      >Évaluation du catalogue</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Modules installés\"
      >Modules installés</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=9bb2e23170e48f' | raw }}{{ '0d1e550d08ddad34df\"
                 data-item=\"Nouveau bon de réduction\"
      >Nouveau bon de réduction</a>
          <a class=\"dropdown-item quick-row-link new-product-button\"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Nouveau produit\"
      >Nouveau produit</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=119ddb83385ea4bc85a1ecdad33a1883\"
                 data-item=\"Nouvelle catégorie\"
      >Nouvelle catégorie</a>
        <div class=\"dropdown-divider\"></div>
          <a id=\"quick-add-link\"
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"89\"
        data-icon=\"icon-AdminParentOrders\"
        data-method=\"add\"
        data-url=\"index.php/sell/orders/174913/view\"
        data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
        data-prompt-text=\"Veuillez nommer ce raccourci :\"
        data-link=\"Commandes - Liste\"
      >
        <i class=\"material-icons\">add_circle</i>
        Ajouter la page actuelle à l\\'accès rapide
      </a>
        <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\">
      <i class=\"material-icons\">settings</i>
      Gérez vos accès rapides
    </a>
  </div>
</div>
      </div>
      <div class=\"component component-search\" id=\"header-search-container\">
        <div class=\"component-search-body\">
          <div class=\"component-search-top\">
            <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/admin919wlkwpjawfriiadmx/index.ph' | raw }}{{ 'p?controller=AdminSearch&amp;token=955d533f2e37cfbd97a2aad4499c30ae\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_query\" name=\"bo_query\" value=\"\" placeholder=\"Rechercher (ex. : référence produit, nom du client, etc.)\" aria-label=\"Barre de recherche\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        Partout
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"Partout\" href=\"#\" data-value=\"0\" data-placeholder=\"Que souhaitez-vous trouver ?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> Partout</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catalogue\" href=\"#\" data-value=\"1\" data-placeholder=\"Nom du produit, référence, etc.\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catalogue</a>
        <a class=\"dropdown-item\" data-item=\"Clients par nom\" href=\"#\" data-value=\"2\" data-placeholder=\"Nom\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clients par nom</a>
        <a class=\"dropdown-item\" data-item=\"Clients par adresse IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clients par adresse IP</a>
        <a class=\"dropdown-item\" data-item=\"Commandes\" href=\"#\" data-value=\"3\" data-placeholder=\"ID commande\" data-icon=\"icon-credit-card\"><i class=\"material-icons\">shopping_basket</i> Commandes</a>
        <a class=\"dropdown-item\" data-item=\"Factures\" href=\"#\" data-value=\"4\" data-placeholder=\"Numéro de facture\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Factures</a>
        <a class=\"dropdown-item\" data-item=\"Paniers' | raw }}{{ '\" href=\"#\" data-value=\"5\" data-placeholder=\"ID panier\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Paniers</a>
        <a class=\"dropdown-item\" data-item=\"Modules\" href=\"#\" data-value=\"7\" data-placeholder=\"Nom du module\" data-icon=\"icon-puzzle-piece\"><i class=\"material-icons\">extension</i> Modules</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">RECHERCHE</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$(\\'#bo_query\\').one(\\'click\\', function() {
    \$(this).closest(\\'form\\').removeClass(\\'collapsed\\');
  });
});
</script>
            <button class=\"component-search-cancel d-none\">Annuler</button>
          </div>

          <div class=\"component-search-quickaccess d-none\">
  <p class=\"component-search-title\">Accès rapide</p>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Commandes\"
    >Commandes</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\"
             data-item=\"Évaluation du catalogue\"
    >Évaluation du catalogue</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Modules installés\"
    >Modules installés</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=9bb2e23170e48f0d1e550d08ddad34df\"
             data-item=\"Nouveau bon de réduction\"
    >Nouveau bon de réduction</a>
      <a cl' | raw }}{{ 'ass=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Nouveau produit\"
    >Nouveau produit</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=119ddb83385ea4bc85a1ecdad33a1883\"
             data-item=\"Nouvelle catégorie\"
    >Nouvelle catégorie</a>
    <div class=\"dropdown-divider\"></div>
      <a id=\"quick-add-link\"
      class=\"dropdown-item js-quick-link\"
      href=\"#\"
      data-rand=\"45\"
      data-icon=\"icon-AdminParentOrders\"
      data-method=\"add\"
      data-url=\"index.php/sell/orders/174913/view\"
      data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
      data-prompt-text=\"Veuillez nommer ce raccourci :\"
      data-link=\"Commandes - Liste\"
    >
      <i class=\"material-icons\">add_circle</i>
      Ajouter la page actuelle à l\\'accès rapide
    </a>
    <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\">
    <i class=\"material-icons\">settings</i>
    Gérez vos accès rapides
  </a>
</div>
        </div>

        <div class=\"component-search-background d-none\"></div>
      </div>

              <div class=\"component hide-mobile-sm\" id=\"header-debug-mode-container\">
          <a class=\"link shop-state\"
             id=\"debug-mode\"
             data-toggle=\"pstooltip\"
             data-placement=\"bottom\"
             data-html=\"true\"
             title=\"<p class=&quot;text-left&quot;><strong>Votre boutique est en mode debug.</strong></p><p class=&quot;text-left&quot;>Tous les messages et erreurs PHP sont affichés. Lorsque vous n&#039;en avez plus besoin' | raw }}{{ ', &lt;strong&gt;désactivez&lt;/strong&gt; ce mode.</p>\"
             href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
          >
            <i class=\"material-icons\">bug_report</i>
            <span>Mode debug</span>
          </a>
        </div>
      
      
      <div class=\"header-right\">
                  <div class=\"component\" id=\"header-shop-list-container\">
              <div class=\"shop-list\">
    <a class=\"link\" id=\"header_shopname\" href=\"https://www.automotoboutic.com/\" target= \"_blank\">
      <i class=\"material-icons\">visibility</i>
      <span>Voir ma boutique</span>
    </a>
  </div>
          </div>
                          <div class=\"component header-right-component\" id=\"header-notifications-container\">
            <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Commandes<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notifications\"
              role=\"tab\"
            >
              Clients<span id=\"_nb_new_customers_\"></span>
            </a>
   ' | raw }}{{ '       </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Messages<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouvelle commande pour le moment :(<br>
              Avez-vous consulté vos <strong><a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=834e3a686d344a1706c4a67ec05b6623\">paniers abandonnés</a></strong> ?<br> Votre prochaine commande s\\'y trouve peut-être !
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Aucun nouveau client pour l\\'instant :(<br>
              Êtes-vous actifs sur les réseaux sociaux en ce moment ?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Pas de nouveau message pour l\\'instant.<br>
              On dirait que vos clients sont satisfaits :)
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"order-notification-template\">
    <a class=\"notif\" href=\\'order_url\\'>
      #_id_order_ -
      de <stro' | raw }}{{ 'ng>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href=\\'customer_url\\'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - enregistré le <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href=\\'message_url\\'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
          </div>
        
        <div class=\"component\" id=\"header-employee-container\">
          <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      <div class=\"employee-top\">
        <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"https://www.automotoboutic.com/img/pr/default.jpg\" alt=\"Stella\" /></span>
        <span class=\"employee_profile\">Ravi de vous revoir Stella</span>
      </div>

      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/2/edit?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">
      <i class=\"material-icons\">edit</i>
      <span>Votre profil</span>
    </a>
    </div>

    <p class=\"divider\"></p>

    
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLogin&amp;logout=1&amp;token=9e4d3e7d81b16309f09a2bc140ca6b8e\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Déconnexion</span>
  ' | raw }}{{ '  </a>
  </div>
</div>
        </div>
              </div>
    </nav>
  </header>

  <nav class=\"nav-bar d-none d-print-none d-md-block\">
  <span class=\"menu-collapse\" data-toggle-url=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/toggle-navigation?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\">
    <i class=\"material-icons rtl-flip\">chevron_left</i>
    <i class=\"material-icons rtl-flip\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <div class=\"logo-container\">
          <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\"></a>
          <span id=\"shop_version\" class=\"header-version\">8.1.7</span>
      </div>

      <ul class=\"main-menu\">
              
                    
                    
          
            <li class=\"link-levelone\" data-submenu=\"1\" id=\"tab-AdminDashboard\">
              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\" class=\"link\" >
                <i class=\"material-icons\">trending_up</i> <span>Tableau de bord</span>
              </a>
            </li>

          
                      
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vendre</span>
            </li>

                              
                  
                                                      
                                                          
                  <li class=\"link-levelone has_submenu link-active open ul-open\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i clas' | raw }}{{ 's=\"material-icons mi-shopping_basket\">shopping_basket</i>
                      <span>
                      Commandes
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_up
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo link-active\" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/invoices/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Factures
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/credit-slips/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">' | raw }}{{ ' Avoirs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/delivery-slips/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Bons de livraison
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&amp;token=834e3a686d344a1706c4a67ec05b6623\" class=\"link\"> Paniers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"159\" id=\"subtab-AdminExport\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminExport&amp;token=20f84f531efc6f605f211317fcae655e\" class=\"link\"> Ordre de fabrication
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
       ' | raw }}{{ '           <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catalogue
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Catégories
                                </a>
                              </li>

                                                                                  
                              
                                                            
 ' | raw }}{{ '                             <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/monitoring/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Suivi
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminAttributesGroups&amp;token=be4c4788bf886173fae54dcf8c61f39e\" class=\"link\"> Attributs &amp; caractéristiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/brands/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Marques et fournisseurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/attachments/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Fichiers
                                </a>
                     ' | raw }}{{ '         </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;token=9bb2e23170e48f0d1e550d08ddad34df\" class=\"link\"> Réductions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/stocks/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Stock
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"132\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"161\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                  ' | raw }}{{ '              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-account_circle\">account_circle</i>
                      <span>
                      Clients
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                      ' | raw }}{{ '                                      
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/addresses/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Adresses
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link\">
                      <i class=\"material-icons mi-chat\">chat</i>
                      <span>
                      SAV
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link' | raw }}{{ '\"> SAV
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customer-service/order-messages/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Messages prédéfinis
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminReturn&amp;token=190a9a687cc07ca77eeb2905f7869399\" class=\"link\"> Retours produits
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;token=dfec7c03d581b79d3142cf9eaf1db3c5\" class=\"link\">
                      <i class=\"material-icons mi-assessment\">assessment</i>
                      <span>
                      Statistiques
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
             ' | raw }}{{ '                                                       keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"37\" id=\"tab-IMPROVE\">
                <span class=\"title\">Personnaliser</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"38\" id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-extension\">extension</i>
                      <span>
                      Modules
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-38\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"39\" id=\"subtab-AdminModulesSf\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Gestionnaire de modules 
                                </a>
                              </li>

               ' | raw }}{{ '                                                                                                                         
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"152\" id=\"subtab-AdminStoreCommander\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStoreCommander&amp;token=708b65e8897d2ff790b42e749972a549\" class=\"link\"> Store Commander
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"158\" id=\"subtab-AdminEcOrdersAndStock\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminEcOrdersAndStock&amp;token=e3b89f80542b8c82297670f705d4028e\" class=\"link\"> Ec Order And Stock
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                      <span>
                      Apparence
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                       ' | raw }}{{ '                                             keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"126\" id=\"subtab-AdminThemesParent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Thème et logo
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"45\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/mail_theme/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Thème d&#039;e-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"47\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/cms-pages/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Pages
                                </a>
                              </li>

                                                                                  
                              
                         ' | raw }}{{ '                                   
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/modules/positions/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Positions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"49\" id=\"subtab-AdminImages\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminImages&amp;token=712d746201c76d7e50af65ac157342fb\" class=\"link\"> Images
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/link-widget/list?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Liste de liens
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"50\" id=\"subtab-AdminParentShipping\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a022' | raw }}{{ '5b7d4d47c6edfa1955516f73\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Livraison
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-50\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"51\" id=\"subtab-AdminCarriers\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a0225b7d4d47c6edfa1955516f73\" class=\"link\"> Transporteurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"52\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/shipping/preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" ' | raw }}{{ 'data-submenu=\"53\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Paiement
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-53\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"54\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Moyens de paiement
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                         ' | raw }}{{ '     
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"56\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      International
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-56\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Localisation
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/zones/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Zones géographiques
                                </a>
                            ' | raw }}{{ '  </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"66\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/taxes/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Taxes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"69\" id=\"subtab-AdminTranslations\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/translations/settings?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Traductions
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"70\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurer</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"71\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                  ' | raw }}{{ '    <span>
                      Paramètres de la boutique
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-71\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminParentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Paramètres généraux
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"75\" id=\"subtab-AdminParentOrderPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/order-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"78\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/product-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSD' | raw }}{{ 'lDPUwW3BdOKJYvQ\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/customer-preferences/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"83\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/contacts/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Contact
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"86\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/seo-urls/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Trafic et SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminPa' | raw }}{{ 'rentSearchConf\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSearchConf&amp;token=d4d5d68441ea494769eed19066d6bbfe\" class=\"link\"> Rechercher
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"92\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                      <span>
                      Paramètres avancés
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-92\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"93\" id=\"subtab-AdminInformation\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Informations
                                </a>
                              </li>

                                       ' | raw }}{{ '                                           
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"94\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Performances
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"95\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/administration/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Administration
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/emails/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> E-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"97\" id=\"subtab-AdminImport\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/import/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3Bd' | raw }}{{ 'OKJYvQ\" class=\"link\"> Importer
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"98\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Équipe
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"102\" id=\"subtab-AdminParentRequestSql\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/sql-requests/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Base de données
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/logs/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminWebservice\">
      ' | raw }}{{ '                          <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/webservice-keys/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Webservice
                                </a>
                              </li>

                                                                                                                                                                                                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"110\" id=\"subtab-AdminFeatureFlag\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/feature-flags/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Fonctionnalités nouvelles et expérimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"111\" id=\"subtab-AdminParentSecurity\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/security/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\"> Security
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"153\" id=\"tab-Sellermania\">
                <span class=\"title\">Sellermania</span>
            </li>

                              
          ' | raw }}{{ '        
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminSellermaniaSettings\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaSettings&amp;token=8d53b28207eeebb79d42845b22550410\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"155\" id=\"subtab-AdminSellermaniaDiagnostics\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaDiagnostics&amp;token=2b18c4445a756b41ff945e8407af624a\" class=\"link\">
                      <i class=\"material-icons mi-star_border\">star_border</i>
                      <span>
                      Diagnostic
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"categ' | raw }}{{ 'ory-title\" data-submenu=\"171\" id=\"tab-NHBusinessCentral\">
                <span class=\"title\">NHBusinessCentral</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"172\" id=\"subtab-ConfigurationController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/configuration?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"173\" id=\"subtab-TransactionGridController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/transactiongrid?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\" class=\"link\">
                      <i class=\"material-icons mi-refresh\">refresh</i>
                      <span>
                      Transactions
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
    ' | raw }}{{ '      
                  </ul>
  </div>
  
</nav>


<div class=\"header-toolbar d-print-none\">
    
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Commandes</li>
          
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
  
        
<div class=\"title-content\">
  <h1 class=\"d-inline\">
    <strong class=\"text-muted\" data-role=\"order-id\">#174913</strong>
    <strong data-role=\"order-reference\">MNESEJNYZ</strong>
  </h1>

  <p class=\"lead d-inline\">
    de

          Florence
      belz
      </p>

  <span class=\"badge rounded badge-dark ml-2 mr-2 font-size-100\">
    58,32 €
  </span>

  <p class=\"lead d-inline\">
    28/01/2025
    <span class=\"text-muted\">
      à

      16:52:55
    </span>
  </p>
</div>

  

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                        
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Aide\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminOrders%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
                   id=\"product_form_open_help\"
                >
                  Aide
                </a>
                                    </div>
        </div>

      
    </div>
  </div>

  
  
  <div class=\"btn-floating\">
    <button class=\"btn btn-primary collapsed\" data-toggle=\"collapse\" data-target=\".btn-floating-container\" aria-expanded=\"false\">
      <i class=\"material-icons\">add</i>
    </button>
    <div class=\"btn-floating-container collapse\">
      <div class=\"btn-floating-menu\">
        
        
                   ' | raw }}{{ '           <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Aide\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminOrders%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ\"
            >
              Aide
            </a>
                        </div>
    </div>
  </div>
  
</div>

<div id=\"main-div\">
          
      <div class=\"content-div  \">

        

                                                        
        <div id=\"ajax_confirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>
<div id=\"content-message-box\"></div>


  ' | raw }}{% block content_header %}{% endblock %}{% block content %}{% endblock %}{% block content_footer %}{% endblock %}{% block sidebar_right %}{% endblock %}{{ '

        

      </div>
    </div>

  <div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>Oh non !</h1>
  <p class=\"mt-3\">
    La version mobile de cette page n\\'est pas encore disponible.
  </p>
  <p class=\"mt-2\">
    Cette page n\\'est pas encore disponible sur mobile, merci de la consulter sur ordinateur.
  </p>
  <p class=\"mt-2\">
    Merci.
  </p>
  <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons rtl-flip\">arrow_back</i>
    Précédent
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    <script type=\"text/javascript\">
  \$(document).ready(function(){
\$(\"#order_grid_panel\").before(\"<div id=\\\\\"sc_message\\\\\" class=\\\\\"alert alert-info\\\\\"><p><b>Store Commander<\\\\/b> est install\\\\u00e9 sur votre boutique. Saviez-vous que vous pouvez :<\\\\/p>\\\\n                        <ul style=\\\\\"list-style-type: disc;padding-left:40px\\\\\">\\\\n                        <li>Filtrer plus finement vos derni\\\\u00e8res commandes pour changer leurs statuts en masse<\\\\/li>\\\\n                        <li>Cr\\\\u00e9er des commandes pour vos clients \\\\u00e0 partir d\\'un simple appel t\\\\u00e9l\\\\u00e9phonique<\\\\/li>\\\\n                        <li>Rechercher, trouver et imprimer les factures\\\\/avoirs\\\\/bons de livraison plus facilement<\\\\/li>\\\\n                        <li>Analyser pr\\\\u00e9cis\\\\u00e9ment l\\'origine de vos commandes pour prendre des d\\\\u00e9cisions sur vos diff\\\\u00e9rents canaux d\\'acquisition<\\\\/li>\\\\n                        <li>Exporter les donn\\\\u00e9es pour faciliter la gestion de votre comptabilit\\\\u00e9 (ventillation TVA, Pays, Mini-guichet...)<\\\\/li>\\\\n                        <\\\\/ul><br\\\\/>\\\\n                        <p>D\\\\u00e9marrez Store Commander pour g\\\\u00e9rer vos commandes plus efficacement, en <b><a href=\\\\\"https:\\\\/\\\\/www.automotoboutic.com\\\\/admin919wlkwpjawfriiadmx\\\\/index.php?controller=AdminStoreCommander&toke' | raw }}{{ 'n=708b65e8897d2ff790b42e749972a549\\\\\">cliquant ici<\\\\/a><\\\\/b> ou depuis le menu Module > Store Commander<\\\\/p>\\\\n                        <p style=\\\\\"text-align:right\\\\\"><a href=\\\\\"\\\\/admin919wlkwpjawfriiadmx\\\\/index.php\\\\/sell\\\\/orders\\\\/?_token=ZyOZJRc7EyFYm2f0cYnyZkUA_jSDlDPUwW3BdOKJYvQ&sc_no_msg_order=1\\\\\">Ne plus afficher ce message<\\\\/a><\\\\/p><\\\\/a><\\\\/div>\");

  });
</script>

</div>
  

      <div class=\"bootstrap\">
      
    </div>
  
' | raw }}{% block javascripts %}{% endblock %}{% block extra_javascripts %}{% endblock %}{% block translate_javascripts %}{% endblock %}</body>{{ '
</html>' | raw }}", "__string_template__6ff6a4c8c8f28c4dc040ac27da5cf7fe", "");
    }
}
