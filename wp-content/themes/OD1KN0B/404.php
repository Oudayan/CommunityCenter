<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package OD1KN0B
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Bienvenue au Twilight Zone du site !', 'od1kn0b' ); ?></h1>
				</header><!-- .page-header -->
                <div class="404-img">
                    <div class="page-content">
                        <p><?php esc_html_e( 'Cette page est perdue dans le néant. Vous pouvez essayer de faire une recherche ...', 'od1kn0b' ); ?></p>

                        <?php
                            get_search_form();

                            /* translators: %1$s: smiley */
                            $archive_content = '<p>' . sprintf( esc_html__( '.. ou recherchez dans les archives. %1$s', 'od1kn0b' ), convert_smilies( ':)' ) ) . '</p>';
                            the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

                            the_widget( 'WP_Widget_Tag_Cloud' );
                        ?>

                    </div><!-- .page-content -->
                </div>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
