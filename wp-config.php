<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsens installationsrutin, utan kan kopiera
 * denna fil direkt till "wp-config.php" och fylla i alla värden.
 *
 * Denna fil innehåller följande konfigurationer:
 *
 * * Inställningar för MySQL
 * * Säkerhetsnycklar
 * * Tabellprefix för databas
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define( 'DB_NAME', 'DB46130' );

/** MySQL-databasens användarnamn */
define( 'DB_USER', 'LinusD' );

/** MySQL-databasens lösenord */
define( 'DB_PASSWORD', 'RokkABiltemA64' );

/** MySQL-server */
define( 'DB_HOST', 'localhost' );

/** Teckenkodning för tabellerna i databasen. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']5y4sjw3:H_Ot-3g`I|!q#/~AxPx+_%ZF8f+#,L6dmWqN_!Z&`$1c{ioH|8ron_.' );
define( 'SECURE_AUTH_KEY',  'Y4QN?bEfahe)Z`j^^Ds$S~&cs.R6^gw;$W]e0^CQ7x6G#$/=_~Gp:pM(M_*Yx19Z' );
define( 'LOGGED_IN_KEY',    '7[V J&3*3WX(J^=8dVAsZ$;`4jnI:JzSw>r_1aP`11E-W%~HXRn|GxKIX{EYw*o`' );
define( 'NONCE_KEY',        'GLIsWE8V-f,|y4&,cxGwF[HCI65S/ T#Nq|ly.;XaX#xX!vnqCpPH#H%{Hj&CEtH' );
define( 'AUTH_SALT',        'MlbvO)K6FonAK;@I5:?M{r$np8[?7RYgc/UwaT~&sn.z_l1nu@J[SbomM-Xi~:=)' );
define( 'SECURE_AUTH_SALT', 'tA7kdKZrt1_Urh2B% 5VrKsGJ$N[@_Y@BXI=.R6)_[IZyA`_L4,&9?bq|]Hj@jHk' );
define( 'LOGGED_IN_SALT',   '[C)+bF|Da6#8R{x}oo/r[n*IV~dQN,?Eq#C~>M<:[JE+%`vMtWMa8=R?IkI(H:%Z' );
define( 'NONCE_SALT',       '4%LV|2ATT+&0QtWLrGlA~%5Lb}>~|02ElrY@U|G.!Z(T8M!G[zQq0C%t-oiMg@2?' );

/**#@-*/

/**
 * Tabellprefix för WordPress-databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Använd endast siffror, bokstäver och understreck!
 */
$table_prefix = 'wp_its_';

/** 
 * För utvecklare: WordPress felsökningsläge. 
 * 
 * Ändra detta till true för att aktivera meddelanden under utveckling. 
 * Det rekommenderas att man som tilläggsskapare och temaskapare använder WP_DEBUG 
 * i sin utvecklingsmiljö. 
 *
 * För information om andra konstanter som kan användas för felsökning, 
 * se dokumentationen. 
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */ 
define('WP_DEBUG', false);

/* Det var allt, sluta redigera här och börja publicera! */

/** Absolut sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');