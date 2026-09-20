<?php if (!defined('ABSPATH')) exit; ?>
		</main>
	</div>
</div>

<?php if (get_theme_mod('nbu_t_footer_enable', true)) : ?>
	<?php
	$footer_text   = trim((string) get_theme_mod('nbu_t_footer_text', '← Back to Home'));
	$footer_link   = get_theme_mod('nbu_t_footer_link', '');
	$center_text   = trim((string) get_theme_mod('nbu_t_footer_center_text', ''));
	$clock_enable  = get_theme_mod('nbu_t_footer_clock_enable', true);
	$clock_label   = trim((string) get_theme_mod('nbu_t_footer_clock_label', 'CST'));
	$clock_tz      = trim((string) get_theme_mod('nbu_t_footer_clock_tz', 'Asia/Shanghai'));

	if (!$footer_link) {
		$footer_link = home_url('/');
	}
	if (!$center_text) {
		$center_text = get_bloginfo('name');
	}
	?>
	<footer class="site-footer" aria-label="<?php esc_attr_e('Site footer', 'crt-terminal'); ?>">
		<div class="site-footer-left">
			<?php if ($footer_text) : ?>
				<a class="site-footer-home" href="<?php echo esc_url($footer_link); ?>">
					<?php echo esc_html($footer_text); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="site-footer-name">
			<?php echo esc_html($center_text); ?>
		</div>

		<div class="site-footer-right">
			<?php if ($clock_enable) : ?>
				<span
					class="site-footer-clock"
					id="footer-beijing-time"
					data-label="<?php echo esc_attr($clock_label); ?>"
					data-tz="<?php echo esc_attr($clock_tz); ?>"
				></span>
			<?php endif; ?>
		</div>
	</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
