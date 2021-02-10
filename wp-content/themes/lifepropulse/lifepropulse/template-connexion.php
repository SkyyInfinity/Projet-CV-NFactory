<?php
/*
Template Name: connexion
*/
$error = false;
if(!empty($_POST)){
    $user = wp_signon($_POST);
    if(is_wp_error($user)){
        $error = $user->get_error_message();
    }else{
        header('location:profil');
    }
}

//Si USER est déja connecter alors redirection sur profil
$user = wp_get_current_user();
if($user->ID != 0){
    header('location:profil');
}
get_header();
?>

<section>
    <h1>Connexion / User</h1>

    <?php if ($error):?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php endif ?>

    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <label for="user_login">Email</label>
        <input type="text" name="user_login" id="user_login" maxlength="50" required>
        <label for="user_password">Mot de passe</label>
        <input type="password" name="user_password" id="user_password" maxlength="50" required>

        <input type="submit" value="Envoyer">
    </form>
</section>



<?php
get_footer();
