<?php
/**
 * Template for displaying the Writing page
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
<body <?php body_class('writing-page'); ?>>
<?php wp_body_open(); ?>

<div class="terminal-container">
    <!-- Logo and Menu Header with breadcrumbs -->
    <div class="page-header">
        <?php include get_stylesheet_directory() . '/studio-header.php'; ?>
    </div>
    
    <!-- Page Content -->
    <div class="page-content" id="writing-content">
        <div class="writing-grid">
            <!-- Articles Column -->
            <div class="writing-column">
                <h2 class="column-header"><a href="<?php echo esc_url(get_category_link(get_cat_ID('article'))); ?>">latest writing/</a></h2>
                <div class="column-content">
                    <?php
                    $articles = new WP_Query(array(
                        'category_name' => 'article',
                        'posts_per_page' => 1,
                        'post_status' => 'publish'
                    ));
                    
                    if ($articles->have_posts()) :
                        while ($articles->have_posts()) : $articles->the_post();
                    ?>
                        <div class="featured-post">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h3 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="post-date"><?php echo get_the_date('Y-m'); ?></div>
                            </div>
                        </div>
                    <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
                <div class="column-footer">
                    <a href="#writing-collection" class="view-all-link">Visit the writing collection/</a>
                </div>
            </div>

            <!-- Blog Column (Arena Feed) -->
            <div class="writing-column">
                <h2 class="column-header"><a href="<?php echo esc_url(home_url('/arena/')); ?>">visual blog/</a></h2>
                <div class="column-content">
                    <?php
                    // Get arena feed data directly from API
                    $channel_slug = 'photo-album-v7neum9xvi4'; // Use the same channel as your arena page
                    $api_url = "https://api.are.na/v2/channels/{$channel_slug}";
                    
                    $response = wp_remote_get($api_url);
                    
                    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                        $body = wp_remote_retrieve_body($response);
                        $data = json_decode($body, true);
                        
                        if ($data && isset($data['contents']) && !empty($data['contents'])) {
                            $latest_block = $data['contents'][0]; // Get the most recent block
                    ?>
                        <div class="featured-post">
                            <?php if (isset($latest_block['image']) && $latest_block['image']): ?>
                                <div class="post-image">
                                    <img src="<?php echo esc_url($latest_block['image']['large']['url']); ?>" alt="<?php echo esc_attr($latest_block['title'] ?? 'Arena block'); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h3 class="post-title"><?php echo esc_html($latest_block['title'] ?? 'Untitled'); ?></h3>
                                <div class="post-excerpt">
                                    <?php 
                                    if (isset($latest_block['description_html']) && $latest_block['description_html']) {
                                        echo wp_trim_words(strip_tags($latest_block['description_html']), 20, '...');
                                    } elseif (isset($latest_block['content']) && $latest_block['content']) {
                                        echo wp_trim_words(strip_tags($latest_block['content']), 20, '...');
                                    } else {
                                        echo 'Recent addition to the arena feed.';
                                    }
                                    ?>
                                </div>
                                <div class="post-date">
                                    <?php 
                                    if (isset($latest_block['created_at'])) {
                                        echo date('Y-m', strtotime($latest_block['created_at']));
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        } else {
                            echo '<div class="featured-post"><div class="post-content"><p>No arena content available.</p></div></div>';
                        }
                    } else {
                        // Fallback to any recent post if arena API fails
                        $recent_posts = new WP_Query(array(
                            'posts_per_page' => 1,
                            'post_status' => 'publish'
                        ));
                        
                        if ($recent_posts->have_posts()) :
                            while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    ?>
                        <div class="featured-post">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h3 class="post-title"><?php the_title(); ?></h3>
                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="post-date"><?php echo get_the_date('Y-m'); ?></div>
                            </div>
                        </div>
                    <?php
                            endwhile;
                        endif;
                        wp_reset_postdata();
                    }
                    ?>
                </div>
                <div class="column-footer">
                    <a href="<?php echo esc_url(home_url('/arena/')); ?>" class="view-all-link">Explore the blog/</a>
                </div>
            </div>

            <!-- Imgexhaust Column -->
            <div class="writing-column">
                <h2 class="column-header"><a href="#">curated data/</a></h2>
                <div class="column-content">
                    <div class="imgexhaust-posts" id="imgexhaust-feed" data-channel="data-1yesrmpc-ko">
                        <!-- Fallback content -->
                        <div class="imgexhaust-item">
                            <div class="post-title">Loading...</div>
                            <div class="post-meta">
                                <span class="post-type">—</span>
                                <span class="post-date">—</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column-footer">
                    <a href="<?php echo esc_url(home_url('/arenadata/')); ?>" class="view-all-link">Go to the feed/</a>
                </div>
            </div>
        </div>

        <!-- Writing Collection Section -->
        <div class="all-writing-section" id="writing-collection">
            <h2 class="section-header">Writing Collection</h2>
            <div class="writing-posts-grid">
                <?php
                // Get the writing category and its children
                $writing_cat = get_category_by_slug('writing');
                $writing_cat_ids = array();
                
                if ($writing_cat) {
                    $writing_cat_ids[] = $writing_cat->term_id;
                    // Get child categories
                    $child_cats = get_categories(array(
                        'child_of' => $writing_cat->term_id,
                        'hide_empty' => false
                    ));
                    foreach ($child_cats as $child_cat) {
                        $writing_cat_ids[] = $child_cat->term_id;
                    }
                }
                
                // Query all posts in writing category and its children
                $writing_posts = new WP_Query(array(
                    'category__in' => $writing_cat_ids,
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($writing_posts->have_posts()) :
                    while ($writing_posts->have_posts()) : $writing_posts->the_post();
                        $post_categories = get_the_category();
                        $main_category = !empty($post_categories) ? $post_categories[0] : null;
                        $post_tags = get_the_tags();
                ?>
                    <div class="writing-post-card">
                        <div class="card-header">
                            <span class="category-label"><?php echo $main_category ? esc_html($main_category->name) : 'writing'; ?>/</span>
                        </div>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-content">
                            <div class="card-author-info">
                                <?php 
                                // Get custom field for author info or use post author
                                $custom_author = get_post_meta(get_the_ID(), 'article_author', true);
                                $author_name = $custom_author ? $custom_author : get_the_author();
                                ?>
                                <span class="author-name"><?php echo esc_html($author_name); ?></span>
                            </div>
                            
                            <h3 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <div class="card-date"><?php echo get_the_date('Y-m'); ?></div>
                        </div>
                        
                        <div class="card-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                        </div>
                        
                        <?php if ($post_tags) : ?>
                            <div class="card-tags">
                                <?php foreach ($post_tags as $tag) : ?>
                                    <span class="tag"><?php echo esc_html($tag->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <div class="no-posts">
                        <p>No writing posts found.</p>
                    </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>