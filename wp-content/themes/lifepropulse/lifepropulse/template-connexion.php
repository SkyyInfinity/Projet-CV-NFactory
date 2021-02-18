<?php
/*
Template Name: connexion
*/
$error = false;
if (!empty($_POST)) {
    $user = wp_signon($_POST);
    if (is_wp_error($user)) {
        $error = $user->get_error_message();
    } else {
        redirection('profil');
    }
}

//Si USER est déja connecter alors redirection sur profil
$user = wp_get_current_user();
if ($user->ID != 0) {
    redirection('profil');
}
get_header();
?>

<section class="site-main">
    <div class="wrap">
        <div class="flex-connexion">

            <div class="image-connexion">
                <img src="<?php echo get_template_directory_uri() ?> /asset/img/connexion-img.png" alt="">
            </div>

            <div class="ensemble-form">
                <h1 class="h1-page-title">Connexion</h1>

                <?php if ($error) : ?>
                    <div class="error">
                        <?php echo $error; ?>
                    </div>
                <?php endif ?>

                <div class="form-connect">
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                        <div class="email-connect champ">
                            <label for="user_login">Votre Email</label>
                            <input type="text" name="user_login" id="user_login" maxlength="50" required placeholder="exemple@email.com">
                        </div>
                        <div class="mdp champ">
                            <label for="user_password">Votre Mot de passe</label>
                            <input type="password" name="user_password" id="user_password" maxlength="50" required placeholder="******">
                        </div>
                        <div class="forgot-psswd champ">
                            <a class="forgot-psswd" href="<?= esc_url(home_url('mot-de-passe-oublie')); ?>" alt="<?php esc_attr_e( 'Lost Password', 'textdomain' ); ?>">
                                <?php esc_html_e( 'Mot de passe oublié ?', 'textdomain' ); ?>
                            </a>
                        </div>
                        <div class="envoyer-formulaire champ">
                            <input class="btn-secondary" type="submit" value="Envoyer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
get_footer();