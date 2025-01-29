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

/* __string_template__fdf91968ca2801dc40e3ac818854f183 */
class __TwigTemplate_f774fdf44f2a3f62bfb3b74cec3c7d80 extends Template
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

<title>Produits • Automotoboutic - Spécialiste accessoire auto</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminProducts';
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
    var token = '08833e7e3db6657bf157f76101685f1f';
    var currentIndex = 'index.php?controller=AdminProducts';
    var employee_token = 'c1bf15cd704edad3fa9a6a94e64c9df0';
    var choose_language_translate = 'Choisissez la langue :';
    var default_language = '1';
    var admin_modules_link = '/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0';
    var admin_notification_get_link = '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0';
    var admin_notification_push_link = adminNotificationPushLink = '/admin919wlkwpjawfriiadmx/index.php/common/notifications/ack?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0';
  ";
        // line 41
        echo "  var tab_modules_list = '';
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
var changeFormLanguageUrl = \"\\/admin919wlkwpjawfriiadmx\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\";
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\u20ac\",\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.00\\u00a0\\u00a4\",\"negativePattern\":\"-#,##0.00\\u00a0\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var number_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"numberSymbols\":[\",\",\"\\u202";
        // line 68
        echo "f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var prestashop = {\"debug\":false};
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

  <script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: '#DF0067',
      textColor: '#FFFFFF',
      notificationGetUrl: '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120";
        // line 97
        echo "000, // Refresh every 2 minutes
    });
  }
