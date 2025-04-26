<?php
// Enqueue parent theme styles
function terminal_database_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('terminal-style', get_stylesheet_directory_uri() . '/terminal.css');
    wp_enqueue_script('terminal-script', get_stylesheet_directory_uri() . '/js/terminal.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'terminal_database_enqueue_styles');

// Register Custom Post Type for Database Entries
function create_database_post_type() {
    register_post_type('database_entry',
        array(
            'labels' => array(
                'name' => __('Database Entries'),
                'singular_name' => __('Database Entry')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            'menu_icon' => 'dashicons-database',
            'rewrite' => array('slug' => 'database'),
        )
    );
}
add_action('init', 'create_database_post_type');

// Register custom taxonomy for categorizing entries
function create_database_taxonomies() {
    register_taxonomy(
        'entry_category',
        'database_entry',
        array(
            'labels' => array(
                'name' => __('Categories'),
                'singular_name' => __('Category')
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'entry-category'),
        )
    );
    
    register_taxonomy(
        'entry_status',
        'database_entry',
        array(
            'labels' => array(
                'name' => __('Statuses'),
                'singular_name' => __('Status')
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'entry-status'),
        )
    );
}
add_action('init', 'create_database_taxonomies');

// Update this function to remove JavaScript and add image data attributes
function terminal_database_shortcode() {
    ob_start();
    ?>
    <div class="terminal-container">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th class="terminal-sort" data-column="1">NAME</th>
                    <th class="terminal-sort" data-column="1">MEDIA</th>
                    <th class="terminal-sort" data-column="3">INTEREST</th>
                    <th class="terminal-sort" data-column="2">DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                );
                
                $query = new WP_Query($args);
                
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        
                        // Get the primary category
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? $categories[0]->name : 'Uncategorized';
                        $category_slug = !empty($categories) ? $categories[0]->slug : '';
                        
                        // Get tags
                        $post_tags = get_the_tags();
                        $tags_html = '';
                        if ($post_tags) {
                            $tags = array();
                            foreach ($post_tags as $tag) {
                                $tags[] = $tag->name;
                            }
                            $tags_html = implode(', ', $tags);
                        } else {
                            $tags_html = '—';
                        }
                        
                        // Check for featured image
                        $has_image = false;
                        $image_url = '';
                        if (has_post_thumbnail()) {
                            $has_image = true;
                            $image_url = get_the_post_thumbnail_url(null, 'medium');
                        }
                        
                        $row_class = $has_image ? 'has-image' : '';
                        ?>
                        <tr data-category="<?php echo esc_attr($category_slug); ?>" class="<?php echo $row_class; ?>" 
                            <?php if ($has_image) : ?>
                            data-image="<?php echo esc_attr($image_url); ?>"
                            <?php endif; ?>>
                            <td><a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a></td>
                            <td><?php echo esc_html($category_name); ?></td>
                            <td><?php echo $tags_html; ?></td>
                            <td><?php echo get_the_date('Y-m-d'); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4">No entries found.</td>
                    </tr>
                    <?php
                }
                
                wp_reset_postdata();
                ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('terminal_database', 'terminal_database_shortcode');