<?php get_header(); ?> 
<section>
<?php if(have_posts()) : ?>


<div class="post" id="post-<?php the_ID(); ?>"> 
<?php while(have_posts()) : the_post(); 


 get_template_part( 'template-parts/content', get_post_format() );?>
 
 <h2><a href="<?php the_permalink(); ?>" title="<?php the_title();
?>"><?php the_title(); ?></a></h2>
 
 <?php the_content(); ?>
 
<!-- Méta données  --> 
 <p class="postmetadata">
<?php the_time('j F Y') ?> par <?php the_author();?> | Cat&eacute;gorie :
<?php the_category(', '); ?> | <?php comments_popup_link('Pas de
commentaires',
'1 Commentaire', '% Commentaires'); ?> <?php edit_post_link('Editer', '&#124;', ''); ?>
</p> 

<!-- Mots-clés -->
<p class="tags"><?php the_tags(); ?></p> 


<?php endwhile; ?>
<?php else : ?>
<h2 class="center">Aucun article trouvé. Essayez une autre recherche?</h2>
<?php include (TEMPLATEPATH . '/searchform.php');
get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>
<?php get_sidebar();?>
<?php get_footer();?>


</section> 