<?php
if (!defined('ABSPATH')) {
	exit;
}
get_header();
?>

<section class="single-post">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="article-head">
				<p class="eyebrow">
					<?php
					$cats = get_the_category();
					if ($cats) {
						echo esc_html($cats[0]->name);
					} else {
						esc_html_e('Journal', 'crt-terminal');
					}
					?>
				</p>

				<h1><?php the_title(); ?></h1>

				<p class="lead">
					<?php echo esc_html(get_the_date('Y.m.d')); ?> ·
					<?php echo esc_html(get_the_author()); ?>
				</p>

				<?php if (has_post_thumbnail()) : ?>
					<div class="article-thumb" style="margin-top:28px;">
						<?php the_post_thumbnail('large'); ?>
					</div>
				<?php endif; ?>
			</header>

			<div class="article-content">
				<?php the_content(); ?>

				<?php
				wp_link_pages(array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'crt-terminal'),
					'after'  => '</div>',
				));
				?>

				<?php
				$tags = get_the_tag_list('', ' ', '');
				if ($tags) :
				?>
					<div class="post-tags">
						<?php echo wp_kses_post($tags); ?>
					</div>
				<?php endif; ?>
			</div>
		</article>

		<?php
		if (comments_open() || get_comments_number()) :
			comments_template();
		endif;
		?>
	<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
