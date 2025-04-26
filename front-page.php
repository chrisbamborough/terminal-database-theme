<?php
/**
 * Template for displaying the front page
 * 
 * @package Terminal_Database
 */

// Get the front page content
if (have_posts()) :
    while (have_posts()) :
        the_post();
        
        // Use the landing page template
        include(get_stylesheet_directory() . '/landing-page.php');
    endwhile;
endif;