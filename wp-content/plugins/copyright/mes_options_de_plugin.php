<?php

// Ajouter une entrée pour les paramètres du plugin dans le  menu
add_action( 'admin_menu', 'monwp_create_menu' );

//créer le  menu qui va s'insérer dans le panneau d'administration
function monwp_create_menu() {
// Les paramètres de add_menu_page() sont : 
// - titre de la page des options, 
// - titre dans le menu, 
// - les capacités, 
// - l'alias du menu (unique)
// - la fonction à appeler
// - url de l'icone
// - position dans le menu

	add_menu_page( 'Page du Plugin Copyright',  // - titre de la page des options,
	'Plugin Copyright', //  titre dans le menu, 
	'manage_options',    //  les capacités, 
	'monwp_main_menu', //  l'alias du menu (unique)
	'monwp_settings_page',   //  la fonction à appeler
	plugins_url( 'wordpress.png',__FILE__),  //  url de l'icone
	6   //  position dans le menu

	);

// enregistrer les paramètres : appel à la fonction d'enregistrement 
	add_action( 'admin_init', 'monwp_register_settings' );
}

// Enregistrer les paramètres
function monwp_register_settings() {
// Les paramètres de register_setting() sont : 
// -  un groupe de paramètres,
// - le nom de l'option à nettoyer et sauvegarder
// - une fonction pour nettoyer les options 
	register_setting( 
	'monwp-settings-group', //  groupe de paramètres,
	'monwp_options',   // le nom de l'option à nettoyer et sauvegarder
	'monwp_sanitize_options' // fonction pour nettoyer les options
	);
}
// Récupération et nettoyage des données du formulaire
function monwp_sanitize_options( $input ) {
	// La liste des options sous forme de tableau
	$input['option_name'] = sanitize_text_field( $input['option_name'] );
	$input['option_email'] = sanitize_email( $input['option_email'] );
	$input['option_url'] = esc_url( $input['option_url'] );
	return $input;
}

// La page de saisie des paramètres
function monwp_settings_page() {
?>
	<div class="wrap">
		<h2>Options du Plugin Copyright </h2>
		<form method="post" action="options.php">
		<?php 
		// Fonction nécessaire à la gestion du formulaire
		settings_fields( 'monwp-settings-group' ); 
		// On récupère les noms des options
		 $monwp_options = get_option( 'monwp_options' ); ?>
		<table class="form-table">
		<tr valign="top">
		<th scope="row">Nom</th>
		<td><input type="text" required name="monwp_options[option_name]" /></td>
		</tr>
		<tr valign="top">
		<th scope="row">Courriel</th>
		<td><input type="email" required name="monwp_options[option_email]" /></td>
		</tr>
		<tr valign="top">
		<th scope="row">URL</th>
		<td><input type="text" name="monwp_options[option_url]" />
		</td>
		</tr>
		</table>
		<?php // print_r($monwp_options); ?>
		<p class="submit">
		<input type="submit" class="button-primary" value="Save Changes" />
		</p>
		</form>
	</div>
<?php
}   //  Fin de monwp_settings_page()



// Test la fonction d'ajout de section 
/* add_settings_section(
    'eg_setting_section',
    __( 'Example settings section in reading', 'textdomain' ),
    'wpdocs_setting_section_callback_function',
    'reading'
); */


 
/**
 * Settings section display callback.
 *
 * @param array $args Display arguments.
 */
/* function wpdocs_setting_section_callback_function( $args ) {
    // echo section intro text here
    echo '<p>id: ' . esc_html( $args['id'] ) . '</p>';                         // id: eg_setting_section
    echo '<p>title: ' . apply_filters( 'the_title', $args['title'] ) . '</p>'; // title: Example settings section in reading
    echo '<p>callback: ' . esc_html( $args['callback'] ) . '</p>';             // callback: eg_setting_section_callback_function
} */
?>