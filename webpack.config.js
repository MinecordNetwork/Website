const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('./public/js/build')
    .setPublicPath('/public/js/build')
    .addEntry('bundle', './public/js/main.js')
    .addEntry('admin', './public/js/admin.js')
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .autoProvidejQuery();

module.exports = Encore.getWebpackConfig();
