<aside>
<ul>
<!-- Insérer un formulaire de recherche -->
 <li id="search"><?php get_search_form(); ?></li>
 
 <!-- Insérer un Calendrier -->
 <li id="calendar"><h3>Calendrier</h3>
 <?php get_calendar(); ?>

 <!-- Insérer une liste de pages -->
<?php wp_list_pages('title_li=<h2>Pages</h2>'); ?>
</li>
</ul>
</aside> 