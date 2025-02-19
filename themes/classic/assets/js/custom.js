function onRecaptchaSuccess() {
    // This function is called when user successfully completes the captcha
    console.log("Sucessfully completed captcha");

    // Enable your submit button
    document.getElementById("submitMessage").disabled = false;
}

$(document).ready(function () {
    const searchWidget = document.getElementById("search_widget");

    searchWidget.addEventListener("focusin", function () {
        searchWidget.classList.toggle("search_widget_active");
    });

    searchWidget.addEventListener("focusout", function () {
        searchWidget.classList.toggle("search_widget_active");
    });

    function hideAllPanelsDesktop() {
        document
            .querySelectorAll(
                "#_desktop_top_menu>.top-menu>.category>.dropdown-item"
            )
            .forEach((button) => {
                button.classList.remove("top-menu-active-category");
            });

        document
            .querySelectorAll(
                "#_desktop_top_menu>.top-menu>.category>.sub-menu"
            )
            .forEach((panel) => {
                panel.classList.remove("collapse");
                panel.classList.remove("sub-menu-active");
            });
    }

    function hideAllPanelsMobile() {
        document
            .querySelectorAll(
                "#_mobile_top_menu>.top-menu>.category>.dropdown-item"
            )
            .forEach((button) => {
                button.classList.remove("top-menu-active-category");
            });

        document
            .querySelectorAll("#_mobile_top_menu>.top-menu>.category>.sub-menu")
            .forEach((panel) => {
                panel.classList.remove("collapse");
                panel.classList.remove("sub-menu-active");
            });
    }

    document
        .querySelectorAll(
            "#_desktop_top_menu>.top-menu>.category>.dropdown-item"
        )
        .forEach((button) => {
            button.addEventListener("click", function () {
                // Remove active class from all buttons
                document.querySelectorAll(".dropdown-item").forEach((btn) => {
                    btn.classList.remove("top-menu-active-category");
                });

                // Add active class to clicked button
                this.classList.add("top-menu-active-category");

                // Show target panel
                const targetPanel = document.getElementById(
                    this.dataset.target
                );

                // if sub menu is open, close it
                let arrow = this.querySelector(".top-menu-arrow");
                if (targetPanel.classList.contains("sub-menu-active")) {
                    targetPanel.classList.remove("sub-menu-active");
                    targetPanel.classList.add("collapse");
                    arrow.classList.remove("rotate-180");

                    console.log("close");
                } else {
                    console.log("open");
                    hideAllPanelsDesktop();
                    targetPanel.classList.remove("collapse");
                    targetPanel.classList.add("sub-menu-active");
                    // rotate the arrow
                    arrow.classList.add("rotate-180");
                }
            });
        });

    document.getElementById("wrapper").addEventListener("click", function (e) {
        hideAllPanelsDesktop();
        hideAllPanelsMobile();
    });

    document
        .querySelectorAll(
            "#_mobile_top_menu>.top-menu>.category>.dropdown-item"
        )
        .forEach((button) => {
            button.addEventListener("click", function () {
                // Remove active class from all buttons
                document.querySelectorAll(".dropdown-item").forEach((btn) => {
                    btn.classList.remove("top-menu-active-category");
                });

                // Add active class to clicked button
                this.classList.add("top-menu-active-category");

                // Show target panel
                const targetPanel = document.getElementById(
                    this.dataset.target
                );

                // if sub menu is open, close it
                let arrow = this.querySelector(".top-menu-arrow");
                if (targetPanel.classList.contains("sub-menu-active")) {
                    targetPanel.classList.remove("sub-menu-active");
                    targetPanel.classList.add("collapse");
                    arrow.classList.remove("rotate-180");

                    console.log("close");
                } else {
                    console.log("open");
                    hideAllPanelsMobile();
                    targetPanel.classList.remove("collapse");
                    targetPanel.classList.add("sub-menu-active");
                    // rotate the arrow
                    arrow.classList.add("rotate-180");
                }
            });
        });
});
