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

/* __string_template__84074326ff2ddbae2f2ca4cca534c126 */
class __TwigTemplate_8b98c30b1555c42f9c60ad3d33e7cf68 extends Template
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

<title>Catégories • Automotoboutic - Spécialiste accessoire auto</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminCategories';
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
    var token = 'd6fcfea9809776874210587fe92e0e6d';
    var currentIndex = 'index.php?controller=AdminCategories';
    var employee_token = '42b24ef1252053f41d6ea09be2305c80';
    var choose_language_translate = 'Choisissez la langue :';
    var default_language = '1';
    var admin_modules_link = '/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA';
    var admin_notification_get_link = '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA';
    var admin_notification_push_link = adminNotificationPushLink = '/admin919wlkwpjawfriiadmx/index.php/common/notifications/ack?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQN";
        // line 40
        echo "vA';
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
var changeFormLanguageUrl = \"\\/admin919wlkwpjawfriiadmx\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\";
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\u20ac\",\"numberSymbols\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.00\\u00a0\\u00a4\",\"negativePattern\":\"-#,##0.00\\u00a0\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var number_specifications = {\"symbol\":[\",\",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"numberSymbols\":[\",\"";
        // line 68
        echo ",\"\\u202f\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
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
      notificationGetUrl: '/admin919wlkwpjawfriiadmx/index.php/common/notifications?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      tim";
        // line 97
        echo "er: 120000, // Refresh every 2 minutes
    });
  }
