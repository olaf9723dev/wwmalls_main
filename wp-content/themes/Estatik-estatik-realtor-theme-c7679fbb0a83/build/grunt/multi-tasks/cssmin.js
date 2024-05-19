'use strict';

module.exports = {
    options: {
        keepSpecialComments: 0,
    },
    plugin: {
        files: [{
            expand: true,
            cwd: '<%= path %>/css',
            src: ['style.css'],
            dest: '<%= path %>/css',
            ext: '-min.css',
        }]
    },
};
