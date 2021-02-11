<?php
/*
Template Name: candidat
*/
get_header();
?>
<?php
$errors = array();
$success = false;
if (!empty($_POST['submitted'])) {
    // Clean XSS
    $email           = clean($_POST['email']);
    $nom             = clean($_POST['lastname']);
    $prenom          = clean($_POST['firstname']);
    // $date            = clean($_POST['date']);
    $diplome_name    = clean($_POST['diplome_name']);
    $diplome_type    = clean($_POST['diplome_type']);
    $diplome_duree   = clean($_POST['diplome_duree']);
    $apprentissage   = clean($_POST['apprentissage']);
    $stage           = clean($_POST['stage']);
    // Validation champs
    $errors = emailValidation($errors, $email, 'email');
    $errors = textValid($errors, $nom, 'nom', 2, 50);
    $errors = textValid($errors, $prenom, 'prenom', 2, 100);
    $errors = textValid($errors, $diplome_name, 'diplome_name', 2, 1000);
    $errors = textValid($errors, $diplome_type, 'diplome_type', 2, 1000);
    $errors = textValid($errors, $diplome_duree, 'diplome_duree', 2, 1000);
    $errors = textValid($errors, $apprentissage, 'apprentissage', 2, 4);
    $errors = textValid($errors, $stage, 'stage', 2, 4);
    if (count($errors) == 0) {
        // requete ID_user
        $sql = "INSERT INTO diplome(cv_id, diplome_name, diplome_type, etablissement, diplome_duree, apprentissage, stage) VALUES (1, :diplome_name, :diplome_type, :etablissement, :diplome_duree, :apprentissage, :stage)";
        $query->bindValue(':diplome_name', $diplome_name, PDO::PARAM_STR);
        $query->bindValue(':diplome_type', $diplome_type, PDO::PARAM_STR);
        $query->bindValue(':diplome_duree', $diplome_duree, PDO::PARAM_STR);
        $query->bindValue(':apprentissage', $apprentissage, PDO::PARAM_STR);
        $query->bindValue(':stage', $stage, PDO::PARAM_STR);
        $query = $pdo->prepare($sql);
        $query->execute();
        $success = true;
    } else {
        echo("marche pas la putain de ta mere");
    }
} else {
    echo("marche pas non plus saloperie de code");
}

?>

