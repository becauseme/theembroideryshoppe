<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'theembro_2embroidery');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '8INZlfS<j*@-lueGtsWYsuP-|f1|>Y:9W+3[O7m }WPC.38ApEIW8]16+%b$)rhj');
define('SECURE_AUTH_KEY',  '7>FW;w(l%STf(CE,)zb-}cH(B0TgC{6_En(qG; RXmmy{0Yn`|:X~@Bw#a~$sT$k');
define('LOGGED_IN_KEY',    '_sH+l?Bkw:DH(_AL5^u^9#u2~2)`)lnY5YTx~@Hfl<Lx@x~CwJzwQY+IFt6#/u9v');
define('NONCE_KEY',        '^otL<r2k|@5HKvJn(:Z_EsCS]SO84=X5T6*EX=N3];W$F[~o-s?FnV!i)7Ih/ZuF');
define('AUTH_SALT',        'v~80%W|;Jf9ARRn-uZ-Sk_UN|A-G@18@g(bAu#c*?4q<<DxE;J3h[r_XCzXSlMdI');
define('SECURE_AUTH_SALT', 'k{b||af5oZOe-U~Pu/Y]#_Sv?TA%A 0,>{BdW|s4$ay*VD,DReEth+dGPI=;/v(Y');
define('LOGGED_IN_SALT',   '4%k-,=s<Cii,Y*4(BvvKobY[p1o4C-`s}v(|#>RNszB|f(_c/-+^u,wgOu.$2eli');
define('NONCE_SALT',       '6he4V-m!7:]6%p)8;|g:!tSbtLLU.To~pjnK$ApJ#oHt4WA^}5t2qoFPI<DAq sf');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
