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
	<div class="wrap">
		<div class="flex">
			<div class="logo-footer_center">
				<div class="logo">
					<a href="#"><img src="<?php echo get_template_directory_uri() ?> /asset/img/logo_footer.png" alt=""></a>
				</div>
				<div class="contact_footer">
					<p>E-Mail contact@lifepropulse.fr</p>
					<p>Telephone 02 32 00 00 00</p>
				</div>
			</div>
			<ul>
				<div class="links">

					<li><a href="#">Mention Légales</a></li>
					<li><a href="#">FAQ</a></li>
					<li><a href="#">Contact</a></li>
				</div>
				<div class="social">

					<li><a href="#">Logo Facebook</a></li>
					<li><a href="#">Logo Twitter</a></li>
					<li><a href="#">Logo Instagram</a></li>
					<li><a href="#">Logo Linkedn</a></li>
				</div>
			</ul>
		</div>
	</div>
	<div class="footer_foot">
		<div class="wrap">
			<p> @COPYRIGHT LIFE PROPULSE 2021 | TOUS DROITS RESERVES</p>
		</div>
	</div>
</footer>
</div> <!-- #page-->
<?php wp_footer(); ?>
</body>
</html>