</script>
<script type=\"application/javascript\">
                        \$(document).ready(function() {
                            \$.get(\"/modules/dpdfrance/cron.php?token=9a2ee22765afa7292bf4ea3c63088764&employee=1\");
                        });
                    </script>

";
        // line 107
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>";
        echo "

<body
  class=\"lang-fr admincategories\"
  data-base-url=\"/admin919wlkwpjawfriiadmx/index.php\"  data-token=\"s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\">

  <header id=\"header\" class=\"d-print-none\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8c04ecbfa406fe9c5f30e8bf6d646ff7\"></a>
      <span id=\"shop_version\">8.1.7</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Accès rapide
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=501ee0e24d72c604d41c0506ab184834\"
                 data-item=\"Commandes\"
      >Commandes</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=0d32e0d114cacbe31f70aa514061046b\"
                 data-item=\"Évaluation du catalogue\"
      >Évaluation du catalogue</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=501ee0e24d72c604d41c0506ab184834\"
                 data-item=\"Modules installés\"
      >Modules installés</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=569950783e1c4bfa0a8569457";
        // line 141
        echo "beb440a\"
                 data-item=\"Nouveau bon de réduction\"
      >Nouveau bon de réduction</a>
          <a class=\"dropdown-item quick-row-link new-product-button\"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=501ee0e24d72c604d41c0506ab184834\"
                 data-item=\"Nouveau produit\"
      >Nouveau produit</a>
          <a class=\"dropdown-item quick-row-link \"
         href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=501ee0e24d72c604d41c0506ab184834\"
                 data-item=\"Nouvelle catégorie\"
      >Nouvelle catégorie</a>
        <div class=\"dropdown-divider\"></div>
          <a id=\"quick-add-link\"
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"160\"
        data-icon=\"icon-AdminCatalog\"
        data-method=\"add\"
        data-url=\"index.php/sell/catalog/categories/2?-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"
        data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=d87c3e8206d0bd312400789f40832a81\"
        data-prompt-text=\"Veuillez nommer ce raccourci :\"
        data-link=\"Cat&eacute;gories - Liste\"
      >
        <i class=\"material-icons\">add_circle</i>
        Ajouter la page actuelle à l'accès rapide
      </a>
        <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=d87c3e8206d0bd312400789f40832a81\">
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
      actio";
        // line 180
        echo "n=\"/admin919wlkwpjawfriiadmx/index.php?controller=AdminSearch&amp;token=37923947359828116fcbfd88693074de\"
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
        <a cla";
        // line 197
        echo "ss=\"dropdown-item\" data-item=\"Paniers\" href=\"#\" data-value=\"5\" data-placeholder=\"ID panier\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Paniers</a>
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
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/orders?token=501ee0e24d72c604d41c0506ab184834\"
             data-item=\"Commandes\"
    >Commandes</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=0d32e0d114cacbe31f70aa514061046b\"
             data-item=\"Évaluation du catalogue\"
    >Évaluation du catalogue</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?token=501ee0e24d72c604d41c0506ab184834\"
             data-item=\"Modules installés\"
    >Modules installés</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=569950783e1c4bfa0a8569457beb440a\"
             data-item=\"Nouveau bon de réduction\"
    >Nouv";
        // line 232
        echo "eau bon de réduction</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products-v2/create?token=501ee0e24d72c604d41c0506ab184834\"
             data-item=\"Nouveau produit\"
    >Nouveau produit</a>
      <a class=\"dropdown-item quick-row-link\"
       href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?token=501ee0e24d72c604d41c0506ab184834\"
             data-item=\"Nouvelle catégorie\"
    >Nouvelle catégorie</a>
    <div class=\"dropdown-divider\"></div>
      <a id=\"quick-add-link\"
      class=\"dropdown-item js-quick-link\"
      href=\"#\"
      data-rand=\"19\"
      data-icon=\"icon-AdminCatalog\"
      data-method=\"add\"
      data-url=\"index.php/sell/catalog/categories/2?-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"
      data-post-link=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=d87c3e8206d0bd312400789f40832a81\"
      data-prompt-text=\"Veuillez nommer ce raccourci :\"
      data-link=\"Cat&eacute;gories - Liste\"
    >
      <i class=\"material-icons\">add_circle</i>
      Ajouter la page actuelle à l'accès rapide
    </a>
    <a id=\"quick-manage-link\" class=\"dropdown-item\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminQuickAccesses&token=d87c3e8206d0bd312400789f40832a81\">
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
                     ";
        // line 277
        echo "     <div class=\"component header-right-component\" id=\"header-notifications-container\">
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
              Avez-vous consu";
        // line 329
        echo "lté vos <strong><a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&action=filterOnlyAbandonedCarts&token=0728597e44d71b4096e2dfa943e69321\">paniers abandonnés</a></strong> ?<br> Votre prochaine commande s'y trouve peut-être !
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
";
        // line 373
        echo "  </script>
          </div>
        
        <div class=\"component\" id=\"header-employee-container\">
          <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      <div class=\"employee-top\">
        <span class=\"employee-avatar\"><img class=\"avatar rounded-circle\" src=\"https://www.automotoboutic.com/img/pr/default.jpg\" alt=\"Nassim\" /></span>
        <span class=\"employee_profile\">Ravi de vous revoir Nassim</span>
      </div>

      <a class=\"dropdown-item employee-link profile-link\" href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/1/edit?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\">
      <i class=\"material-icons\">edit</i>
      <span>Votre profil</span>
    </a>
    </div>

    <p class=\"divider\"></p>

    
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLogin&amp;logout=1&amp;token=0cd15a8709deb5425554da0d64bd0532\">
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
  <span class=\"menu-collapse\" data-toggle-url=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/toggle-navigation?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\">
    <i class=\"material-icons rtl-flip\">chevron_left</i>
    <i class=\"material-icons rtl-flip\">chevron_left</i>
  </span>

  <div class=\"nav-bar-overflow\">
      <div class=\"logo-container\">
          <a id=\"header_logo\" class=\"logo float-left\" href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8c04ecbfa406fe9c5f30e8bf6d646ff7\"></a>
          ";
        // line 417
        echo "<span id=\"shop_version\" class=\"header-version\">8.1.7</span>
      </div>

      <ul class=\"main-menu\">
              
                    
                    
          
            <li class=\"link-levelone\" data-submenu=\"1\" id=\"tab-AdminDashboard\">
              <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8c04ecbfa406fe9c5f30e8bf6d646ff7\" class=\"link\" >
                <i class=\"material-icons\">trending_up</i> <span>Tableau de bord</span>
              </a>
            </li>

          
                      
                                          
                    
          
            <li class=\"category-title link-active\" data-submenu=\"2\" id=\"tab-SELL\">
                <span class=\"title\">Vendre</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
";
        // line 459
        echo "                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/invoices/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Factures
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/credit-slips/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Avoirs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/orders/delivery-slips/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Bons de livraison
                                </a>
                              </li>

                                                                                  
                              
                                                            ";
        // line 489
        echo "
                              <li class=\"link-leveltwo\" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarts&amp;token=0728597e44d71b4096e2dfa943e69321\" class=\"link\"> Paniers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"159\" id=\"subtab-AdminExport\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminExport&amp;token=12610b55593557cb833d64966a0f4c9d\" class=\"link\"> Ordre de fabrication
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                                                          
                  <li class=\"link-levelone has_submenu link-active open ul-open\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
                      <i class=\"material-icons mi-store\">store</i>
                      <span>
                      Catalogue
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_up
                                                            </i>
                                            </a>
                                    ";
        // line 519
        echo "          <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/products?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo link-active\" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Catégories
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/monitoring/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Suivi
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwp";
        // line 548
        echo "jawfriiadmx/index.php?controller=AdminAttributesGroups&amp;token=ddfbc2419ec18f50ad239a6c056b2c75\" class=\"link\"> Attributs &amp; caractéristiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/brands/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Marques et fournisseurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/attachments/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Fichiers
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCartRules&amp;token=569950783e1c4bfa0a8569457beb440a\" class=\"link\"> Réductions
                                </a>
                              </li>

                                                                                  
                              
                                 ";
        // line 578
        echo "                           
                              <li class=\"link-leveltwo\" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/stocks/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Stock
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"132\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=018f8d34818052c2a4e757482d13f8ed\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"161\" id=\"subtab-AdminLm_SurmesureCustomCarpets\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminLm_SurmesureCustomArticles&amp;token=018f8d34818052c2a4e757482d13f8ed\" class=\"link\"> Tapis sur mesure
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/";
        // line 607
        echo "sell/customers/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customers/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Clients
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/addresses/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Adresses
                                </a>
                              </li>

                                                                                                                                    </ul>
                                        </li>
                                              
                  
                                                      
                  
                ";
        // line 639
        echo "  <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2962ee707f50fe89023491e9b17481d0\" class=\"link\">
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
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCustomerThreads&amp;token=2962ee707f50fe89023491e9b17481d0\" class=\"link\"> SAV
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/customer-service/order-messages/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Messages prédéfinis
                                </a>
                              </li>

                                                                                  
  ";
        // line 667
        echo "                            
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminReturn&amp;token=639ea8f8f937fb4d8f5dcaf6eec1ce42\" class=\"link\"> Retours produits
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStats&amp;token=0d32e0d114cacbe31f70aa514061046b\" class=\"link\">
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

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"38\"";
        // line 705
        echo " id=\"subtab-AdminParentModulesSf\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/modules/manage?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Gestionnaire de modules 
                                </a>
                              </li>

                                                                                                                                        
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"152\" id=\"subtab-AdminStoreCommander\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminStoreCommander&amp;token=5842615f6c23b3f61094d54e15182556\" class=\"link\"> Store Commander
                                </a>
                              </li>

                                                                                  
                              
 ";
        // line 734
        echo "                                                           
                              <li class=\"link-leveltwo\" data-submenu=\"158\" id=\"subtab-AdminEcOrdersAndStock\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminEcOrdersAndStock&amp;token=f8d91f491a408812dc53a99fb06859e9\" class=\"link\"> Ec Order And Stock
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"43\" id=\"subtab-AdminParentThemes\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/themes/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Thème et logo
           ";
        // line 762
        echo "                     </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"45\" id=\"subtab-AdminParentMailTheme\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/mail_theme/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Thème d&#039;e-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"47\" id=\"subtab-AdminCmsContent\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/cms-pages/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Pages
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"48\" id=\"subtab-AdminModulesPositions\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/design/modules/positions/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Positions
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"49\" id=\"subtab-AdminImages\">
                                <a href=\"ht";
        // line 793
        echo "tps://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminImages&amp;token=42dc81a7833357e3bf16b22b2ef2fef5\" class=\"link\"> Images
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"118\" id=\"subtab-AdminLinkWidget\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/link-widget/list?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Liste de liens
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"50\" id=\"subtab-AdminParentShipping\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=5f2e898416b1778661724f53b444a8cd\" class=\"link\">
                      <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                      <span>
                      Livraison
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-50\" class=\"submenu panel-collapse\">
                                                      
                              
                          ";
        // line 824
        echo "                                  
                              <li class=\"link-leveltwo\" data-submenu=\"51\" id=\"subtab-AdminCarriers\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminCarriers&amp;token=5f2e898416b1778661724f53b444a8cd\" class=\"link\"> Transporteurs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"52\" id=\"subtab-AdminShipping\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/shipping/preferences/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"53\" id=\"subtab-AdminParentPayment\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
                      <i class=\"material-icons mi-payment\">payment</i>
                      <span>
                      Paiement
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=";
        // line 854
        echo "\"collapse-53\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"54\" id=\"subtab-AdminPayment\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/payment_methods?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Moyens de paiement
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"55\" id=\"subtab-AdminPaymentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/payment/preferences?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Préférences
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"56\" id=\"subtab-AdminInternational\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
                      <i class=\"material-icons mi-language\">language</i>
                      <span>
                      International
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
 ";
        // line 885
        echo "                                                           </i>
                                            </a>
                                              <ul id=\"collapse-56\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"57\" id=\"subtab-AdminParentLocalization\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/localization/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Localisation
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"62\" id=\"subtab-AdminParentCountries\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/zones/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Zones géographiques
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"66\" id=\"subtab-AdminParentTaxes\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/taxes/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Taxes
                                </a>
                              </li>

                                                                                  
                              
                                                            
        ";
        // line 915
        echo "                      <li class=\"link-leveltwo\" data-submenu=\"69\" id=\"subtab-AdminTranslations\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/improve/international/translations/settings?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Traductions
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"70\" id=\"tab-CONFIGURE\">
                <span class=\"title\">Configurer</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"71\" id=\"subtab-ShopParameters\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Paramètres de la boutique
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                              <ul id=\"collapse-71\" class=\"submenu panel-collapse\">
                                                      
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"72\" id=\"subtab-AdminP";
        // line 950
        echo "arentPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/preferences/preferences?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Paramètres généraux
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"75\" id=\"subtab-AdminParentOrderPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/order-preferences/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Commandes
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"78\" id=\"subtab-AdminPPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/product-preferences/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Produits
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"79\" id=\"subtab-AdminParentCustomerPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/customer-preferences/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Clients
                                </a>
                              </li>

                                                      ";
        // line 979
        echo "                            
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"83\" id=\"subtab-AdminParentStores\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/contacts/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Contact
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"86\" id=\"subtab-AdminParentMeta\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/shop/seo-urls/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Trafic et SEO
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"89\" id=\"subtab-AdminParentSearchConf\">
                                <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSearchConf&amp;token=d0980883391fb061b0867f27cf25ba1c\" class=\"link\"> Rechercher
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone has_submenu\" data-submenu=\"92\" id=\"subtab-AdminAdvancedParameters\">
                    <a href=\"/admin919wlkwpjawfr";
        // line 1010
        echo "iiadmx/index.php/configure/advanced/system-information/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/system-information/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Informations
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"94\" id=\"subtab-AdminPerformance\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/performance/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Performances
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-";
        // line 1039
        echo "leveltwo\" data-submenu=\"95\" id=\"subtab-AdminAdminPreferences\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/administration/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Administration
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"96\" id=\"subtab-AdminEmails\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/emails/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> E-mail
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"97\" id=\"subtab-AdminImport\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/import/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Importer
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"98\" id=\"subtab-AdminParentEmployees\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/employees/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Équipe
                                </a>
                              </li>

                                                                                ";
        // line 1068
        echo "  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"102\" id=\"subtab-AdminParentRequestSql\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/sql-requests/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Base de données
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"105\" id=\"subtab-AdminLogs\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/logs/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Logs
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"106\" id=\"subtab-AdminWebservice\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/webservice-keys/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Webservice
                                </a>
                              </li>

                                                                                                                                                                                                                                                    
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"110\" id=\"subtab-AdminFeatureFlag\">
                       ";
        // line 1096
        echo "         <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/feature-flags/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Fonctionnalités nouvelles et expérimentales
                                </a>
                              </li>

                                                                                  
                              
                                                            
                              <li class=\"link-leveltwo\" data-submenu=\"111\" id=\"subtab-AdminParentSecurity\">
                                <a href=\"/admin919wlkwpjawfriiadmx/index.php/configure/advanced/security/?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\"> Security
                                </a>
                              </li>

                                                                              </ul>
                                        </li>
                              
          
                      
                                          
                    
          
            <li class=\"category-title\" data-submenu=\"153\" id=\"tab-Sellermania\">
                <span class=\"title\">Sellermania</span>
            </li>

                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"154\" id=\"subtab-AdminSellermaniaSettings\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaSettings&amp;token=80ceb6439bb184102734254040f5e982\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                                                    <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
";
        // line 1132
        echo "                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"155\" id=\"subtab-AdminSellermaniaDiagnostics\">
                    <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminSellermaniaDiagnostics&amp;token=7d391749a87310f58f73ba4e43bc3f8f\" class=\"link\">
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
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/configuration?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
                      <i class=\"material-icons mi-settings\">settings</i>
                      <span>
                      Configuration
                      </span>
                      ";
        // line 1170
        echo "                              <i class=\"material-icons sub-tabs-arrow\">
                                                                    keyboard_arrow_down
                                                            </i>
                                            </a>
                                        </li>
                                              
                  
                                                      
                  
                  <li class=\"link-levelone\" data-submenu=\"173\" id=\"subtab-TransactionGridController\">
                    <a href=\"/admin919wlkwpjawfriiadmx/index.php/modules/nhbusinesscentral/transactiongrid?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" class=\"link\">
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
              <a href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\" aria-current=\"page\">Catégories</a>
            </li>
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Catégories          </h1>
      

      
        <div class=\"toolbar-icons\"";
        // line 1221
        echo ">
          <div class=\"wrapper\">
            
                                                          <a
                  class=\"btn btn-primary pointer\"                  id=\"page-header-desc-configuration-add\"
                  href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?id_parent=2&amp;_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"                  title=\"Ajouter une catégorie\"                                  >
                  <i class=\"material-icons\">add_circle_outline</i>                  Ajouter une catégorie
                </a>
                                      
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Aide\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminCategories%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"
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
              class=\"btn btn-floating-item   pointer\"              id=\"page-header-desc-floating-configuration-add\"
              href=\"/admin919wlkwpjawfriiadmx/index.php/sell/catalog/categories/new?id_parent=2&amp;_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"              title=\"Ajouter une catégorie\"            >
              Ajo";
        // line 1259
        echo "uter une catégorie
              <i class=\"material-icons\">add_circle_outline</i>            </a>
                  
                              <a class=\"btn btn-floating-item btn-help btn-sidebar\" href=\"#\"
               title=\"Aide\"
               data-toggle=\"sidebar\"
               data-target=\"#right-sidebar\"
               data-url=\"/admin919wlkwpjawfriiadmx/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop-project.org%252Ffr%252Fdoc%252FAdminCategories%253Fversion%253D8.1.7%2526country%253Dfr/Aide?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA\"
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
  <a href=\"https://www.automotoboutic.com/admin919wlkwpjawfriiadmx/index.php?controller=AdminDashboard&amp;token=8c04ecbfa406fe9c5f30e8bf6d646ff7\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons rtl-flip\">arrow_back</i>
    Précédent
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    <script type=\"text/javascript\">
  \$(document).ready(function(){
\$(\"#category_grid_panel\").before(\"<div id=\\\"sc_message\\\" class=\\\"alert alert-info\\\"><p><b>Store Commander<\\/b> est install\\u00e9 sur votre boutique. Saviez-vous que vous pouvez :<\\/p>\\n                        <ul style=\\\"list-style-type: disc;padding-left:40px\\\">\\n                        <li>Trouver et modifier en masse toutes les informations de vos produits<\\/li>\\n                        <li>Exporter et r\\u00e9-importer tout votre catalogue ou une s\\u00e9lection de produits<\\/li>\\n                        <li>Pr\\u00e9parer les p\\u00e9riodes de soldes et promotions vitesse turbo, en contr\\u00f4lant vos marges<\\/li>\\n                        <li>Am\\u00e9liorer tous les crit\\u00e8res SEO de chaque fiche produit : meta tags, taille des meta tags...<\\/li>\\n                        <li>Identifier et corriger des centaines de probl\\u00e8mes avec le menu Outils > FixMyPrestaShop<\\/li>\\n                        <\\/ul><br\\/>\\n                        <p>D\\u00e9marrez Store Commander pour g\\u00e9rer votre catalogue plus efficacement, en <b><a href=\\\"https:\\/\\/www.automotoboutic.com\\/admin919wlkwpjawfriiadmx\\/index.php?controller=AdminStoreCommander&token=5842615f6c23b3f61094d54e15182556\\\">cliquant ici<\\/a><\\/b> ou depuis le ";
        // line 1315
        echo "menu Module > Store Commander<\\/p>\\n                        <p style=\\\"text-align:right\\\"><a href=\\\"\\/admin919wlkwpjawfriiadmx\\/index.php\\/sell\\/catalog\\/categories?_token=s6-HxWdPIn9D6py72n8r6_7JdyaCn8E5HqDe9NKQNvA&sc_no_msg_catalog=1\\\">Ne plus afficher ce message<\\/a><\\/p><\\/a><\\/div>\");

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
        return "__string_template__84074326ff2ddbae2f2ca4cca534c126";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1502 => 1327,  1481 => 1287,  1470 => 107,  1461 => 1327,  1447 => 1315,  1413 => 1287,  1383 => 1259,  1343 => 1221,  1290 => 1170,  1250 => 1132,  1212 => 1096,  1182 => 1068,  1151 => 1039,  1120 => 1010,  1087 => 979,  1056 => 950,  1019 => 915,  987 => 885,  954 => 854,  922 => 824,  889 => 793,  856 => 762,  826 => 734,  795 => 705,  755 => 667,  725 => 639,  691 => 607,  660 => 578,  628 => 548,  597 => 519,  565 => 489,  533 => 459,  489 => 417,  443 => 373,  397 => 329,  343 => 277,  296 => 232,  259 => 197,  240 => 180,  199 => 141,  160 => 107,  148 => 97,  117 => 68,  87 => 40,  46 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__84074326ff2ddbae2f2ca4cca534c126", "");
    }
}
