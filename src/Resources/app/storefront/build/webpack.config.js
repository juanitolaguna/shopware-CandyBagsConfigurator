const {resolve, join} = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

console.log('Custom CandyBags config');

module.exports = ({config}) => {
    console.log(config.module.rules);
    return {
        mode: 'production',
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                }
            ]
        },
        plugins: [
            new VueLoaderPlugin()
        ],
        resolveLoader: {
            // alias: {
            //     'vue-loader': resolve(join(__dirname, '..', 'node_modules', 'vue-loader'))
            // },
            modules: [
                resolve(join(__dirname, '../', 'node_modules')),
                resolve(join(__dirname, '../../../../../../../../', 'vendor/shopware/storefront/Resources/app/storefront/node_modules')),
            ]
        },
        resolve: {
            alias: {
                'vue': resolve(join(__dirname, '..', 'node_modules', 'vue')),
                'v-lazy-image': resolve(join(__dirname, '..', 'node_modules', 'v-lazy-image'))
            }
        }
    };
}
