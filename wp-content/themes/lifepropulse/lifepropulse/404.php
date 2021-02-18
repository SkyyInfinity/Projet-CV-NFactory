<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package lifepropulse
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<div class="wrap">
				<div class="flex">
					<img src="<?php echo get_template_directory_uri(); ?>/asset/img/not-found.svg" alt="Erreur 404">
					<div class="content">
						<h1 class="title-NotFound">404</h1>
						<p class="info-NotFound">Oups, cette page n'existe pas</p>
					</div>
				</div>
				<div class="btn">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="btn-secondary">Retour à l'accueil</a>
				</div>
			</div>
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
