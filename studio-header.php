<?php
// filepath: /Users/smthspce/Local Sites/chrisbamboroughcom/app/public/wp-content/themes/terminal-database-theme/studio-header.php
?>
<?php
// Get current page info for breadcrumbs
$current_page = get_queried_object();
$is_studio = is_page('studio');
$is_arena = is_page('arena');
$is_single_post = is_single();
$is_category = is_category();
$is_tag = is_tag();
?>
<!-- Breadcrumb navigation in separate div -->
<div class="breadcrumb-nav">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">HOME</a>
    
    <?php if ($is_studio): ?>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current">STUDIO</span>
        
    <?php elseif ($is_arena): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current">BLOG</span>
        
    <?php elseif ($is_single_post): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        
        <?php 
        // Get post categories
        $categories = get_the_category();
        if (!empty($categories)): 
            $main_category = $categories[0];
        ?>
            <span class="breadcrumb-separator"> > </span>
            <span class="breadcrumb-current"><?php echo strtoupper($main_category->name); ?></span>
        <?php endif; ?>
        
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current"><?php echo strtoupper(get_the_title()); ?></span>
        
    <?php elseif ($is_category): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current"><?php echo strtoupper(single_cat_title('', false)); ?></span>
        
    <?php elseif ($is_tag): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current"><?php echo strtoupper(single_tag_title('', false)); ?></span>
        
    <?php endif; ?>
</div>

<div class="studio-header-bar">
    <div class="ascii-title" tabindex="0" style="cursor:pointer;">
        <?php include get_stylesheet_directory() . '/svg-logo-studio.php'; ?>
    </div>
</div>
<div class="studio-category-menu">
    <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="studio-category-link">Index</a>
    <a href="#" class="studio-category-link">Writing</a>
    <a href="<?php echo esc_url(home_url('/arena/')); ?>" class="studio-category-link">Blog</a>
    <a href="#" class="studio-category-link about-link" id="about-trigger">About</a>
</div>
<div class="about-expander" id="about-expander">
    <div class="about-content">
        <?php if ($is_studio): ?>
            <p>This terminal database catalogues creative projects, research, and documentation. Use the search and filter functions to navigate through different types of work.</p>
        <?php elseif ($is_arena): ?>
            <p>Visual research and inspiration collected from various sources. Images are organized by themes and updated regularly.</p>
        <?php else: ?>
            <p>This is a terminal-style database for documenting and organizing creative work. Navigate through different categories to explore projects, writings, and visual content.</p>
        <?php endif; ?>
        <p>Built with WordPress and designed to mimic the aesthetic of early computer interfaces.</p>
    </div>
</div>