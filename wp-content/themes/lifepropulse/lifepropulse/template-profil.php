<?php
/*
Template Name: profil
*/
$user = wp_get_current_user();
if($user->ID == 0){
    header('location:connexion');
}

get_header();
?>

<section class="site-main profil-page">
    <h1 class="h1-page-title">Mes Informations</h1>

    <div class="info-profil">
        <p><i class="fas fa-user"></i> Bonjour <span><?= $user->display_name; ?></span></p>
        <p><i class="fas fa-envelope"></i> Votre email : <span><?= $user->user_email; ?></span></p>
        <p><i class="fas fa-clock"></i> Premiére connexion le: <span><?= $user->user_registered; ?></span></p>
        <div class="btn-recruteur">
            <?php
            $roleRecruteur = get_role('contributor');
            $currentRole = $user->caps;
            if($currentRole == array('contributor' => 1)): ?>
                <a class="btn-secondary" href="<?= esc_url(home_url('recruteur')); ?>">Mon espace</a>
            <?php else: ?>
                <a class="btn-secondary" href="<?= esc_url(home_url('candidat')); ?>">Créer un CV</a>
            <?php endif; ?>
        </div>
    </div>

</section>

<?php
get_footer();
