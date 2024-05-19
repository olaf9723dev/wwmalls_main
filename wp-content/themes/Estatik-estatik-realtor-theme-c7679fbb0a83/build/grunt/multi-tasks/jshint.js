'use strict';

module.exports = {
    options: {
        // more options here if you want to override JSHint defaults
        globals: {
            jQuery: true
        },
        // 'esnext': true,
    },
    // test the source javascript
    src: [
        'javascripts/main.js',
    ],
    dest: ['<%= path %>/js/scripts.js'], // test the distributed javascript
    grunt: ['Gruntfile.js'], // test the gruntfile itself
};