</script>
<script type=\"application/javascript\">
                        \$(document).ready(function() {
                            \$.get(\"/modules/dpdfrance/cron.php?token=9a2ee22765afa7292bf4ea3c63088764&employee=2\");
                        });
                    </script>

";
        // line 107
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>";
        echo "

<body
  class=\"lang-fr adminproducts\"
  data-base-url=\"/admin919wlkwpjawfriiadmx/index.php\"  data-token=\"DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\">

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
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=9bb2e23170e48f0d1e550d08dda";
        // line 141
        echo "d34df\"
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
        data-rand=\"129\"
        data-icon=\"icon-AdminCatalog\"
        data-method=\"add\"
        data-url=\"index.php/sell/catalog/products-v2\"
        data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
        data-prompt-text=\"Veuillez nommer ce raccourci :\"
        data-link=\"Produits - Liste\"
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
      action=\"/admin919wlkwpjawfriiadmx/index.php?controller=Admi";
        // line 180
        echo "nSearch&amp;token=955d533f2e37cfbd97a2aad4499c30ae\"
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
        <a class=\"dropdown-item\" data-item=\"Paniers\" href=\"#\" data-v";
        // line 197
        echo "alue=\"5\" data-placeholder=\"ID panier\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Paniers</a>
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
      <a class=\"dropdown-ite";
        // line 233
        echo "m quick-row-link\"
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
      data-rand=\"98\"
      data-icon=\"icon-AdminCatalog\"
      data-method=\"add\"
      data-url=\"index.php/sell/catalog/products-v2\"
      data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=c2a09956a956aa35efaffc1597ff1e1d\"
      data-prompt-text=\"Veuillez nommer ce raccourci :\"
      data-link=\"Produits - Liste\"
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
            <div i";
        // line 278
        echo "d=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
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
          </li>
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
              Avez-vous consulté vos <strong><a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=Adm";
        // line 329
        echo "inCarts&action=filterOnlyAbandonedCarts&token=834e3a686d344a1706c4a67ec05b6623\">paniers abandonnés</a></strong> ?<br> Votre prochaine commande s'y trouve peut-être !
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
      de <strong>_customer_name_</strong> (_iso_code_)_carrier_
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
     ";
        // line 377
        echo "     <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      <div class=\"employee-top\">
        <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"https://www.automotoboutic.com/img/pr/default.jpg\" alt=\"Stella\" /></span>
        <span class=\"employee_profile\">Ravi de vous revoir Stella</span>
      </div>

      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/2/edit?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\">
      <i class=\"material-icons\">edit</i>
      <span>Votre profil</span>
    </a>
    </div>

    <p class=\"divider\"></p>

    
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLogin&amp;logout=1&amp;token=9e4d3e7d81b16309f09a2bc140ca6b8e\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Déconnexion</span>
    </a>
  </div>
</div>
        </div>
              </div>
    </nav>
  </header>

  <nav class=\"nav-bar d-none d-print-none d-md-block\">
  <span class=\"menu-collapse\" data-toggle-url=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/toggle-navigation?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\">
    <i class=\"material-icons rtl-flip\">chevron_left</i>
    <i class=\"material-icons rtl-flip\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <div class=\"logo-container\">
          <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\"></a>
          <span id=\"shop_version\" class=\"header-version\">8.1.7</span>
      </div>

      <ul class=\"main-menu\">
   ";
        // line 421
        echo "           
                    
                    
          
            <li class=\"link-levelone\" data-submenu=\"1\" id=\"tab-AdminDashboard\">
              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8473404de4dd012e2eee39dfc509dce5\" class=\"link\" >
                <i class=\"material-icons\">trending_up</i> <span>Tableau de bord</span>
              </a>
            </li>

          
                      
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vendre</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-shopping_basket\">shopping_basket</i>
                      <span>
                      Commandes
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=DHouPaGRP";
        // line 459
        echo "Ecw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/invoices/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Factures
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/credit-slips/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Avoirs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/delivery-slips/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Bons de livraison
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
        ";
        // line 491
        echo "                        <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&amp;token=834e3a686d344a1706c4a67ec05b6623\" class=\"link\"> Paniers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"159\" id=\"subtab-AdminExport\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminExport&amp;token=20f84f531efc6f605f211317fcae655e\" class=\"link\"> Ordre de fabrication
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                                                          
                  <li class=\"link-levelone has_submenu link-active open ul-open\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catalogue
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_up
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                            ";
        // line 520
        echo "          
                              
                                                            
                              <li class=\"link-leveltwo link-active\" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Catégories
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/monitoring/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Suivi
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminAttributesGroups&amp;token=be4c4788bf886173fae54dcf8c61f39e\" class=\"";
        // line 548
        echo "link\"> Attributs &amp; caractéristiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/brands/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Marques et fournisseurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/attachments/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Fichiers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;token=9bb2e23170e48f0d1e550d08ddad34df\" class=\"link\"> Réductions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"";
        // line 579
        echo "subtab-AdminStockManagement\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/stocks/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Stock
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"132\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"161\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=06aa3a513e1c3c726f267ba07d8c4d6e\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i";
        // line 608
        echo " class=\"material-icons mi-account_circle\">account_circle</i>
                      <span>
                      Clients
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/addresses/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Adresses
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
        ";
        // line 640
        echo "            <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link\">
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
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2d5aff4c64333fba4ea07153a9d3c2c0\" class=\"link\"> SAV
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customer-service/order-messages/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Messages prédéfinis
                                </a>
                              </li>

                                                                                  
                              
                                                            
                ";
        // line 669
        echo "              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
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
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"37\" id=\"tab-IMPROVE\">
                <span class=\"title\">Personnaliser</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"38\" id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improv";
        // line 706
        echo "e/modules/manage?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Gestionnaire de modules 
                                </a>
                              </li>

                                                                                                                                        
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"152\" id=\"subtab-AdminStoreCommander\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStoreCommander&amp;token=708b65e8897d2ff790b42e749972a549\" class=\"link\"> Store Commander
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-";
        // line 735
        echo "leveltwo\" data-submenu=\"158\" id=\"subtab-AdminEcOrdersAndStock\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminEcOrdersAndStock&amp;token=e3b89f80542b8c82297670f705d4028e\" class=\"link\"> Ec Order And Stock
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                      <span>
                      Apparence
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"126\" id=\"subtab-AdminThemesParent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Thème et logo
                                </a>
                              </li>

                                           ";
        // line 765
        echo "                                       
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"45\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/mail_theme/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Thème d&#039;e-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"47\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/cms-pages/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Pages
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/modules/positions/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Positions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"49\" id=\"subtab-AdminImages\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminImages&amp;token=712d74620";
        // line 793
        echo "1c76d7e50af65ac157342fb\" class=\"link\"> Images
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/link-widget/list?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Liste de liens
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"50\" id=\"subtab-AdminParentShipping\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a0225b7d4d47c6edfa1955516f73\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Livraison
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-50\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5";
        // line 825
        echo "1\" id=\"subtab-AdminCarriers\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=6b50a0225b7d4d47c6edfa1955516f73\" class=\"link\"> Transporteurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"52\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/shipping/preferences/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"53\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Paiement
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-53\" class=\"submenu panel-collapse\">
                                                      
     ";
        // line 856
        echo "                         
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"54\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Moyens de paiement
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/preferences?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"56\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      International
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                          ";
        // line 886
        echo "  </a>
                                              <ul id=\"collapse-56\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Localisation
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/zones/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Zones géographiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"66\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/taxes/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Taxes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"69\" id=\"subtab-AdminTranslations\">
         ";
        // line 916
        echo "                       <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/translations/settings?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Traductions
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"70\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurer</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"71\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Paramètres de la boutique
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-71\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminParentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/";
        // line 951
        echo "shop/preferences/preferences?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Paramètres généraux
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"75\" id=\"subtab-AdminParentOrderPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/order-preferences/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"78\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/product-preferences/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/customer-preferences/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                              ";
        // line 981
        echo "              
                              <li class=\"link-leveltwo\" data-submenu=\"83\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/contacts/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Contact
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"86\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/seo-urls/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Trafic et SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminParentSearchConf\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSearchConf&amp;token=d4d5d68441ea494769eed19066d6bbfe\" class=\"link\"> Rechercher
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"92\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0";
        // line 1010
        echo "\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Informations
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"94\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Performances
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"95\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/a";
        // line 1040
        echo "dmin919wlkwpjawfriiadmx/index.php/configure/advanced/administration/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Administration
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/emails/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> E-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"97\" id=\"subtab-AdminImport\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/import/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Importer
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"98\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Équipe
                                </a>
                              </li>

                                                                                  
                              
                                                            
           ";
        // line 1071
        echo "                   <li class=\"link-leveltwo\" data-submenu=\"102\" id=\"subtab-AdminParentRequestSql\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/sql-requests/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Base de données
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/logs/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminWebservice\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/webservice-keys/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Webservice
                                </a>
                              </li>

                                                                                                                                                                                                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"110\" id=\"subtab-AdminFeatureFlag\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/feature-flags/?_token=DHouPaGRPEc";
        // line 1096
        echo "w_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Fonctionnalités nouvelles et expérimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"111\" id=\"subtab-AdminParentSecurity\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/security/?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\"> Security
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"153\" id=\"tab-Sellermania\">
                <span class=\"title\">Sellermania</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminSellermaniaSettings\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaSettings&amp;token=8d53b28207eeebb79d42845b22550410\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                         ";
        // line 1133
        echo "   </a>
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
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"171\" id=\"tab-NHBusinessCentral\">
                <span class=\"title\">NHBusinessCentral</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"172\" id=\"subtab-ConfigurationController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/configuration?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                  ";
        // line 1171
        echo "                                  keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"173\" id=\"subtab-TransactionGridController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/transactiongrid?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" class=\"link\">
                      <i class=\"material-icons mi-refresh\">refresh</i>
                      <span>
                      Transactions
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                              
          
                  </ul>
  </div>
  
</nav>


<div class=\"header-toolbar d-print-none\">
    
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Catalogue</li>
          
                      <li class=\"breadcrumb-item active\">
              <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\" aria-current=\"page\">Produits</a>
            </li>
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Produits          </h1>
      

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                                                          <a
      ";
        // line 1225
        echo "            class=\"btn btn-primary new-product-button pointer\"                  id=\"page-header-desc-configuration-add\"
                  href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?shopId=1&amp;_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\"                  title=\"Nouveau produit\"                  data-modal-title=\"Ajouter un nouveau produit\"                >
                  <i class=\"material-icons\">add_circle_outline</i>                  Nouveau produit
                </a>
                                      
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Aide\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminProducts%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\"
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
        
                              <a
              class=\"btn btn-floating-item new-product-button  pointer\"              id=\"page-header-desc-floating-configuration-add\"
              href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?shopId=1&amp;_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\"              title=\"Nouveau produit\"            >
              Nouveau produit
              <i class=\"material-icons\">";
        // line 1260
        echo "add_circle_outline</i>            </a>
                  
                              <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Aide\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminProducts%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0\"
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
        // line 1287
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
\$(\"#main-div > .content-div > .row > div > .products-catalog\").before(\"<div id=\\\"sc_message\\\" class=\\\"alert alert-info\\\"><p><b>Store Commander<\\/b> est install\\u00e9 sur votre boutique. Saviez-vous que vous pouvez :<\\/p>\\n                        <ul style=\\\"list-style-type: disc;padding-left:40px\\\">\\n                        <li>Trouver et modifier en masse toutes les informations de vos produits<\\/li>\\n                        <li>Exporter et r\\u00e9-importer tout votre catalogue ou une s\\u00e9lection de produits<\\/li>\\n                        <li>Pr\\u00e9parer les p\\u00e9riodes de soldes et promotions vitesse turbo, en contr\\u00f4lant vos marges<\\/li>\\n                        <li>Am\\u00e9liorer tous les crit\\u00e8res SEO de chaque fiche produit : meta tags, taille des meta tags...<\\/li>\\n                        <li>Identifier et corriger des centaines de probl\\u00e8mes avec le menu Outils > FixMyPrestaShop<\\/li>\\n                        <\\/ul><br\\/>\\n                        <p>D\\u00e9marrez Store Commander pour g\\u00e9rer votre catalogue plus efficacement, en <b><a href=\\\"https:\\/\\/www.automotoboutic.com\\/admin919wlkwpjawfriiadmx\\/index.php?controller=AdminStoreCommander&token=708b65e8897d2ff790b42e749972a549\\\"";
        // line 1315
        echo ">cliquant ici<\\/a><\\/b> ou depuis le menu Module > Store Commander<\\/p>\\n                        <p style=\\\"text-align:right\\\"><a href=\\\"\\/admin919wlkwpjawfriiadmx\\/index.php\\/sell\\/catalog\\/products?_token=DHouPaGRPEcw_Pa0tji7vCQWExBFkawq6kmsfxH5rh0&sc_no_msg_catalog=1\\\">Ne plus afficher ce message<\\/a><\\/p><\\/a><\\/div>\");

  });
