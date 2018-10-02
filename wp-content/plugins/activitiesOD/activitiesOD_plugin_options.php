<?php

// Ajouter une entrée pour les paramètres du plugin dans le  menu
add_action( 'admin_menu', 'create_activity_menu' );

//créer le  menu qui va s'insérer dans le panneau d'administration
function create_activity_menu() {
    // Les paramètres de add_menu_page() sont : 
    // - titre de la page des options, 
    // - titre dans le menu, 
    // - les capacités, 
    // - l'alias du menu (unique)
    // - la fonction à appeler
    // - url de l'icone
    // - position dans le menu
	add_menu_page( 'Page du Plugin des activités',  // - titre de la page des options,
	'Plugin Activités', //  titre dans le menu, 
	'manage_options',    //  les capacités, 
	'activities_main_menu', //  l'alias du menu (unique)
	'activities_settings_page',   //  la fonction à appeler
	plugins_url( 'activitiesOD_icon.png',__FILE__),  //  url de l'icone
	5   //  position dans le menu
	);

    // enregistrer les paramètres : appel à la fonction d'enregistrement 
	add_action('admin_init', 'activities_permission_settings');
    add_action('admin_init', 'activities_default_settings');

}

// Enregistrer les paramètres
function activities_permission_settings() {
	register_setting( 
        'activities-settings-group', //  groupe de paramètres,
        'activities_permissions',   // le nom de l'option à nettoyer et sauvegarder
        'sanitize_activity_permissions' // fonction pour nettoyer les options
	);
}

// Récupération et nettoyage des données du formulaire
function sanitize_activity_permissions($input) {
    $all_roles = get_all_existing_user_roles();
    foreach ($all_roles as $role) {
        if (isset($input[$role])) {
            $input[$role] = sanitize_text_field($input[$role]);
        }
    }
	return $input;
}


// Enregistrer les paramètres
function activities_default_settings() {
	register_setting( 
        'activities-settings-group', //  groupe de paramètres,
        'activities_default',   // le nom de l'option à nettoyer et sauvegarder
        'sanitize_activities_default' // fonction pour nettoyer les options
	);
}

// Récupération et nettoyage des données du formulaire
function sanitize_activities_default($input) {
	$input['sas_name'] = sanitize_text_field( $input['sas_name'] );
	$input['sas_phone'] = sanitize_text_field( $input['sas_phone'] );
	$input['sas_email'] = sanitize_email( $input['sas_email'] );
	$input['cas_title'] = sanitize_text_field($input['cas_title']);
	$input['cas_start_date'] = sanitize_text_field($input['cas_start_date']);
	$input['cas_end_date'] = sanitize_text_field($input['cas_end_date']);
	$input['cas_category'] = sanitize_text_field($input['cas_category']);
	$input['cas_place'] = sanitize_text_field($input['cas_place']);
	$input['cas_animator'] = sanitize_text_field($input['cas_animator']);
	$input['cas_description'] = sanitize_text_field($input['cas_description']);
	$input['cas_fee'] = sanitize_text_field($input['cas_fee']);
	$input['cas_maxparticipants'] = sanitize_text_field($input['cas_maxparticipants']);
    return $input;
}


function get_all_existing_user_roles() {
    //$user_roles = array("administrator", "editor", "author", "subscriber", "contributor");
    global $wpdb;
    $roles = $wpdb->get_results("SELECT meta_value FROM " . $wpdb->prefix . "usermeta WHERE meta_key='" . $wpdb->prefix . "capabilities' GROUP BY meta_value;");
    foreach ($roles as $role) {
        // print_r($role);
        $user_role = unserialize($role->meta_value);
        foreach ($user_role as $key => $u_role) {
            $all_roles[] = $key;
        }
    }
    sort($all_roles);
    //print_r($all_roles);
    return $all_roles;
}


