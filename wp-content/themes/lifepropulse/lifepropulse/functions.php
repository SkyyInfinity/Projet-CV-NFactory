<?php

require get_template_directory() . '/inc/generale.php';
require get_template_directory() . '/inc/func.php';
require get_template_directory() . '/inc/pdo.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';


// pour cacher la barre admin en haut
// add_filter('show_admin_bar', '__return_false');

