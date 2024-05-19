'use strict';

module.exports = {
    options: {
        backups: 'db_backup',
        environments: {
            local: {
                title: 'local',
                db: {
                    host: env.local.db_host,
                    name: env.local.db_name,
                    user: env.local.db_user,
                    pass: env.local.db_pass,
                    type: 'mysql'
                }
            },
            staging: {
                title: 'staging',
                db: {
                    host: env.staging.db_host,
                    name: env.staging.db_name,
                    user: env.staging.db_user,
                    pass: env.staging.db_pass,
                    type: 'mysql'
                },
                ssh: {
                    host: env.staging.host,
                    user: env.staging.user
                }
            },
            dev: {
                title: 'dev',
                db: {
                    host: env.dev.db_host,
                    name: env.dev.db_name,
                    user: env.dev.db_user,
                    pass: env.dev.db_pass,
                    type: 'mysql'
                },
                ssh: {
                    host: env.dev.host,
                    user: env.dev.user
                }
            },
            live: {
                title: 'live',
                db: {
                    host: env.live.db_host,
                    name: env.live.db_name,
                    user: env.live.db_user,
                    pass: env.live.db_pass,
                    type: 'mysql'
                },
                ssh: {
                    host: env.live.host,
                    user: env.live.user
                }
            }
        }
    },
    'pull-staging': { action: 'pull', source: 'staging' },
    'pull-local': { action: 'pull', source: 'local' },
    'pull-dev': { action: 'pull', source: 'dev' },
    'pull-live': { action: 'pull', source: 'live' },
    'push-staging-to-local': { action: 'push', source: 'staging', target: 'local' },
    'push-dev-to-local': { action: 'push', source: 'dev', target: 'local' },
    'push-live-to-local': { action: 'push', source: 'live', target: 'local' },
    'push-local-to-staging': { action: 'push', source: 'local', target: 'staging' },
    'push-local-to-dev': { action: 'push', source: 'local', target: 'dev' },
    'push-local-to-live': { action: 'push', source: 'local', target: 'live' }
};
