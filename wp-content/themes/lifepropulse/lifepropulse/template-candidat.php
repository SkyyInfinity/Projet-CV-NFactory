<?php
/*
Template Name: candidat
*/

// confirmation par mail quand le cv est créé
$to = 'aurore.fournier0809@gmail.com';
$sujet = 'Confirmation de création du CV';
$message = 'Vous venez de créer votre CV, nous vous en confirmons la bonne réception et reviendrons vers vous dans les plus brefs délais.


Service de recrutement, Life Propulse';

wp_mail( $to, $sujet, $message );

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

                $success['create_cv'] = 'Le cv est créé';
    
            }else{
                $errors['create_cv'] = 'Tu ne peux pas avoir 2 CVs qui ont le même nom';
            }
        }else{
            $errors['create_cv'] = 'Tu ne peux pas avoir plus de 5 CVs';
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

        $success['delete_cv'] = "Supprimé avec succès";

        //On actualise la liste en cas de modification
        $sql = "SELECT id FROM cv WHERE user_id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id',$user->ID,PDO::PARAM_INT);
        $query->execute();
        $list_cvbis = $query->fetchAll();

    }else{
        $errors['delete_cv'] = "Erreur permission refusée";
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

        $success['select_cv'] = 'Le CV '. $select_name_cv .' peut être modifié avec succès';

    }else{
        $errors['select_cv'] = "Erreur permission refusée";
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
        $errors['submit_name'] = 'Erreur dans la saisie, vérifiez les champs';
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

        if($countDiplome < 5){
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
                $errors['diplome_send'] = 'Erreur dans la saisie, vérifiez les champs';
            }
        }else{
            $errors['diplome_send'] = 'Nombre de diplômes max atteint';
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
        }else{
            $errors['diplome_delete'] = 'Accès refusé ! ';
        }
    }
    //listing par user

    $sql = "SELECT * FROM diplome WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $Diplomeperusers = $query->fetchAll();

}

// Expérience

if(!empty($_SESSION["select_cv"])){

    //liste des diplomes par cv

    $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $countexperience = $query->fetchColumn();

    //ajoute d'un diplome pour un CV

    if(!empty($_POST['experience_send'])){

        //Protection
        $experience_date = clean($_POST['experience_date']);
        $experience_duree = clean($_POST['experience_duree']);
        $experience_entreprise = clean($_POST['experience_entreprise']);
        $experience_missions = clean($_POST['experience_missions']);

        $errors = textValid($errors,$experience_entreprise,'experience_entreprise',5,180);
        $errors = textValid($errors,$experience_missions,'experience_missions',5,80);

        if($countDiplome < 5){
            if(empty($errors['experience_entreprise']) AND empty($errors['experience_missions']) AND !empty($experience_date) AND !empty($experience_duree) AND !empty($experience_entreprise) AND !empty($experience_missions)){
            
                //on ajoute les données
    
                $sql = "INSERT INTO experience (cv_id, date, entreprise, mission, duree) VALUES (:cv_id, :date, :entreprise, :mission, :duree)";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->bindValue(':date',$experience_date,PDO::PARAM_STR);
                $query->bindValue(':entreprise',$experience_entreprise,PDO::PARAM_STR);
                $query->bindValue(':mission',$experience_missions,PDO::PARAM_STR);
                $query->bindValue(':duree',$experience_duree,PDO::PARAM_INT);
                $query->execute();


                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->execute();
                $countexperience = $query->fetchColumn();
    
            }else{
                $errors['experience_send'] = 'Erreur dans la saisie, vérifiez les champs';
                die('experreur1');
            }
        }else{
            $errors['experience_send'] = 'Nombre de diplômes max atteint';
            die('experreur1');
        }


    }


    //suppresion des experience par user

    if(!empty($_POST['experience_delete_submit'])){

        $experience_delete_cvid = clean($_POST['experience_delete_cvid']);
        $experience_delete_id = clean($_POST['experience_delete_id']);

        //Si l'user à un _id_cv qui est à lui
        
        if(in_array($experience_delete_cvid,$select_listcv)){
            
            $sql = "DELETE FROM experience WHERE id = :id AND cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id',$experience_delete_id,PDO::PARAM_INT);
            $query->bindValue(':cv_id',$experience_delete_cvid,PDO::PARAM_INT);
            $query->execute();

            //On actualise le count

            $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
            $query->execute();
            $countexperience = $query->fetchColumn();
        }else{
            $errors['experience_delete'] = 'Accès refusé !';
        }
    }
    //listing par user

    $sql = "SELECT * FROM experience WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $experienceperusers = $query->fetchAll();

}

