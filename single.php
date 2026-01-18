<?php
/**
 * 글쓰기 스킨 - 글 상세 페이지
 */

get_header();
?>

<?php
while (have_posts()) : the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php if (is_singular('post')) : ?>
    <header class="entry-header">
        <h1 class="entry-title" style="display: none;"><?php the_title(); ?></h1>
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
    
</article>

<?php
endwhile;
?>

<?php
get_footer();
?>
