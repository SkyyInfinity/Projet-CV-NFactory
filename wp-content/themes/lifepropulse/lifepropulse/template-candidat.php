<?php
/*
Template Name: candidat
*/

//On vérifie que l'user est bien connecter sinon on le renvoie à la page connection
$user = wp_get_current_user();
if($user->ID == 0){
    header('location:connexion');
}
$errors = array();
$success = array();
session_start();

//crée un nouveau CV
if(!empty($_POST["create_cv"])){

    if(!empty($_POST['cr_cv_name'])){

        //Protection
        $create_name = clean($_POST['cr_cv_name']);
        $create_user_id = $user->ID;

        //si user a plus de 5 cv on ne peux pas le créer
        $sql = "SELECT COUNT(user_id) FROM cv WHERE user_id = :user_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':user_id',$create_user_id,PDO::PARAM_INT);
        $query->execute();
        $count_cv = $query->fetchColumn(); //COUNT AVG SUM MIN MAX

        if($count_cv < 5){

            //Si 2 cv on le méme nom alors on ne peux pas le crée
            $sql = "SELECT COUNT(name) FROM cv WHERE name = :name AND user_id = :user_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':name',$create_name,PDO::PARAM_STR);
            $query->bindValue(':user_id',$create_user_id,PDO::PARAM_INT);
            $query->execute();
            $create_cv_name = $query->fetchColumn(); //COUNT AVG SUM MIN MAX

            if($create_cv_name == 0){
                //Insert
                $sql = "INSERT INTO cv(user_id,name) VALUES (:id,:name)";
                $query = $pdo->prepare($sql);
                $query->bindValue(':id',$create_user_id,PDO::PARAM_INT);
                $query->bindValue(':name',$create_name,PDO::PARAM_STR);
                $query->execute();

                $success['create_cv'] = 'Le cv est crée';
    
            }else{
                $errors['create_cv'] = 'Tu ne peux pas avoir 2 cv qui ont le méme nom';
            }
        }else{
            $errors['create_cv'] = 'Tu ne peux pas avoir plus de 5 CV';
        }
    }
}


//supprimer les cvs

//requete qui prends tout les cv qui appartient à l'user
$sql = "SELECT id FROM cv WHERE user_id = :id";
$query = $pdo->prepare($sql);
$query->bindValue(':id',$user->ID,PDO::PARAM_INT);
$query->execute();
$list_cvbis = $query->fetchAll();

$select_listcv = array();
    //réarrangement de la liste
foreach($list_cvbis as $list_cvbi){
    array_push($select_listcv, $list_cvbi['id']);
}

if(!empty($_POST['delete_cv'])){

    $delete_id = clean($_POST['delete_id']);

    //Si l'user posséde le cv alors il peux le delete
    if(in_array($delete_id,$select_listcv)){

        $delete_id = clean($_POST['delete_id']);
        $sql ="DELETE FROM cv WHERE ID = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id',$delete_id ,PDO::PARAM_INT);
        $query->execute();

        $success['delete_cv'] = "Suprimer avec success";

        //On actualise la liste en cas de modification
        $sql = "SELECT id FROM cv WHERE user_id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id',$user->ID,PDO::PARAM_INT);
        $query->execute();
        $list_cvbis = $query->fetchAll();

    }else{
        $errors['delete_cv'] = "Erreur permission denied";
    }
}

//selectionner les cv

$sql = "SELECT * FROM cv WHERE user_id = :id";
$query = $pdo->prepare($sql);
$query->bindValue(':id',$user->ID,PDO::PARAM_INT);
$query->execute();
$list_cv = $query->fetchAll();

//selectionner LE cv

if(!empty($_POST['select_cv'])){

    $selectcv = clean($_POST['select_id']);
    $select_name_cv = clean($_POST['select_cv']);

    if(in_array($selectcv,$select_listcv)){
        
        $_SESSION["select_cv"]=$selectcv;

        $success['select_cv'] = 'Le CV '. $select_name_cv .' peux étre modifier avec success';

    }else{
        $errors['select_cv'] = "Erreur permission denied";
    }
}


$select_listcv = array();
    //réarrangement de la liste
foreach($list_cvbis as $list_cvbi){
    array_push($select_listcv, $list_cvbi['id']);
}



//modifier les informations perso
$find_meta = get_user_meta($user->ID, 'birthday', false);

if(!empty($_POST['submit_name'])){

    if(!empty($_POST['firstname']) AND !empty($_POST['birthdate']) AND !empty($_POST['email'])){

        $name = clean($_POST['firstname']);
        $age = clean($_POST['birthdate']);
        $email = clean($_POST['email']);

        if($age != $find_meta){
            if(empty($find_meta)){
                add_user_meta($user->ID, 'birthday', $age);
            }else{
                update_user_meta( $user->ID, 'birthday', $age);
            }
        }
        if($name != $user->display_name){
            $name_update = wp_update_user(array(
                'ID' => $user->ID,
                'display_name' => $name
            ));
        }
        if($email != $user->user_email){
            $email_update = wp_update_user(array(
                'ID' => $user->ID,
                'user_email' => $email
            ));
        }

    }else{
        $errors['submit_name'] = 'erreur dans la saisit vérifier les champs';
    }
}

