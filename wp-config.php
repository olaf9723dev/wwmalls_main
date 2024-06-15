<?php
define('WP_AUTO_UPDATE_CORE', 'minor');
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wwmalls_main_restore' );
/** MySQL database username */
define( 'DB_USER', 'wwmalls_wp134' );
/** MySQL database password */
define( 'DB_PASSWORD', 'p]UPPjjJ5E$r' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'eh4qedj7uac6gexpkcqy7wfb7vub1vkthrahfwfknvlbetoe2uqphb4blh8b0rao' );
define( 'SECURE_AUTH_KEY',  '9r6vzu9gkpah30snwqtc2imlmzphk63oyckvu7yffqu94snpfbg1ke6wbnolsgep' );
define( 'LOGGED_IN_KEY',    'qvtwkp1ifq77bycn2mdotqqzysiuysmkdzyjgwxvp6jwocir07orfljdmskgrhoo' );
define( 'NONCE_KEY',        'nffc9wpnyrlpotkurofg3lhia2eqp3gpwfesbv9uhoyj5qe7gusatwyc5wuc4fcs' );
define( 'AUTH_SALT',        'hzdotavc7vdqx4c8iv1sw3pysghl7decegxgeqdqavfg0bwra0u6prx1knvuvseb' );
define( 'SECURE_AUTH_SALT', '43er2izm0kjhyeje6rpkxid6wapq0y5zp2cngb14c5l8qeaqnroxpxllagfkhem6' );
define( 'LOGGED_IN_SALT',   'nbj9qso1g25oj0na6dkhvocz8ag9muvrqdxbzsyfzsxl8hzihtoidg2hk7xmjotz' );
define( 'NONCE_SALT',       'k1iyehmuxeeieuzuypgjdgny1aozjcedgz1rmutmpelr0z299rqfy6mgtd47pezg' );

define('COOKIE_DOMAIN', '.wwmalls.com');
define('COOKIE_PATH', '/');
define('COOKIEHASH', md5('wwmalls.com'));
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpt4_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';