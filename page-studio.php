<?php
/**
 * Template for displaying the Studio page
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
<body <?php body_class('studio-database-page'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <!-- Logo and Menu Header with breadcrumbs -->
    <div class="page-header">
        <?php include get_stylesheet_directory() . '/studio-header.php'; ?>
    </div>
    
    <!-- Page Content -->
    <div class="page-content" id="studio-database-content">
        <?php echo do_shortcode('[terminal_database]'); ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>