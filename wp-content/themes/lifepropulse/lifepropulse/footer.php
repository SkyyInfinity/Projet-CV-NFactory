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
$args3 = array(
    'post_type' => 'tweet',
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
);

$the_query3 = new WP_Query($args3);




?>

<!DOCTYPE html>
<html lang="fr">


    <footer id="l-footer">
        <div class="wrap">
            <div class="flex">
                <div class="logo-footer_center">
                    <div class="logo">
                        <a href="#"><img src="./asset/img/logo_footer.png" alt=""></a>
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
	<?php wp_footer(); ?>
</html>





	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'lifepropulse' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'lifepropulse' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'lifepropulse' ), 'lifepropulse', '<a href="http://underscores.me/">Underscores.me</a>' );
				?>
		</div><!-- .site-info -->		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
