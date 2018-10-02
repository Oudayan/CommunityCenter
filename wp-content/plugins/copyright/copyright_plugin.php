<?php
/*
Plugin Name: Copyright Plugin
Plugin URI: http://example.com/premier-plugin
Description: Ceci est un exemple de plugin qui ajoute une mention de copyright a chaque article.
Version: 1.0
Author: Faysal A.
Author URI: http://example.com
License: GPLv2
*/

/* Copyright YEAR PLUGIN_AUTHOR_NAME (email : PLUGIN AUTHOR EMAIL)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

include ("mes_options_de_plugin.php");

/***********************/
/*    Installation     */
/***********************/
register_activation_hook( __FILE__, 'monwp_install' );
// Fonction d'installation
function monwp_install() {
	global $wp_version;
	if (version_compare($wp_version, '3.5', '<')) {
		wp_die( 'Cette extension requiert WordPress version 3.5 ou plus.');
	}
	
	// C'est ici que vient le code d'installation du plugin
	// Créer les tables dans la BD, par exemple
}


/***********************/
/*    Désinstallation     */
/***********************/
register_deactivation_hook( __FILE__, 'monwp_deactivate()' );
function monwp_deactivate() {
 // code pour la désactivation
 // Supprimer les tables dans la BD, par exemple
 delete_option('monwp_options');
}

// Fonction de filtrage du contenu
function copyright_content( $content = '' ) {

	// On récupère les options du plugin
	// echo "options:".get_option('$monwp_options["option_email"]');

	// On retourne le contenu du post auquel on rajoute la mention de copyright
	// return $content . '<br /><br />Copyright Maisonneuve 2016 - ';
	return $content . '<br /><br />Copyright 2017 - '. get_option('monwp_options')['option_name'] . '<br>  Courriel : ' . get_option('monwp_options')['option_email'];
}

// Fonction d'initialisation du plugin
// C'est ici que s'insère le code du plugin
function copyright_init() {
	add_filter('the_content', 'copyright_content');
}

// Crochet pour exécuter le code du plugin
add_action('plugins_loaded', 'copyright_init'); 

?> 

