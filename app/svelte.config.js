const sveltePreprocess = require("svelte-preprocess");

module.exports = {
    emitCss: true,
    hotReload: true,
    preprocess: sveltePreprocess({
        typescript: true,
        postcss: {configFilePath: __dirname},
    }),
};
