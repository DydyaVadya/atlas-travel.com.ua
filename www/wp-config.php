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
define('DB_NAME', 'atlast01_db');

/** MySQL database username */
define('DB_USER', 'atlast01_db');

/** MySQL database password */
define('DB_PASSWORD', 'kd2d6wgn');

/** MySQL hostname */
define('DB_HOST', 'atlast01.mysql.tools');

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
define('AUTH_KEY',         'xd901CU&qy@V0!C4Ae0T#egZX2C&FGA6b@6wy22yu2uxu69TDz#j@(5mVOf2jnfo');
define('SECURE_AUTH_KEY',  'ANPJF7uIbMldx&3t9)z711xzRpqaVlT9dbqkICpP(RGZFCAhuLWcf7JV!OVSzP3v');
define('LOGGED_IN_KEY',    '60SM)rIcj!77Z#BjCzxAK#b@E1yULOdkb)@3%IOZcsoxlclIAcIaa%hd6JoWaKl(');
define('NONCE_KEY',        '94XKxE(3ieCYArlh@P0bG^@Y0q9YvPNZaQY!ZAp0FGAn&)L1QQqxtLS2!Fn18Jwo');
define('AUTH_SALT',        'AoAwTC(PtXd!HiFETBG8uu7#H(Zjqy2SDy5vZPh1TJsWDT3m7%Org&1Jf3xb%d&3');
define('SECURE_AUTH_SALT', '%iS^Bx!NBS5nv41RhMcM8w9FpC36Mdtr&zDgS^VT4lDOMYEM#uCl3GWG0!cOfur(');
define('LOGGED_IN_SALT',   '#Ntp!8E&uuiR6k94tN^nqriRNB02Bp81^KOA8pQRAQwbVxc!H)T#pBbGMgpK)jA4');
define('NONCE_SALT',       's77Z1O1dHiL4yASEslp3%X9Qf*VN6CdlUhlQekUl7SXcwOts4X0ZBD&!Zv!nIf&h');
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

define( 'WP_ALLOW_MULTISITE', true );

define ('FS_METHOD', 'direct');
