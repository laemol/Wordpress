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

define('API_URL', 'http://ticketandtours.test/api/v3/');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY', '=noHJk?brL`qI?K-7koR1:tNAixJ7`x`Q1%{b #1ldqg.P},9![| @^_mTg@Xg`6');
define('SECURE_AUTH_KEY', 'J*5p/utp}rH^Eg1< N&Sn| hghamFC2;TN3uYp=ffXFd)kVVM@c8pZ&2qs1e6anH');
define('LOGGED_IN_KEY', '_Lu<&:Z%w*u~;|.&L-!kEX,Tt8jpA6?FUDHIqYjX0+Q;&_6OtLp+)gGrx( }BoGF');
define('NONCE_KEY', '!/rzP_3H;KHCmRjtNS-4ES<8vPM95suu$/Ew&E9nF:?Kra*43%[8P,`^T^0/G|_A');
define('AUTH_SALT', 'Qf=DLVW2>BtRF~+U4suj{FKTXnfzMyp!w>$G]aH0{sz4+v!<pt#DKDT3-|GB@^9>');
define('SECURE_AUTH_SALT', '-IK`zYq4)p+E#?%CdeeVA~kRem|+e DlKK%$xXf@I4Z,$]f<xOWPBE9H,`Wn2#4_');
define('LOGGED_IN_SALT', 'bY%`+Jpd^>Te#gG1JYsEMfcn4w/cYQS@.-tT5b,;B$W|6f(!V1W6X3AUra3]|)-5');
define('NONCE_SALT', 'DrfQ>HAxck-!eY-os=OqUA D+dI(j/w3zf%J3bYJOIwL{G>`l;v(ogp^,K69rq6G');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
