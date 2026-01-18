<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    
    <!-- 헤더 -->
    <header class="site-header">
        <div class="header-inner">
            <div class="container">
                <?php
                $logo_url = get_theme_mod('aros_logo_url', get_template_directory_uri() . '/images/logo.png');
                $site_title = get_theme_mod('aros_site_title', get_bloginfo('name'));
                ?>
                
                <div class="site-logo">
                    <?php if ($logo_url) : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_title); ?>">
                        </a>
                    <?php endif; ?>
                </div>
                
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php echo esc_html($site_title); ?>
                    </a>
                </h1>
            </div>
        </div>
    </header>
    
    <!-- 탭 메뉴 -->
    <div class="tab-wrapper">
        <div class="container">
            <nav class="tab-container">
                <ul class="tabs">
                    <?php
                    // 탭 정보 가져오기
                    $tabs = array();
                    for ($i = 1; $i <= 3; $i++) {
                        $title = get_theme_mod("aros_tab{$i}_title", "탭 {$i}");
                        $url = get_theme_mod("aros_tab{$i}_url", "#aros{$i}");
                        
                        if (!empty($title)) {
                            $tabs[] = array(
                                'title' => $title,
                                'url' => $url,
                                'active' => ($i === 1)
                            );
                        }
                    }
                    
                    // 탭이 설정되지 않은 경우 기본값
                    if (empty($tabs)) {
                        $tabs = array(
                            array('title' => '신청방법', 'url' => '#aros1', 'active' => true),
                            array('title' => '대상조건', 'url' => '#aros2', 'active' => false),
                            array('title' => '지급조회', 'url' => '#aros3', 'active' => false)
                        );
                    }
                    
                    foreach ($tabs as $tab) :
                    ?>
                    <li class="tab-item">
                        <a class="tab-link <?php echo $tab['active'] ? 'active' : ''; ?>" 
                           href="<?php echo esc_url($tab['url']); ?>">
                            <?php echo esc_html($tab['title']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const hash = window.location.hash;
        let activeTabFound = false;

        tabs.forEach(tab => {
            if (hash) {
                if (tab.getAttribute('href') === hash || tab.getAttribute('href').endsWith(hash)) {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    activeTabFound = true;
                }
            }
        });

        if (!activeTabFound) {
            const defaultActiveTab = document.querySelector('.tab-link.active');
            if (defaultActiveTab) {
                defaultActiveTab.classList.add('active');
            }
        }
        
        // 스크롤 오프셋
        if (hash) {
            setTimeout(() => {
                const targetId = hash.substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    const offset = window.innerHeight * 0.15;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const scrollPosition = targetPosition - offset;

                    window.scrollTo({
                        top: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            }, 300);
        }
    });
    </script>
    
    <!-- 메인 컨텐츠 시작 -->
    <main class="site-content">
        <div class="container">