// Diplome

if(!empty($_SESSION["select_cv"])){

    //liste des diplomes par cv

    $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $countDiplome = $query->fetchColumn();

    //ajoute d'un diplome pour un CV

    if(!empty($_POST['diplome_send'])){

        //Protection
        $diplome_name = clean($_POST['diplome_name']);
        $diplome_type = clean($_POST['diplome_type']);
        $diplome_date = clean($_POST['diplome_date']);
        $diplome_duree = clean($_POST['diplome_duree']);
        $diplome_lieu = clean($_POST['diplome_lieu']);
        $diplome_apprentissage = clean($_POST['diplome_apprentissage']);
        $diplome_stage = clean($_POST['diplome_stage']);

        $errors = textValid($errors,$diplome_name,'diplome_name',5,80);
        $errors = textValid($errors,$diplome_type,'diplome_type',5,180);
        $errors = textValid($errors,$diplome_lieu,'diplome_lieu',5,80);

        if($countDiplome < 6){
            if(empty($errors['diplome_name']) AND empty($errors['diplome_type']) AND empty($errors['diplome_lieu']) AND !empty($diplome_date) AND !empty($diplome_duree) AND !empty($diplome_lieu) AND !empty($diplome_apprentissage) AND !empty($diplome_stage)){
            
                //on ajoute les données
    
                $sql = "INSERT INTO diplome (cv_id,date,diplome_name,diplome_type,etablissement,diplome_duree,apprentissage,stage) VALUES (:cv_id,:date,:diplome_name,:diplome_type,:etablissement,:diplome_duree,:apprentissage,:stage)";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->bindValue(':date',$diplome_date,PDO::PARAM_STR);
                $query->bindValue(':diplome_name', $diplome_name,PDO::PARAM_STR);
                $query->bindValue(':diplome_type',$diplome_type,PDO::PARAM_STR);
                $query->bindValue(':etablissement',$diplome_lieu,PDO::PARAM_STR);
                $query->bindValue(':diplome_duree',$diplome_duree,PDO::PARAM_INT);
                $query->bindValue(':apprentissage',$diplome_apprentissage,PDO::PARAM_BOOL);
                $query->bindValue(':stage',$diplome_stage,PDO::PARAM_BOOL);
                $query->execute();


                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->execute();
                $countDiplome = $query->fetchColumn();
    
            }else{
                $errors['diplome_send'] = 'Erreur dans saissit veuillez vérifier les champs';
            }
        }else{
            $errors['diplome_send'] = 'Nombre de diplome max atteint';
        }


    }


    //suppresion des diplome par user

    if(!empty($_POST['diplome_delete_submit'])){

        $diplome_delete_cvid = clean($_POST['diplome_delete_cvid']);
        $diplome_delete_id = clean($_POST['diplome_delete_id']);

        //Si l'user à un _id_cv qui est à lui
        
        if(in_array($diplome_delete_cvid,$select_listcv)){
            
            $sql = "DELETE FROM diplome WHERE id = :id AND cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id',$diplome_delete_id,PDO::PARAM_INT);
            $query->bindValue(':cv_id',$diplome_delete_cvid,PDO::PARAM_INT);
            $query->execute();

            //On actualise le count

            $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
            $query->execute();
            $countDiplome = $query->fetchColumn();
        }
    }
    //listing par user

    $sql = "SELECT * FROM diplome WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $Diplomeperusers = $query->fetchAll();

}


get_header();
?>

