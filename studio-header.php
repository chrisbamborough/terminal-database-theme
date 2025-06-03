<?php
// filepath: /Users/smthspce/Local Sites/chrisbamboroughcom/app/public/wp-content/themes/terminal-database-theme/studio-header.php
?>
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
        <p>This is a terminal-style database for documenting and organizing creative work. Navigate through different categories to explore projects, writings, and visual content.</p>
        <p>Built with WordPress and designed to mimic the aesthetic of early computer interfaces.</p>
    </div>
</div>