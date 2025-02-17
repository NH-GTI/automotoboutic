/**
 * All config. options available here:
 * https://cookieconsent.orestbida.com/reference/configuration-reference.html
 */
import "https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.1.0/dist/cookieconsent.umd.js";

CookieConsent.run({
    categories: {
        necessary: {
            enabled: true, // this category is enabled by default
            readOnly: true, // this category cannot be disabled
        },
        analytics: {},
    },

    language: {
        default: "fr",
        translations: {
            en: {
                consentModal: {
                    title: "We use cookies",
                    description: "Cookie modal description",
                    acceptAllBtn: "Accept all",
                    acceptNecessaryBtn: "Reject all",
                    showPreferencesBtn: "Manage Individual preferences",
                },
                preferencesModal: {
                    title: "Manage cookie preferences",
                    acceptAllBtn: "Accept all",
                    acceptNecessaryBtn: "Reject all",
                    savePreferencesBtn: "Accept current selection",
                    closeIconLabel: "Close modal",
                    sections: [
                        {
                            title: "Somebody said ... cookies?",
                            description: "I want one!",
                        },
                        {
                            title: "Strictly Necessary cookies",
                            description:
                                "These cookies are essential for the proper functioning of the website and cannot be disabled.",

                            //this field will generate a toggle linked to the 'necessary' category
                            linkedCategory: "necessary",
                        },
                        {
                            title: "Performance and Analytics",
                            description:
                                "These cookies collect information about how you use our website. All of the data is anonymized and cannot be used to identify you.",
                            linkedCategory: "analytics",
                        },
                        {
                            title: "More information",
                            description:
                                'For any queries in relation to my policy on cookies and your choices, please <a href="#contact-page">contact us</a>',
                        },
                    ],
                },
            },
            fr: {
                consentModal: {
                    title: "Les cookies, à vous de choisir !",
                    description:
                        'A l\'exception des Cookies essentiels au fonctionnement du site, Automotoboutic vous laisse le choix : vous pouvez accepter tous ces Cookies en cliquant sur le bouton "Accepter et continuer", choisir les Cookies qui vous intéressent en cliquant sur le lien "Personnaliser", ou refuser tous les Cookies en cliquant sur le lien "Continuer sans accepter". Vous pourrez mettre à jour votre choix à tout moment en cliquant sur le lien Politique Cookies en bas de notre site.',
                    acceptAllBtn: "Accepter et continuer",
                    acceptNecessaryBtn: "Continuer sans accepter",
                    showPreferencesBtn: "Personnaliser",
                },
                preferencesModal: {
                    title: "Paramétrer les cookies",
                    acceptAllBtn: "Tout accepter",
                    acceptNecessaryBtn: "Tout refuser",
                    savePreferencesBtn: "Valider mes choix",
                    closeIconLabel: "Fermer la fenêtre",
                    sections: [
                        {
                            title: "Cookies ? Qu'est-ce que c'est ?",
                            description:
                                "Lors de la consultation de notre site Automotoboutic.com, des cookies (petits fichiers texte) sont déposés sur votre ordinateur, votre mobile ou votre tablette, et lus lors de votre navigation sur Internet. Automotoboutic.com vous laisse la possibilité d’accepter ou non l’installation sur votre appareil des cookies en fonction de l’usage qui en est fait, à l’exception des cookies essentiels qui sont indispensables au bon fonctionnement du site.Merci de choisir les catégories de cookie dont vous acceptez l’installation, et de cliquer sur le bouton « valider mes choix » en bas de page",
                        },
                        {
                            title: "Cookies nécessaires",
                            description:
                                "Ces cookies sont essentiels pour le bon fonctionnement du site et ne peuvent pas être désactivés.",

                            //this field will generate a toggle linked to the 'necessary' category
                            linkedCategory: "necessary",
                        },
                        {
                            title: "Performance et fonctionnement",
                            description:
                                "Ces cookies collectes des informations sur le fonctionnement du site. Toutes les données sont anonymisées et ne peuvent pas être utilisées pour vous identifier.",
                            linkedCategory: "analytics",
                        },
                        {
                            title: "Plus d'information",
                            description:
                                'Pour toute demande, n\'hésitez à nous contacter via <a href="/nous-contacter">ce formulaire</a>',
                        },
                    ],
                },
            },
        },
    },
});
