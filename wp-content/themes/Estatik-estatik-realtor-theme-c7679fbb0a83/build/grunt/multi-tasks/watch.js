'use strict';

module.exports = {
    options: {
        spawn: false // add spawn option in watch task
    },
    grunt: {
        files: ['Gruntfile.js'],
        tasks: ['jshint:grunt'],
    },
    plugin: {
        files: ['stylesheets/**/*'],
        tasks: ['sass:plugin','postcss:plugin','cssmin:plugin'],
    },
    scripts: {
        files: [
            'javascripts/main.js',
        ],
        tasks: ['jshint:src','concat:scripts','uglify:scripts'],
    },
};
