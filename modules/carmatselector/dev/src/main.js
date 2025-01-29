import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";

console.log("Vue app starting to mount");
const app = createApp(App);
app.use(createPinia());

// Add error handler
app.config.errorHandler = (err, vm, info) => {
    console.error("Vue Error:", err);
    console.error("Error Info:", info);
};

// Add warning handler
app.config.warnHandler = (msg, vm, trace) => {
    console.warn("Vue Warning:", msg);
    console.warn("Trace:", trace);
};

// Mount with check
const mountPoint = document.getElementById("carmat-app");
if (mountPoint) {
    app.mount("#carmat-app");
    console.log("Vue app mounted successfully");
} else {
    console.error("Mount point #carmat-app not found!");
}
