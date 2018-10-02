<?php

/*
Plugin Name: Gestion et inscription d'activités
Plugin URI: http://example.com
Description: Afficher toutes les activités d'un centre communautaire
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/

include ("activitiesOD_plugin_options.php");


/***********************/
/*    Installation     */
/***********************/
register_activation_hook(__FILE__, 'activities_install');
// Fonction d'installation
function activities_install() {
	global $wp_version;
	if (version_compare($wp_version, '3.5', '<')) {
		wp_die( 'Cette extension requiert WordPress version 3.5 ou plus.');
	}
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    // Création de la table wp_activity_categories
    $sql1 = "CREATE TABLE " . $wpdb->prefix . "activity_categories(
        category_id bigint (20) AUTO_INCREMENT PRIMARY KEY, 
        category_name varchar (255) NOT NULL,
        category_active boolean NOT NULL default true 
    )ENGINE=InnoDB;";
	dbDelta($sql1);
    // Création de la table wp_activity_periodicities
    $sql2 = "CREATE TABLE " . $wpdb->prefix . "activity_periodicities(
        periodicity_id bigint (20) AUTO_INCREMENT PRIMARY KEY, 
        periodicity_name varchar (255) NOT NULL, 
        periodicity_active boolean NOT NULL default true
    )ENGINE=InnoDB;";
    dbDelta($sql2);
    // Création de la table wp_activities
    $sql3 = "CREATE TABLE " . $wpdb->prefix . "activities(
        activity_id bigint (20) AUTO_INCREMENT PRIMARY KEY, 
        activity_title varchar (255) NOT NULL, 
        activity_start_date date NOT NULL, 
        activity_end_date date NOT NULL, 
        activity_category_id bigint (20) NOT NULL, 
        activity_place varchar (255) NOT NULL, 
        activity_animator varchar (255) NOT NULL, 
        activity_description varchar (255) NOT NULL, 
        activity_periodicity_id bigint (20) NOT NULL, 
        activity_fee decimal (15,2) NOT NULL, 
        activity_max_participants bigint (20) NOT NULL, 
        activity_owner_id bigint (20) UNSIGNED NOT NULL, 
        FOREIGN KEY (activity_category_id) REFERENCES " . $wpdb->prefix . "activity_categories(category_id), 
        FOREIGN KEY (activity_periodicity_id) REFERENCES " . $wpdb->prefix . "activity_periodicities(periodicity_id), 
        FOREIGN KEY (activity_owner_id) REFERENCES " . $wpdb->prefix . "users(ID)
    )ENGINE=InnoDB;";
    dbDelta($sql3);
    // Création de la table wp_activity_paticipations
    $sql4 = "CREATE TABLE " . $wpdb->prefix . "activity_paticipations(
        participation_id bigint (20) AUTO_INCREMENT PRIMARY KEY, 
        participation_activity_id bigint (20) NOT NULL, 
        participation_user_name varchar (255) NOT NULL, 
        participation_user_phone varchar (255) NOT NULL, 
        participation_user_email varchar (255) NOT NULL, 
        FOREIGN KEY (participation_activity_id) REFERENCES " . $wpdb->prefix . "activities(activity_id)
    )ENGINE=InnoDB;";
    dbDelta($sql4);
    // Insertion des périodicités
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Ponctuel (une seule fois)', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Tous les jours', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Tous les jours de semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Tous les jours de fin de semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", '6 fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", '5 fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", '4 fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", '3 fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", '2 fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois par semaine', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux deux semaines', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux trois semaines', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois par mois', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux 2 mois', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux 3 mois', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux 4 mois', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois aux 6 mois', true));
    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_periodicities (periodicity_name, periodicity_active) VALUES (%s, %s)", 'Une fois par année', true));
    // Création des options permissions et default
    $insert_permission_options = array("administrator" => "on");
    serialize($insert_permission_options);
    $insert_default_options = array("sas_activity_id" => "", "sas_name" => "", "sas_phone" => "", "sas_email" => "", "cas_title" => "", "cas_start_date" => "", "cas_end_date" => "", "cas_category" => "", "cas_place" => "", "cas_animator" => "", "cas_description" => "", "cas_periodicity" => "", "cas_fee" => "", "cas_maxparticipants" => "");
    serialize($insert_default_options);
    // print_r($insert_permission_options);
    // print_r($insert_default_options);
    add_option('activities_permissions', $insert_permission_options);
    add_option('activities_default', $insert_default_options);
}


