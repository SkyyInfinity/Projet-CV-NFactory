<?php
/*
Template Name: CV To PDF Preset
*/
get_header();
?>
<?php

?>

<section class="site-main">

    <div class="container-cv">
        <div class="box-cv" id="js_cvToPDF">
            <div class="head">
                <div class="head-box">
                    <div class="user-avatar">
                        <img src="<?= get_template_directory_uri(); ?>/asset/img/user.svg" alt="user-avatar">
                    </div>
                    <div class="user-info">
                        <h1 class="user-title">John Doe</h1>
                        <h2 class="user-job">Développeur Web - <span>24 ans</span></h2>
                    </div>
                </div>
            </div>
            <!-- DIPLOMES ET COMPETENCES -->
            <section class="diplomeCompetences section-cv">
                <div class="flex-cv">
                    <div class="diplome sect">
                        <h1 class="title-section-cv">Diplome</h1>
                        <ul>
                            <li><h3>Bachelor Développeur Web <span>(NFactory School)</span></h3></li>
                        </ul>
                    </div>
                    <div class="competences sect">
                        <h1 class="title-section-cv">Competences</h1>
                        <ul>
                            <li><h3>Programmation HTML/CSS/JS/PHP</h3></li>
                        </ul>
                    </div>
                </div>
            </section>
            <!-- FORMATIONS ET EXPERIENCES -->
            <section class="formationsExperience section-cv">
                <div class="flex-cv">
                    <div class="formations sect">
                        <h1 class="title-section-cv">Formations</h1>
                        <ul>
                            <li><h3>Développeur Web <span>(CCI Epaignes)</span></h3></li>
                        </ul>
                    </div>
                    <div class="experience sect">
                        <h1 class="title-section-cv">Experience</h1>
                        <ul>
                            <li><h3>Période de stage chez ...</h3></li>
                        </ul>
                    </div>
                </div>
            </section>
            <!-- LOISIRS ET INFOS -->
            <section class="formationsExperience section-cv">
                <div class="flex-cv">
                    <div class="formations sect">
                        <h1 class="title-section-cv">Loisirs</h1>
                        <ul>
                            <li><h3>Développeur Web <span>(CCI Epaignes)</span></h3></li>
                        </ul>
                    </div>
                    <div class="experience sect">
                        <h1 class="title-section-cv">Infos</h1>
                        <ul>
                            <li><h3>Permis B</h3></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="btn-download">
        <a href="javascript:void(0);" id="js_downloadPDF"><i class="fas fa-file-download"></i><span> Telecharger en PDF</span></a>
    </div>

</section>

<?php
get_footer();
