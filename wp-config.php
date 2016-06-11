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
define('DB_NAME', 'wits');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1:8889' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

# Security Salts, Keys, Etc
define('AUTH_KEY',         '_w<@oyD>D>}55C)|NPlU9$svE+Sy;Tg8=0OG$#3}DUZf35ukysc(uGQty%Qj3Mm$');
define('SECURE_AUTH_KEY',  '3pU06&n=-k1Ah3Gn|ab-l-*l^o*+Wfth5W~v,j05Iw}~?B,]Z[8}{b:l$o1,.;r?');
define('LOGGED_IN_KEY',    '#:.diM,h--JNcm3Tfmz^NH-@6@J2B=F(g@DDtON_rzmu&xHtMj$b[U#s-Q!7~ofs');
define('NONCE_KEY',        '[#w>@|(I:^+ V?}[K<P*i>$<5uf{;1BP FY6C*7d^a(*:v=|hcK[n#<BG4{Ix4:r');
define('AUTH_SALT',        'fg*cK0+I_~-UN)gLTrnLOm4{uyWSZ+JxmM~4>r%frcn8Y; u*^prv%]=>^74gGI,');
define('SECURE_AUTH_SALT', 'r@n,jTueU5C 5P9)R$pcvLyu1;/`z{=ZvNRnn"XbYk!kaeBo)`S%yhR9VfCfbSQG');
define('LOGGED_IN_SALT',   'w_oPEAXo02=cQ<}~qL c5P}"nvfp/.*.RH0Hef^$h(+[7J~GzKSI zWhGh1a_X3o');
define('NONCE_SALT',       'y>)xX@gUn;iNhD+~$@^>9hI=cXDP7xj$^s~jrvRW7rjLL9cE(]G+^vThamu6Z|NZ');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wits_';

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
