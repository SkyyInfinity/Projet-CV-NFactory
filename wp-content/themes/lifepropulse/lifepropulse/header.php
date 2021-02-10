<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lifepropulse
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'lifepropulse' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$lifepropulse_description = get_bloginfo( 'description', 'display' );
			if ( $lifepropulse_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $lifepropulse_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'lifepropulse' ); ?></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
		</nav><!-- #site-navigation -->

		<!-- 	Si user connecter alors il peux acceder à son profil et se deconnecter
				Si user pas connecter alors il peux se connecter
		-->
		<div class="social">
			<?php $user = wp_get_current_user(); ?>
			<?php if($user->ID == 0): ?>
				<a href='<?php echo bloginfo('url');?>/connexion'>Se connecter</a>
				<a href='<?php echo bloginfo('url');?>/inscription'>S'inscrire'</a>
			<?php else: ?>
				<a href='<?php echo bloginfo('url');?>/profil'> Mon profil</a>
				<a href='<?php echo bloginfo('url');?>/logout'> Se déconnecter </a>
			<?php endif; ?>


		</div>
	</header><!-- #masthead -->