// Compétence

if(!empty($_SESSION["select_cv"])){

    //liste des competences par cv

    $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $countcompetence = $query->fetchColumn();

    //ajoute d'une competences pour un CV

    if(!empty($_POST['competence_send'])){

        //Protection
        $competence_name = clean($_POST['competence_name']);
        $competence_type = clean($_POST['competence_type']);

        $errors = textValid($errors,$competence_name,'competence_name',5,80);
        $errors = textValid($errors,$competence_type,'competence_type',5,180);


        if($countcompetence < 15){
            if(empty($errors['competence_name']) AND empty($errors['competence_type']) AND !empty($competence_name) AND !empty($competence_type)){
            
                //on ajoute les données
    
                $sql = "INSERT INTO competences (cv_id,competence_name,competence_type) VALUES (:cv_id,:competence_name,:competence_type)";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->bindValue(':competence_name',$competence_name,PDO::PARAM_STR);
                $query->bindValue(':competence_type', $competence_type,PDO::PARAM_STR);
                $query->execute();


                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->execute();
                $countcompetence = $query->fetchColumn();
    
            }else{
                die('erreurA');
                $errors['$competence_send'] = 'Erreur dans la saisie, vérifiez les champs';
            }
        }else{
            die('erreurB');
            $errors['$competence_send'] = 'Nombre de compétences max atteint';
        }


    }


    //suppresion des diplome par user

    if(!empty($_POST['competence_delete_submit'])){

        $competence_delete_cvid = clean($_POST['competence_delete_cvid']);
        $competence_delete_id = clean($_POST['competence_delete_id']);

        //Si l'user à un _id_cv qui est à lui
        
        if(in_array($competence_delete_cvid,$select_listcv)){
            
            $sql = "DELETE FROM competences WHERE id = :id AND cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id',$competence_delete_id,PDO::PARAM_INT);
            $query->bindValue(':cv_id',$competence_delete_cvid,PDO::PARAM_INT);
            $query->execute();

            //On actualise le count

            $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
            $query->execute();
            $countcompetence = $query->fetchColumn();
        }else{
            $errors['competence_delete'] = 'Accès refusé !';
        }
    }
    //listing par user

    $sql = "SELECT * FROM competences WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $competenceusers = $query->fetchAll();

}

// loisir

if(!empty($_SESSION["select_cv"])){

    //liste des loisir par cv

    $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $countloisir = $query->fetchColumn();

    //ajoute d'une loisirs pour un CV

    if(!empty($_POST['loisir_send'])){

        //Protection
        $loisir_name = clean($_POST['loisir_name']);

        $errors = textValid($errors,$loisir_name,'loisir_name',5,80);

        if($countloisir < 15){
            if(empty($errors['loisir_name']) AND !empty($loisir_name)){
                //on ajoute les données
    
                $sql = "INSERT INTO loisir (cv_id,loisir_name) VALUES (:cv_id,:loisir_name)";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->bindValue(':loisir_name',$loisir_name,PDO::PARAM_STR);
                $query->execute();


                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
                $query->execute();
                $countloisir = $query->fetchColumn();
    
            }else{
                die('erreurA');
                $errors['$loisir_send'] = 'Erreur dans la saisie, vérifiez les champs';
            }
        }else{
            die('erreurB');
            $errors['$loisir_send'] = 'Nombre de compétences max atteint';
        }


    }


    //suppresion des diplome par user

    if(!empty($_POST['loisir_delete_submit'])){

        $loisir_delete_cvid = clean($_POST['loisir_delete_cvid']);
        $loisir_delete_id = clean($_POST['loisir_delete_id']);

        //Si l'user à un _id_cv qui est à lui
        
        if(in_array($loisir_delete_cvid,$select_listcv)){
            
            $sql = "DELETE FROM loisir WHERE id = :id AND cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id',$loisir_delete_id,PDO::PARAM_INT);
            $query->bindValue(':cv_id',$loisir_delete_cvid,PDO::PARAM_INT);
            $query->execute();

            //On actualise le count

            $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
            $query->execute();
            $countloisir = $query->fetchColumn();
        }else{
            $errors['loisir_delete'] = 'Accès refusé !';
        }
    }
    //listing par user

    $sql = "SELECT * FROM loisir WHERE cv_id = :cv_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':cv_id',$_SESSION["select_cv"],PDO::PARAM_INT);
    $query->execute();
    $loisirusers = $query->fetchAll();

}

get_header();
?>

