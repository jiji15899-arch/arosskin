<?php
/**
 * 페이지 템플릿
 */

get_header();
?>

<?php
while (have_posts()) : the_post();
?>

<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>
    
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
