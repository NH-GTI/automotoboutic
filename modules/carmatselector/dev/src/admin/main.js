import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";

const app = createApp(App);
app.use(createPinia());

// Add error handler
app.config.errorHandler = (err, vm, info) => {
    console.error("Vue Error:", err);
    console.error("Error Info:", info);
};

// Mount with check
const mountPoint = document.getElementById("carmat-admin-app");
if (mountPoint) {
    app.mount("#carmat-admin-app");
    console.log("Vue admin app mounted successfully");
} else {
    console.error("Mount point #carmat-admin-app not found!");
}
