<?php
/*
Template Name: Activities
*/

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage od1kn0b
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <article class='page'>
            <h1>Toutes les activités - Thème OD1KN0B</h1>
            <?php
            $args = array('posts_per_page' => '-1', 'post_type' => 'Activites');
            $mesActivites = new WP_Query($args); 

            while ($mesActivites->have_posts() ) : $mesActivites->the_post(); ?>

                <section class="the_content">
                    <div class="the_title">
                        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>', true); ?>
                    </div>

                    <div class="the_excerpt">
                        <p>Sommaire&nbsp;:</p><?php the_excerpt(); ?>
                    </div>

                    <div class="the_terms">
                        <?php the_terms($post->ID, 'activity_categories', 'Catégorie d\'activité : ', ', ', ' '); ?>
                    </div>

                    <div class="the_meta">
                        <p>Infos&nbsp;:</p><?php the_meta(); ?>
                    </div>

                    <?php
                    // get_template_part( 'template-parts/page/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) : ?>
                    <div class="">
                        <?php comments_template(); ?>
                    </div>
                    <?php endif; ?>
                </section>

            <?php endwhile; // End of the loop.
            ?>
            </article>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
