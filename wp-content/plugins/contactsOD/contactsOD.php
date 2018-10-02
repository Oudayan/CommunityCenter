<?php

/*
Plugin Name: Affichage, ajout et recherche des contacts
Plugin URI: http://example.com
Description: Afficher tous les contacts dans WordPress
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/

function afficher_contacts() {
    global $wpdb;
    $contacts = $wpdb->get_results("SELECT * FROM wp_contacts;");
    echo "<table>";
    echo "<tr><th>Prénom&nbsp;:</th><th>Nom&nbsp;:</th><th>Adresse&nbsp;:</th><th>Ville&nbsp;:</th><th>Téléphone&nbsp;:&nbsp;</th><th>Cellulaire&nbsp;:&nbsp;</th></tr>";
    foreach ($contacts as $contact) {
        echo "<tr>";
        echo "<td>" . $contact->Prenom_Contact . "</td>";
        echo "<td>" . $contact->Nom_Contact . "</td>";
        echo "<td>" . $contact->Adresse_Contact . "</td>";
        echo "<td>" . $contact->Ville_Contact . "</td>";
        echo "<td>" . $contact->Telephone_Contact . "</td>";
        echo "<td>" . $contact->Cellulaire_Contact . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

add_shortcode( 'afficher_contacts', 'afficher_contacts' );



/*
Plugin Name: Formulaire de saisie d'un contact
Plugin URI: http://example.com
Description: Saisir un contact dans WordPress
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/

// Fonction d’affichage du formulaire :
function html_form_code_contact() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Prénom du Contact (requis) <br />';
    echo '<input type="text" name="cf-prenom" pattern="[a-zA-Z0-9 ]+" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Nom du Contact (requis) <br />';
    echo '<input type="text" name="cf-nom" pattern="[a-zA-Z0-9 ]+" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Adresse (requis) <br />';
    echo '<input type="text" name="cf-adresse" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Ville (requis) <br />';
    echo '<input type="text" name="cf-ville" />';
    echo '</p>';
    echo '<p>';
    echo 'Téléphone (requis) <br />';
    echo '<input type="text" name="cf-telephone" pattern="([2-9][\d]{2}( |-)?)([2-9][\d]{2}( |-)?)([\d]{4})" size="20" />';
    echo '</p>';
    echo '<p>';
    echo 'Cellulaire <br />';
    echo '<input type="text" name="cf-cellulaire" pattern="([2-9][\d]{2}( |-)?)([2-9][\d]{2}( |-)?)([\d]{4})" size="20" />';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Enregistrer"/></p>';
    echo '</form>';
}

// Fonction d’insertion dans la BD
function inserer_contact() {
    // if the submit button is clicked
    if (isset($_POST['cf-submitted'])) {
        // sanitize form values
        $prenom = sanitize_text_field($_POST["cf-prenom"]);
        $nom = sanitize_text_field($_POST["cf-nom"]);
        $adresse = sanitize_text_field($_POST["cf-adresse"]);
        $ville = sanitize_text_field($_POST["cf-ville"]);
        $telephone = sanitize_text_field($_POST["cf-telephone"]);
        $cellulaire = sanitize_text_field($_POST["cf-cellulaire"]);
        // Code de traitement du formulaire : insertion dans la BD
        global $wpdb;
        $wpdb->query($wpdb->prepare("INSERT INTO wp_contacts (Prenom_Contact, Nom_Contact, Adresse_Contact, Ville_Contact, Telephone_Contact, Cellulaire_Contact) VALUES ( %s, %s, %s, %s, %s, %s )", $prenom, $nom, $adresse, $ville, $telephone, $cellulaire));
    }
}


// Fonction de création du shortcode
function contact_shortcode() {
    ob_start(); // temporisation de sortie. Rien en sera envoyé au
    // navigateur tant qu'on n'a pas fini.
    html_form_code_contact();
    inserer_contact();
    return ob_get_clean(); // fin de la temporisation de sortie.
}


// Créer un shortcode pour insérer le formulaire
add_shortcode( 'formulaire_contact', 'contact_shortcode' );



/*
Plugin Name: Formulaire de recherche d'un contact
Plugin URI: http://example.com
Description: Rechercher un contact dans WordPress
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/
// Écrire une fonction d’affichage du formulaire :
function html_contact_search_form() {
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '  <p>Rechercher contact : <input type="text" name="cs-input" pattern="[a-zA-Z0-9.,% ]+" size="40" /></p>';
    echo '  <p><input type="submit" name="cs-submitted" value="Rechercher"/></p>';
    echo '</form>';
}

// Fonction de recherche de contact dans la BD
function rechercher_contact() {
    // if the submit button is clicked
    if (isset($_POST['cs-submitted'])) {
        // sanitize form values
        $input = sanitize_text_field($_POST["cs-input"]);
        $search = explode(" ", $input);
        // Code de traitement du formulaire : insertion dans la BD
        global $wpdb;
        // Construction de la requête SQL
        $sql = "SELECT * FROM wp_contacts ";
        for ($i=0; $i<count($search); $i++) {
            if ($i==0) { $sql .= "WHERE "; }
            else { $sql .= "OR "; }
            $sql .= "Prenom_Contact LIKE '%" . $search[$i] . "%' OR Nom_Contact LIKE '%" . $search[$i] . "%' ";
        }
        $sql .= "GROUP BY ID_Contact ORDER BY Nom_Contact ASC";
        // Storer le résultat de la requête dans le tableau $contacts
        $contacts = $wpdb->get_results($sql);
        if (!empty($contacts)) {
            echo "<table>";
            echo "<tr><th>Prénom&nbsp;:</th><th>Nom&nbsp;:</th><th>Adresse&nbsp;:</th><th>Ville&nbsp;:</th><th>Téléphone&nbsp;:&nbsp;</th><th>Cellulaire&nbsp;:&nbsp;</th></tr>";
            foreach ($contacts as $contact) {
                echo "<tr>";
                echo "<td>" . $contact->Prenom_Contact . "</td>";
                echo "<td>" . $contact->Nom_Contact . "</td>";
                echo "<td>" . $contact->Adresse_Contact . "</td>";
                echo "<td>" . $contact->Ville_Contact . "</td>";
                echo "<td>" . $contact->Telephone_Contact . "</td>";
                echo "<td>" . $contact->Cellulaire_Contact . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else {
            echo "Aucun contact ne contient cette entrée.";
        }
    }
}


// Création du shortcode
function cs_shortcode() {
    ob_start(); // temporisation de sortie. Rien en sera envoyé au navigateur tant qu'on n'a pas fini.
    html_contact_search_form();
    rechercher_contact();
    return ob_get_clean(); // fin de la temporisation de sortie.
}


// Créer un shortcode pour insérer le formulaire de recherche de contact
add_shortcode( 'rechercher_contact', 'cs_shortcode' );

?>