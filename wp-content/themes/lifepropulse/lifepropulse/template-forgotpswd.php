<?php
/*
Template Name: Forgot Password Preset
*/

global $wpdb;
        
$errors = array();
$success = false;

// check if we're in reset form
if(isset($_POST['submitted'])) {
    $email = clean($_POST['email']);
    $errors = emailValidation($errors, $email, 'email');
    
    if(!email_exists($email) && !empty($_POST['email'])) {
        $errors['email'] = 'L\'adresse email ne correspond à aucun utilisateur';
    }

    if(count($errors) == 0) {

        // Variables utiles
        $token = bin2hex(random_bytes(32));
        $expire = date('U') + 1800;
        $user = get_user_by('email', $email);
        $link = esc_url(home_url('mot-de-passe-oublie')) . '?nickname=' . $user->user_nicename . '&token=' . $token;

        add_user_meta($user->ID, 'token', $token, true);

        // Insertion du token en Base De Donnée (table : wp_usermeta)
        $wpdb->query(
            $wpdb->prepare(
            "
            INSERT INTO $wpdb->usermeta
            (user_id, meta_key, meta_value)
            VALUES ( %d, %s, %s )
            ",
            array(
                  $user->ID,
                  'token',
                  $token,
               )
            )
        );

        // Envoyer le mail avec le lien de réinitialisation
        $to = $email;
        $subject = 'Votre nouveau mot de passe';
        $sender = get_option('name');
        $message = '<h1>Vous avez demander une réinitialisation de mot de passe</h1>';
        $message .= 'Voici votre lien de réinitialisation: ' . $link;
        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";
        $mail = wp_mail($to, $subject, $message, $headers);

        $success = true;
    }
}
if(isset($_POST['resetPswd'])) {
    $password = clean($_POST['password1']);
    $password2 = clean($_POST['password2']);
    $userNickname = $_GET['nickname'];

    $userID = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_value = '$userNickname'", ARRAY_A);

    if(empty($password)) {
        $errors['password1'] = 'Veuillez renseigner ce champ';
    }
    if(empty($password2)) {
        $errors['password2'] = 'Veuillez renseigner ce champ';
    }
    if($_POST['password1'] != $_POST['password2']) {
        $errors['password2'] = 'Les deux mot de passe doivent être identiques';
    }
    
    if(count($errors) == 0) {

        $token = $_GET['token'];
        $prevToken = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'token' AND meta_value = '$token'", ARRAY_A);
        
        if(!empty($prevToken[0]['meta_value'])) {

            delete_user_meta($userID[0]['user_id'], 'token', $prevToken[0]['meta_value']);
            $update_user = wp_update_user(array(
                'ID' => $userID[0]['user_id'],
                'user_pass' => $password
                )
            );

            $success = true;
            redirection('connexion');
        } else {
            redirection('404');
        }
    }
}
get_header();
?>

<section class="site-main forgot-box">
    <div class="wrap">
        <?php
        if(isset($_GET['token'])) {
            if($success == true) {
                echo '<p class= success>L\'email à bien été envoyer !</p>';
            } else {
                ?>
                <h1 class="h1-page-title">Réinitialisation du mot de passe</h1>
                <form action="" method="post">
                    <div class="mdp1 champ">
                        <label for="password1">Votre nouveau mot de passe</label>
                        <input type="password" name="password1" id="password1" required placeholder="*******">
                        <span class="error"><?php if(isset($errors['password1'])) {echo $errors['password1'];}; ?></span>
                    </div>
                    <div class="mdp2 champ">
                        <label for="password2">Confirmation du nouveau mot de passe</label>
                        <input type="password" name="password2" id="password2" required placeholder="*******">
                        <span class="error"><?php if(isset($errors['password2'])) {echo $errors['password2'];}; ?></span>
                    </div>
                    <div class="submit champ">
                        <input id="js_resetPswd" class="btn-secondary" type="submit" name="resetPswd" value="Réinitialiser">
                        <a href="<?= esc_url(home_url('connexion')); ?>" class="btn-primary">Annuler</a>
                    </div>
                </form>
                <?php
            }
        } else {
            ?>
            <h1 class="h1-page-title">Mot de passe oublié</h1>
            <form action="" method="post">
                <div class="email champ">
                    <label for="email">Votre Email</label>
                    <p class="forgot-info">Un mail de réinitialisation sera envoyer à cette adresse</p>
                    <input type="email" name="email" id="email" required placeholder="exemple@email.com">
                    <span class="error"><?php if(isset($errors['email'])) {echo $errors['email'];}; ?></span>
                </div>
                <div class="submit champ">
                    <input id="js_emailResetPswd" class="btn-secondary" type="submit" name="submitted" value="Recevoir l'email">
                    <a href="<?= esc_url(home_url('connexion')); ?>" class="btn-primary">Annuler</a>
                </div>
            </form>
            <?php
        }
        ?>
    </div>
</section>



<?php
get_footer();