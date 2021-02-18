    <?php
    /*
    Template Name: candidat
    */

    //On vérifie que l'user est bien connecter sinon on le renvoie à la page connection
    $user = wp_get_current_user();
    if ($user->ID == 0) {
        header('location:connexion');
    }
    $errors = array();
    $success = array();
    session_start();

    //crée un nouveau CV
    if (!empty($_POST["create_cv"])) {

        if (!empty($_POST['cr_cv_name'])) {

            //Protection
            $create_name = clean($_POST['cr_cv_name']);
            $create_user_id = $user->ID;

            //si user a plus de 5 cv on ne peux pas le créer
            $sql = "SELECT COUNT(user_id) FROM cv WHERE user_id = :user_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':user_id', $create_user_id, PDO::PARAM_INT);
            $query->execute();
            $count_cv = $query->fetchColumn(); //COUNT AVG SUM MIN MAX

            if ($count_cv < 5) {

                //Si 2 cv on le méme nom alors on ne peux pas le crée
                $sql = "SELECT COUNT(name) FROM cv WHERE name = :name AND user_id = :user_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':name', $create_name, PDO::PARAM_STR);
                $query->bindValue(':user_id', $create_user_id, PDO::PARAM_INT);
                $query->execute();
                $create_cv_name = $query->fetchColumn(); //COUNT AVG SUM MIN MAX

                if ($create_cv_name == 0) {
                    //Insert
                    $sql = "INSERT INTO cv(user_id,name) VALUES (:id,:name)";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':id', $create_user_id, PDO::PARAM_INT);
                    $query->bindValue(':name', $create_name, PDO::PARAM_STR);
                    $query->execute();

                    $success['create_cv'] = 'Le cv est crée';
                } else {
                    $errors['create_cv'] = 'Tu ne peux pas avoir 2 cv qui ont le méme nom';
                }
            } else {
                $errors['create_cv'] = 'Tu ne peux pas avoir plus de 5 CV';
            }
        }
    }


    //supprimer les cvs

    //requete qui prends tout les cv qui appartient à l'user
    $sql = "SELECT id FROM cv WHERE user_id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $user->ID, PDO::PARAM_INT);
    $query->execute();
    $list_cvbis = $query->fetchAll();

    $select_listcv = array();
    //réarrangement de la liste
    foreach ($list_cvbis as $list_cvbi) {
        array_push($select_listcv, $list_cvbi['id']);
    }

    if (!empty($_POST['delete_cv'])) {

        $delete_id = clean($_POST['delete_id']);

        //Si l'user posséde le cv alors il peux le delete
        if (in_array($delete_id, $select_listcv)) {

            $delete_id = clean($_POST['delete_id']);
            $sql = "DELETE FROM cv WHERE ID = :id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id', $delete_id, PDO::PARAM_INT);
            $query->execute();

            $success['delete_cv'] = "Suprimer avec success";

            //On actualise la liste en cas de modification
            $sql = "SELECT id FROM cv WHERE user_id = :id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':id', $user->ID, PDO::PARAM_INT);
            $query->execute();
            $list_cvbis = $query->fetchAll();
        } else {
            $errors['delete_cv'] = "Erreur permission denied";
        }
    }

    //selectionner les cv

    $sql = "SELECT * FROM cv WHERE user_id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $user->ID, PDO::PARAM_INT);
    $query->execute();
    $list_cv = $query->fetchAll();

    //selectionner LE cv

    if (!empty($_POST['select_cv'])) {

        $selectcv = clean($_POST['select_id']);
        $select_name_cv = clean($_POST['select_cv']);

        if (in_array($selectcv, $select_listcv)) {

            $_SESSION["select_cv"] = $selectcv;

            $success['select_cv'] = 'Le CV ' . $select_name_cv . ' peux étre modifier avec success';
        } else {
            $errors['select_cv'] = "Erreur permission denied";
        }
    }


    $select_listcv = array();
    //réarrangement de la liste
    foreach ($list_cvbis as $list_cvbi) {
        array_push($select_listcv, $list_cvbi['id']);
    }



    //modifier les informations perso
    $find_meta = get_user_meta($user->ID, 'birthday', false);

    if (!empty($_POST['submit_name'])) {

        if (!empty($_POST['firstname']) and !empty($_POST['birthdate']) and !empty($_POST['email'])) {

            $name = clean($_POST['firstname']);
            $age = clean($_POST['birthdate']);
            $email = clean($_POST['email']);

            if ($age != $find_meta) {
                if (empty($find_meta)) {
                    add_user_meta($user->ID, 'birthday', $age);
                } else {
                    update_user_meta($user->ID, 'birthday', $age);
                }
            }
            if ($name != $user->display_name) {
                $name_update = wp_update_user(array(
                    'ID' => $user->ID,
                    'display_name' => $name
                ));
            }
            if ($email != $user->user_email) {
                $email_update = wp_update_user(array(
                    'ID' => $user->ID,
                    'user_email' => $email
                ));
            }
        } else {
            $errors['submit_name'] = 'erreur dans la saisit vérifier les champs';
        }
    }

    // Diplome

    if (!empty($_SESSION["select_cv"])) {

        //liste des diplomes par cv

        $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $countDiplome = $query->fetchColumn();

        //ajoute d'un diplome pour un CV

        if (!empty($_POST['diplome_send'])) {

            //Protection
            $diplome_name = clean($_POST['diplome_name']);
            $diplome_type = clean($_POST['diplome_type']);
            $diplome_date = clean($_POST['diplome_date']);
            $diplome_duree = clean($_POST['diplome_duree']);
            $diplome_lieu = clean($_POST['diplome_lieu']);
            $diplome_apprentissage = clean($_POST['diplome_apprentissage']);
            $diplome_stage = clean($_POST['diplome_stage']);

            $errors = textValid($errors, $diplome_name, 'diplome_name', 5, 80);
            $errors = textValid($errors, $diplome_type, 'diplome_type', 5, 180);
            $errors = textValid($errors, $diplome_lieu, 'diplome_lieu', 5, 80);

            if ($countDiplome < 5) {
                if (empty($errors['diplome_name']) and empty($errors['diplome_type']) and empty($errors['diplome_lieu']) and !empty($diplome_date) and !empty($diplome_duree) and !empty($diplome_lieu) and !empty($diplome_apprentissage) and !empty($diplome_stage)) {

                    //on ajoute les données

                    $sql = "INSERT INTO diplome (cv_id,date,diplome_name,diplome_type,etablissement,diplome_duree,apprentissage,stage) VALUES (:cv_id,:date,:diplome_name,:diplome_type,:etablissement,:diplome_duree,:apprentissage,:stage)";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->bindValue(':date', $diplome_date, PDO::PARAM_STR);
                    $query->bindValue(':diplome_name', $diplome_name, PDO::PARAM_STR);
                    $query->bindValue(':diplome_type', $diplome_type, PDO::PARAM_STR);
                    $query->bindValue(':etablissement', $diplome_lieu, PDO::PARAM_STR);
                    $query->bindValue(':diplome_duree', $diplome_duree, PDO::PARAM_INT);
                    $query->bindValue(':apprentissage', $diplome_apprentissage, PDO::PARAM_BOOL);
                    $query->bindValue(':stage', $diplome_stage, PDO::PARAM_BOOL);
                    $query->execute();


                    //On actualise le count

                    $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->execute();
                    $countDiplome = $query->fetchColumn();
                } else {
                    $errors['diplome_send'] = 'Erreur dans saissit veuillez vérifier les champs';
                }
            } else {
                $errors['diplome_send'] = 'Nombre de diplome max atteint';
            }
        }


        //suppresion des diplome par user

        if (!empty($_POST['diplome_delete_submit'])) {

            $diplome_delete_cvid = clean($_POST['diplome_delete_cvid']);
            $diplome_delete_id = clean($_POST['diplome_delete_id']);

            //Si l'user à un _id_cv qui est à lui

            if (in_array($diplome_delete_cvid, $select_listcv)) {

                $sql = "DELETE FROM diplome WHERE id = :id AND cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':id', $diplome_delete_id, PDO::PARAM_INT);
                $query->bindValue(':cv_id', $diplome_delete_cvid, PDO::PARAM_INT);
                $query->execute();

                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM diplome WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                $query->execute();
                $countDiplome = $query->fetchColumn();
            } else {
                $errors['diplome_delete'] = 'ACCES DENIED';
            }
        }
        //listing par user

        $sql = "SELECT * FROM diplome WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $Diplomeperusers = $query->fetchAll();
    }

    // Expérience

    if (!empty($_SESSION["select_cv"])) {

        //liste des diplomes par cv

        $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $countexperience = $query->fetchColumn();

        //ajoute d'un diplome pour un CV

        if (!empty($_POST['experience_send'])) {

            //Protection
            $experience_date = clean($_POST['experience_date']);
            $experience_duree = clean($_POST['experience_duree']);
            $experience_entreprise = clean($_POST['experience_entreprise']);
            $experience_missions = clean($_POST['experience_missions']);

            $errors = textValid($errors, $experience_entreprise, 'experience_entreprise', 5, 180);
            $errors = textValid($errors, $experience_missions, 'experience_missions', 5, 80);

            if ($countDiplome < 5) {
                if (empty($errors['experience_entreprise']) and empty($errors['experience_missions']) and !empty($experience_date) and !empty($experience_duree) and !empty($experience_entreprise) and !empty($experience_missions)) {

                    //on ajoute les données

                    $sql = "INSERT INTO experience (cv_id, date, entreprise, mission, duree) VALUES (:cv_id, :date, :entreprise, :mission, :duree)";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->bindValue(':date', $experience_date, PDO::PARAM_STR);
                    $query->bindValue(':entreprise', $experience_entreprise, PDO::PARAM_STR);
                    $query->bindValue(':mission', $experience_missions, PDO::PARAM_STR);
                    $query->bindValue(':duree', $experience_duree, PDO::PARAM_INT);
                    $query->execute();


                    //On actualise le count

                    $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->execute();
                    $countexperience = $query->fetchColumn();
                } else {
                    $errors['experience_send'] = 'Erreur dans saissit veuillez vérifier les champs';
                    die('experreur1');
                }
            } else {
                $errors['experience_send'] = 'Nombre de diplome max atteint';
                die('experreur1');
            }
        }


        //suppresion des experience par user

        if (!empty($_POST['experience_delete_submit'])) {

            $experience_delete_cvid = clean($_POST['experience_delete_cvid']);
            $experience_delete_id = clean($_POST['experience_delete_id']);

            //Si l'user à un _id_cv qui est à lui

            if (in_array($experience_delete_cvid, $select_listcv)) {

                $sql = "DELETE FROM experience WHERE id = :id AND cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':id', $experience_delete_id, PDO::PARAM_INT);
                $query->bindValue(':cv_id', $experience_delete_cvid, PDO::PARAM_INT);
                $query->execute();

                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM experience WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                $query->execute();
                $countexperience = $query->fetchColumn();
            } else {
                $errors['experience_delete'] = 'ACCES DENIED';
            }
        }
        //listing par user

        $sql = "SELECT * FROM experience WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $experienceperusers = $query->fetchAll();
    }

    // Compétence

    if (!empty($_SESSION["select_cv"])) {

        //liste des competences par cv

        $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $countcompetence = $query->fetchColumn();

        //ajoute d'une competences pour un CV

        if (!empty($_POST['competence_send'])) {

            //Protection
            $competence_name = clean($_POST['competence_name']);
            $competence_type = clean($_POST['competence_type']);

            $errors = textValid($errors, $competence_name, 'competence_name', 5, 80);
            $errors = textValid($errors, $competence_type, 'competence_type', 5, 180);


            if ($countcompetence < 15) {
                if (empty($errors['competence_name']) and empty($errors['competence_type']) and !empty($competence_name) and !empty($competence_type)) {

                    //on ajoute les données

                    $sql = "INSERT INTO competences (cv_id,competence_name,competence_type) VALUES (:cv_id,:competence_name,:competence_type)";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->bindValue(':competence_name', $competence_name, PDO::PARAM_STR);
                    $query->bindValue(':competence_type', $competence_type, PDO::PARAM_STR);
                    $query->execute();


                    //On actualise le count

                    $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->execute();
                    $countcompetence = $query->fetchColumn();
                } else {
                    die('erreurA');
                    $errors['$competence_send'] = 'Erreur dans saissit veuillez vérifier les champs';
                }
            } else {
                die('erreurB');
                $errors['$competence_send'] = 'Nombre de competence max atteint';
            }
        }


        //suppresion des diplome par user

        if (!empty($_POST['competence_delete_submit'])) {

            $competence_delete_cvid = clean($_POST['competence_delete_cvid']);
            $competence_delete_id = clean($_POST['competence_delete_id']);

            //Si l'user à un _id_cv qui est à lui

            if (in_array($competence_delete_cvid, $select_listcv)) {

                $sql = "DELETE FROM competences WHERE id = :id AND cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':id', $competence_delete_id, PDO::PARAM_INT);
                $query->bindValue(':cv_id', $competence_delete_cvid, PDO::PARAM_INT);
                $query->execute();

                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM competences WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                $query->execute();
                $countcompetence = $query->fetchColumn();
            } else {
                $errors['competence_delete'] = 'ACCES DENIED';
            }
        }
        //listing par user

        $sql = "SELECT * FROM competences WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $competenceusers = $query->fetchAll();
    }

    // loisir

    if (!empty($_SESSION["select_cv"])) {

        //liste des loisir par cv

        $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $countloisir = $query->fetchColumn();

        //ajoute d'une loisirs pour un CV

        if (!empty($_POST['loisir_send'])) {

            //Protection
            $loisir_name = clean($_POST['loisir_name']);

            $errors = textValid($errors, $loisir_name, 'loisir_name', 5, 80);

            if ($countloisir < 15) {
                if (empty($errors['loisir_name']) and !empty($loisir_name)) {
                    //on ajoute les données

                    $sql = "INSERT INTO loisir (cv_id,loisir_name) VALUES (:cv_id,:loisir_name)";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->bindValue(':loisir_name', $loisir_name, PDO::PARAM_STR);
                    $query->execute();


                    //On actualise le count

                    $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                    $query->execute();
                    $countloisir = $query->fetchColumn();
                } else {
                    die('erreurA');
                    $errors['$loisir_send'] = 'Erreur dans saissit veuillez vérifier les champs';
                }
            } else {
                die('erreurB');
                $errors['$loisir_send'] = 'Nombre de competence max atteint';
            }
        }

        //suppresion des diplome par user

        if (!empty($_POST['loisir_delete_submit'])) {

            $loisir_delete_cvid = clean($_POST['loisir_delete_cvid']);
            $loisir_delete_id = clean($_POST['loisir_delete_id']);

            //Si l'user à un _id_cv qui est à lui

            if (in_array($loisir_delete_cvid, $select_listcv)) {

                $sql = "DELETE FROM loisir WHERE id = :id AND cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':id', $loisir_delete_id, PDO::PARAM_INT);
                $query->bindValue(':cv_id', $loisir_delete_cvid, PDO::PARAM_INT);
                $query->execute();

                //On actualise le count

                $sql = "SELECT COUNT(ID) FROM loisir WHERE cv_id = :cv_id";
                $query = $pdo->prepare($sql);
                $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
                $query->execute();
                $countloisir = $query->fetchColumn();
            } else {
                $errors['loisir_delete'] = 'ACCES DENIED';
            }
        }
        //listing par user

        $sql = "SELECT * FROM loisir WHERE cv_id = :cv_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':cv_id', $_SESSION["select_cv"], PDO::PARAM_INT);
        $query->execute();
        $loisirusers = $query->fetchAll();
    }