<div class="wrap2">
    <section class="site-main" id="formulaire">
        <form id="formcv" action="" method="POST">
            <!-- id -->
            <div class="champform">
                <label for="">Votre prénom </label>
                <input type="text" name="firstname" placeholder="" value="<?= (!empty($_POST['firstname'])) ? $_POST['firstname'] : '' ?>">
                <p class="error"><?php if(!empty($errors['firstname'])) {echo $errors['firstname'];} ?></p>
                <label for="">Votre nom </label>
                <input type="text" name="lastname" placeholder="" value="<?= (!empty($_POST['lastname'])) ? $_POST['lastname'] : '' ?>">
                <p class="error"><?php if(!empty($errors['lastname'])) {echo $errors['lastname'];} ?></p>
                <label for="">Votre email </label>
                <input type="mail" name="email" placeholder="" value="<?php if (!empty($_POST['email'])) echo $_POST['email'];
                                                                        elseif (!empty($_SESSION['visitor']['email'])) echo $_SESSION['visitor']['email']; ?>">
                <label for="">Votre date de naissance </label>
                <!-- <input type="date" name="birthdate" placeholder="" value="<?= (!empty($_POST['birthdate'])) ? $_POST['birthdate'] : '' ?>"> -->
            </div>
            <!-- champs diplômes -->
            <div class="champform">
                <label for="">Nom du diplôme </label>
                <textarea name="diplome_name"><?= (!empty($_POST['diplome_name'])) ? $_POST['diplome_name'] : '' ?></textarea>
                <p class="error"><?php if(!empty($errors['diplome_name'])) {echo $errors['diplome_name'];} ?></p>
                <label for="">Type du diplôme </label>
                <textarea name="diplome_type"><?= (!empty($_POST['diplome_type'])) ? $_POST['diplome_type'] : '' ?></textarea>
                <p class="error"><?php if(!empty($errors['diplome_type'])) {echo $errors['diplome_type'];} ?></p>
                <label for="">Date du diplôme </label>
                <textarea name="diplome_duree"><?= (!empty($_POST['diplome_duree'])) ? $_POST['diplome_duree'] : '' ?></textarea>
                <p class="error"><?php if(!empty($errors['diplome_duree'])) {echo $errors['diplome_duree'];} ?></p>
                <label for="">Durée du diplôme </label>
                <input type="date" name="date" placeholder="" value="<?= (!empty($_POST['date'])) ? $_POST['date'] : '' ?>">
                <p class="error"><?php if(!empty($errors['date'])) {echo $errors['date'];} ?></p>
                <label for="">Etablissement </label>
                <input type="text" name="etablissement" placeholder="" value="<?= (!empty($_POST['etablissement'])) ? $_POST['etablissement'] : '' ?>">
                <p class="error"><?php if(!empty($errors['etablissement'])) {echo $errors['etablissement'];} ?></p>
                <label for="">Apprentissage </label>
                <select name="apprentissage" id="">
                    <option value="" <?= (!empty($_POST['apprentissage'])) ? '' : 'selected' ?>hidden> --Choisissez-- </option>
                    <option value="oui" <?= (!empty($_POST['apprentissage']) && $_POST['apprentissage'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= (!empty($_POST['apprentissage']) && $_POST['apprentissage'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
                <p class="error"><?php if(!empty($errors['apprentissage'])) {echo $errors['apprentissage'];} ?></p>
                <label for="">Stage </label>
                <select name="stage" id="">
                    <option value="" <?= (!empty($_POST['stage'])) ? '' : 'selected' ?> hidden> --Choisissez-- </option>
                    <option value="oui" <?= (!empty($_POST['stage']) && $_POST['stage'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= (!empty($_POST['stage']) && $_POST['stage'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
                <p class="error"><?php if(!empty($errors['stage'])) {echo $errors['stage'];} ?></p>
            </div>
            <!-- champs expériences pro -->
            <div class="champform">
                <label for="">Nom du poste </label>
                <textarea name="nom" placeholder=""><?= (!empty($_POST['poste_name'])) ? $_POST['poste_name'] : '' ?></textarea>
                <label for="">Date de l'expérience </label>
                <input type="date" name="date" placeholder="" value="<?= (!empty($_POST['poste_date'])) ? $_POST['poste_date'] : '' ?>">
                <label for="">Durée de l'expérience </label>
                <textarea name="duree" placeholder=""><?= (!empty($_POST['poste_duree'])) ? $_POST['poste_duree'] : '' ?></textarea>
                <label for="">Entreprise </label>
                <input type="text" name="entreprise" placeholder="" value="<?= (!empty($_POST['entreprise'])) ? $_POST['entreprise'] : '' ?>">
                <label for="">Missions demandées</label>
                <textarea name="missions" placeholder="" <?= (!empty($_POST['missions'])) ? $_POST['missions'] : '' ?>></textarea>
            </div>
            <!-- champs formations -->
            <div class="champform">
                <label for="">Nom de la formation </label>
                <textarea name="formation_name" placeholder=""><?= (!empty($_POST['formation_name'])) ? $_POST['formation_name'] : '' ?></textarea>
                <label for="">Type de formation </label>
                <textarea name="formation_type" placeholder=""><?= (!empty($_POST['formation_type'])) ? $_POST['formation_type'] : '' ?></textarea>
                <label for="">Date de la formation </label>
                <input type="date" name="date" placeholder="" value="<?= (!empty($_POST['date'])) ? $_POST['date'] : '' ?>">
                <label for="">Durée de la formation </label>
                <textarea name="duree" placeholder=""><?= (!empty($_POST['duree'])) ? $_POST['duree'] : '' ?></textarea>
                <label for="">Etablissement </label>
                <input type="text" name="etablissement" placeholder="" value="<?= (!empty($_POST['etablissement'])) ? $_POST['etablissement'] : '' ?>">
            </div>
            <!-- champs compétences -->
            <div class="champform">
                <label for="">Type de compétence </label>
                <textarea name="competence_type" placeholder=""><?= (!empty($_POST['competence_type'])) ? $_POST['competence_type'] : '' ?></textarea>
                <label for="">Nom de compétence </label>
                <textarea name="competence_name" placeholder=""><?= (!empty($_POST['competence_name'])) ? $_POST['competence_name'] : '' ?></textarea>
                <label for="">Niveau de compétence </label>
                <textarea name="niveau" placeholder=""><?= (!empty($_POST['niveau'])) ? $_POST['niveau'] : '' ?></textarea>
            </div>
            <!-- champs loisirs -->
            <div class="champform">
                <label for="">Nom du loisir </label>
                <textarea name="loisir_name" placeholder=""><?= (!empty($_POST['loisir_name'])) ? $_POST['loisir_name'] : '' ?></textarea>
                <label for="">Type de loisir </label>
                <textarea name="loisir_type" placeholder=""><?= (!empty($_POST['loisir_type'])) ? $_POST['loisir_type'] : '' ?></textarea>
                <label for="">Niveau </label>
                <textarea name="niveau" placeholder=""><?= (!empty($_POST['niveau'])) ? $_POST['niveau'] : '' ?></textarea>
            </div>
            <input type="submit" name="submitted" class="btn" value="Valider">
            <?php
            $dest = "destinataire@gmail.com";
            $sujet = "Email de test";
            $corp = "Salut ceci est un email de test envoyé par un script PHP";
            $headers = "From: VotreGmailId@gmail.com";
            if (mail($dest, $sujet, $corp, $headers)) {
                echo "Email envoyé avec succès à $dest ...";
            } else {
                echo "Échec de l'envoi de l'email...";
            }
            ?>
        </form>
    </section>
</div>
<?php
get_footer();