<?php
if (!defined('ABSPATH')) {
	exit;
}
get_header();
?>

<section class="error-page">
	<div class="error-box">
		<div class="error-code">ERROR 404</div>
		<h1><?php esc_html_e('Page not found', 'crt-terminal'); ?></h1>
		<p><?php esc_html_e('The resource you requested could not be located. Try returning to the home screen or browse recent entries.', 'crt-terminal'); ?></p>

		<div class="error-actions">
			<a class="button primary" href="<?php echo esc_url(home_url('/')); ?>">
				<?php esc_html_e('Back to home', 'crt-terminal'); ?>
			</a>
			<a class="button" href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/')); ?>">
				<?php esc_html_e('Open journal', 'crt-terminal'); ?>
			</a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
