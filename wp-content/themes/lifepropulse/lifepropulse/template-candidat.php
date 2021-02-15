<?php
/*
Template Name: candidat
*/
get_header();
?>
<?php
$errors = array();
$success = false;

$to = $_POST['email'];
$subject = 'Confirmation de la création de votre CV';
// faire un joli mail si on a le temps
$body = 'Super ! Vous venez de créer votre CV ! Notre équipe l\'a bien réceptionné !';
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $body, $headers );

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

 <section class="site-main candidat">
 <main>
    <!-- STEPS -->
    <div class="stepper">
        <div class="step--1 step-active"><a href="#">1. Informations</a></div>
        <div class="step--2"><a href="#">2. Formations</a></div>
        <div class="step--3"><a href="#">3. Diplômes</a></div>
        <div class="step--4"><a href="#">4. Expériences</a></div>
        <div class="step--5"><a href="#">5. Compétences</a></div>
        <div class="step--6"><a href="#">6. Loisirs</a></div>
    </div>
    <form class="form form-active">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos informations personnelles
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vous.
            </p>
        </div>
        <input type="text" name="name" placeholder="Nom" />
        <input type="text" name="firstname" placeholder="Prénom" />
        <input type="text" name="email" placeholder="Email" />
        <input type="text" name="birthdate" placeholder="Date de naissance" />
        <input type="text" name="cellphone" placeholder="Numero de telephone" />
        <button class="form__btn" id="btn-1">Suivant</button>
    </form>

    <!-- DIPLOMES -->
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos diplômes
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vos diplômes.
            </p>
        </div>
        <h2>Diplôme 1 </h2>
        <input type="text" name="diplome_type" placeholder="Type du diplôme" />
        <span class="error" id="error_diplome_type"></span>
        <input type="text" name="diplome_name" placeholder="Nom du diplôme" />
        <span class="error" id="error_diplome_name"></span>
        <input type="text" name="diplome_duree" placeholder="Date du diplôme" />
        <span class="error" id="error_diplome_duree"></span>
        <input type="text" name="diplome_etablissement" placeholder="Etablissement" />
        <span class="error" id="error_diplome_etablissement"></span>
        <label for="">Apprentissage </label>
        <select name="apprentissage" id="">
            <option value="" hidden> --Choisissez-- </option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>
        <p class="error"></p>
        <label for="">Stage </label>
        <select name="stage" id="">
            <option value="" hidden> --Choisissez-- </option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>
        <!-- <input type="text" placeholder="Diplome2" />
        <input type="text" placeholder="Diplome3" />
        <input type="text" placeholder="Diplome4" /> -->
        <button class="form__btn" id="btn-2-prev">Précédent</button>
        <button class="form__btn" id="btn-2-next">suivant</button>
    </form>

    <!-- FORMATIONS -->
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos formations
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vos formations.
            </p>
        </div>
        <h2>Formation 1 </h2>
        <input type="text" name="formation_type" placeholder="Type de formation" />
        <span class="error" id="error_formation_type"></span>
        <input type="text" name="formation_name" placeholder="Nom de la formation" />
        <span class="error" id="error_formation_name"></span>
        <input type="text" name="date" placeholder="Date de la formation" />
        <span class="error" id="error_date"></span>
        <input type="text" name="formation_duree" placeholder="Durée de la formation" />
        <span class="error" id="error_formation_duree"></span>
        <input type="text" name="formation_etablissement" placeholder="Etablissement" />
        <span class="error" id="error_formation_etablissement"></span>
        <label for="">Apprentissage </label>
        <select name="apprentissage" id="">
            <option value="" hidden> --Choisissez-- </option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>
        <p class="error"></p>
        <label for="">Stage </label>
        <select name="stage" id="">
            <option value="" hidden> --Choisissez-- </option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>
        <!-- <input type="text" placeholder="formation2" />
        <input type="text" placeholder="formation3" />
        <input type="text" placeholder="formatione4" /> -->
        <button class="form__btn" id="btn-2-prev">Previous</button>
        <button class="form__btn" id="btn-2-next">Next</button>
    </form>

    <!-- EXPERIENCES -->
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos expériences
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vos expériences.
            </p>
        </div>
        <h2>Expérience 1</h2>
        <input type="text" name="poste_name" placeholder="Intitulé du poste" />
        <input type="text" name="poste_date" placeholder="Date de l'expérience" />
        <input type="text" name="poste_duree" placeholder="Durée de l'expérience" />
        <input type="text" name="entreprise" placeholder="Entreprise" />
        <input type="text" name="missions" placeholder="Missions demandées" />
        <!-- <input type="text" placeholder="expérience2" />
        <input type="text" placeholder="expérience3" />
        <input type="text" placeholder="expérience4" /> -->
        <button class="form__btn" id="btn-2-prev">Précédent</button>
        <button class="form__btn" id="btn-2-next">suivant</button>
    </form>


    <!-- COMPETENCES -->
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos compétences
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vos compétences. </p>
        </div>
        <h2>Compétence 1 </h2>
        <input type="text" name="competence_type" placeholder="Type de compétence" />
        <input type="text" name="competence_name" placeholder="Nom de la compétence" />
        <input type="text" name="competence_niveau" placeholder="Niveau de compétence" />

        <!-- <input type="text" placeholder="compétence2" />
        <input type="text" placeholder="compétence3" />
        <input type="text" placeholder="compétence4" /> -->
        <button class="form__btn" id="btn-2-prev">Previous</button>
        <button class="form__btn" id="btn-2-next">Next</button>
    </form>

    <!-- LOISIRS -->
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Vos loisirs
            </h1>
            <p class="form--header-text">
                Dites-nous en plus à propos de vos loisirs.
            </p>
        </div>
        <h2>Loisir 1 </h2>
        <input type="text" name="loisir_name" placeholder="Nom du loisir" />
        <input type="text" name="loisir_type" placeholder="Type du loisir" />
        <input type="text" name="niveau" placeholder="Niveau" />
        <!-- <input type="text" placeholder="loisir2" />
        <input type="text" placeholder="loisir3" />
        <input type="text" placeholder="loisir4" /> -->
        <button class="form__btn" id="btn-3">Envoyez</button>
    </form>
    <div class="form--message"></div>
</main>
</section>

<?php
get_footer();
