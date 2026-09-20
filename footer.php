</div><?php if(get_theme_mod('nbu_t_footer_enable',true)):
$footerText=get_theme_mod('nbu_t_footer_text','← Back to Home');
$footerLink=get_theme_mod('nbu_t_footer_link','');
$footerLink=$footerLink?esc_url($footerLink):esc_url(home_url('/'));
$centerText=get_theme_mod('nbu_t_footer_center_text','');
$centerText=$centerText!==''?$centerText:get_bloginfo('name');
$clockEnable=get_theme_mod('nbu_t_footer_clock_enable',true);
$clockLabel=get_theme_mod('nbu_t_footer_clock_label','CST');
$clockTz=get_theme_mod('nbu_t_footer_clock_tz','Asia/Shanghai');
?><footer class="site-footer" role="contentinfo"><?php if($footerText!==''):?><a class="site-footer-home" href="<?php echo $footerLink; ?>"><?php echo esc_html($footerText); ?></a><?php endif;?><span class="site-footer-name"><?php echo esc_html($centerText); ?></span><?php if($clockEnable):?><span class="site-footer-clock" id="footer-beijing-time" data-tz="<?php echo esc_attr($clockTz); ?>" data-label="<?php echo esc_attr($clockLabel); ?>"><?php echo esc_html($clockLabel); ?> / --</span><?php endif; ?></footer><?php endif; ?></div><?php wp_footer(); ?></body></html>
