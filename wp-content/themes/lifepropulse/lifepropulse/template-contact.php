<?php
/*
Template Name: Contact Preset
*/

$success = false;
$errors = array();

if (!empty($_POST['submitted'])) {
    // Faille XSS
    $nom = clean($_POST['nom']);
    $prenom = clean($_POST['prenom']);
    $email = clean($_POST['email']);
    $sujet = clean($_POST['sujet']);
    $message = clean($_POST['message']);
    // Validation
    $errors = textValid($errors, $nom, 'nom', 1, 100);
    $errors = textValid($errors, $prenom, 'prenom', 1, 100);
    $errors = emailValidation($errors, $email, 'email');
    $errors = textValid($errors, $sujet, 'sujet', 4, 60);
    $errors = textValid($errors, $message, 'message', 8, 3000);
    // Plus de Variables
    $status = 'Nouveau';
    $createdAt = date("Y-m-d H:i:s");

    if (count($errors) == 0) {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "
                INSERT INTO messages
                (nom, prenom, email, sujet, message, created_at, status)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                ",
                array(
                    $nom,
                    $prenom,
                    $email,
                    $sujet,
                    $message,
                    $createdAt,
                    $status
                )
            )
        );
        $success = true;
        header('refresh:5;url=' . esc_url(home_url('/')));
    }
};

get_header(); ?>

<section id="section_contact" class="site-main">
    <div class="wrap">
        <?php
        if($success == true) {
            ?>
            <h2 class="success">Votre message a bien été envoyé ! <span>Veuillez patientez...</span></h2>
            <?php
        } else {
            ?>
            <h1 class="h1-page-title">Contact</h1>
            <form action="" method="post">
                <!-- NOM -->
                <div class="nom champ">
                    <label for="nom">Votre Nom</label>
                    <input type="text" name="nom" id="nom" placeholder="Doe">
                    <span class="error"><?php if (!empty($errors['nom'])) { echo $errors['nom'];}; ?></span>
                </div>
                <!-- PRENOM -->
                <div class="prenom champ">
                    <label for="prenom">Votre Prénom</label>
                    <input type="text" name="prenom" id="prenom" placeholder="John">
                    <span class="error"><?php if (!empty($errors['prenom'])) {echo $errors['prenom'];}; ?></span>
                </div>
                <!-- EMAIL -->
                <div class="email champ">
                    <label for="email">Votre Email</label>
                    <input type="email" name="email" id="email" placeholder="exemple@email.com">
                    <span class="error"><?php if (!empty($errors['email'])) {echo $errors['email'];}; ?></span>
                </div>
                <!-- SUJET -->
                <div class="sujet champ">
                    <label for="sujet">Le sujet du message</label>
                    <input type="text" name="sujet" id="sujet" placeholder="A propos de...">
                    <span class="error"><?php if (!empty($errors['sujet'])) {echo $errors['sujet'];}; ?></span>
                </div>
                <!-- MESSAGE -->
                <div class="message champ">
                    <label for="message">Votre message</label>
                    <textarea name="message" id="message"></textarea>
                    <span class="error"><?php if (!empty($errors['message'])) { echo $errors['message'];}; ?></span>
                </div>
                <!-- SUBMIT -->
                <div class="submit champ">
                    <input class="btn-secondary" type="submit" name="submitted">
                </div>
            </form>
            <?php
        }
        ?>
    </div>
</section>

<?php get_footer();
