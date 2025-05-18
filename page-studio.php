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
    <div class="terminal-header">
         <a href="<?php echo esc_url(home_url('/')); ?>" class="terminal-back"> < HOME</a>
        </div>
<div class="studio-header-bar">
    <div class="ascii-title" tabindex="0" style="cursor:pointer;">
        <?php include get_stylesheet_directory() . '/svg-logo-studio.php'; ?>
    </div>
</div>  
    <div class="studio-category-menu">
    <?php
    $categories = get_categories([
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty' => false,
    ]);
    foreach ($categories as $cat) {
        echo '<a href="#" class="studio-category-link" data-cat-id="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</a>';
    }
    ?>
    </div>
    <?php echo do_shortcode('[terminal_database]'); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>