<div class="wrap2">
    <section class="site-main" id="formulaire">
        <h1>Crée un CV:</h1>

        <form action="template-recruteur.php" method="POST">

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
            <form action="template-recruteur.php" method="post">
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
                    echo '<form action="template-recruteur.php"" method="post">';
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

                    <form action="template-recruteur.php" method="POST">
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
                    <p class="error"><?php if(!empty($errors['diplome_delete'])) {echo $errors['diplome_delete'];} ?></p>
                <h1>------------------------</h1>
                </div>
                <!-- champs expériences pro -->
                <h1>------------------------</h1>
                <h1>Expérience pro</h1>
                <h1>------------------------</h1>
                <div class="">
                    <form action="" method="POST">

                        <label for="competence_date">Date de l'expérience </label>
                        <input type="date" name="experience_date">

                        <label for="competence_duree">Durée de l'expérience en mois</label>
                        <input type="number" name="experience_duree" placeholder="">

                        <label for="competence_entreprise">Entreprise </label>
                        <input type="text" name="experience_entreprise">

                        <label for="competence_missions">Missions demandées</label>
                        <textarea name="experience_missions"></textarea>

                        <input type="submit" name="experience_send">
                    </form>
                </div>
                <h1>------------------------</h1>
                <h1>------------------------</h1>
                <h1>Listings des expérience</h1>
                    <ul>
                        <?php foreach($experienceperusers as $experienceperuser){
                            echo '<form action="" method="POST">';
                            echo '<input name="experience_delete_cvid" type="hidden" value="'.$experienceperuser['cv_id'].'">';
                            echo '<input name="experience_delete_id" type="hidden" value="'.$experienceperuser['id'].'">';
                            echo '<li>'. $experienceperuser['entreprise'] .' <input name="experience_delete_submit" type="submit" value="DELETE"></li>';
                            echo '</form>';
                        }
                            ?>
                    </ul>
                    <p class="error"><?php if(!empty($errors['diplome_delete'])) {echo $errors['diplome_delete'];} ?></p>
                <h1>------------------------</h1>
                <h1>------------------------</h1>
                <h1>------------------------</h1>
                <h1>Compétence</h1>
                <h1>------------------------</h1>
                <!-- champs compétences -->
                <div class="champform">
                    <form action="" method="POST">
                        <label for="competence_type">Type de compétence </label>
                        <input type="text" name="competence_type">
                        <label for="competence_name">Nom de compétence </label>
                        <input type="text" name="competence_name">
                        <input type="submit" name="competence_send" value="competence">
                    </form>
                </div>
                <h1>------------------------</h1>
                <h1>Listings des compétence</h1>
                    <ul>
                        <?php foreach($competenceusers as $competenceuser){
                            echo '<form action="" method="POST">';
                            echo '<input name="competence_delete_cvid" type="hidden" value="'.$competenceuser['cv_id'].'">';
                            echo '<input name="competence_delete_id" type="hidden" value="'.$competenceuser['id'].'">';
                            echo '<li>'. $competenceuser['competence_name'] .' <input name="competence_delete_submit" type="submit" value="DELETE"></li>';
                            echo '</form>';
                        }
                            ?>
                    </ul>
                <h1>------------------------</h1>
                <h1>Loisirs</h1>
                <h1>------------------------</h1>
                <!-- champs loisirs -->
                <div class="champform">

                    <form action="" method="POST">
                        <label for="loisir_send
                        ">Nom du loisir </label>
                        <input type="text" name="loisir_name">

                        <input type="submit" name="loisir_send">
                    </form>
                </div>

                <h1>------------------------</h1>
                <h1>List des loisirs</h1>
                <ul>
                        <?php foreach($loisirusers as $loisiruser){
                            echo '<form action="" method="POST">';
                            echo '<input name="loisir_delete_cvid" type="hidden" value="'.$loisiruser['cv_id'].'">';
                            echo '<input name="loisir_delete_id" type="hidden" value="'.$loisiruser['id'].'">';
                            echo '<li>'. $loisiruser['loisir_name'] .' <input name="loisir_delete_submit" type="submit" value="DELETE"></li>';
                            echo '</form>';
                        }
                            ?>
                    </ul>
                <h1>------------------------</h1>

                <?php }else{?>
                    <h1>Veuillez choisir un cv </h1>
                <?php } ?>
    </section>
</div>
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
    <form class="form form-active" action="template-recruteur.php">
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
    <form class="form" action="template-recruteur.php">
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
    <form class="form" action="template-recruteur.php">
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
    <form class="form" action="template-recruteur.php">
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
    <form class="form" action="template-recruteur.php">
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
    <form class="form" action="template-recruteur.php">
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
