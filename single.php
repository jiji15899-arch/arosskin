<?php
/**
 * 글쓰기 스킨 - 글 상세 페이지 (수정됨)
 */

get_header();
?>

<?php
while (have_posts()) : the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php if (is_singular('post')) : ?>
    <header class="entry-header">
        <div class="entry-meta-top">
            <span class="post-category"><?php the_category(', '); ?></span>
            <span class="post-date"><?php echo get_the_date('Y.m.d'); ?></span>
        </div>
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>
    <?php endif; ?>
    
    <div class="entry-content">
        <?php
        the_content();
        
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aros-homepage'),
            'after'  => '</div>',
        ));
        ?>
    </div>
    
    <?php if (has_tag()) : ?>
    <div class="entry-tags">
        <?php the_tags('', '', ''); ?>
    </div>
    <?php endif; ?>

</article>

<?php
endwhile;
?>

<?php
get_footer();
?>