/***********************/
/*    Désinstallation  */
/***********************/
register_deactivation_hook(__FILE__, 'activities_deactivate');
// Fonction de désactivation
function activities_deactivate() {
    // code pour la désactivation des options
    unregister_setting('activities-settings-group', 'activities_permissions');
    unregister_setting('activities-settings-group', 'activities_default');
    delete_option('activities_permissions');
    delete_option('activities_default');
    // Supprimer les tables dans la BD
    global $wpdb;
    $sql1 = "DROP TABLE " . $wpdb->prefix . "activity_paticipations";
    $wpdb->get_results($sql1);
    $sql2 = "DROP TABLE " . $wpdb->prefix . "activities";
    $wpdb->get_results($sql2);
    $sql3 = "DROP TABLE " . $wpdb->prefix . "activity_periodicities";
    $wpdb->get_results($sql3);
    $sql4 = "DROP TABLE " . $wpdb->prefix . "activity_categories";
    $wpdb->get_results($sql4);
}


// Fonction pour déterminer si l'usager actuel a les permission pour créer une activité
// Return le rôle de l'usager
function activity_user_permitted() {
    if (is_user_logged_in()) {
        // Récupérer une instance et le rôle de l’utilisateur courant
        $user_id = wp_get_current_user()->ID;
        $user = new WP_User($user_id);
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        // Aller chercher dans les options tous les rôles qui ont la permission
        $all_roles = get_all_existing_user_roles();
        foreach ($all_roles as $role) {
            if (isset(get_option("activities_permissions")[$role])) {
                $permitted_roles[] = $role;
            }
        }
        // Comparer les rôles qui ont la permission avec le role de l'utilisateur
        foreach ($permitted_roles as $permit_role) {
            if ($permit_role == $user_roles[0]) {
                return $user_roles[0];
            }
        }
    }
    return false;
}


// Fonction pour déterminer si l'usager actuel est le propriétaire (créateur) de l'activité ou administrateur
function activity_owner($activity_id) {
    if (activity_user_permitted()) {
        $role = activity_user_permitted();
        // echo $role;
        if ($role=="administrator") {
            return true;
        }
        global $wpdb;
        $activities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities;");
        foreach ($activities as $activity) {
            if ($activity_id == $activity->activity_id && $activity->activity_owner_id == get_current_user_id()) {
                return true;
            }
        }
    }
    return false;
}


// Fonction pour afficher le lien à la page d'ajout d'activité
function add_activity_link() {
    /*
    //$sql5 = '"' . $wpdb->prefix . 'options", array("option_name" => "activities_permissions", "option_value" => "' . $insert_permission_options . '", "autoload" => "yes"), array("%s", "%s", "%s")';
    //echo  $sql5;
    $wpdb->insert('"' . $wpdb->prefix . 'options", array("option_name" => "activities_permissions", "option_value" => "' . $insert_permission_options . '", "autoload" => "yes"), array("%s", "%s", "%s")');
    $sql5 = '"' . $wpdb->prefix . 'options", array("option_name" => "activities_permissions", "option_value" => "' . $insert_default_options . '", "autoload" => "yes"), array("%s", "%s", "%s")';
    $wpdb->insert($sql5);


    $sql5 = "INSERT INTO " . $wpdb->prefix . "options (option_name, option_value, autoload) VALUES (%s, %s, %s), 'activities_permissions', '" . $insert_permission_options . "', 'yes';";
    //$wpdb->query($wpdb->prepare($sql5));
    $sql6 = "INSERT INTO " . $wpdb->prefix . "options (option_name, option_value, autoload) VALUES (%s, %s, %s), 'activities_permissions', '" . $insert_default_options . "', 'yes';";
    //$wpdb->query($wpdb->prepare($sql6));
    */
    
    
    
    if (activity_user_permitted()) {
        echo "<p><a href='wordpress/ajouter-activite/'><button>Ajouter une activité</button></a></p>";
    }
}

