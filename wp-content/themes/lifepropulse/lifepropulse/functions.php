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

add_action('send_headers','site_router');

function site_router(){
    $root = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
    $url = str_replace($root, '', $_SERVER['REQUEST_URI']);
    $url = explode('/',$url);
    if(count($url) == 1 && $url[0] == 'connexion'){
        require 'template-connexion.php';
        die();
    }
    else if(count($url) == 1 && $url[0] == 'profil'){
        require 'template-profil.php';
        die();
    }
    else if(count($url) == 1 && $url[0] == 'logout'){
        wp_logout();
        header('location:'.$root);
        die();
    }
    else if(count($url) == 1 && $url[0] == 'inscription'){
        require 'template-inscription.php';
        die();
    }
}

/**
 * RESET PASSWORD
 */