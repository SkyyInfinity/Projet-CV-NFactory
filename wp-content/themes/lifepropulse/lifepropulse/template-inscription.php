<?php
/*
Template Name: inscription
*/
//Si USER est déja connecter alors redirection sur profil
$user = wp_get_current_user();
if ($user->ID != 0) {
    header('location:profil');
}

$error = false;
if (!empty($_POST)) {
    $d = $_POST;
    if ($d['user_password'] != $d['user_password2']) {
        $error = 'Les mots de passe ne correspondent pas';
    } else {
        if (!is_email($d['user_email'])) {
            $error = 'Adresse email incorrect';
        } else {
            $name = $d['user_prenom'] . ' ' . $d['user_nom'];
            $user = wp_insert_user(array(
                'display_name' => $name,
                'user_pass' => $d['user_password'],
                'user_email' => $d['user_email'],
                'user_login' => $d['user_email'],
                'user_registered' => date('Y-m-d H:i:s')
            ));
            if (is_wp_error($user)) {
                $error = $user->get_error_message();
            } else {
                $msg = 'Vous étes inscrit bienvenue';
                $headers = 'From : ' . get_option('admin_email') . '\r\n';
                wp_mail($d['user_email'], 'Inscription réussie', $msg, $headers);
                $d = array();
                wp_signon($user);
                header('Location: profil');
            }
        }
    }
}

get_header();
?>

<section class="site-main">
    <div class="wrap">

        <div class="flex-inscription">

            <div class="image-inscription">
                <img src="<?php echo get_template_directory_uri() ?> /asset/img/group_style.png" alt="">
            </div>

            <div class="ensemble-form">
                <div class="title-form">
                    <h1>Inscription</h1>

                </div>

                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

                    <div class="nom-prenom champ">

                        <div class="nom ">
                            <label for="user_nom">Nom</label>
                            <input type="text" name="user_nom" id="user_nom" maxlength="50" value="<?php echo isset($d['user_nom']) ? $d['user_nom'] : ''; ?>" required>
                        </div>
                        <div class="prenom">
                            <label for="user_prenom">Prenom</label>
                            <input type="text" name="user_prenom" id="user_prenom" maxlength="50" value="<?php echo isset($d['user_prenom']) ? $d['user_prenom'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="email champ">
                        <label for="user_email">Email</label>
                        <input type="text" name="user_email" id="user_email" maxlength="50" value="<?php echo isset($d['user_email']) ? $d['user_email'] : ''; ?>" required>
                    </div>

                    <div class="mdp champ">
                        <label for="user_password">Mot de passe</label>
                        <input type="password" name="user_password" id="user_password" maxlength="50" required>
                    </div>

                    <div class="mdp-confirm champ">
                        <label for="user_password2">Confirmer Mot de passe</label>
                        <input type="password" name="user_password2" id="user_password2" maxlength="50" required>

                    </div>
                    <?php if ($error) : ?>
                        <div class=" error">
                            <?php echo '<p class="error">' . $error . ' </p> '; ?>
                        </div>
                    <?php endif ?>

                    <div class="envoyer-formulaire champ ">
                        <input class="btn-secondary" type="submit" value="Envoyer">
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<?php
get_footer();