// Fonction pour afficher les filtres de dates et de catégorie d'activité
function filter_activity_form() {
    $today = date("Y-m-d");
    global $wpdb;
    $categories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_categories;");
    echo "<h2>Filtrer les activités&nbsp;:</h2>";
    echo "<form action='" . esc_url($_SERVER["REQUEST_URI"]) . "' method='post'>";
        echo "<p>";
            echo "<label>Du&nbsp;</label>";
            echo "<input name='faf-start-date' type='date' size='10' min='" . $today . "' value='" . $today . "'>";
        echo "</p>";
        echo "<p>";
            echo "<label>Au&nbsp;:</label>";
            echo "<input name='faf-end-date' type='date' size='10' min='" . $today . "'>";
        echo "</p>";
        echo "<p>";
            echo "<label>Catégorie de l'activité&nbsp;:</label><br>";
            echo "<select name='faf-category'>";
                echo "<option disabled='disabled' selected='selected'>Toutes les catégories d'activités</option>";
                foreach ($categories as $cat_row) {
                    if ($cat_row->category_active) {
                        echo "<option value='" . $cat_row->category_id . "'>" . $cat_row->category_name . "</option>";
                    }
                }
            echo "</select>" ;
        echo "</p>";
        echo "<p><input type='submit' name='faf-submitted' value='Rechercher'></p>";
    echo "</form>";
}


// Fonction pour afficher toutes les activités
function display_activities() {
    $today = date("Y-m-d");
    // if the submit button is clicked
    if (isset($_REQUEST['faf-submitted'])) {
        // sanitize form values
        if (isset($_REQUEST['faf-start-date'])) {
            $start_date = sanitize_text_field($_REQUEST["faf-start-date"]);
        }
        else {
            $start_date = $today;
        }
        if (isset($_REQUEST['faf-end-date'])) {
            $end_date = sanitize_text_field($_REQUEST["faf-end-date"]);
        }
        if (isset($_REQUEST['faf-category'])) {
            $category = sanitize_text_field($_REQUEST["faf-category"]);
        }
        
        global $wpdb;
        // $query = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities", ARRAY_N);
        // ARRAY_N - result will be output as a numerically indexed array of numerically indexed arrays.
        $activities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities ORDER BY activity_start_date ASC;");
        foreach ($activities as $row) {
            if ($start_date <= $row->activity_end_date && (!isset($end_date) || $end_date <= $row->activity_end_date) && (!isset($category) || $category == $row->activity_category_id)) {
                echo "<hr>";
                echo "<h4>" . $row->activity_title . "</h4>";
                echo "<p>";
                echo "<br>Du : " . $row->activity_start_date;
                echo "<br>Au : " . $row->activity_end_date;
                $categories = $wpdb->get_results("SELECT category_name FROM " . $wpdb->prefix . "activity_categories WHERE category_id=" . $row->activity_category_id);
                echo "<br> Catégorie : " . $categories[0]->category_name . " ";
                echo "<br>Emplacement : " . $row->activity_place;
                echo "<br>Animateur : " . $row->activity_animator;
                echo "<br>Description : " . $row->activity_description;
                $periodicities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_periodicities WHERE periodicity_id=" . $row->activity_periodicity_id);
                echo "<br>Périodicité : " . $periodicities[0]->periodicity_name;
                echo "<br>Tarif : " . $row->activity_fee;
                echo "<br> Maximum de participants : " . $row->activity_max_participants;
                echo "<p><a href='wordpress/inscription?activity_id=" . $row->activity_id . "'><button>S'inscrire</button></a></p>";
                if (activity_owner($row->activity_id)) {
                    echo "<p><a href='wordpress/participants?activity_id="  . $row->activity_id . "'><button>Liste des participants</button></a></p>";
                }
                echo "</p>";
            }
        }
    }
}

