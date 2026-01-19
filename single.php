<?php
/**
 * 글쓰기 스킨 - 깔끔한 블로그 스타일 (수정됨)
 */

get_header();
?>

<div class="blog-post-wrapper">
    <?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('clean-blog-card'); ?>>
        
        <?php if (is_singular('post')) : ?>
        <header class="blog-header">
            <div class="blog-meta">
                <span class="cat-label"><?php the_category(' '); ?></span>
                <span class="date-label"><?php echo get_the_date('Y.m.d'); ?></span>
            </div>
            <h1 class="blog-title"><?php the_title(); ?></h1>
        </header>
        <hr class="blog-divider">
        <?php endif; ?>
        
        <div class="entry-content blog-content">
            <?php
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aros-homepage'),
                'after'  => '</div>',
            ));
            ?>
        </div>
        
        <?php if (has_tag()) : ?>
        <div class="blog-tags">
            <?php the_tags('#', ' #', ''); ?>
        </div>
        <?php endif; ?>

    </article>

    <?php endwhile; ?>
</div>

<?php
get_footer();
?>
