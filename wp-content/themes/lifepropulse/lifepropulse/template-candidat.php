<?php
/*
Template Name: candidat
*/
get_header();
?>
<?php
$errors = array();
$sucess = false;
if (!empty($_POST['submitted'])) {
    // Clean XSS
    $email        = clean($_POST['email']);
    $nom          = clean($_POST['nom']);
    $prenom       = clean($_POST['prenom']);
    $date         = clean($_POST['date']);
    $diplome_name = clean($_POST['diplome_name']);
    $diplome_type = clean($_POST['diplome_type']);
    $diplome_type = clean($_POST['diplome_duree']);
    // Validation champs
    $errors = emailValidation($errors, $email, 'email');
    $errors = textValid($errors, $nom, 'nom', 2, 50);
    $errors = textValid($errors, $prenom, 'prenom', 2, 100);
    $errors = textValid($errors, $date, 'date', 10, 50);
    $errors = textValid($errors, $diplome_name, 'diplome_name', 10, 1000);
    $errors = textValid($errors, $diplome_type, 'diplome_type', 10, 1000);
    $errors = textValid($errors, $diplome_duree, 'diplome_duree', 10, 1000);
    if (count($errors) == 0) {
        // requete ID_user
        //$sql = SELECT id FROM wp_users WHERE id = 1;
        //
        //  insertion en BDD 
        $sql = "INSERT INTO diplome VALUES 
        (:cv_id,NOW(),:diplome_name,:diplome_type,:etablissement,:diplome_duree,:apprentissage,:stage)";
        $query->bindValue(':cv_id', $cv_id, PDO::PARAM_INT);
        $query->bindValue('NOW()', $date, PDO::PARAM_INT);
        $query->bindValue(':diplome_name', $diplome_name, PDO::PARAM_STR);
        $query->bindValue(':diplome_type', $diplome_type, PDO::PARAM_STR);
        $query->bindValue(':etablissement', $etablissement, PDO::PARAM_STR);
        $query->bindValue(':diplome_duree', $diplome_duree, PDO::PARAM_INT);
        $query->bindValue(':apprentissage', $apprentissage, PDO::PARAM_STR);
        $query->bindValue(':stage', $stage, PDO::PARAM_STR);
        $query->execute();
        $success = true;
    }
}

?>

<div class="wrap2">
    <section id="formulaire">
        <form id="formcv" action="" method="POST">
            <!-- id -->
            <div class="champform">
                <label for="">Votre nom </label>
                <input type="text" name="firstname" placeholder="Votre prénom" value="<?= (!empty($_POST['firstname'])) ? $_POST['firstname'] : '' ?>">
                <label for="">Votre prénom </label>
                <input type="text" name="lastname" placeholder="Votre nom" value="<?= (!empty($_POST['lastname'])) ? $_POST['lastname'] : '' ?>">
                <label for="">Votre email </label>
                <input type="mail" name="email" placeholder="Votre email" value="">
                <!-- <input type="date" name="birthdate" placeholder="Votre date de naissance" value=""> -->
            </div>
            <!-- champs diplômes -->
            <div class="champform">
                <label for="">Nom du diplôme </label>
                <textarea name="diplome_name" placeholder=""></textarea>
                <label for="">Type du diplôme </label>
                <textarea name="type" placeholder=""></textarea>
                <label for="">Date du diplôme </label>
                <input type="date" name="date" placeholder="" value="">
                <label for="">Etablissement </label>
                <input type="text" name="etablissement" placeholder="" value="">
                <label for="">Apprentissage </label>
                <select name="apprentissage" id="">
                    <option value="" <?= (!empty($_POST['apprentissage'])) ? '' : 'selected' ?>hidden> --Choisissez-- </option>
                    <option value="oui"<?= (!empty($_POST['apprentissage']) && $_POST['apprentissage'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non"<?= (!empty($_POST['apprentissage']) && $_POST['apprentissage'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
                <label for="">Stage </label>
                <select name="stage" id="">
                    <option value="" <?= (!empty($_POST['stage'])) ? '' : 'selected' ?> hidden> --Choisissez-- </option>
                    <option value="oui" <?= (!empty($_POST['stage']) && $_POST['stage'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= (!empty($_POST['stage']) && $_POST['stage'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            <!-- champs expériences pro -->
            <div class="champform">
                <label for="">Nom du poste </label>
                <textarea name="nom" placeholder=""></textarea>
                <label for="">Date de l'expérience </label>
                <input type="date" name="date" placeholder="" value="">
                <label for="">Durée de l'expérience </label>
                <textarea name="duree" placeholder=""></textarea>
                <label for="">Entreprise </label>
                <input type="text" name="entreprise" placeholder="" value="">
                <label for="">Missions demandées</label>
                <textarea name="missions" placeholder=""></textarea>
            </div>
            <!-- champs formations -->
            <div class="champform">
                <label for="">Nom de la formation </label>
                <textarea name="nom" placeholder=""></textarea>
                <label for="">Type de formation </label>
                <textarea name="type" placeholder=""></textarea>
                <label for="">Date de la formation </label>
                <input type="date" name="date" placeholder="" value="">
                <label for="">Durée de la formation </label>
                <textarea name="duree" placeholder=""></textarea>
                <label for="">Etablissement </label>
                <input type="text" name="etablissement" placeholder="" value="">
            </div>
            <!-- champs compétences -->
            <div class="champform">
                <label for="">Type de compétence </label>
                <textarea name="type" placeholder=""></textarea>
                <label for="">Nom de compétence </label>
                <textarea name="nom" placeholder=""></textarea>
                <label for="">Niveau de compétence </label>
                <textarea name="niveau" placeholder=""></textarea>
            </div>
            <!-- champs loisirs -->
            <div class="champform">
                <label for="">Nom du loisir </label>
                <textarea name="nom" placeholder=""></textarea>
                <label for="">Type de loisir </label>
                <textarea name="type" placeholder=""></textarea>
                <label for="">Niveau </label>
                <textarea name="niveau" placeholder=""></textarea>
            </div>
            <input type="submit" name="submit" class="btn" value="Valider">
        </form>
    </section>
</div>
<?php
get_footer();