</script>

</div>
  

      <div class=\"bootstrap\">
      
    </div>
  
";
        // line 1327
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>";
        echo "
</html>";
    }

    // line 107
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_extra_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 1287
    public function block_content_header($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_content_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_sidebar_right($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 1327
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_extra_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function block_translate_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "__string_template__fdf91968ca2801dc40e3ac818854f183";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1502 => 1327,  1481 => 1287,  1470 => 107,  1461 => 1327,  1447 => 1315,  1413 => 1287,  1384 => 1260,  1347 => 1225,  1291 => 1171,  1251 => 1133,  1212 => 1096,  1185 => 1071,  1152 => 1040,  1120 => 1010,  1089 => 981,  1057 => 951,  1020 => 916,  988 => 886,  956 => 856,  923 => 825,  889 => 793,  859 => 765,  827 => 735,  796 => 706,  757 => 669,  726 => 640,  692 => 608,  661 => 579,  628 => 548,  598 => 520,  567 => 491,  533 => 459,  493 => 421,  447 => 377,  397 => 329,  344 => 278,  297 => 233,  259 => 197,  240 => 180,  199 => 141,  160 => 107,  148 => 97,  117 => 68,  88 => 41,  46 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__fdf91968ca2801dc40e3ac818854f183", "");
    }
}
