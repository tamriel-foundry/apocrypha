<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache




/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tamrielfoundry_com');

/** MySQL database username */
define('DB_USER', 'tamrielfoundryco');

/** MySQL database password */
define('DB_PASSWORD', 'm*wcD^TM');

/** MySQL hostname */
define('DB_HOST', 'mysql.tamrielfoundry.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'y8eK?|kb^aR$Ix:?cg(%/`d1J9(V0d@7@iqUGyq*7(R1wSfk:|bw%NamPsQ&C1CJ');
define('SECURE_AUTH_KEY',  '%HN_y:gxp3NQTCEX+#nXi5"m*8Kb92(I!VeB0"/x^8|yx8mjSN&R~wAZoa)E~6EX');
define('LOGGED_IN_KEY',    'e2|am*qdhBTS7fZvfk@J5JDH#L"bVV4Ijbo;~RyG%SoCryX#W)J?OUQfwjxD6ma!');
define('NONCE_KEY',        'H~l~cURPunH&v*n`Njd8y1~4U"BJ~/d2CreAzrxpB`G@g~+hKUZEH"ivl_$_lGs"');
define('AUTH_SALT',        'YLNaYF@u6Ls|L^w@HM`;99!xA6UxBoqjGC2#j|p0/EudpNZoIGd+:7Yc9AJo6DnI');
define('SECURE_AUTH_SALT', 'rmq:5|Rv)O;vI8DDQVTTTP"buH^ZW3Za39|W!KT%qtNeUIY1JZAdUa~MOrP"CwkZ');
define('LOGGED_IN_SALT',   'ltR2?_?3*+Rn9bA;vBmMls`jHD|OJs2%^D:#Oxz`NQcm(dlyibJao2bQWf3/L_*K');
define('NONCE_SALT',       'f8K*^Xx)zAm!sLC:R2_QlRbMVjJOw@UtG2@zF6%/vX#_Ocfy3yB1ukvT;hx9_oGl');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_3bin9p_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  1);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* Disable Automatic Concatenation of Scripts
 * Hopefully this fixes TinyMCE
*/
define( 'CONCATENATE_SCRIPTS', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

