<?php
/**
 * Template for displaying single posts
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
<body <?php body_class('single-post-page'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <!-- Logo and Menu Header with breadcrumbs -->
    <div class="page-header">
        <?php include get_stylesheet_directory() . '/studio-header.php'; ?>
    </div>
    
    <!-- Page Content -->
    <div class="page-content" id="single-post-content">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo ' | <span class="categories">';
                            $cat_links = array();
                            foreach ($categories as $category) {
                                $cat_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . $category->name . '</a>';
                            }
                            echo implode(', ', $cat_links);
                            echo '</span>';
                        }
                        ?>
                    </div>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $tags = get_the_tags();
                    if ($tags) {
                        echo '<div class="tags-links">Tags: ';
                        $tag_links = array();
                        foreach ($tags as $tag) {
                            $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . $tag->name . '</a>';
                        }
                        echo implode(', ', $tag_links);
                        echo '</div>';
                    }
                    ?>
                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>