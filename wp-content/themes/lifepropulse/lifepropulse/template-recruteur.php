<?php
/*
Template Name: recruteur
*/
// die('rghngfrefgdfres');
$errors = array();
$success = false;

// SI user est pas connecté alors redirection vers connexion
$user = wp_get_current_user();
if ($user->ID == 0) {
    header('location:connexion');
}

// debug($user);
$errors = array($user);

if (!empty($_GET['id'])) {
    // recuperation du cv
    $cv_id = $_GET['id'];
    // USER
    $single_user = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN $wpdb->users
        ON cv.user_id = $wpdb->users.ID
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    // DIPLOME
    $single_diplome = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN diplome
        ON cv.id = diplome.cv_id
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    // EXPERIENCE
    $single_experience = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN experience
        ON cv.id = experience.cv_id
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    // FORMATION
    $single_formation = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN formation
        ON cv.id = formation.cv_id
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    // COMPETENCES
    $single_competence = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN competences
        ON cv.id = competences.cv_id
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    // LOISIRS
    $single_loisir = $wpdb->get_results(
        "
        SELECT * FROM cv INNER JOIN loisir
        ON cv.id = loisir.cv_id
        WHERE cv.id = $cv_id
        ",
        ARRAY_A
    );
    get_header(); ?>

    <section class="site-main">

        <div class="container-cv">
            <div class="box-cv" id="js_cvToPDF">
                <div class="head">
                    <div class="head-box">
                        <div class="user-avatar">
                            <img src="<?= get_template_directory_uri(); ?>/asset/img/user.svg" alt="user-avatar">
                        </div>
                        <div class="user-info">
                            <h1 class="user-title"><?php if(!empty($single_user[0])){echo $single_user[0]['display_name'];}; ?></h1>
                            <h2 class="user-job">Développeur Web - <span><?php
                            
                            $dateBirth = get_user_meta($single_user[0]['id'], 'birthday');
                            if(!empty($dateBirth)){echo ageCalculator($dateBirth[0]);};
                             ?> ans</span></h2>
                        </div>
                    </div>
                </div>
                <!-- DIPLOMES ET COMPETENCES -->
                <section class="diplomeCompetences section-cv">
                    <div class="flex-cv">
                        <div class="diplome sect">
                            <h1 class="title-section-cv">Diplome</h1>
                            <ul>
                                <li>
                                    <h3><?php if (!empty($single_diplome[0])) {
                                            if(!empty($single_experience[0]['date'])){ $dateTSD = strtotime($single_experience[0]['date']); echo date('d/m/Y', $dateTSD);} if(!empty($single_diplome[0])){ echo ' - ' . mb_ucfirst($single_diplome[0]['diplome_type']) . ' ' . mb_ucfirst($single_diplome[0]['diplome_name']);};
                                        }; ?><span><br><?php if (!empty($single_diplome[0])) {
                                                        echo mb_ucfirst($single_diplome[0]['etablissement']);
                                                    }; ?></span> <?php if (!empty($single_diplome[0])) {
                                                                    echo '(' . $single_diplome[0]['diplome_duree'] . ')';
                                                                }; ?></h3>
                                </li>
                            </ul>
                        </div>
                        <div class="competences sect">
                            <h1 class="title-section-cv">Competences</h1>
                            <ul>
                                <li>
                                    <h3><?php if (!empty($single_competence[0])) {
                                            echo mb_ucfirst($single_competence[0]['competence_type']) . ': ' . mb_ucfirst($single_competence[0]['competence_name']);
                                        }; ?></h3>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!--  EXPERIENCES ET LOISIRS -->
                <section class="formationsExperience section-cv">
                    <div class="flex-cv">
                        <div class="experience sect">
                            <h1 class="title-section-cv">Experience</h1>
                            <ul>
                                <li>
                                    <h3><?php if (!empty($single_experience[0])) {
                                            $dateTSE = strtotime($single_experience[0]['date']);
                                            echo date('d/m/Y', $dateTSE) . ' - ' . $single_experience[0]['entreprise'];
                                        }; ?><span><br> <?php if (!empty($single_experience[0])) {
                                                        echo $single_experience[0]['mission'];
                                                    }; ?></span> <?php if (!empty($single_experience[0])) {
                                                        echo '(' . $single_experience[0]['duree'] . ' ans)';
                                                    }; ?></h3>
                                </li>
                            </ul>
                        </div>
                        <div class="loisirs sect">
                            <h1 class="title-section-cv">Loisirs</h1>
                            <ul>
                                <li>
                                    <h3><?php if (!empty($single_loisir[0])) {
                                            echo mb_ucfirst($single_loisir[0]['loisir_name']);
                                        }; ?></h3>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- LOISIRS ET INFOS -->
                <section class="formationsExperience section-cv">
                    <div class="flex-cv">
                        <div class="infos sect">
                            <h1 class="title-section-cv">Infos</h1>
                            <ul>
                                <li>
                                    <h3>Permis B</h3>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="btn-download">
            <a href="javascript:void(0);" id="js_downloadPDF"><i class="fas fa-file-download"></i><span> Telecharger en PDF</span></a>
        </div>
        <div class="btn-cancel">
            <a href="<?= esc_url(home_url('recruteur')) ?>" class="btn-primary">Retour</a>
        </div>

    </section>

<?php get_footer();
} else {
    // select des cv utilisateurs
    $cvLists = $wpdb->get_results(
        "
        SELECT *
        FROM $wpdb->users, cv
        WHERE $wpdb->users.ID = cv.user_id",
        ARRAY_A
    );

    get_header(); ?>

    <section class="site-main">
        <div id="recruteur">

            <div class="welRecru">
                <h2>Bonjour<span><?php if (!empty($current_user->display_name) && $current_user->display_name != '') {
                                        echo $current_user->display_name;
                                    } ?></span>, <br><br>
                    Bienvenue sur votre espace recruteur </h2>
            </div>
            <p class="listingCv">Voici la liste des CVs disponibles !</p>
        </div>

        <table class="tftable" border="1">
            <tr>
                <th>Nom</th>
                <th>Titre du CV</th>
                <th>Voir le CV complet</th>
            </tr>
            <?php
            foreach ($cvLists as $cvList) {
                $link = esc_url(home_url('recruteur')) . '?id=' . $cvList['id'];
            ?>
                <tr>
                    <td><?= $cvList['display_name']; ?></td>
                    <td><?= $cvList['name']; ?></td>
                    <td><a href="<?= $link; ?>">Voir</a></td>
                </tr>
            <?php
            }
            ?>
        </table>


        <!-- <tr class="welRecru">
                    <td class="headName">Nom</th>
                    <td class="headName">Prénom</th>
                    <td class="headName">Voir le CV complet</th>
                </tr> -->
        <!-- <tr class="headerTable">

                    <th class="headName"></th>
                    <th class="headName"><a href="<?php echo get_template_directory_uri() ?>/asset/img/cv-logo-logo.jpg" width="70px" <span class="dashicons dashicons-plus"></a></span></a></th>

                </tr>
                <tr class="headerTable">
                    <th class="headName"></th>
                    <th class="headName"></th>
                    <th class="headName"><a href="<?php echo get_template_directory_uri() ?>/asset/img/cv-logo.png" width="70px" <span class="dashicons dashicons-plus"></a></span></a></th>
                </tr>

                <tr class="headerTable">
                    <th class="headName"></th>
                    <th class="headName"></th>
                    <th class="headName"><a href="<?php echo get_template_directory_uri() ?>/asset/img/modele-cvfemme.png" width="70px" <span class="dashicons dashicons-plus"></span></a></th>
                </tr> -->

        <!-- // //Aappel de la fonction
        // confirm_resa();
        // function confirm_resa()
        // {
        //     echo '$_SESSION :';
        //     // print_r($_SESSION);
        //     print_r($_GET);
        //     echo '<br> /> <br />$_POST :';
        //     print_r($_POST);

        //     $param = true;
        //     $tab_param = array();

        //     //————————-Vérification des champs  NOM
        //     if (!empty($_POST['nom']))
        //         $tab_param['nom'] = $_POST['nom'];
        //     else
        //         $param = false;

        //     //Prenom
        //     if (!empty($_POST['prenom']))
        //         $tab_param['prenom'] = $_POST['prenom'];
        //     else
        //         $param = false;
        //     //… ECT version simplifiée

        //     if (!$param)
        //         $tab_param = false;

        //     echo $tab_param;
        //     //Si tous les champs sont bien renseignés 
        //     if ($tab_param) {
                
        //         //Insertion BDD et renvoie sur la banque
        //     } else {
        //         //On rejette l’utilisateur
        //     }

        // }
        //  -->

        <!-- formulaire de recherche -->
        <!-- <main class="container m-auto max-w-2xl mt-4 p-2 sm:px-8">
            <input type="search" id="search" placeholder="Filter users..." class="appearance-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 rounded-lg text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
            <div class="my-4 shadow rounded-lg overflow-hidden">
                <table class="items min-w-full leading-normal"></table>
            </div>
        </main> -->
    </section>

<?php get_footer();
}
