<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 * 
 * @package Terminal_Database
 */

// Don't use get_header() to avoid theme elements
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('terminal-body landing-page'); ?>>
<?php wp_body_open(); ?>

<div class="landing-container">
    <div class="terminal-header">
        <h1 id="pixel-name" class="pixel-animation">
            <span id="first-name">Christopher</span>
            <span id="last-name">Bamborough</span>
        </h1>
    </div>

        <!-- Links Section -->
        <div class="landing-links">
        <nav class="terminal-links">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/studio')); ?>" class="terminal-link">Studio DATA</a></li>
                <li><a href="<?php echo esc_url(home_url('/arena')); ?>" class="terminal-link">News</a></li>
                <li><a href="https://archmanu.com/" class="terminal-link" target="_blank">Arch_Manu</a></li>
                <li><a href="https://www.linkedin.com/in/chrisbamborough/" class="terminal-link" target="_blank">LinkedIn</a></li>
                <li><a href="https://www.youtube.com/@Smthspce/podcasts" class="terminal-link" target="_blank">smthspce::motion</a></li>


                <?php
                // Query for posts with the 'Landing_Page' tag
                $landing_page_items = new WP_Query(array(
                    'tag' => 'Landing_Page', // Replace with the slug of your tag
                    'posts_per_page' => -1, // Retrieve all posts with this tag
                ));

                // Loop through the posts and display them
                if ($landing_page_items->have_posts()) :
                    while ($landing_page_items->have_posts()) : $landing_page_items->the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" class="terminal-link" target="_blank">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
        </nav>
    </div>
    
    <!-- Text Content Section -->
    <div class="landing-text">
        <p class="landing-intro"><?php echo get_the_content(); ?></p>
    </div>
    
    <div id="landing-page-content">
        <!-- landing page content -->
    </div>

</div>

<?php wp_footer(); ?>
</body>
</html>