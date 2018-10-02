<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package OD1KN0B
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post(); ?>
            
            <div class='the_content'>
			<?php get_template_part( 'template-parts/content', get_post_type()); ?>
            </div>
            
            <?php
            $post_type = get_post_type();
            if ($post_type === "activites") { ?>
                
                <div class='the_terms'>
                    <?php the_terms($post->ID, 'activity_categories', '<span>Catégorie d\'activité&nbsp;:</span>', ', ', ' '); ?>
                </div>
                
                <div class='the_meta'>
                    <?php the_meta(); ?>
                </div>
 
                <div class="the_inscription">
                    <?php $inscription_link = get_page_link_by_title("Inscription à une activité - Thème OD1KN0B"); ?>
                    <p><a href='<?= $inscription_link . "?activity_id=" . $post->ID ?>'><button>S'inscrire</button></a></p>
                </div>
                
                <?php if (get_current_user_id() === get_the_author_meta('ID')) { ?>
                <div class="the_participants">
                    <?php $participants_link = get_page_link_by_title("Liste des participants - Thème OD1KN0B"); ?>
                    <p><a href='<?= $participants_link . "?activity_id="  . $post->ID ?>'><button>Liste des participants</button></a></p>
                <?php }

                $post = get_post();
                ?>
                <div class="meta_nav_box">
                    <h4>Autres activites à ce lieu (<?= get_post_meta($post->ID, "Lieu", true); ?>)&nbsp;:</h4>
                    <div class='meta_nav'>
                        <?php $meta_value = get_post_custom_values("Lieu", $post->ID);
                        meta_navigation($post->ID, "Lieu", $meta_value[0]); ?>
                    </div>
                </div>

                <div class='the_accueil'>
                    <?php $activites_link = get_page_link_by_title("Toutes les activités - Thème OD1KN0B"); ?>
                    <p><a href='<?= $activites_link ?>'><button>Retour aux activités</button></a></p>
                </div>

            <?php }
            else {
                the_post_navigation();
            }

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
