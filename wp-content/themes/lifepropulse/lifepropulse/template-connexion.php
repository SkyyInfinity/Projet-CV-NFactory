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
        header('location:profil');
    }
}

//Si USER est déja connecter alors redirection sur profil
$user = wp_get_current_user();
if ($user->ID != 0) {
    header('location:profil');
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
                <div class="title-form-connect">
                    <h1 class="title-connect">Connexion</h1>

                </div>

                <?php if ($error) : ?>
                    <div class="error">
                        <?php echo $error; ?>
                    </div>
                <?php endif ?>

                <div class="form-connect champ">
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                        <div class="email-connect champ ">
                            <label for="user_login">Votre adresse e-mail:</label>
                            <input type="email" name="user_login" id="user_login" maxlength="50" required placeholder=" e-mail">
                        </div>
                        <div class="mdp champ ">
                            <label  for="user_password">Votre Mot de passe:</label>
                            <input type="password" name="user_password" id="user_password" maxlength="50" required placeholder="Mot de Passe">
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



