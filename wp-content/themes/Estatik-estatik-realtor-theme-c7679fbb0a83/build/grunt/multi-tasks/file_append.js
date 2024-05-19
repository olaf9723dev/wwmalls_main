'use strict';

var date = new Date();

module.exports = {
    deploy: {
        files: [
            {
                append: date + " Site deploy to <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    push_app: {
        files: [
            {
                append: date + " App deploy to <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    push_db: {
        files: [
            {
                append: date + " DB deploy to <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    pull_db: {
        files: [
            {
                append: date + " Get DB <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    dump_db: {
        files: [
            {
                append: date + " Dump DB <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    theme: {
        files: [
            {
                append: date + " DB deploy to <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    },
    plugins: {
        files: [
            {
                append: date + " Plugins deploy to <%= grunt.task.current.args[0] %> <%= grunt.task.current.args[1] %>\n",
                input: 'grunt/deploy.log'
            }
        ]
    }
};
