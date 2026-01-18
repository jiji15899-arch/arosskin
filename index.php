<?php
/**
 * Template Name: 홈페이지 목차
 * 목차 스킨 메인 페이지
 */

get_header();
?>

<?php
// 메인 카드 정보
$main_card_title = get_post_meta(get_the_ID(), 'main_card_title', true);
$main_card_content = get_post_meta(get_the_ID(), 'main_card_content', true);
$main_card_icon = get_post_meta(get_the_ID(), 'main_card_icon', true);

// 기본값 설정
if (empty($main_card_title)) {
    $main_card_title = '근로장려금 신청';
    $main_card_content = '대한민국 92%가 놓치고 있던 사실!<br/>근로장려금, 자금 받을 수 있습니다!<br/>바로 확인하고 혜택 놓치지 마세요!';
    $main_card_icon = '🎁';
}
?>

<!-- 메인 설명 카드 -->
<div class="content-card">
    <h2 class="card-title"><?php echo wp_kses_post($main_card_title); ?></h2>
    <p class="card-text"><?php echo wp_kses_post($main_card_content); ?></p>
    <span class="card-icon"><?php echo $main_card_icon; ?></span>
</div>

<?php
// 섹션들을 동적으로 가져오기
$sections = get_posts(array(
    'post_type' => 'aros_section',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

foreach ($sections as $section) :
    $section_id = get_post_meta($section->ID, 'section_id', true);
    $cards = get_post_meta($section->ID, 'cards', true);
?>

<h2 class="section-title" id="<?php echo esc_attr($section_id); ?>">
    <?php echo esc_html($section->post_title); ?>
</h2>

<div class="support-grid">
    <?php
    if (is_array($cards)) :
        foreach ($cards as $card) :
    ?>
    <a class="support-card <?php echo esc_attr($card['color_class']); ?>" 
       href="<?php echo esc_url($card['url']); ?>">
        <div class="support-title"><?php echo esc_html($card['title']); ?></div>
        <div class="support-subtitle"><?php echo esc_html($card['subtitle']); ?></div>
        <div class="support-icon"><?php echo $card['icon']; ?></div>
    </a>
    <?php
        endforeach;
    endif;
    ?>
</div>

<?php endforeach; ?>

<!-- 광고 영역 (옵션) -->
<?php if (get_post_meta(get_the_ID(), 'show_ad', true)) : ?>
<div class="ad-container">
    <?php
    $ad_code = get_post_meta(get_the_ID(), 'ad_code', true);
    if (!empty($ad_code)) {
        echo $ad_code;
    } else {
        echo '<div style="color: #999;">광고 영역</div>';
    }
    ?>
</div>
<?php endif; ?>

<?php
get_footer();
?>