//$wpdb::fetch


// Création du shortcode pour afficher les activités
function fa_shortcode() {
    ob_start(); // temporisation de sortie. Rien en sera envoyé au navigateur tant qu'on n'a pas fini.
    add_activity_link();
    filter_activity_form();
    display_activities();
    return ob_get_clean(); // fin de la temporisation de sortie.
}

add_shortcode( 'activities', 'fa_shortcode' );



/*
Plugin Name: Formulaire de création d'activité
Plugin URI: http://example.com
Description: Créer une activité d'un centre communautaire dans WordPress
Version: 1.0
Author: Oudayan Dutta
Author URI: http://exemple.com
*/


// Fonction d’affichage du formulaire de création d'activité :
function create_activity_form() {

    if (activity_user_permitted()) {
        $today = date("Y-m-d");
        global $wpdb;
        $categories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_categories;");
        $periodicities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_periodicities;");
        echo "<form action='" . esc_url($_SERVER["REQUEST_URI"]) . "' method='post'>";
            echo "<p>";
                echo "<label>Titre de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-title' value='" . get_option("activities_default")["cas_title"] . "' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Du&nbsp;:&nbsp;(requis)</label>";
                echo "<input type='date' name='caf-start-date' value='" . get_option("activities_default")["cas_start_date"] . "' size='10' min='" . $today . "' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Au&nbsp;:&nbsp;(requis)</label>";
                echo "<input type='date' name='caf-end-date' value='" . get_option("activities_default")["cas_end_date"] . "' size='10' min='" . $today . "' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Catégorie de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<select name='caf-category' required>";
                    echo "<option disabled='disabled' selected='selected'>Veuillez choisir une catégorie</option>";
                    foreach ($categories as $cat_row) {
                        if ($cat_row->category_active) {
                            echo "<option value='" . $cat_row->category_id . "' " . (get_option("activities_default")["cas_category"]==$cat_row->category_id ? "selected" : "") . ">" . $cat_row->category_name . "</option>";
                        }
                    }
                echo "</select>" ;
            echo "</p>";
            echo "<p>";
                echo "<label>Lieu de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-place' value='" . get_option("activities_default")["cas_place"] . "' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Animateur de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-animator' value='" . get_option("activities_default")["cas_animator"] . "' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Description de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-description' value='" . get_option("activities_default")["cas_description"] . "' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Périodicité de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<select name='caf-periodicity' required>";
                    echo "<option disabled='disabled' selected='selected'>Veuillez choisir une périodicité</option>";
                    foreach ($periodicities as $per_row) {
                        if ($per_row->periodicity_active) {
                            echo "      <option value='" . $per_row->periodicity_id . "' " . (get_option("activities_default")["cas_periodicity"]==$per_row->periodicity_id ? "selected" : "") . ">" . $per_row->periodicity_name . "</option>";
                        }
                    }
                echo "</select>" ;
            echo "</p>";
            echo "<p>";
                echo "<label>Tarif de l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-fee' value='" . get_option("activities_default")["cas_fee"] . "' size='40' required>";
            echo "</p>";
            echo "<p>";
                echo "<label>Maximum de participants à l'activité&nbsp;:&nbsp;(requis)</label><br>";
                echo "<input type='text' name='caf-maxparticipants' value='" . get_option("activities_default")["cas_maxparticipants"] . "' size='3' required>";
            echo "</p>";
            echo "<p><input type='submit' name='caf-submitted' value='Enregistrer'></p>";
        echo "</form>";
    }
    else {
        echo "Seuls les membres inscrits peuvent visualiser cette information.";
    }

}


