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
<body <?php body_class('terminal-body'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <div class="terminal-header">
        <div class="terminal-back-link">
            <a href="<?php echo esc_url(home_url()); ?>">← Back Home</a>
        </div>
        <br>
        <pre class="ascii-title">
  ____  _             _ _          ____        _        
 / ___|| |_ _   _  __| (_) ___    |  _ \  __ _| |_ __ _ 
 \___ \| __| | | |/ _` | |/ _ \   | | | |/ _` | __/ _` |
  ___) | |_| |_| | (_| | | (_) |  | |_| | (_| | || (_| |
 |____/ \__|\__,_|\__,_|_|\___/   |____/ \__,_|\__\__,_|
                                                        
        </pre>
    </div>
    
    <?php echo do_shortcode('[terminal_database]'); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>