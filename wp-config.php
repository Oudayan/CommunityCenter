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
define('DB_NAME', 'WordPress');

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
define('AUTH_KEY',         '%23f!]2Qm,cBQ46 A3os_<1G:gUdsi3GY%+uY9en&LCv?6R$y4+jDai*^]<h(m5{');
define('SECURE_AUTH_KEY',  '={=OxIwQzARH_55/Ddt3&KZEYU?<4hLS1;*f.yo)VHgaYN1=YRH?mw-;wFYHk@xC');
define('LOGGED_IN_KEY',    '4.i(x?^@~y`PwgobqrMOcYvCI|c)WkMVWOuZT]sWy,<hZF9wT$A4GXv[iw_d)!M`');
define('NONCE_KEY',        'UL?Av#0osl_]^&o#LKr8%]9+4FyO65=X3h3E1uGwlRNY$7`,]@/5Ng}.UFe-wC4W');
define('AUTH_SALT',        'x=D4L+}{%CvmSb2}O=Yc=]GNJ_zO@[&q<k`,D+:!F|WernF(jwIWVvaC,1a>q?`2');
define('SECURE_AUTH_SALT', '_wYzw&~gW4q!Oh!0*(]xbyHRY/t#[2H1_lw6OqWgM<k_z8jr:vBpN>|+GZe6(}-x');
define('LOGGED_IN_SALT',   'm{=ku&a6nP8S]T>boV! d%Mos V>VFd>2&Z5B+c|v=K_afSRK/Rz^b*m|~EI-7aP');
define('NONCE_SALT',       '!(zA`;O);/l[zq^#^*T~`YvqvM:P 5L*xgGNC-cd9kfa/ 907F_CU4tfaQ``{6D(');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
