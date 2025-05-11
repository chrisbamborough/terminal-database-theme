<?php
/**
 * Template for displaying all single posts
 * 
 * @package Terminal_Database
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('terminal-body single-post'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <div class="terminal-header">
        <div class="terminal-navigation">
            <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="terminal-back">< BACK TO DATABASE</a>
        </div>
        <!-- <h1 class="database-title"><?php the_title(); ?></h1> -->
    </div>

    <div class="entry-content">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <div class="entry-meta">
                <?php 
                // Get categories
                $categories = get_the_category();
                if ($categories) : ?>
                    <div class="entry-categories">
                        <span class="meta-label">MEDIA:</span>
                        <?php foreach($categories as $category) : ?>
                            <span class="meta-value"><?php echo esc_html($category->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Get tags
                $tags = get_the_tags();
                if ($tags) : ?>
                    <div class="entry-tags">
                        <span class="meta-label">INTEREST:</span>
                        <?php foreach($tags as $tag) : ?>
                            <span class="meta-value"><?php echo esc_html($tag->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-date">
                    <span class="meta-label">DATE:</span>
                    <span class="meta-value"><?php echo get_the_date(); ?></span>
                </div>
            </div>

            <div class="entry-main">
                <div class="entry-text">
                    <?php the_content(); ?>
                </div>
            </div>
            
        <?php endwhile; endif; ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>