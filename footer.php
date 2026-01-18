</div><!-- .container -->
    </main><!-- .site-content -->

</div><!-- .site-wrapper -->

<!-- 푸터 -->
<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-left">
            <?php
            $footer_brand = get_theme_mod('aros_footer_brand', '굿인포');
            $footer_address = get_theme_mod('aros_footer_address', '대전광역시 동구 동부로10번길55');
            $footer_business = get_theme_mod('aros_footer_business', '784-15-02513');
            ?>
            
            <div class="footer-brand"><?php echo esc_html($footer_brand); ?></div>
            <ul class="footer-info">
                <li>
                    <i>📍</i>
                    사업자 주소: <?php echo esc_html($footer_address); ?>
                </li>
                <li>
                    <i>🏢</i>
                    사업자 번호: <?php echo esc_html($footer_business); ?>
                </li>
            </ul>
        </div>
        
        <div class="footer-right">
            <p>제작자: 아로스</p>
            <p>홈페이지: <a href="https://aros100.com" target="_blank">바로가기</a></p>
            <p class="footer-copyright">
                Copyrights &copy; <?php echo date('Y'); ?> All Rights Reserved by (주)아백
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
