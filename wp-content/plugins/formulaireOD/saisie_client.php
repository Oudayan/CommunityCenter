<?php
/*
Plugin Name: Formulaire de saisie d'un client
Plugin URI: http://example.com
Description: Saisir un client dans WordPress
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/
// Écrire une fonction d’affichage du formulaire :
function html_form_code() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Nom du client (requis) <br />';
    echo '<input type="text" name="cf-nom" pattern="[a-zA-Z0-9 ]+" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Adresse (requis) <br />';
    echo '<input type="text" name="cf-adresse" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Courriel (requis) <br />';
    echo '<input type="email" name="cf-courriel" />';
    echo '</p>';
    echo '<p>';
    echo 'Téléphone (requis) <br />';
    echo '<input type="text" name="cf-telephone" pattern="[0-9 ]+" size="20" />';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Enregistrer"/></p>';
    echo '</form>';
}

// Écrire une fonction d’insertion dans la BD
function inserer_client() {
    // if the submit button is clicked
    if ( isset( $_POST['cf-submitted'] ) ) {
        // sanitize form values
        $nom = sanitize_text_field( $_POST["cf-nom"] );
        $adresse = sanitize_text_field( $_POST["cf-adresse"] );
        $courriel = sanitize_text_field( $_POST["cf-courriel"] );
        $telephone = sanitize_text_field( $_POST["cf-telephone"] );
        // Code de traitement du formulaire : insertion dans la BD
        global $wpdb;
        $wpdb->query( $wpdb->prepare("INSERT INTO wp_contacs (nom_client, adresse_client, courriel, telephone) VALUES ( %s, %s, %s, %s )", $nom, $adresse, $courriel, $telephone));
    }
}


// Écrire une fonction de création du shortcode
function cf_shortcode() {
    ob_start(); // temporisation de sortie. Rien en sera envoyé au
    // navigateur tant qu'on n'a pas fini.
    inserer_client();
    html_form_code();
    return ob_get_clean(); // fin de la temporisation de sortie.
}


// Écrire le code suivant à la fin de votre script :
add_shortcode( 'mon_formulaire_de_saisie', 'cf_shortcode' );
// crée un shortcode pour insérer le formulaire
