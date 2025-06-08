<?php
// Enqueue parent theme styles
function terminal_database_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('terminal-style', get_stylesheet_directory_uri() . '/terminal.css');
    wp_enqueue_style('terminal-mobile', get_stylesheet_directory_uri() . '/mobile.css', array('terminal-style'), null, 'all and (max-width: 900px)');
    wp_enqueue_script('terminal-script', get_stylesheet_directory_uri() . '/js/terminal.js', array('jquery'), '1.0', true);
    
    // Enqueue studio header JavaScript
    wp_enqueue_script(
        'studio-header-js',
        get_stylesheet_directory_uri() . '/js/studio-header.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'terminal_database_enqueue_styles');

// Enqueue Adobe Typekit fonts
function terminal_enqueue_typekit() {
    wp_enqueue_style('typekit', 'https://use.typekit.net/smy2pep.css', array(), null);
}
add_action('wp_enqueue_scripts', 'terminal_enqueue_typekit');

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
    <div class="database-container">
        <!-- Add search bar, category filter, and tag filter -->
        <div class="terminal-controls">
            <input type="text" class="terminal-search" placeholder="Search...">
            
            <!-- Category Filter -->
            <!-- <select id="category-filter" class="terminal-filter">
                <option value="">All Categories</option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select> -->

            <!-- Tag Filter -->
            <select id="tag-filter" class="terminal-filter">
                <option value="">All Tags</option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    echo '<option value="' . esc_attr($tag->slug) . '">' . esc_html($tag->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <table class="terminal-table">
            <thead>
                <tr>
                    <th class="terminal-sort gridlite-heading" data-column="1">NAME <span class="sort-indicator">▲▼</span></th>
                    <th class="terminal-sort gridlite-heading" data-column="1">MEDIA <span class="sort-indicator">▲▼</span></th>
                    <th class="terminal-sort gridlite-heading" data-column="3">INTEREST <span class="sort-indicator">▲▼</span></th>
                    <th class="terminal-sort gridlite-heading" data-column="2">DATE <span class="sort-indicator">▲▼</span></th>
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
                        $tags_data = '';
                        $tag_slugs = array();
                        if ($post_tags) {
                            $tags = array();
                            foreach ($post_tags as $tag) {
                                $tags[] = $tag->name;
                                $tag_slugs[] = $tag->slug;
                            }
                            $tags_html = implode(', ', $tags);
                            $tags_data = implode(' ', $tag_slugs);
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
                        <tr data-category="<?php echo esc_attr($category_slug); ?>" 
                            data-tags="<?php echo esc_attr($tags_data); ?>" 
                            class="<?php echo $row_class; ?>" 
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

// Enqueue Are.na JavaScript
function terminal_enqueue_arena_script() {
    // Load arena.js on all pages since the ticker appears site-wide
    wp_enqueue_script('arena-script', get_stylesheet_directory_uri() . '/js/arena.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'terminal_enqueue_arena_script');

// Create Arena Data page programmatically
function create_arenadata_page() {
    // Check if the page already exists
    $arenadata_page = get_page_by_path('arenadata');
    
    if (!$arenadata_page) {
        // Create the page
        $page_data = array(
            'post_title' => 'Arena Data',
            'post_name' => 'arenadata',
            'post_content' => '', // Empty content since template handles display
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1, // Admin user ID
        );
        
        $page_id = wp_insert_post($page_data);
        
        if ($page_id) {
            // Flush rewrite rules to ensure the new page slug works
            flush_rewrite_rules();
        }
    }
}
add_action('after_switch_theme', 'create_arenadata_page');

// Create Writing page programmatically
function create_writing_page() {
    // Check if the page already exists
    $writing_page = get_page_by_path('writing');
    
    if (!$writing_page) {
        // Create the page
        $page_data = array(
            'post_title' => 'Writing',
            'post_name' => 'writing',
            'post_content' => '', // Empty content since template handles display
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1, // Admin user ID
        );
        
        wp_insert_post($page_data);
    }
}
add_action('after_switch_theme', 'create_writing_page');

// Force flush rewrite rules when needed
function flush_rewrite_rules_on_page_creation() {
    if (get_option('arenadata_page_created') !== 'yes') {
        flush_rewrite_rules();
        update_option('arenadata_page_created', 'yes');
    }
}
add_action('init', 'flush_rewrite_rules_on_page_creation');

// Arena Channel Shortcode
function arena_channel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'channel' => 'photo-album-v7neum9xvi4', // default channel
    ), $atts);

    ob_start();
    ?>
    <div id="arena-content" data-channel="<?php echo esc_attr($atts['channel']); ?>" class="arena-grid">
        <div class="loading-message">Loading Arena content...</div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('arena_channel', 'arena_channel_shortcode');