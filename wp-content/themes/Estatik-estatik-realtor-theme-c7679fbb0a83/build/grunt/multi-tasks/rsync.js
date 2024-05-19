'use strict';

module.exports =  {
    // Deploy
    deploy: {
        options: {
            args: ["-avzu","--progress", "--verbose", "--delete"],
            exclude: ["README.md","node_modules","composer.json","composer.lock","phpcs.xml","wp-cli.yml",".DS_Store","deploy",".git*","web/content/themes/*/build","web/content/plugins/*/build"],
            recursive: true,
            src: "../../../../../",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        }
    },
    deploy_local: {
        options: {
            args: ["-avzu","--progress", "--verbose", "--delete"],
            exclude: ["README.md","node_modules","composer.json","composer.lock","phpcs.xml","wp-cli.yml",".DS_Store","node_modules","deploy",".git*","web/content/uploads/","web/content/themes/*/build"],
            recursive: true,
            src: "../../../../../",
            dest: '<%= grunt.task.current.args[2] %>',
        }
    },

    // App
    push_app: {
        options: {
            args: ["-avzu","--progress", "--verbose", "--delete"],
            recursive: true,
            src: "../../../../wp/",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        }
    },

    // Theme
    push_theme: {
        options: {
            args: ["-avzu","--progress", "--verbose", "--delete"],
            exclude: [".DS_Store",".git*","build"],
            recursive: true,
            src: "../../<%= pkg.name %>",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        }
    },

    // Plugins
    push_plugins: {
        options: {
            args: ["-avzu","--progress", "--verbose", "--delete"],
            exclude: [".DS_Store",".git*","build"],
            recursive: true,
            src: "../../../plugins/",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        }
    },

    // Env Variables
    push_env: {
        options: {
            args: ["-avz","--progress", "--verbose"],
            recursive: true,
            src: "../../../../../deploy/<%= grunt.task.current.args[3] %>/",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        }
    },
    push_env_local: {
        options: {
            args: ["-avz","--progress", "--verbose"],
            recursive: true,
            src: "../../../../../deploy/<%= grunt.task.current.args[3] %>/",
            dest: '<%= grunt.task.current.args[2] %>',
        }
    },

    // Uploads
    pull_uploads: {
        options: {
            exclude: [".git*",".DS_Store"],
            args: ["-e ssh","--recursive","-l","--progress", "--verbose"],
            src: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>:<%= grunt.task.current.args[2] %>uploads/',
            dest: "<%= local_uploads %>",
        },
    },
    push_uploads: {
        options: {
            exclude: [".git*",".DS_Store"],
            args: ["-l","--progress", "--verbose"],
            recursive: true,
            src: "<%= local_uploads %>",
            dest: '<%= grunt.task.current.args[2] %>',
            host: '<%= grunt.task.current.args[1] %>@<%= grunt.task.current.args[0] %>',
        },
    }
};
