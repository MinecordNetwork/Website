const path = require('path');

module.exports = {
    entry: './public/js/main.js',
    mode: 'production',
    resolve: {
        extensions: ['*', '.js', '.jsx']
    },
    output: {
        filename: 'bundle.js',
        path: path.join(__dirname, 'public', 'js', 'build'),
    },
};