get_header();
?>
    <div class="wrap2">
        <section class="site-main candidat" id="formulaire">
            <main>
            <h1 class="h1-page-title">Créer votre CV</h1>
                <!-- création de CV -->
                <form class="form form-active" action="" method="POST">
                    <div class="form--header-container">
                        <h1 class="form--header-title">
                            Crée votre CV rapidement
                        </h1>
                        <p class="form--header-text">
                            Dites-nous le nom de votre CV.
                        </p>
                    </div>
                    <label for="cr_cv_name">Entrée le nom de votre CV : </label>
                    <input type="text" name="cr_cv_name" value="">
                    <p class="error"><?php if (!empty($errors['create_cv'])) {echo $errors['create_cv'];} ?></p>
                    <p class="success"><?php if (!empty($success['create_cv'])) {echo $success['create_cv'];} ?></p>
                    <input type="submit" class="form__btn" id="btn-1" value="Crée" name="create_cv">
                </form>

                <!-- selectionner un CV -->
                <form class="form">
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
                        } ?>
                        </tbody>
                    </table>
                    <p class="error"><?php if(!empty($errors['delete_cv'])) {echo $errors['delete_cv'];} ?></p>
                    <p class="success"><?php if(!empty($success['delete_cv'])) {echo $success['delete_cv'];} ?></p>
                </form>


                <!-- Modifier un CV  -->
                <form class="form" action="" method="POST">
                    <div class="form--header-container">
                        <h1 class="form--header-title">
                            Modifier un CV
                        </h1>
                        <p class="form--header-text"></p>
                    </div>
                    <div class="champform">
                        <h2>Information perso :</h2>
                        <h3>Modifier si besoin</h3>
                        <h4><?php if (!empty($errors['submit_name'])) {echo $errors['submit_name'];} ?></h4>
                        <label for="">Votre nom prénom </label>
                        <input type="text" name="firstname" placeholder="" value="<?php if (!empty($_POST['firstname'])) {echo $_POST['firstname'];} else {echo $user->display_name;} ?>">
                        <p class="error"><?php if (!empty($errors['firstname'])) {echo $errors['firstname'];} ?></p>
                        <label for="">Votre email </label>
                        <input type="mail" name="email" placeholder="" value="<?php if (!empty($_POST['email'])) {echo $_POST['email'];} else {echo $user->user_email;} ?>" required>
                        <p></p><label for="">Votre date de naissance </label>
                        <input type="date" name="birthdate" placeholder="" value="<?php if (!empty($_POST['birthdate'])) {echo $_POST['birthdate'];} else {echo $find_meta[0];} ?>" required>
                        <p></p>
                    </div>
                    <div>
                        <p>Votre nom prénom : <?php if (!empty($_POST['firstname'])) {echo $_POST['firstname'];} else {echo $user->display_name;} ?></p>
                        <p>Votre date naissance : <?php if (!empty($_POST['birthdate'])) {echo $_POST['birthdate'];} else {echo $find_meta[0];} ?></p>
                        <p>Votre email : <?php if (!empty($_POST['email'])) {echo $_POST['email'];} else {echo $user->user_email;} ?></p>
                    </div>
                </form>

                <!-- DIPLOMES -->
                <form class="form">
                    <div>
                        <h1>------------------------</h1>
                        <h1>Selectionner le CV :</h1>
                        <?php
                        foreach ($list_cv as $one_cv) {
                            echo '<form action="" method="post">';
                            echo '<input name="select_id" type="hidden" value="' . $one_cv['id'] . '">';
                            echo '<input name="select_cv" type="submit" value="' . $one_cv['name'] . '">';
                            echo '</form>';
                        } ?>
                        <p class="error"><?php if (!empty($errors['select_cv'])) {echo $errors['select_cv'];} ?></p>
                        <p class="success"><?php if (!empty($success['select_cv'])) {echo $success['select_cv'];} ?></p>
                        <h1>------------------------</h1>
                    </div>
                    <div class="champform">
                    <h1>------------------------</h1>
                    <?php if (!empty($_SESSION["select_cv"]) and in_array($_SESSION["select_cv"], $select_listcv)) { ?>
                        <form action="" method="POST">
                            <h2>diplome nb de diplome : <?= $countDiplome ?></h2>
                            <label for="diplome_name">Nom du diplôme </label>
                            <input type="text" name="diplome_name" required>
                            <p class="error"><?php if (!empty($errors['diplome_name'])) {echo $errors['diplome_name'];} ?></p>

                            <label for="diplome_type">Type du diplôme </label>
                            <input type="text" name="diplome_type" required>
                            <p class="error"><?php if (!empty($errors['diplome_type'])) {echo $errors['diplome_type'];} ?></p>

                            <label for="diplome_date">Date de l'obtention diplôme </label>
                            <input type="date" name="diplome_date" required>

                            <label for="diplome_duree">Durée du diplôme en années</label>
                            <input type="number" name="diplome_duree" required>

                            <label for="diplome_lieu">Etablissement </label>
                            <input type="text" name="diplome_lieu" required>
                            <p class="error"><?php if (!empty($errors['diplome_lieu'])) {echo $errors['diplome_lieu'];} ?></p>

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
                            <p class="error"><?php if (!empty($errors['diplome_send'])) {echo $errors['diplome_send'];} ?></p>
                            <input type="submit" name="diplome_send" value="Envoyer">
                        </form>
                    </div>
                    <h1>------------------------</h1>
                    <h1>Listings des diplomes</h1>
                    <ul>
                        <?php foreach ($Diplomeperusers as $Diplomeperuser) {
                        echo '<form action="" method="POST">';
                            echo '<input name="diplome_delete_cvid" type="hidden" value="' . $Diplomeperuser['cv_id'] . '">';
                            echo '<input name="diplome_delete_id" type="hidden" value="' . $Diplomeperuser['id'] . '">';
                            echo '<li>' . $Diplomeperuser['diplome_name'] . ' fait le ' . $Diplomeperuser['diplome_duree'] . ' <input name="diplome_delete_submit" type="submit" value="DELETE"></li>';
                        echo '</form>';
                        } ?>
                    </ul>
                    <p class="error"><?php if (!empty($errors['diplome_delete'])) {echo $errors['diplome_delete'];} ?></p>
                </form>

                        <!-- Expériences pro -->
                        <form class="form">
                        <h1>------------------------</h1>
                        <h1>Expérience pro</h1>
                        <h1>------------------------</h1>
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
                        <h1>------------------------</h1>
                        <h1>------------------------</h1>
                        <h1>Listings des expérience</h1>
                        <ul>
                            <?php foreach ($experienceperusers as $experienceperuser) {
                                echo '<form action="" method="POST">';
                                echo '<input name="experience_delete_cvid" type="hidden" value="' . $experienceperuser['cv_id'] . '">';
                                echo '<input name="experience_delete_id" type="hidden" value="' . $experienceperuser['id'] . '">';
                                echo '<li>' . $experienceperuser['entreprise'] . ' <input name="experience_delete_submit" type="submit" value="DELETE"></li>';
                                echo '</form>';
                            }
                            ?>
                        </ul>
                    <p class="error"><?php if (!empty($errors['diplome_delete'])) {echo $errors['diplome_delete'];} ?></p>
                </form>

                        <!-- Competences -->
                        <form class="form">
                            <h1>------------------------</h1>
                            <h1>Compétence</h1>
                            <h1>------------------------</h1>
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
                                <?php foreach ($competenceusers as $competenceuser) {
                                    echo '<form action="" method="POST">';
                                    echo '<input name="competence_delete_cvid" type="hidden" value="' . $competenceuser['cv_id'] . '">';
                                    echo '<input name="competence_delete_id" type="hidden" value="' . $competenceuser['id'] . '">';
                                    echo '<li>' . $competenceuser['competence_name'] . ' <input name="competence_delete_submit" type="submit" value="DELETE"></li>';
                                    echo '</form>';
                                } ?>
                            </ul>
                        </form>

                        <!-- null
                        <form class="form">
                            <div class="form--header-container">
                                <h1 class="form--header-title">
                                    Vos compétences
                                </h1>
                                <p class="form--header-text">
                                    Dites-nous en plus à propos de vos compétences. </p>
                            </div>
                            <button class="form__btn" id="btn-5-prev">Précédent</button>
                            <button class="form__btn" id="btn-5-next">Suivant</button>
                        </form> -->

                        <!-- LOISIRS -->
                        <form class="form">
                            <h1>------------------------</h1>
                            <h1>Loisirs</h1>
                            <h1>------------------------</h1>
                            <div class="champform">
                                <form action="" method="POST">
                                    <label for="loisir_send">Nom du loisir </label>
                                    <input type="text" name="loisir_name">
                                    <input type="submit" name="loisir_send">
                                </form>
                            </div>

                            <h1>------------------------</h1>
                            <h1>List des loisirs</h1>

                            <ul>
                                <?php foreach ($loisirusers as $loisiruser) {
                                    echo '<form action="" method="POST">';
                                    echo '<input name="loisir_delete_cvid" type="hidden" value="' . $loisiruser['cv_id'] . '">';
                                    echo '<input name="loisir_delete_id" type="hidden" value="' . $loisiruser['id'] . '">';
                                    echo '<li>' . $loisiruser['loisir_name'] . ' <input name="loisir_delete_submit" type="submit" value="DELETE"></li>';
                                    echo '</form>';
                                } ?>
                            </ul>
                        </form>
            </main>
        <?php } else { ?>
            <h1>Veuillez choisir un cv</h1>
        <?php } ?>
        </section>
    </div>
    <?php
    get_footer();
