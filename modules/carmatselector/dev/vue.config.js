const path = require("path");

module.exports = {
    chainWebpack: (config) => {
        // Stop generating the HTML page
        config.plugins.delete("html");
        config.plugins.delete("preload");
        config.plugins.delete("prefetch");

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
            filename: "app.js",
        },
    },
};
