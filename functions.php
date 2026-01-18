<?php
/**
 * 아로스 홈페이지 스킨 Functions
 * Theme Functions and Definitions
 */

// 테마 설정
function aros_theme_setup() {
    // 테마 지원 기능
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    // 메뉴 등록
    register_nav_menus(array(
        'primary' => '메인 메뉴',
        'footer' => '푸터 메뉴'
    ));
}
add_action('after_setup_theme', 'aros_theme_setup');

// CSS/JS 로드
function aros_enqueue_scripts() {
    // 메인 스타일
    wp_enqueue_style('aros-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // 커스텀 JS
    wp_enqueue_script('aros-main', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
    
    // Puter.js (선택사항)
    if (get_theme_mod('aros_enable_puter', false)) {
        wp_enqueue_script('puter-js', 'https://js.puter.com/v2/', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'aros_enqueue_scripts');

// ============================================
// 커스텀 포스트 타입: 목차 섹션
// ============================================
function aros_create_section_post_type() {
    register_post_type('aros_section',
        array(
            'labels' => array(
                'name' => '목차 섹션',
                'singular_name' => '섹션',
                'add_new' => '새 섹션 추가',
                'add_new_item' => '새 섹션 추가',
                'edit_item' => '섹션 수정',
                'new_item' => '새 섹션',
                'view_item' => '섹션 보기',
                'search_items' => '섹션 검색',
                'not_found' => '섹션이 없습니다',
                'not_found_in_trash' => '휴지통에 섹션이 없습니다'
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-grid-view',
            'supports' => array('title', 'page-attributes'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'aros_create_section_post_type');

// 섹션 메타박스
function aros_add_section_metaboxes() {
    add_meta_box(
        'aros_section_details',
        '섹션 설정',
        'aros_render_section_metabox',
        'aros_section',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aros_add_section_metaboxes');

function aros_render_section_metabox($post) {
    wp_nonce_field('aros_section_metabox', 'aros_section_metabox_nonce');
    
    $section_id = get_post_meta($post->ID, 'section_id', true);
    $cards = get_post_meta($post->ID, 'cards', true);
    
    if (!is_array($cards)) {
        $cards = array();
    }
    ?>
    
    <div class="aros-section-metabox">
        <p>
            <label><strong>섹션 ID (예: aros1, aros2):</strong></label><br>
            <input type="text" name="section_id" value="<?php echo esc_attr($section_id); ?>" 
                   style="width: 100%;" placeholder="aros1">
        </p>
        
        <hr>
        
        <h3>카드 목록</h3>
        <div id="cards-container">
            <?php
            $card_index = 0;
            $colors = array(
                'card-blue' => '파란색', 'card-blue2' => '파란색2', 'card-blue3' => '파란색3',
                'card-green' => '녹색', 'card-purple' => '보라색', 'card-teal' => '청록색',
                'card-orange' => '주황색', 'card-amber' => '호박색', 'card-violet' => '바이올렛'
            );
            
            foreach ($cards as $card) :
            ?>
            <div class="card-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
                <h4>카드 #<?php echo ($card_index + 1); ?></h4>
                
                <p>
                    <label>제목:</label><br>
                    <input type="text" name="cards[<?php echo $card_index; ?>][title]" 
                           value="<?php echo esc_attr($card['title']); ?>" style="width: 100%;">
                </p>
                
                <p>
                    <label>부제목:</label><br>
                    <input type="text" name="cards[<?php echo $card_index; ?>][subtitle]" 
                           value="<?php echo esc_attr($card['subtitle']); ?>" style="width: 100%;">
                </p>
                
                <p>
                    <label>URL:</label><br>
                    <input type="url" name="cards[<?php echo $card_index; ?>][url]" 
                           value="<?php echo esc_url($card['url']); ?>" style="width: 100%;">
                </p>
                
                <p>
                    <label>아이콘 (이모지):</label><br>
                    <input type="text" name="cards[<?php echo $card_index; ?>][icon]" 
                           value="<?php echo esc_attr($card['icon']); ?>" style="width: 100px;">
                </p>
                
                <p>
                    <label>배경색 클래스:</label><br>
                    <select name="cards[<?php echo $card_index; ?>][color_class]" style="width: 100%;">
                        <?php foreach ($colors as $class => $label) : 
                            $selected = ($card['color_class'] === $class) ? 'selected' : '';
                        ?>
                            <option value="<?php echo esc_attr($class); ?>" <?php echo $selected; ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                
                <button type="button" class="button remove-card" style="background: #dc3232; color: white;">
                    카드 삭제
                </button>
            </div>
            <?php
            $card_index++;
            endforeach;
            ?>
        </div>
        
        <button type="button" id="add-card" class="button button-primary">
            새 카드 추가
        </button>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        let cardIndex = <?php echo count($cards); ?>;
        
        $('#add-card').on('click', function() {
            const colorOptions = <?php echo json_encode($colors); ?>;
            let optionsHtml = '';
            
            for (const [value, label] of Object.entries(colorOptions)) {
                optionsHtml += `<option value="${value}">${label}</option>`;
            }
            
            const newCard = `
                <div class="card-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
                    <h4>카드 #${cardIndex + 1}</h4>
                    <p><label>제목:</label><br><input type="text" name="cards[${cardIndex}][title]" style="width: 100%;"></p>
                    <p><label>부제목:</label><br><input type="text" name="cards[${cardIndex}][subtitle]" style="width: 100%;"></p>
                    <p><label>URL:</label><br><input type="url" name="cards[${cardIndex}][url]" style="width: 100%;"></p>
                    <p><label>아이콘:</label><br><input type="text" name="cards[${cardIndex}][icon]" style="width: 100px;" value="🔥"></p>
                    <p><label>배경색:</label><br><select name="cards[${cardIndex}][color_class]" style="width: 100%;">${optionsHtml}</select></p>
                    <button type="button" class="button remove-card" style="background: #dc3232; color: white;">카드 삭제</button>
                </div>
            `;
            
            $('#cards-container').append(newCard);
            cardIndex++;
        });
        
        $(document).on('click', '.remove-card', function() {
            $(this).closest('.card-item').remove();
        });
    });
    </script>
    <?php
}

function aros_save_section_meta($post_id) {
    if (!isset($_POST['aros_section_metabox_nonce'])) return;
    if (!wp_verify_nonce($_POST['aros_section_metabox_nonce'], 'aros_section_metabox')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['section_id'])) {
        update_post_meta($post_id, 'section_id', sanitize_text_field($_POST['section_id']));
    }
    
    if (isset($_POST['cards'])) {
        $cards = array();
        foreach ($_POST['cards'] as $card) {
            $cards[] = array(
                'title' => sanitize_text_field($card['title']),
                'subtitle' => sanitize_text_field($card['subtitle']),
                'url' => esc_url_raw($card['url']),
                'icon' => sanitize_text_field($card['icon']),
                'color_class' => sanitize_text_field($card['color_class'])
            );
        }
        update_post_meta($post_id, 'cards', $cards);
    }
}
add_action('save_post_aros_section', 'aros_save_section_meta');

// ============================================
// 테마 커스터마이저
// ============================================
function aros_customize_register($wp_customize) {
    // 헤더 섹션
    $wp_customize->add_section('aros_header_section', array(
        'title' => '헤더 설정',
        'priority' => 30,
    ));
    
    // 로고 URL
    $wp_customize->add_setting('aros_logo_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('aros_logo_url', array(
        'label' => '로고 이미지 URL',
        'section' => 'aros_header_section',
        'type' => 'url',
    ));
    
    // 사이트 제목
    $wp_customize->add_setting('aros_site_title', array(
        'default' => '오늘의 아파트',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aros_site_title', array(
        'label' => '사이트 제목',
        'section' => 'aros_header_section',
        'type' => 'text',
    ));
    
    // 탭 설정
    $wp_customize->add_section('aros_tabs_section', array(
        'title' => '탭 메뉴 설정',
        'priority' => 31,
    ));
    
    for ($i = 1; $i <= 3; $i++) {
        $wp_customize->add_setting("aros_tab{$i}_title", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control("aros_tab{$i}_title", array(
            'label' => "탭 {$i} 제목",
            'section' => 'aros_tabs_section',
            'type' => 'text',
        ));
        
        $wp_customize->add_setting("aros_tab{$i}_url", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("aros_tab{$i}_url", array(
            'label' => "탭 {$i} URL",
            'section' => 'aros_tabs_section',
            'type' => 'url',
        ));
    }
    
    // 푸터 설정
    $wp_customize->add_section('aros_footer_section', array(
        'title' => '푸터 설정',
        'priority' => 32,
    ));
    
    $wp_customize->add_setting('aros_footer_brand', array(
        'default' => '굿인포',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aros_footer_brand', array(
        'label' => '브랜드명',
        'section' => 'aros_footer_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aros_footer_address', array(
        'default' => '대전광역시 동구 동부로10번길55',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aros_footer_address', array(
        'label' => '사업자 주소',
        'section' => 'aros_footer_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('aros_footer_business', array(
        'default' => '784-15-02513',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aros_footer_business', array(
        'label' => '사업자 번호',
        'section' => 'aros_footer_section',
        'type' => 'text',
    ));
}
add_action('customize_register', 'aros_customize_register');

// ============================================
// 숏코드
// ============================================
function aros_gray_card_center_shortcode($atts, $content = null) {
    return '<div class="aros-gray-card-center">' . do_shortcode($content) . '</div>';
}
add_shortcode('gray_card_center', 'aros_gray_card_center_shortcode');

function aros_gray_card_shortcode($atts, $content = null) {
    return '<div class="aros-gray-card">' . do_shortcode($content) . '</div>';
}
add_shortcode('gray_card', 'aros_gray_card_shortcode');

function aros_blue_card_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array('title' => ''), $atts);
    $output = '<div class="aros-blue-card">';
    if (!empty($atts['title'])) {
        $output .= '<h2>' . esc_html($atts['title']) . '</h2>';
    }
    $output .= do_shortcode($content);
    $output .= '</div>';
    return $output;
}
add_shortcode('blue_card', 'aros_blue_card_shortcode');

function aros_white_card_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array('title' => ''), $atts);
    $output = '<div class="aros-white-card">';
    if (!empty($atts['title'])) {
        $output .= '<h2>' . esc_html($atts['title']) . '</h2>';
    }
    $output .= do_shortcode($content);
    $output .= '</div>';
    return $output;
}
add_shortcode('white_card', 'aros_white_card_shortcode');

function aros_button_container_shortcode($atts) {
    $atts = shortcode_atts(array('url' => '#', 'text' => '클릭하기'), $atts);
    return '<div class="link-container">
        <a class="custom-link" href="' . esc_url($atts['url']) . '">
            <div class="button-container">
                <div class="button-content">
                    <span class="button-text">' . esc_html($atts['text']) . '</span>
                    <span>→</span>
                </div>
            </div>
        </a>
    </div>';
}
add_shortcode('button_container', 'aros_button_container_shortcode');

function aros_benefit_card_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array('title' => '함께 보면 좋은 글'), $atts);
    return '<div class="aros-gray-card benefit-card">
        <h3 class="benefit-title">
            <span class="icon">🎯</span>
            ' . esc_html($atts['title']) . '
        </h3>
        <div class="benefit-list">
            ' . do_shortcode($content) . '
        </div>
    </div>';
}
add_shortcode('benefit_card', 'aros_benefit_card_shortcode');

function aros_benefit_item_shortcode($atts) {
    $atts = shortcode_atts(array('url' => '#', 'text' => '', 'icon' => '💰'), $atts);
    return '<a href="' . esc_url($atts['url']) . '">
        <div class="benefit-item">
            <span class="benefit-text">• ' . esc_html($atts['text']) . '</span>
            <span>' . $atts['icon'] . '</span>
        </div>
    </a>';
}
add_shortcode('benefit_item', 'aros_benefit_item_shortcode');

function aros_bottom_button_shortcode($atts) {
    $atts = shortcode_atts(array('url' => '#', 'text' => '더 알아보기'), $atts);
    return '<a href="' . esc_url($atts['url']) . '">
        <button class="bottom-button">
            <span>' . esc_html($atts['text']) . '</span>
            <span>→</span>
        </button>
    </a>';
}
add_shortcode('bottom_button', 'aros_bottom_button_shortcode');

function aros_ad_container_shortcode($atts, $content = null) {
    return '<div class="ad-container">' . do_shortcode($content) . '</div>';
}
add_shortcode('ad_container', 'aros_ad_container_shortcode');
?>
