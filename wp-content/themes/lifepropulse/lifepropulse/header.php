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
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header id="masthead l-header" class="site-header">
		<div class="wrap">
			<nav id="site-navigation" class="main-navigation widget_nav">
				<div class="logo">
					<a href="<?php echo esc_url(home_url('/')); ?>"><img id="js_logo" src="<?php echo get_template_directory_uri(); ?>/asset/img/logo-nav-x250.png" alt="logo du site"></a>
				</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'menu-1',
						'menu'            => '',
						'container'       => '',
						'menu_id'         => 'header',
						'container_class' => 'pages-btn',
					)
				);
				?>
				<ul class="account-btn" id="js_account">
					<!-- 	Si user connecter alors il peux acceder à son profil et se deconnecter
						Si user pas connecter alors il peux se connecter
					-->
					<?php $user = wp_get_current_user(); ?>
					<?php if($user->ID == 0): ?>
						<li><a class="btn-secondary" href='<?php echo bloginfo('url');?>/inscription'>S'inscrire</a></li>
						<li><a class="btn-primary" href='<?php echo bloginfo('url');?>/connexion'>Se connecter</a></li>
					<?php else: ?>
						<li><a class="btn-primary" href='<?php echo bloginfo('url');?>/profil'>Mon profil</a></li>
						<li><a class="btn-secondary" href='<?php echo bloginfo('url');?>/logout'>Se déconnecter</a></li>
					<?php endif; ?>
				</ul>
				<div class="burger" id="js_burger">
					<div class="bar bar-top"></div>
					<div class="bar bar-middle"></div>
					<div class="bar bar-bottom"></div>
				</div>
			</nav><!-- #site-navigation -->
        </div>
	</header><!-- #masthead -->