// Fonction d’insertion d'activité dans la BD
function insert_activity() {
    $err_mess = "";
    $title = $sdate = $edate = $cat = $place =  $anim =  $desc =  $period =  $fee =  $maxpart =  $owner = "";
    $title_chk = $sdate_chk = $edate_chk = $cat_chk = $place_chk =  $anim_chk =  $desc_chk =  $period_chk =  $fee_chk =  $maxpart_chk =  $owner_chk = false;
    // if the submit button is clicked
    if (isset($_REQUEST["caf-submitted"])) {
        // Validate and sanitize form values
        if (isset($_REQUEST["caf-title"])) {
            $title = $_REQUEST["caf-title"];
            if (is_string($title) && trim($title)!="") {
                $title = sanitize_text_field($title);
                $title_chk = true;
            }
            else {
                $title_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de titre.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer un titre.";
        }
        if (isset($_REQUEST["caf-start-date"])) {
            $sdate = $_REQUEST["caf-start-date"];
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $sdate)) {
                $startDate = sanitize_text_field($sdate);
                $sdate_chk = true;
            }
            else {
                $sdate_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de date : AAAA-MM-JJ.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une date de début.";
        }
        if (isset($_REQUEST["caf-end-date"])) {
            $edate = $_REQUEST["caf-end-date"];
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $edate)) {
                $endDate = sanitize_text_field($edate);
                $edate_chk = true;
            }
            else {
                $edate_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de date : AAAA-MM-JJ.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une date de fin.";
        }
        if (isset($_REQUEST["caf-category"])) {
            $cat = $_REQUEST["caf-category"];
            if (is_numeric($cat) && $cat>0) {
                $category = sanitize_text_field($cat);
                $cat_chk = true;
            }
            else {
                $cat_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de catégorie.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une catégorie.";
        }
        if (isset($_REQUEST["caf-place"])) {
            $place = $_REQUEST["caf-place"];
            if (is_string($place) && trim($place)!="") {
                $place = sanitize_text_field($place);
                $place_chk = true;
            }
            else {
                $place_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide d'emplacement.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer un emplacement.";
        }
        if (isset($_REQUEST["caf-animator"])) {
            $anim = $_REQUEST["caf-animator"];
            if (is_string($anim) && trim($anim)!="") {
                $animator = sanitize_text_field($anim);
                $anim_chk = true;
            }
            else {
                $anim_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide d'animateur.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer un animateur.";
        }
        if (isset($_REQUEST["caf-description"])) {
            $desc = $_REQUEST["caf-description"];
            if (is_string($desc) && trim($desc)!="") {
                $description = sanitize_text_field($desc);
                $desc_chk = true;
            }
            else {
                $desc_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de description.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une description.";
        }
        if (isset($_REQUEST["caf-periodicity"])) {
            $period = $_REQUEST["caf-periodicity"];
            if (is_numeric($period) && $period>0) {
                $periodicity = sanitize_text_field($period);
                $period_chk = true;
            }
            else {
                $period_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de périodicité.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une périodicité.";
        }
        if (isset($_REQUEST["caf-fee"])) {
            $fee = $_REQUEST["caf-fee"];
            if (is_numeric($fee) && $fee>=0) {
                $fee = sanitize_text_field($fee);
                $fee_chk = true;
            }
            else {
                $fee_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de tarif.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer un tarif.";
        }
        if (isset($_REQUEST["caf-maxparticipants"])) {
            $maxpart = $_REQUEST["caf-maxparticipants"];
            if (is_numeric($maxpart) && $maxpart>0) {
                $maxparticipants = sanitize_text_field($maxpart);
                $maxpart_chk = true;
            }
            else {
                $maxpartv_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide de nombre de participants.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer un nombre de participants.";
        }
        if (is_user_logged_in()) {
            $activity_owner_id = get_current_user_id();
            $owner_chk = true;
        }
        else {
            $owner_chk = false;
            $err_mess .= "<br>Vous devez avoir les permissions pour créer une nouvelle activité.";
        }

        
        if ($title_chk && $sdate_chk && $edate_chk && $cat_chk && $place_chk &&  $anim_chk &&  $desc_chk &&  period_chk &&  $fee_chk &&  $maxpart_chk &&  $owner_chk) {
            // Code de traitement du formulaire : insertion dans la BD
            global $wpdb;
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activities (activity_title, activity_start_date, activity_end_date, activity_category_id, activity_place, activity_animator, activity_description, activity_periodicity_id, activity_fee, activity_max_participants, activity_owner_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", $title, $startDate, $endDate, $category, $place, $animator, $description, $periodicity, $fee, $maxparticipants, $activity_owner_id));
            echo "<h3>L'activité " . $title . " a été rajouté avec succès.</h3>";
        }
        else {
            echo "<h3>" . $err_mess ."</h3>";
        }
    }
}


// Fonction de création du shortcode pour insérer le formulaire
function activity_shortcode() {
    // temporisation de sortie. Rien en sera envoyé au navigateur tant qu'on n'a pas fini.
    ob_start();
    create_activity_form();
    insert_activity();
    // fin de la temporisation de sortie.
    return ob_get_clean();
}

add_shortcode( "create_activity", "activity_shortcode" );



/*
Plugin Name: Activities Inscription 
Plugin URI: http://example.com
Description: Inscription à une activité
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/

// Fonction pour chercher le titre d'une activité
function get_activity_title($activity_id) {
    global $wpdb;
    $titles = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities;");
    foreach ($titles as $row) {
        if ($row->activity_id == $activity_id) {
            $title = $row->activity_title;
        }
    }
    return $title;
}


// Fonction pour afficher le formulaire d'inscription à une activité
function subscribe_to_activity_form() {
    global $wpdb;
    if (isset($_REQUEST["activity_id"])) {
        $title = get_activity_title($_REQUEST["activity_id"]);
        echo "<h2>Inscription pour l'activité " . $title . "</h2>";
    }
    echo "<h3>Veuillez entrer les informations suivantes :</h3>";
    echo "<form method='get' action='" . esc_url($_SERVER["REQUEST_URI"]) . "'>";
    if (isset($_REQUEST["activity_id"])) {
        $activity_id = $_REQUEST["activity_id"];
        echo "  <input type='hidden' name='saf-activity-id' value='" . $activity_id . "'>";
    }
    else {
        $activities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activities;");
        echo "  <p><label>Activité&nbsp;:&nbsp;(requis)</label>";
        echo "  <select name='saf-activity-id' required>";
            echo "      <option value='0' disabled='disabled' selected='selected'>Veuillez choisir une activité</option>";
            foreach ($activities as $row) {
                echo "      <option value='" . $row->activity_id . "'>" . $row->activity_title . "</option>";
            }
        echo "  </select></p>";
    }
    echo "  <p><label>Nom&nbsp;:&nbsp;(requis)</label><input type='text' name='saf-name' value='" . get_option("activities_default")["sas_name"] . "' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required></p>";
    echo "  <p><label>Téléphone&nbsp;:&nbsp;(requis)</label><input type='text' name='saf-phone' value='" . get_option("activities_default")["sas_phone"] . "' pattern='[0-9 \-\(\)]+' size='40' required></p>";
    echo "  <p><label>Courriel&nbsp;:&nbsp;(requis)</label><input type='email' name='saf-email' value='" . get_option("activities_default")["sas_email"] . "' pattern='[a-zA-Z0-9.@]+' size='40' required></p>";
    echo "  <p><input type='submit' name='saf-submitted' value='Inscription'></p>";
    echo "</form>";
}


// Fonction pour insérer un participant à une activité
function insert_activity_participant() {
    $err_mess = "";
    $id_chk = $name_chk = $tel_chk = $email_chk = false;
    // if the submit button is clicked
    if (isset($_REQUEST["saf-submitted"])) {
        // Validate and sanitize form values
        if (isset($_REQUEST["saf-activity-id"])) {
            $id = $_REQUEST["saf-activity-id"];
            if (is_numeric($id) && $id>0) {
                $participation_activity_id = sanitize_text_field($id);
                $id_chk = true;
            }
            else {
                $id_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide d'activité.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer une activité.";
        }
        if (isset($_REQUEST["saf-name"])) {
            $name = $_REQUEST["saf-name"];
            if (is_string($name) && trim($name)!="" && preg_match("/^[a-zA-Z -.]*$/",$name)) {
                $participation_name = sanitize_text_field($name);
                $name_chk = true;
            }
            else {
                $name_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide pour votre nom.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer votre nom.";
        }
        if (isset($_REQUEST["saf-phone"])) {
            $tel = $_REQUEST["saf-phone"];
            if (preg_match("/^(([2-9][\d]{2}( |-)?)([2-9][\d]{2}( |-)?)([\d]{4}))$/",$tel)) {
                $participation_phone = sanitize_text_field($tel);
                $tel_chk = true;
            }
            else {
                $tel_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide pour votre téléphone.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer votre numéro de téléphone.";
        }
        if (isset($_REQUEST["saf-email"])) {
            $email = $_REQUEST["saf-email"];
            if (is_email($email)) {
                $participation_email = sanitize_text_field($email);
                $email_chk = true;
            }
            else {
                $email_chk = false;
                $err_mess .= "<br>Veuillez entrer un format valide pour votre courriel.";
            }
        }
        else {
            $err_mess .= "<br>Veuillez entrer votre adresse courriel.";
        }
        
        if ($id_chk && $name_chk && $tel_chk && $email_chk) {
            // Code de traitement du formulaire : insertion dans la BD
            global $wpdb;
            $participants = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_paticipations;");
            $unique = true;
            foreach ($participants as $row) {
                if ($row->participation_activity_id == $participation_activity_id && $row->participation_user_email == $participation_email) {
                    $unique = false;
                }
            }
            if ($unique) {
                $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "activity_paticipations (participation_activity_id, participation_user_name, participation_user_phone, participation_user_email) VALUES (%s, %s, %s, %s)", $participation_activity_id, $participation_name, $participation_phone, $participation_email));
                echo "<h3>Votre inscription s'est effectuée avec succès.</h3>";
            }
            else {
                echo "<h3>Vous êtes déjà inscrits à cette activité.</h3>";
            }
        }
        else {
            echo "<h3>" . $err_mess . "</h3>";
        }
    }
}


// Création du shortcode pour insérer le formulaire de participation à une activité
function iaf_shortcode() {
    ob_start(); // temporisation de sortie. Rien en sera envoyé au navigateur tant qu'on n'a pas fini.
    subscribe_to_activity_form();
    insert_activity_participant();
    return ob_get_clean(); // fin de la temporisation de sortie.
}

add_shortcode( "subscribe_to_activity", "iaf_shortcode" );



/*
Plugin Name: Afficher les participants d'une activité 
Plugin URI: http://example.com
Description: Afficher les participants d'une activité
Version: 1.0
Author: OD1 K. No B.
Author URI: http://exemple.com
*/

// Fonction pour afficher les participants d'une activité
function display_activity_participants() {
    if (isset($_REQUEST["activity_id"])) {
        $id = $_REQUEST["activity_id"];
        if (activity_owner($id)) {
            $title = get_activity_title($id);
            echo "<h2>Liste de participants de l'activité " . $title . "</h2>";
            global $wpdb;
            $participants = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_paticipations WHERE participation_activity_id='" . $id . "';");
            $cnt = 0;
            echo "<table>";
            echo "<tr><th># </th><th>Nom : </th><th>Téléphone : </th><th>Courriel : </th></tr>";
            foreach ($participants as $row) {
                $cnt++;
                echo "<tr><td>" . $cnt . "</td><td>" . $row->participation_user_name . "</td><td>" . $row->participation_user_phone . "</td><td>" . $row->participation_user_email . "</td></tr>";
            }
            echo "</table>";
        }
        else {
            echo "Seuls le créateur de cette activité et l'administrateur peuvent visualiser cette information.";
        }
    }
    else {
        echo "<h3>Aucune activité n'a été sélectionnée</h3>";
    }
}

// Créer un shortcode pour insérer le formulaire de participation à une activité
add_shortcode( "participants", "display_activity_participants" );


?>