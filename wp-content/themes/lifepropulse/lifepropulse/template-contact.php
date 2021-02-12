<?php
/*
Template Name: Contact Preset
*/
get_header(); ?>

<section id="section_contact" class="site-main">
    <div class="wrap">
        <h1>Contact</h1>
        <form action="" method="post">
            <!-- NOM -->
            <div class="nom champ">
                <label for="nom">Votre Nom</label>
                <input type="text" name="nom" id="nom">
            </div>
            <!-- PRENOM -->
            <div class="prenom champ">
                <label for="prenom">Votre Prénom</label>
                <input type="text" name="prenom" id="prenom">
            </div>
            <!-- EMAIL -->
            <div class="email champ">
                <label for="email">Votre Email</label>
                <input type="email" name="email" id="email">
            </div>
            <!-- SUJET -->
            <div class="sujet champ">
                <label for="sujet">Le sujet du message</label>
                <input type="text" name="sujet" id="sujet">
            </div>
            <!-- MESSAGE -->
            <div class="message champ">
                <label for="message">Votre message</label>
                <textarea name="message" id="message"></textarea>
            </div>
            <!-- SUBMIT -->
            <div class="submit champ">
                <input class="btn-secondary" type="submit" name="submitted">
            </div>
        </form>
    </div>
</section>

<?php get_footer();