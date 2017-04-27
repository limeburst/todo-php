var path = require('path');

module.exports = {
  entry: {
    user: './src/static/jsx/user.jsx',
    home: './src/static/jsx/home.jsx',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist')
  },
  module: {
    rules: [
      {
        include: [
          path.resolve(__dirname, "src/static/jsx")
        ],
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        }
      }
    ]
  }
};
