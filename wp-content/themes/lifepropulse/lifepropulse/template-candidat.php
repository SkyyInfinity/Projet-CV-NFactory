<?php
/*
Template Name: candidat
*/
get_header();
?>
<?php
// include('inc/pdo.php');
include('inc/func.php');
$errors = array();
$sucess = false;
if (!empty($_POST['submitted'])) {
    // Clean XSS
    $email           = clean($_POST['email']);
    $nom             = clean($_POST['nom']);
    $prenom          = clean($_POST['prenom']);
    $date            = clean($_POST['date']);
    $diplome_name    = clean($_POST['diplome_name']);
    $diplome_type    = clean($_POST['diplome_type']);
    $diplome_duree   = clean($_POST['diplome_duree']);
    $apprentissage   = clean($_POST['apprentissage']);
    $stage           = clean($_POST['stage']);
    // Validation champs
    $errors = emailValidation($errors, $email, 'email');
    $errors = textValid($errors, $nom, 'nom', 2, 50);
    $errors = textValid($errors, $prenom, 'prenom', 2, 100);
    $errors = textValid($errors, $date, 'date', 10, 50);
    $errors = textValid($errors, $diplome_name, 'diplome_name', 10, 1000);
    $errors = textValid($errors, $diplome_type, 'diplome_type', 10, 1000);
    $errors = textValid($errors, $diplome_duree, 'diplome_duree', 10, 1000);
    $errors = textValid($errors, $apprentissage, 'apprentissage', 10, 1000);
    $errors = textValid($errors, $stage, 'stage', 10, 1000);
    if (count($errors) == 0) {
        // requete ID_user
        //$sql = SELECT id FROM wp_users WHERE id = 1;
        //
        //  insertion en BDD 
        $sql = "INSERT INTO diplome (cv_id, date, diplome_name, diplome_type, etablissement, diplome_duree, apprentissage, stage) 
        VALUES ('$cv_id', '$date', '$diplome_name', '$diplome_type', '$etablissement', '$diplome_duree', '$apprentissage', '$stage')";
        $query->bindValue(':date', $date, PDO::PARAM_INT);
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
            <div class="champform">
                <input type="text" name="firstname" placeholder="Votre prénom" value="">
                <input type="text" name="lastname" placeholder="Votre nom" value="">
                <input type="mail" name="email" placeholder="Votre email" value="">
                <!-- <input type="date" name="birthdate" placeholder="Votre date de naissance" value=""> -->
            </div>
            <div class="champform">
                <label for="Formation"></label>
                <input type="text" name="Formation" placeholder="" value="">
                <input type="date" name="date" placeholder="" value="">
                <input type="text" name="Nom de la formation" placeholder="" value="">
                <textarea name="" placeholder="">
                <input type="text" name="Type de la formation" placeholder="" value="">
                <textarea name="formations" placeholder="">
                <input type="text" name="Etablissement" placeholder="" value="">
                <textarea name="formations" placeholder="">
                <input type="text" name="Durée de la formation" placeholder="" value="">
                <textarea name="formations" placeholder="">
            </div>
            <div>
                <input type="text" name="" placeholder="Vos diplomes" value="">
                <input type="date" name="date" placeholder="" value="">
                <textarea name="diplomes" placeholder="Vos diplomes">
            </div>
            <div>
                <input type="text" name="" placeholder="Vos expériences professionnelles" value="">
                <input type="date" name="date" placeholder="" value="">
                <textarea name="experiences" placeholder="Vos expériences professionnelles">
            </div>
            <div>
                <input type="text" name="competences" placeholder="Vos compétences" value="">
                <textarea name="competences" placeholder="Vos compétences">
            </div>
            <textarea name="loisirs" placeholder="Vos loisirs">

            <input type="submit" name="submit" class="btn" value="Valider">
        </form>
    </section>
</div>

<?php
get_footer();
