'use strict';

module.exports = {
    options: {
        sourceMap: true
    },
    dist: {
        files: {
            '<%= path %>/css/style.css': 'stylesheets/style.scss', // outputs the front end theme
        }
    },
    plugin: {
        files: {
            '<%= path %>/css/style.css': 'stylesheets/style.scss', // outputs the front end theme
        }
    },
};
