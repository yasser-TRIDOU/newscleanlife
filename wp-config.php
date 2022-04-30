<?php
define( 'WP_CACHE', true /* Modified by NitroPack */ );
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_newscleanlife' );

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
define( 'AUTH_KEY',         'N760s*x!xZu;jm)6Ievr@jLu^@x]l]R{/!XM4f0}[8?Zc+>W2V%YWUrl+`Q(bUSv' );
define( 'SECURE_AUTH_KEY',  '|jm%DUDwufxh}^o<3;ii2m[s pY5?unx9cbRgAb]Bqc{AAExpkp>=) 5)}wPD`Mh' );
define( 'LOGGED_IN_KEY',    '6{mD4zZ~VY9pWW`$l#j=]{0u;!#SBdsY!h3/ E[T9fF~91<=YrnPr:sm*PM,z?t`' );
define( 'NONCE_KEY',        '64uIDR!Y$~CyX_u6A_|aKM+7AbB<TWb/qQs{QUYX Shsv=XQ1pOm&vEs=R&Kv_qu' );
define( 'AUTH_SALT',        'qUy<I&8LH9bmq%C+-LGQbXb|/OGM;AY=Plt>#:D&Q#n|O^s&5)R&}u9B,}))yJ{d' );
define( 'SECURE_AUTH_SALT', 'G+2q0YPpvDB?f(wG~aT3je.jVQV?6,%@Hx4?gA*mgM~s%O|e!5[,U*6foaoDPh81' );
define( 'LOGGED_IN_SALT',   '9PrXq[103Y<60#,2*e[nMh<SXtn=FZdE00BdB%]Hw[pG:-IdMk-U>WaMsx_P64u*' );
define( 'NONCE_SALT',       's;6hjFDLbFV=42:d(u=bKP)}J (qj?q=Hx^H=B]RKy]C!c3K<joVE~^2y)l<D3K,' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
