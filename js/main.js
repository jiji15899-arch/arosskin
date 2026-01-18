/**
 * 아로스 홈페이지 스킨 메인 JavaScript
 */

(function($) {
    'use strict';
    
    // 문서 준비
    $(document).ready(function() {
        
        // 탭 활성화
        initTabs();
        
        // 스무스 스크롤
        initSmoothScroll();
        
        // 카드 호버 효과
        initCardEffects();
        
    });
    
    /**
     * 탭 초기화
     */
    function initTabs() {
        const tabs = document.querySelectorAll('.tab-link');
        const hash = window.location.hash;
        let activeTabFound = false;

        tabs.forEach(tab => {
            // 클릭 이벤트
            tab.addEventListener('click', function(e) {
                // 내부 페이지 링크인 경우
                const href = this.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    
                    // 모든 탭 비활성화
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // 현재 탭 활성화
                    this.classList.add('active');
                    
                    // 스크롤
                    const targetId = href.substring(1);
                    scrollToSection(targetId);
                    
                    // URL 업데이트
                    history.pushState(null, null, href);
                }
            });
            
            // 현재 해시와 일치하는 탭 활성화
            if (hash) {
                const tabHref = tab.getAttribute('href');
                if (tabHref === hash || tabHref.endsWith(hash)) {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    activeTabFound = true;
                }
            }
        });
        
        // 해시가 있으면 해당 섹션으로 스크롤
        if (hash && !activeTabFound) {
            setTimeout(() => {
                const targetId = hash.substring(1);
                scrollToSection(targetId);
            }, 300);
        }
    }
    
    /**
     * 섹션으로 스크롤
     */
    function scrollToSection(targetId) {
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
    }
    
    /**
     * 스무스 스크롤 초기화
     */
    function initSmoothScroll() {
        // 모든 앵커 링크에 스무스 스크롤 적용
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this).attr('href');
            
            if (target === '#' || target === '') {
                return;
            }
            
            const $target = $(target);
            if ($target.length) {
                e.preventDefault();
                
                const offset = 150; // 헤더 + 탭 높이
                const scrollPosition = $target.offset().top - offset;
                
                $('html, body').animate({
                    scrollTop: scrollPosition
                }, 600);
            }
        });
    }
    
    /**
     * 카드 효과 초기화
     */
    function initCardEffects() {
        // 카드 호버 시 약간의 그림자 효과
        $('.support-card, .benefit-item').hover(
            function() {
                $(this).css('box-shadow', '0 8px 20px rgba(0, 0, 0, 0.15)');
            },
            function() {
                $(this).css('box-shadow', '');
            }
        );
    }
    
    /**
     * Puter.js 연동 (선택사항)
     */
    if (typeof puter !== 'undefined') {
        console.log('Puter.js is available');
        
        // Puter.js를 사용한 동적 콘텐츠 로딩 예제
        // async function loadDynamicContent() {
        //     try {
        //         const data = await puter.fs.read('data.json');
        //         console.log('Data loaded:', data);
        //     } catch (error) {
        //         console.error('Puter.js error:', error);
        //     }
        // }
        // 
        // loadDynamicContent();
    }
    
    /**
     * 반응형 처리
     */
    function handleResize() {
        const windowWidth = $(window).width();
        
        if (windowWidth < 480) {
            // 모바일 최적화
            $('.card-icon').hide();
        } else {
            $('.card-icon').show();
        }
    }
    
    // 초기 실행
    handleResize();
    
    // 리사이즈 이벤트
    $(window).on('resize', handleResize);
    
})(jQuery);
