<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'life_propulse' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'rUwj9o#,&4PF~Ed262G t=U_1{HNsc@quUU%ESJ%5k6U^{}f!0`Z[.at1rtmyIh!' );
define( 'SECURE_AUTH_KEY',  'ofY)iBkYXcn-uKxoG@(K0LhrI~$z;rvjoI?6d(L#$&[7rocS]FN9^{94_!~0qwV8' );
define( 'LOGGED_IN_KEY',    '4](R!.sLWjgYOBW9{a[xVJlJOI>|-(~TKqxQuot2JK7P.lrI<2r_+^sm9Us#k6*}' );
define( 'NONCE_KEY',        '*:yi]:J+gkKT%*W9=C_/ :?07n9XS]i6&EI*L`$wJW,GLGA?{RH{{i9[tBy*E4aa' );
define( 'AUTH_SALT',        '6i2^22LwvV$sk>`;It^EmaUXJ{%gEU2ud?KobYhQE-Xs0C{]+^3Ol^>2[_pWqmL!' );
define( 'SECURE_AUTH_SALT', 'LG*H/l[D9|@3ftiQQCT{gp&M*tM(wX604Lt(p3;RtC]DV30;MoQ58Wn*3?pq~b(p' );
define( 'LOGGED_IN_SALT',   '.1[K4h|YJMkKBo#| zZ~u6Fg<cg>j&PGgYRU<GQ1@PjX_N`;2_~3PHaOm}~>azL`' );
define( 'NONCE_SALT',       'q~+lHU-h[@[hz>Z5=rh%Sx6MCf=F.z!DXOOfI0ZgyK(&Yk`XhTIOw$D Yx0r?FzM' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
