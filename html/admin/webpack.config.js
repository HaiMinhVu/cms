const path = require('path');
const webpack = require('webpack');
const config = require('./config.json');

// include the js minification plugin
// const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

// include the css extraction and minification plugins
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const VueLoaderPlugin = require('vue-loader/lib/plugin');



module.exports = env => {
    return {
        entry: ['./app/src/js/main.js', './app/src/css/main.scss'],
        output: {
            filename: './assets/dist/main.js',
            path: path.resolve(__dirname)
        },
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js'
            }
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: "babel-loader",
                        options: {
                            // presets: ['babel-preset-env'],
                            plugins: ["@babel/plugin-proposal-object-rest-spread"]
                        },
                    }
                },
                {
                    test: /\.(sass|scss)$/,
                    use: [
                        {
                            loader: MiniCssExtractPlugin.loader
                        },
                        {
                            loader: 'css-loader',
                            options: {
                                importLoaders: 2,
                                sourceMap: true
                            }
                        },
                        {
                            loader: 'sass-loader'
                        }
                    ]
                },
                {
                    test: /\.css$/i,
                    use: [
                        {
                            loader: 'style-loader',
                        },
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true,
                            }
                        },

                    ]
                },
                {
                    test: /\.(png|woff|woff2|eot|ttf|svg|gif)$/,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                              name: '[name].[ext]',
                              outputPath: 'static/assets/',
                              publicPath: 'assets/',
                              postTransformPublicPath: (p) => `__webpack_public_path__ + ${p}`,
                            }
                        }
                    ]
                },
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                }
            ]
        },
        plugins: [
            new webpack.SourceMapDevToolPlugin({}),
            new MiniCssExtractPlugin({
                filename: './assets/dist/main.css'
            }),
            new VueLoaderPlugin(),
            new webpack.DefinePlugin({
              "CONFIG": JSON.stringify(config[env.NODE_ENV])
              // 'process.env.NODE_ENV': JSON.stringify('test')
            })
        ],
        optimization: {
            minimizer: [
                // enable the js minification plugin
                // new UglifyJSPlugin({
                //     cache: true,
                //     parallel: true,
                //     uglifyOptions: {
                //         output: {
                //             comments: false,
                //         },
                //     }
                // }),
                new TerserPlugin({
                    parallel: true,
                    terserOptions: {
                        ecma: 6,
                    },
                }),
                // enable the css minification plugin
                new OptimizeCSSAssetsPlugin({})
            ]
        },
        node: {
           fs: "empty"
        }
    }
};