<div class="wrap2">
    <section class="site-main" id="formulaire">
        <h1>Crée un CV:</h1>

        <form action="" method="POST">

            <label for="cr_cv_name">Entrée le nom de votre CV : </label>
            <input type="text" name="cr_cv_name" value="">
            <p class="error"><?php if(!empty($errors['create_cv'])) {echo $errors['create_cv'];} ?></p>
            <p class="success"><?php if(!empty($success['create_cv'])) {echo $success['create_cv'];} ?></p>
            
            <input type="submit" value="Crée" name="create_cv">

        </form>

        <h1>Selectionner un CV</h1>
           <table>
                <thead>
                    <tr>
                        <th>Titre du CV</th>
                        <th>Date de création</th>
                        <th>DELETE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($list_cv as $one_cv){
                        echo '<form action="" method="post">';
                            echo '<tr><tb>'. $one_cv['name'] .'</tb>';
                            echo '<tb>'. $one_cv['date'] .'</tb>';
                            echo '<input name="delete_id" type="hidden" value="'.$one_cv['id'].'">';
                            echo '<tb><input name="delete_cv" type="submit" value="DELETE"></tb></tr><br>';
                        echo '</form>';
                    }
                    ?>
                </tbody>
            </table>
            <p class="error"><?php if(!empty($errors['delete_cv'])) {echo $errors['delete_cv'];} ?></p>
            <p class="success"><?php if(!empty($success['delete_cv'])) {echo $success['delete_cv'];} ?></p>

        <h1>Modifier un CV:</h1>
            <!-- id -->
            <form action="" method="post">
                <div class="champform">
                    <h2>Information perso :</h2>
                    <h3>Modifier si bessoin</h3>

                    <h4><?php if(!empty($errors['submit_name'])) {echo $errors['submit_name'];} ?></h4>

                    <label for="">Votre nom prénom </label>
                    <input type="text" name="firstname" placeholder="" value="<?php if(!empty($_POST['firstname'])){echo $_POST['firstname'];}else{echo $user->display_name;} ?>">
                    <p class="error"><?php if(!empty($errors['firstname'])) {echo $errors['firstname'];} ?></p>

                    <label for="">Votre email </label>
                    <input type="mail" name="email" placeholder="" value="<?php if(!empty($_POST['email'])){echo $_POST['email'];}else{echo $user->user_email;} ?>" required>
                    <p></p><label for="">Votre date de naissance </label>
                    <input type="date" name="birthdate" placeholder="" value="<?php if(!empty($_POST['birthdate'])){ echo $_POST['birthdate'];} else{echo $find_meta[0]; }?>" required>
                    <p></p>
                    <input type="submit" value="Ajouter" name="submit_name">
                </div>
            </form>
                <div>
                    <p>Votre nom prénom : <?php if(!empty($_POST['firstname'])){echo $_POST['firstname'];}else{echo $user->display_name;} ?></p>
                    <p>Votre date naissance : <?php if(!empty($_POST['birthdate'])){ echo $_POST['birthdate'];} else{echo $find_meta[0]; }?></p>
                    <p>Votre email : <?php if(!empty($_POST['email'])){echo $_POST['email'];}else{echo $user->user_email;} ?></p>
                </div>
            <!-- champs diplômes -->
            <div>
                <h1>------------------------</h1>
                <h1>Selectionner le CV :</h1>
                <?php 
                foreach($list_cv as $one_cv){
                    echo '<form action="" method="post">';
                        echo '<input name="select_id" type="hidden" value="'.$one_cv['id'].'">';
                        echo '<input name="select_cv" type="submit" value="'. $one_cv['name'] .'">';
                    echo '</form>';
                    }
                ?>
                <p class="error"><?php if(!empty($errors['select_cv'])) {echo $errors['select_cv'];} ?></p>
                <p class="success"><?php if(!empty($success['select_cv'])) {echo $success['select_cv'];} ?></p>
                <h1>------------------------</h1>
            </div>

                <div class="champform">

                <h1>------------------------</h1>
                <?php if(!empty($_SESSION["select_cv"]) AND in_array($_SESSION["select_cv"],$select_listcv)){ ?>

                    <form action="" method="POST">
                        <h2>diplome nb de diplome : <?= $countDiplome ?></h2>
                        <label for="diplome_name">Nom du diplôme </label>
                        <input type="text" name="diplome_name" required>
                        <p class="error"><?php if(!empty($errors['diplome_name'])) {echo $errors['diplome_name'];} ?></p>

                        <label for="diplome_type">Type du diplôme </label>
                        <input type="text" name="diplome_type" required>
                        <p class="error"><?php if(!empty($errors['diplome_type'])) {echo $errors['diplome_type'];} ?></p>
                        
                        <label for="diplome_date">Date de l'obtention diplôme </label>
                        <input type="date" name="diplome_date" required>

                        <label for="diplome_duree">Durée du diplôme en années</label>
                        <input type="number" name="diplome_duree" required>

                        <label for="diplome_lieu">Etablissement </label>
                        <input type="text" name="diplome_lieu" required>
                        <p class="error"><?php if(!empty($errors['diplome_lieu'])) {echo $errors['diplome_lieu'];} ?></p>


                        <label for="">Apprentissage </label>
                        <select name="diplome_apprentissage" id="">
                            <option value="" hidden> --Choisissez-- </option>
                            <option value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>

                        <label for="">Stage </label>
                        <select name="diplome_stage" id="">
                            <option value="" hidden> --Choisissez-- </option>
                            <option value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>
                        <p class="error"><?php if(!empty($errors['diplome_send'])) {echo $errors['diplome_send'];} ?></p>
                        <input type="submit" name="diplome_send" value="Envoyer">

                    </form>
                <h1>------------------------</h1>
                <h1>Listings des diplomes</h1>
                    <ul>
                        <?php foreach($Diplomeperusers as $Diplomeperuser){
                            echo '<form action="" method="POST">';
                            echo '<input name="diplome_delete_cvid" type="hidden" value="'.$Diplomeperuser['cv_id'].'">';
                            echo '<input name="diplome_delete_id" type="hidden" value="'.$Diplomeperuser['id'].'">';
                            echo '<li>'. $Diplomeperuser['diplome_name'] .' fait le '. $Diplomeperuser['diplome_duree'].' <input name="diplome_delete_submit" type="submit" value="DELETE"></li>';
                            echo '</form>';
                        }
                            ?>
                    </ul>
                <h1>------------------------</h1>
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
                <?php }else{?>
                    <h1>Veuillez choisir un cv </h1>
                <?php } ?>
    </section>
</div>
<?php
get_footer();