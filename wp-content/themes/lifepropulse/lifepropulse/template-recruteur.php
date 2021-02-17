<?php
/*
Template Name: recruteur
*/
// die('rghngfrefgdfres');

get_header();
?>

<?php
$errors = array();

// user connecté
$user = wp_get_current_user();
if ($user->ID != 0) {
    header('location:profil');
}

// debug($user);
get_header();


// select des cv utilisateurs
$sql = "SELECT * FROM cv";
$query = $pdo->prepare($sql);
$query->execute();
$list_cv = $query->fetchAll();
// debug($list_cv);
?>

<section class="site-main">

    <div id="recruteur">
        <div class="welRecru">
            <h2>Bonjour <?php if (!empty($current_user->user_login) && $current_user->user_login != '') {
             echo $current_user->user_login;
                }?>
        Bienvenue sur votre espace recruteur </h2>
        </div>
        <p class="listingCv">Voici la liste des CVs disponibles !</p>

        <!-- <?php echo $resultat['list_cvbis'];
                ?> -->

    </div>
    <table>
       <thead>
           <tr>
               <th>Nom</th>
               <th>Prénom</th>
               <th>Voir le CV complet</th>
           </tr>
       </thead>
    </table>


    <!-- formulaire de recherche -->
    <!-- <main class="container m-auto max-w-2xl mt-4 p-2 sm:px-8">
        <input type="search" id="search" placeholder="Filter users..." class="appearance-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 rounded-lg text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
        <div class="my-4 shadow rounded-lg overflow-hidden">
            <table class="items min-w-full leading-normal"></table>
        </div>
    </main> -->
</section>

<?php
get_footer();
