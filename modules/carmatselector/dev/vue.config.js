const path = require("path");

module.exports = {
    pages: {
        front: {
            entry: "src/front/main.js",
            // Remove template: null
        },
        admin: {
            entry: "src/admin/main.js",
            // Remove template: null
        },
    },
    chainWebpack: (config) => {
        // Stop generating the HTML page
        config.plugins.delete("html-front"); // Changed from "html"
        config.plugins.delete("html-admin"); // Add this line
        config.plugins.delete("preload-front"); // Changed from "preload"
        config.plugins.delete("preload-admin"); // Add this line
        config.plugins.delete("prefetch-front"); // Changed from "prefetch"
        config.plugins.delete("prefetch-admin"); // Add this line

        // Allow resolving images in the subfolder src/assets/
        config.resolve.alias.set("@", path.resolve(__dirname, "src"));

        // Modify the chunk configuration
        config.optimization.splitChunks(false);
    },
    css: {
        extract: false,
    },
    runtimeCompiler: true,
    productionSourceMap: false,
    filenameHashing: false,
    outputDir: "../views/js",
    assetsDir: "",
    publicPath: "../modules/carmatselector/views/js",
    configureWebpack: {
        optimization: {
            splitChunks: false,
            minimize: true,
        },
        output: {
            filename: "[name].js",
            chunkFilename: "[name].js",
        },
    },
};
