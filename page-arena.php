<?php
/**
 * Template for displaying the Are.na channel grid page
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
<body <?php body_class('arena-grid-page'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <!-- Navigation Header -->
    <div class="terminal-header">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="terminal-back"> < HOME</a>
    </div>
    
    <!-- Logo and Menu Header -->
    <div class="page-header">
        <?php include get_stylesheet_directory() . '/studio-header.php'; ?>
    </div>
    
    <!-- Page Content -->
    <div class="page-content" id="arena-grid-content">
        <div class="arena-controls">
            <input type="text" class="terminal-search" placeholder="Search...">
        </div>
        
        <?php 
        // Use the specific "Photo Album" channel by default
        $channel = isset($_GET['channel']) ? sanitize_text_field($_GET['channel']) : 'photo-album-v7neum9xvi4';
        echo do_shortcode('[arena_channel channel="' . esc_attr($channel) . '"]'); 
        ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>