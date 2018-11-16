<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'people');

/** MySQL database username */
define('DB_USER', 'thaipbsdev');

/** MySQL database password */
define('DB_PASSWORD', 'thaipbs145');

/** MySQL hostname */
define('DB_HOST', 'thaipbsdev.cfpnbxirruke.ap-southeast-1.rds.amazonaws.com:3306');

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
define('AUTH_KEY',         '@~ut*ph-+?y/1Fw`Kws|(izQA>QKw|Y(D:$MiG4.(^/8DyBR yINt<)u2<EUYq%:');
define('SECURE_AUTH_KEY',  'U`Ij!W$+4HYovtNd#PI#tJQT^J*^=i )}8;$X@]V9W+U~%fsQo%`WUn726Y59R{F');
define('LOGGED_IN_KEY',    'E|$u7Dj.~KUl_Zr=ii.G_L?^(X*[/_9cOL7]DpyDM<r+=JTeujhy&G&JAmwq|w1!');
define('NONCE_KEY',        '@+dd)6C5wVp=?)-=QyZMA^js30jnR0*@x+71yVL9`)zNn!qS.i#r4ooz#|$Cyas*');
define('AUTH_SALT',        '(!R~lJ(L8*r:},kc5IspT}CGk0vh{#ZjyekoQNy@B8kaUWQ.i#L;@ytH`/b,4gt,');
define('SECURE_AUTH_SALT', 'dOAW@Cgi[/<1/5JWl4+IslEZ;0nMKKGLd,L(({`_N]SqOjoN:lbC(qYJVQhy]6nj');
define('LOGGED_IN_SALT',   'l_Sp5lS[_Lsa{iyZ+ $45Wjh]fryuQg;}JnN>YX+65zn1X;AAf^r:9iqEdUm3*t+');
define('NONCE_SALT',       'c2bGBW;@[KzVZ<5ClXgXREb{RhU]3Nq[lnu]{t{,l7SdSUmDl<bl9pN@`D?~{VQu');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
