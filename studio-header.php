<?php
// filepath: /Users/smthspce/Local Sites/chrisbamboroughcom/app/public/wp-content/themes/terminal-database-theme/studio-header.php
?>
<?php
// Get current page info for breadcrumbs
$current_page = get_queried_object();
$is_studio = is_page('studio');
$is_arena = is_page('arena');
$is_writing = is_page('writing');
$is_arenadata = is_page('arenadata');
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
        
    <?php elseif ($is_arenadata): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current">CURATED DATA</span>
        
    <?php elseif ($is_writing): ?>
        <span class="breadcrumb-separator"> > </span>
        <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="breadcrumb-link">STUDIO</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current">WRITING</span>
        
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

<div class="arena-ticker" onclick="window.location.href='<?php echo esc_url(home_url('/arena/')); ?>'" style="cursor: pointer;">
    <div class="ticker-content">
        <span class="ticker-text" id="arena-ticker-text" data-channel="photo-album-v7neum9xvi4">Loading latest from Are.na...</span>
    </div>
</div>

<div class="studio-header-bar">
    <div class="ascii-title" tabindex="0" style="cursor:pointer;">
        <?php include get_stylesheet_directory() . '/svg-logo-studio.php'; ?>
    </div>
</div>

<div class="studio-category-menu">
    <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="studio-category-link">Index</a>
    <a href="<?php echo esc_url(home_url('/writing/')); ?>" class="studio-category-link">Writing</a>
    <a href="#" class="studio-category-link about-link" id="about-trigger">About</a>
</div>
<div class="about-expander" id="about-expander">
    <div class="about-content">
            <p>Studio Data is the practice of Christopher Bamborough, a designer, researcher and writer of the built environment.
        <?php if ($is_studio): ?>
            <p>The index view catalogues all his work. Use the search and filter functions to navigate through different types of projects.</p>
        <?php elseif ($is_arena): ?>
            <p>Images and text describing Christopher practice, updated regularly.</p>
        <?php elseif ($is_arenadata): ?>
            <p>Data and research materials organized and collected from various sources. Content includes links, texts, and references related to digital culture.</p>
        <?php elseif ($is_writing): ?>
            <p>Writing is a collection of articles, blog posts, and publications exploring digital culture and the built environment.</p>
        <?php else: ?>
            <<p>The index view catalogues all his work. Use the search and filter functions to navigate through different types of projects.</p>
        <?php endif; ?>
                <p>Contact or follow on <a href="https://www.linkedin.com/in/chrisbamborough/" target="_blank" rel="noopener noreferrer">LinkedIn</a>, 
                <a href="https://bsky.app/profile/smthspce.bsky.social" target="_blank" rel="noopener noreferrer">BlueSky</a>,
                <a href="https://www.youtube.com/@studio_DATA" target="_blank" rel="noopener noreferrer">YouTube</a>.</p>
    </div>
</div>