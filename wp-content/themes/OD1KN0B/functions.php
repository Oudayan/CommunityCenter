<?php
/**
 * OD1KN0B functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package OD1KN0B
 */

if ( ! function_exists( 'od1kn0b_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function od1kn0b_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on OD1KN0B, use a find and replace
		 * to change 'od1kn0b' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'od1kn0b', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

        add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

        add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'top' => esc_html__( 'Top menu', 'od1kn0b' ), 
     	) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'od1kn0b_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
        
        /*
         * Création de la table wp_activity_paticipations
         */
        create_activity_participations_table();
	}
endif;
add_action( 'after_setup_theme', 'od1kn0b_setup' );



/*
 * Fonction de création de la table wp_activity_paticipations
 */
function create_activity_participations_table() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "activity_paticipations(
        participation_id bigint (20) AUTO_INCREMENT PRIMARY KEY, 
        participation_activity_id bigint (20) UNSIGNED, 
        participation_user_name varchar (255) NOT NULL, 
        participation_user_phone varchar (255) NOT NULL, 
        participation_user_email varchar (255) NOT NULL, 
        FOREIGN KEY (participation_activity_id) REFERENCES " . $wpdb->prefix . "posts(ID) ON DELETE SET NULL
    )ENGINE=InnoDB;";
    dbDelta($sql);
}


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function od1kn0b_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'od1kn0b_content_width', 525 );
}
add_action( 'after_setup_theme', 'od1kn0b_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function od1kn0b_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'od1kn0b' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'od1kn0b' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'od1kn0b_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function od1kn0b_scripts() {
	wp_enqueue_style( 'od1kn0b-style', get_stylesheet_uri() );

	wp_enqueue_script( 'od1kn0b-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'od1kn0b-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'od1kn0b_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


/*
* Fonction de création du post personnalisé activites
*/
function activites_post_type() {
    // On définit les labels pour le post personnalisé
    $labels = array(
        'name' => _x('Activités', 'Post Type General Name', 'od1kn0b'), 
        'singular_name' => _x('Activités', 'Post Type Singular Name', 'od1kn0b'), 
        'menu_name' => __('Activités', 'od1kn0b'), 
        'parent_item_colon' => __('Parent Film', 'od1kn0b'), 
        'all_items' => __('Toutes les activités', 'od1kn0b'), 
        'view_item' => __('Afficher une activité', 'od1kn0b'), 
        'add_new_item' => __('Ajouter une nouvelle activité', 'od1kn0b'), 
        'add_new' => __('Nouvelle activité', 'od1kn0b'), 
        'edit_item' => __('Editer activité', 'od1kn0b'), 
        'update_item' => __('Mettre à jour activité', 'od1kn0b'), 
        'search_items' => __('Rechercher activité', 'od1kn0b'), 
        'not_found' => __('Non trouvé', 'od1kn0b'), 
        'not_found_in_trash' => __('Non trouvé dans la corbeille', 'od1kn0b')
    );
    // On définit les autres options pour le post personnalisé
    $args = array(
        'label' => __('Activites', 'od1kn0b'), 
        'description' => __('Activites du centre communautaire OD', 'od1kn0b'), 
        'labels' => $labels, 
        // On peut l'éditer dans l'éditeur de posts, définir un résumé, des champs personnalisés, ..
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'author'), 
        // On l'associe avec une taxonomie (ici genres).
        'taxonomies' => array('activity_categories'), 
        /* Un post personnalisé hiérarchique est comme une Page et peut avoir un Parent et des enfants. Un PP non-hiérarchique est comme un article. */
        'hierarchical' => true, 
        'public' => true, 
        'show_ui' => true, 
        'show_in_menu' => true, 
        'show_in_nav_menus' => true, 
        'show_in_admin_bar' => true, 
        'menu_position' => 5, 
        'can_export' => true, 
        'has_archive' => true, 
        'exclude_from_search' => false, 
        'publicly_queryable' => true, 
        //'capability_type' => 'post', 
        'capability_type' => array('activity', 'activities'), 
        'map_meta_cap' => true
    );
    // Enregistrer le Type de Post personnalisé
    register_post_type('Activites', $args);
}

add_action('init', 'activites_post_type', 0);



/**
* Créer une taxonomie
*/
function define_activities_categories_taxonomy() {
    register_taxonomy(
        'activity_categories', 
        'activites', 
        array(
            'hierarchical' => true,
            'label' => 'Catégories d\'activites', 
            'query_var' => true, 
            'rewrite' => true
        )
    );
}

add_action('init', 'define_activities_categories_taxonomy');



/**
* Donner les permissions aux création d'activités et catégories d'activités
*/
function activities_add_role_caps() {
    $roles = array('administrator', 'editor', 'contributor');
    foreach ($roles as $the_role) { 
        $role = get_role($the_role);
        $role->add_cap( 'read' );
        $role->add_cap( 'read_activity');
        $role->add_cap( 'edit_activity' );
        $role->add_cap( 'edit_activities' );
        if ($the_role == 'administrator' || $the_role == 'editor') {
            $role->add_cap( 'publish_activities' );
            $role->add_cap( 'edit_published_activities' );
            $role->add_cap( 'read_private_activities' );
            $role->add_cap( 'delete_private_activities' );
            $role->add_cap( 'delete_published_activities' );
            $role->add_cap( 'edit_others_activities' );
            $role->add_cap( 'delete_others_activities' );
        }
    }
}

add_action('admin_init','activities_add_role_caps',999);



/**
* Définir les custom fields dans le formulaire
*/
function set_custom_fields($post_id) {
    add_post_meta($post_id, "Lieu", "", true );
    add_post_meta($post_id, "Date", "", true );
    add_post_meta($post_id, "Animateur", "", true );
}

add_action('wp_insert_post', 'set_custom_fields');


/**
* Fonction de création de pages défaut du thème
*/
function create_page($page_title, $page_name, $page_content, $menu_order, $page_template) {
    if (isset($_REQUEST["activated"])) {
        $args = array (
            "post_type" => "page", 
            "post_author" => 1, 
            "post_title" => $page_title, 
            "post_name" => $page_name, 
            "post_content" => $page_content, 
            "post_status" => "publish", 
            "menu_order" => $menu_order
        );

        $new_page = wp_insert_post($args);

        if (!empty($page_template)) {
            update_post_meta($new_page, '_wp_page_template', $page_template);
        }
    }
}

// Création des pages défaut du thème OD1KN0B
function create_all_pages() {
    create_page("Accueil - Thème OD1KN0B", "Accueil_od1kn0b", "[activities_home]", 0, "default");
    create_page("Toutes les activités - Thème OD1KN0B", "Activites_od1kn0b", "", 1, "page_activity.php");
    create_page("Inscription à une activité - Thème OD1KN0B", "Inscription_od1kn0b", "[subscribe_to_activity]", 2, "default");
    create_page("Liste des participants - Thème OD1KN0B", "Participants_od1kn0b", "[participants]", 3, "default");
}

add_action("init", "create_all_pages");


/**
* Fonction pour créer le lien d'une page par son titre
*/
function get_page_link_by_title($title) {
    $page = get_page_by_title($title);
    if (isset($page->ID)) {
        $page_link = get_page_link($page->ID);
        return $page_link;
    }
    else {
        return false;
    }
}


/**
* Fonction de création des menus défauts du thème
*/
function create_menus($items, $args) {
    $page_accueil = get_page_by_title("Accueil - Thème OD1KN0B");
    if (isset($page_accueil->ID)) {
        $accueil_link = get_page_link($page_accueil->ID);
        $items .= "<li><a href='" . $accueil_link . "'>Accueil</a></li>";
    }
    $page_activites = get_page_by_title("Toutes les activités - Thème OD1KN0B");
    if (isset($page_activites->ID)) {
        $activites_link = get_page_link($page_activites->ID);
        $items .= "<li><a href='" . $activites_link . "'>Toutes les activités</a></li>";
    }
    $page_inscription = get_page_by_title("Inscription à une activité - Thème OD1KN0B");
    if (isset($page_inscription->ID)) {
        $inscription_link = get_page_link($page_inscription->ID);
        $items .= "<li><a href='" . $inscription_link . "'>Inscription à une activité</a></li>";
    }
    return $items;
}

add_filter("wp_nav_menu_items", "create_menus", 10, 2);


/**
* Fonction pour changer l'option de la page défaut du site
*/
function set_home_page() {
    update_option('show_on_front', 'page');
    $page_accueil = get_page_by_title("Accueil - Thème OD1KN0B");
    update_option('page_on_front', $page_accueil->ID);
}

add_action('after_setup_theme', 'set_home_page');


/**
* Fonction pour effacer une page par son titre
*/
function delete_page($title) {
    $page = get_page_by_title($title);
     if (isset($page->ID)) {
        wp_delete_post($page->ID, true);
    }
}

/**
* Fonction pour effacer les pages défaut du thème à la désinstallation (changement de thème)
*/
function delete_theme_params() {
    delete_page("Accueil - Thème OD1KN0B");
    delete_page("Toutes les activités - Thème OD1KN0B");
    delete_page("Inscription à une activité - Thème OD1KN0B");
    delete_page("Liste des participants - Thème OD1KN0B");
}

add_action("switch_theme", "delete_theme_params");


/**
* Code pour l'affichage de la page d'accueil
*/
function activities_home() {
    $args = array('posts_per_page' => '3', 'post_type' => 'Activites');
    $mesActivites = new WP_Query($args); 

    while ($mesActivites->have_posts()) : $mesActivites->the_post(); ?>

        <section class="the_content">
            <div class="the_title">
                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>', true); ?>
            </div>

            <div class="the_excerpt">
                <span>Sommaire&nbsp;:</span><?php the_excerpt(); ?>
            </div>

            <div class="the_terms">
            <?php 
                $post = get_post();
                the_terms($post->ID, 'activity_categories', '<span>Catégorie d\'activité&nbsp;:</span>', ', ', ' ');
            ?>
            </div>

            <div class="the_meta">
                <span>Infos&nbsp;:</span><?php the_meta(); ?>
            </div>
        </section>
        
    <?php endwhile; // End of the loop.
}


// Créer un shortcode pour insérer le formulaire de participation à une activité
add_shortcode( "activities_home", "activities_home" );



/**
* Liens de navigation entre activités d'un même lieu
*/
function meta_navigation($post_id, $meta_key, $meta_value) {
    global $wpdb;
    // Chercher tous les posts qui ont les même données metas définies
    $same_meta = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "postmeta m JOIN " . $wpdb->prefix . "posts p ON m.post_id=p.ID WHERE p.post_type='activites' AND p.post_status='publish' AND m.meta_key='" . $meta_key . "' AND m.meta_value='" . $meta_value . "';");
    // Nombre de posts avec les même données metas
    $cnt_meta = count($same_meta);
    for ($i=0; $i<$cnt_meta; $i++) {
        // Aller chercher l'ID du post précédent dans le tableau
        if ($i > 0 && $same_meta[$i]->ID == $post_id) {
            $prevID = $same_meta[($i-1)]->ID;
        }
        // Aller chercher l'ID du prochain post dans le tableau
        if ($i < ($cnt_meta-1) && $same_meta[$i]->ID == $post_id) {
            $nextID = $same_meta[($i+1)]->ID;
        }
    }
    // Link "previous activity"
    if (!empty($prevID)) { ?>
        <div class='meta_nav_L'>
            <p><a href="?p=<?= $prevID; ?>"><button>&lt;&lt; Activité précédente&nbsp;: <?= get_the_title($prevID) ?></button></a></p>
        </div>
    <?php }
    // Link "next activity"
    if (!empty($nextID)) { ?>
        <div class='meta_nav_R'>
            <p><a href="?p=<?= $nextID; ?>"><button>Prochaine activité&nbsp;: <?= get_the_title($nextID) ?> &gt;&gt;</button></a></p>
        </div>
    <?php }
}



// Fonction pour afficher le formulaire d'inscription à une activité
function subscribe_to_activity_form() {
    global $wpdb;
    if (isset($_REQUEST["activity_id"])) { ?>
        <h2>Inscription pour l'activité <?= get_the_title($_REQUEST["activity_id"]) ?></h2>
    <?php } ?>
    <h3>Veuillez entrer les informations suivantes :</h3>
    <form method='post' action ="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>">
        
        <table>
        <?php if (isset($_REQUEST["activity_id"])) { ?>
            <input type='hidden' name='saf-activity-id' value='<?= $_REQUEST["activity_id"] ?>'>
        <?php  }
        else {
            $activities = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type='activites' AND post_status='publish';");
            //echo "bug 1!!!!!"; ?>
            <tr><td><label for saf-activity-id>Activité&nbsp;:&nbsp;(requis)&nbsp;</label></td>
            <td><select name='saf-activity-id' required>
                <option value='0' disabled='disabled' selected='selected'>Veuillez choisir une activité</option>
                <?php
                foreach ($activities as $row) { ?>
                    <option value='<?= $row->ID ?>'><?= $row->post_title ?></option>
                <?php } ?>
            </select></td>
        <?php } //echo "bug 2!!!!!"; ?>
        <tr><td><label for saf-name>Nom&nbsp;:&nbsp;(requis)&nbsp;</label></td><td><input type='text' name='saf-name' value='<?= get_option("activities_default")["sas_name"] ?>' pattern='[a-zéèêëàâçîïôA-ZÉÈÊËÀÂÇÎÏ0-9 ._\-\(\)]+' size='40' required></td></tr>
        <tr><td><label for saf-phone>Téléphone&nbsp;:&nbsp;(requis)</label></td><td><input type='text' name='saf-phone' value='<?= get_option("activities_default")["sas_phone"] ?>' pattern='[0-9 \-\(\)]+' size='40' required></td></tr>
        <tr><td><label for saf-email>Courriel&nbsp;:&nbsp;(requis)</label></td><td><input type='email' name='saf-email' value='<?= get_option("activities_default")["sas_email"] ?>' pattern='[a-zA-Z0-9.@]+' size='40' required></td></tr>
    </table>

    <p><button type='submit' name='saf-submitted'>Inscription</button></p>
    </form>
    <?php if (isset($_REQUEST["activity_id"])) { ?>
    <div class='the_activity'>
        <p><a href='<?= get_post_permalink($_REQUEST["activity_id"]) ?>'><button>Retour à l'activité <?= get_the_title($_REQUEST["activity_id"]) ?></button></a></p>
    </div>
    <?php } ?>
    <div class='the_accueil'>
        <?php $activites_link = get_page_link_by_title("Toutes les activités - Thème OD1KN0B"); ?>
        <p><a href='<?= $activites_link ?>'><button>Retour aux activités</button></a></p>
    </div>
    
<?php }


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
                $err_mess .= "<br><span class='error'>Veuillez entrer un format valide d'activité.</span>";
            }
        }
        else {
            $err_mess .= "<br><span class='error'>Veuillez entrer une activité.</span>";
        }
        if (isset($_REQUEST["saf-name"])) {
            $name = $_REQUEST["saf-name"];
            if (is_string($name) && trim($name)!="" && preg_match("/^[a-zA-Z -.]*$/",$name)) {
                $participation_name = sanitize_text_field($name);
                $name_chk = true;
            }
            else {
                $name_chk = false;
                $err_mess .= "<br><span class='error'>Veuillez entrer un format valide pour votre nom.</span>";
            }
        }
        else {
            $err_mess .= "<br><span class='error'>Veuillez entrer votre nom.</span>";
        }
        if (isset($_REQUEST["saf-phone"])) {
            $tel = $_REQUEST["saf-phone"];
            if (preg_match("/^(([2-9][\d]{2}( |-)?)([2-9][\d]{2}( |-)?)([\d]{4}))$/",$tel)) {
                $participation_phone = sanitize_text_field($tel);
                $tel_chk = true;
            }
            else {
                $tel_chk = false;
                $err_mess .= "<br><span class='error'>Veuillez entrer un format valide pour votre téléphone.</span>";
            }
        }
        else {
            $err_mess .= "<br><span class='error'>Veuillez entrer votre numéro de téléphone.</span>";
        }
        if (isset($_REQUEST["saf-email"])) {
            $email = $_REQUEST["saf-email"];
            if (is_email($email)) {
                $participation_email = sanitize_text_field($email);
                $email_chk = true;
            }
            else {
                $email_chk = false;
                $err_mess .= "<br><span class='error'>Veuillez entrer un format valide pour votre courriel.</span>";
            }
        }
        else {
            $err_mess .= "<br><span class='error'>Veuillez entrer votre adresse courriel.</span>";
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
                echo "<h3><span class='success'>Votre inscription s'est effectuée avec succès.</span></h3>";
            }
            else {
                echo "<h3><span class='error'>Vous êtes déjà inscrits à cette activité.</span></h3>";
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



// Fonction pour afficher les participants d'une activité
function display_activity_participants() {
    if (isset($_REQUEST["activity_id"])) {
        $id = $_REQUEST["activity_id"];
        $author = get_post($id)->post_author;
        if (get_current_user_id() == get_post($id)->post_author) { ?>
            <h2>Liste de participants de l'activité <?= get_the_title($id) ?></h2>
            <?php global $wpdb;
            $participants = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "activity_paticipations WHERE participation_activity_id='" . $id . "';");
            $cnt = 0; ?>
            <table>
                <tr><th># </th><th>Nom : </th><th>Téléphone : </th><th>Courriel : </th></tr>
                <?php foreach ($participants as $row) {
                $cnt++; ?>
                <tr><td>" . $cnt . "</td><td>" . $row->participation_user_name . "</td><td>" . $row->participation_user_phone . "</td><td>" . $row->participation_user_email . "</td></tr>
            <?php } ?>
            </table>
            <div class='the_activity'>
                <p><a href='<?= get_post_permalink($_REQUEST["activity_id"]) ?>'><button>Retour à l'activité <?= get_the_title($_REQUEST["activity_id"]) ?></button></a></p>
            </div>
            <div class='the_accueil'>
                <?php $activites_link = get_page_link_by_title("Toutes les activités - Thème OD1KN0B"); ?>
                <p><a href='<?= $activites_link ?>'><button>Retour aux activités</button></a></p>
            </div>

        <?php }
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

