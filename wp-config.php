<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'university' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'LjQf&>Htb4*vCaTU&qm$q:I1uZ7v.oyyh}**Q_ZM^UK%s(&n{`EVQo&MQFxh3Z>B' );
define( 'SECURE_AUTH_KEY',  '7&V!nr)sL2O!hRm|xuyeurvJR4PW6YgQUdn`j{_BN|S[--^rjf`!8Qtxd +x]~/r' );
define( 'LOGGED_IN_KEY',    'W#]9?m1`$Hxs;4tW.c#y0ibep3i.a21MW:i0S>FQp?D!T{=1<~2Ng&_RQDR`csdR' );
define( 'NONCE_KEY',        ']P_ut<^q_MR+Vo,+55q`-hbfo5<tcBuqIhCy|rBhM6e&Ui=K]/AMs/@)E&:dt79l' );
define( 'AUTH_SALT',        'Lq<XL;m`5nI-!7pg|EAH9X`2I:3lTO8z!.V=+;a(b1[}(5M=hVetbN/5pD0:Qc;I' );
define( 'SECURE_AUTH_SALT', 'e=*U5d(A2<{mzKx!8m+{fJL6* Y`k{a1Cph;;F95]pYUKrm?cx)!lT`tI1s$/-Pe' );
define( 'LOGGED_IN_SALT',   'AN%tuRQ>?KWQc:tssK]R0%&Z+&71bGa~w$FW&^YkJAWhbU6OGzWO*)*HGt8S8#5i' );
define( 'NONCE_SALT',       'g<mg3s~DM`9d?qv%Hsf)Kk!BiPSE:s%Mj-@V:1*hoP~h;e9<>%.2|MQEII{}f?>>' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
