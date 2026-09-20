<?php
if (!defined('ABSPATH')) {
	exit;
}
get_header();
?>

<section class="archive-page">
	<div class="titlebar">
		<h2><?php wp_title(''); ?></h2>
		<p><?php esc_html_e('ARCHIVE / INDEX / ENTRIES', 'crt-terminal'); ?></p>
	</div>

	<div class="posts archive-posts">
		<?php if (have_posts()) : ?>
			<?php $i = 1; ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php
				$cat  = get_the_category();
				$name = $cat ? $cat[0]->name : esc_html__('Uncategorized', 'crt-terminal');
				?>
				<a class="post-row" href="<?php the_permalink(); ?>">
					<span class="code"><?php echo esc_html(sprintf('ARC-%03d', $i)); ?></span>
					<span class="pill"><?php echo esc_html($name); ?></span>
					<span class="post-title"><?php the_title(); ?></span>
					<span class="meta"><?php echo esc_html(get_the_author()); ?></span>
					<time class="date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
						<?php echo esc_html(get_the_date('Y.m.d')); ?>
					</time>
					<span class="arrow" aria-hidden="true">→</span>
				</a>
				<?php $i++; ?>
			<?php endwhile; ?>

			<div class="footer">
				<span><?php previous_posts_link(esc_html__('← Newer', 'crt-terminal')); ?></span>
				<span><?php esc_html_e('Archive navigation', 'crt-terminal'); ?></span>
				<span><?php next_posts_link(esc_html__('Older →', 'crt-terminal')); ?></span>
			</div>
		<?php else : ?>
			<div class="empty"><?php esc_html_e('No posts found.', 'crt-terminal'); ?></div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
