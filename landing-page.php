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
        <h1 id="pixel-name" class="pixel-animation">Chris Bamborough</h1>
    </div>
    
    <div class="landing-content">
        <p class="landing-intro"><?php echo get_the_content(); ?></p>
        
        <nav class="terminal-links">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/studio')); ?>" class="terminal-link">Studio Data</a></li>
                <li><a href="https://linkedin.com/in/your-linkedin" class="terminal-link" target="_blank">LinkedIn</a></li>
                <li><a href="https://substack.com/@yoursubstack" class="terminal-link" target="_blank">Substack</a></li>
                <li><a href="https://bsky.app/profile/yourprofile.bsky.social" class="terminal-link" target="_blank">Bluesky</a></li>
            </ul>
        </nav>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>