// La page de saisie des paramètres
function activities_settings_page() {

    global $wpdb;
    $activities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities;");
    $categories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_categories;");
    $periodicities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_periodicities;");

    echo "<div class='wrap'>";
        echo "  <h1>Options du plugin des activités</h1>";
        echo "  <form method='post' action='options.php'>";
        // echo "  <form method='post' action='options.php'>";
            // Fonction nécessaire à la gestion du formulaire
            settings_fields("activities-settings-group"); 
            
            // Section des permissions de création d'activité des usager
            // On récupère les noms de l'option activities_permissions
            $activities_permissions = get_option("activities_permissions");    
            echo "  <hr>";
            echo "  <h2>Permissions de gestion des activités&nbsp;:</h2>";
            echo "  <table class='form-table'>";
                // Chercher tous les roles d'usager existants dans la base de donnée
                $user_roles = get_all_existing_user_roles();
                // Générer les checkbox pour chaque role d'usager
                for ($i=0; $i<count($user_roles); $i++) {
                    echo "      <tr valign='top'><th scope='row'><label>" . $user_roles[$i] . "&nbsp;:</label></th><td><input type='checkbox' name='activities_permissions[$user_roles[$i]]'" . ($user_roles[$i]=="administrator" ? "readonly checked" : (isset(get_option("activities_permissions")[$user_roles[$i]]) ? "checked" : "")) . "></td></tr>";
                }
            echo "</table>";

            // Section des valeurs par défaut des formulaires
            // On récupère les noms de l'option activities_default
            $activities_default = get_option("activities_default");   
            // Formulaire de création d'activité
            echo "  <hr>";
            echo "  <h2>Valeurs par défault du formulaire d'inscription à une activité&nbsp;:</h2>";
            echo "  <table class='form-table'>";
                echo "  <tr valign='top'>";
                    echo "  <th scope='row'><label>Activité&nbsp;</label></th><td>";
                    echo "  <select name='activities_default[sas_activity_id]'>";
                        echo "      <option value='0'>Veuillez choisir une activité</option>";
                        foreach ($activities as $act_row) {
                            echo "      <option value='" . $act_row->activity_id . "' " . (get_option("activities_default")["sas_activity_id"]==$act_row->activity_id ? "selected" : "") . ">" . $act_row->activity_title . "</option>";
                        }
                    echo "  </select></p>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "        <th scope='row'><label>Nom&nbsp;</label></th><td><input type='text' name='activities_default[sas_name]' value='" . get_option("activities_default")["sas_name"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "        <th scope='row'><label>Téléphone&nbsp;</label></th><td><input type='text' name='activities_default[sas_phone]' value='" . get_option("activities_default")["sas_phone"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "        <th scope='row'><label>Courriel&nbsp;</label></th><td><input type='text' name='activities_default[sas_email]' value='" . get_option("activities_default")["sas_email"] . "' size='40'></td>";
                echo "  </tr>";
            echo "</table>";
            // Formulaire de création d'activité
            echo "  <hr>";
            echo "  <h2>Valeurs par défault du formulaire de création des activités&nbsp;:</h2>";
            echo "<table class='form-table'>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Titre de l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_title]' value='" . get_option("activities_default")["cas_title"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Du&nbsp;</label></th><td><input type='date' name='activities_default[cas_start_date]' value='" . get_option("activities_default")["cas_start_date"] . "' size='10'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Au&nbsp;</label></th><td><input type='date' name='activities_default[cas_end_date]' value='" . get_option("activities_default")["cas_end_date"] . "' size='10'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Catégorie de l'activité&nbsp;</label></th><td>";
                    echo "      <select name='activities_default[cas_category]'>";
                        echo "          <option value='0'>Veuillez choisir une catégorie</option>";
                        foreach ($categories as $cat_row) {
                            echo "      <option value='" . $cat_row->category_id . "' " . (get_option("activities_default")["cas_category"]==$cat_row->category_id ? "selected" : "") . ">" . $cat_row->category_name . "</option>";
                        }
                    echo "</select></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Lieu de l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_place]' value='" . get_option("activities_default")["cas_place"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Animateur de l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_animator]' value='" . get_option("activities_default")["cas_animator"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Description de l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_description]' value='" . get_option("activities_default")["cas_description"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Périodicité de l'activité&nbsp;</label></th><td>";
                    echo "      <select name='activities_default[cas_periodicity]'>";
                        echo "          <option value='0'>Veuillez choisir une périodicité</option>";
                        foreach ($periodicities as $per_row) {
                           echo "      <option value='" . $per_row->periodicity_id . "' " . (get_option("activities_default")["cas_periodicity"]==$per_row->periodicity_id ? "selected" : "") . ">" . $per_row->periodicity_name . "</option>";
                        }
                    echo "</select></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Tarif de l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_fee]' value='" . get_option("activities_default")["cas_fee"] . "' size='40'></td>";
                echo "  </tr>";
                echo "  <tr valign='top'>";
                    echo "      <th scope='row'><label>Maximum de participants à l'activité&nbsp;</label></th><td><input type='text' name='activities_default[cas_maxparticipants]' value='" . get_option("activities_default")["cas_maxparticipants"] . "' size='3'></td>";
                echo "  </tr>";
            echo "</table>";

            echo "<p class='submit'><input type='submit' name='admin_options_submitted' class='button-primary' value='Sauvegarder les options'></p>";
        echo "</form><br>";

        // Section d'ajout et retrait des catégories d'activités
        echo "  <hr>";
        echo "  <h2>Ajout et retrait des catégories d'activités&nbsp;:</h2>";
        echo "<form action='" . esc_url($_SERVER["REQUEST_URI"]) . "' method='post'>";
            echo "  <table class='form-table'>";
            foreach ($categories as $category) {
                echo "      <tr valign='top'><th scope='row'><label>" . $category->category_name . "&nbsp;:</label></th><td><input type='checkbox' name='uaf_" . $category->category_name . "' " . ($category->category_active ? "checked" : "") . "></td></tr>";
            }
            echo "  <tr valign='top'><th scope='row'><label>Nouvelle catégorie d'activité&nbsp;</label></th><td><input type='text' name='new_activity' size='40'></td></tr>";
            echo "</table>";
            echo "<p class='submit'><input type='submit' name='categories_submitted' class='button-primary' value='Sauvegarger les catégories'></p>";
        echo "</form><br>";
    echo "</div>";
    
    // Mise-à-jour et insertion des catégories d'activités
    if (isset($_REQUEST["categories_submitted"])) {
        // Ajout d'uyne nouvelle catégorie d'activité
        if (isset($_REQUEST["new_activity"]) && trim($_REQUEST["new_activity"])!="") {
            $exists = false;
            $new_activity = ucfirst(strtolower(trim($_REQUEST["new_activity"])));
            foreach ($categories as $category) {
                if ($new_activity == $category->category_name) {
                    $exists = true;
                    echo "<h3>La catégorie d'activité " . $category->category_name . " existe déjà.</h3>";
                }
            }
            if (!$exists) {
                $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_categories (category_name, category_active) VALUES (%s, %s)", $new_activity, true));
            }
        }
        // Mise-à-jour des catégories d'activités existantes (activation/désactivation)
        foreach ($categories as $category) {
            if (isset($_REQUEST["uaf_" . $category->category_name])) {
                $wpdb->update($wpdb->prefix . "activity_categories", array("category_active" => true), array("category_id" => $category->category_id));
            }
            else {
                $wpdb->update($wpdb->prefix . "activity_categories", array("category_active" => false), array("category_id" => $category->category_id));
            }
        }
    }

}

?>