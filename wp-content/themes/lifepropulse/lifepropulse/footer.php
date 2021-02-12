<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lifepropulse
 */

?>

<footer id="l-footer colophon" class="site-footer">

	<div class="banner-head">
		<div class="wrap">
			<div class="flex">
				<div class="logo-footer_center">
					<div class="logo">
						<a href="<?= esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri() ?> /asset/img/logo_footer.png" alt=""></a>
					</div>
					<div class="contact_footer">
						<p><span>E-Mail: </span><a href="mailto:contact@lifepropulse.fr">contact@lifepropulse.fr</a></p>
						<p><span>Telephone: </span><a href="tel:+33232000000">02.32.00.00.00</a></p>
					</div>
				</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'menu-2',
						'menu'            => '',
						'container'       => '',
						'container_class' => 'links',
					)
				);
				?>
				<div class="social">
					<ul>
						<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
						<li><a href="#"><i class="fab fa-instagram"></i></a></li>
						<li><a href="#"><i class="fab fa-twitter"></i></a></li>
						<li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="banner-copyright">
		<div class="wrap">
			<p>&copy; Copyright Life Propulse 2021 | Tous droits réservés</p>
		</div>
	</div>

</footer>
</div> <!-- #page-->
<?php wp_footer(); ?>
</body>

</html>