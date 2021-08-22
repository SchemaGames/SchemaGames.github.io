const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: './schemapp.js',
  output: {
    filename: 'schemapp.min.js',
    path: path.resolve(__dirname),
  },
  mode: 'production',
  devtool: 'source-map',
  module: {
    rules: [
      {
        test: [/deps\/.*\.js$/, /scripts\/.*\.js$/],
        use: [ 'script-loader' ]
      },
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
    ],
  },
};