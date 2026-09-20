<?php
if (!defined('ABSPATH')) {
	exit;
}
get_header();

$posts = new WP_Query(array(
	'post_type'           => 'post',
	'posts_per_page'      => 12,
	'ignore_sticky_posts' => true,
));
?>

<section class="vault">
	<div class="titlebar">
		<h2><?php bloginfo('name'); ?></h2>
		<p><?php bloginfo('description'); ?></p>
	</div>

	<div class="search-shell" id="search-shell">
		<span class="search-prompt">search ></span>
		<input id="journal-search" type="search" placeholder="<?php esc_attr_e('Search posts, categories, excerpts...', 'crt-terminal'); ?>">
		<span class="fake-cursor" aria-hidden="true"></span>
		<span class="key-hint">⌘K</span>
	</div>

	<div class="filterbar" aria-label="<?php esc_attr_e('Filters', 'crt-terminal'); ?>">
		<button class="filter active" type="button" data-filter="all"><?php esc_html_e('All', 'crt-terminal'); ?></button>
		<?php
		$filters = get_categories(array('hide_empty' => true, 'number' => 8));
		foreach ($filters as $filter) :
		?>
			<button class="filter" type="button" data-filter="<?php echo esc_attr($filter->slug); ?>">
				<?php echo esc_html($filter->name); ?>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="posts" id="projects">
		<?php if ($posts->have_posts()) : ?>
			<?php $i = 1; ?>
			<?php while ($posts->have_posts()) : $posts->the_post(); ?>
				<?php
				$cat         = get_the_category();
				$slug        = $cat ? sanitize_html_class($cat[0]->slug) : 'uncategorized';
				$name        = $cat ? $cat[0]->name : esc_html__('Uncategorized', 'crt-terminal');
				$search_text = wp_strip_all_tags(get_the_title() . ' ' . $name . ' ' . get_the_excerpt());
				?>
				<a
					class="post-row"
					href="<?php the_permalink(); ?>"
					data-category="<?php echo esc_attr($slug); ?>"
					data-search="<?php echo esc_attr($search_text); ?>"
				>
					<span class="code"><?php echo esc_html(sprintf('LOG-%03d', $i)); ?></span>
					<span class="pill active-pill"><?php echo esc_html($name); ?></span>
					<span class="post-title"><?php the_title(); ?></span>
					<span class="meta"><?php echo esc_html(get_the_author()); ?></span>
					<time class="date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
						<?php echo esc_html(get_the_date('Y.m.d')); ?>
					</time>
					<span class="arrow" aria-hidden="true">→</span>
				</a>
				<?php $i++; ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<div class="empty"><?php esc_html_e('No posts found.', 'crt-terminal'); ?></div>
		<?php endif; ?>

		<div class="no-results" id="no-results"><?php esc_html_e('No matching results.', 'crt-terminal'); ?></div>
	</div>
</section>

<section class="activity">
	<div class="titlebar">
		<h2><?php esc_html_e('Activity', 'crt-terminal'); ?></h2>
		<p><?php esc_html_e('RECENT LOGS / SYSTEM STATE / ACTIVE TAGS', 'crt-terminal'); ?></p>
	</div>

	<div class="activity-grid">
		<div class="panel">
			<div class="panel-head">
				<h2><?php esc_html_e('Recent entries', 'crt-terminal'); ?></h2>
			</div>
			<div class="log">
				<?php
				$recent = get_posts(array(
					'numberposts' => 4,
					'post_type'   => 'post',
				));
				if ($recent) :
					foreach ($recent as $post) :
						setup_postdata($post);
						?>
						<div>
							<time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
						<?php
					endforeach;
					wp_reset_postdata();
				else :
					?>
					<div>
						<time>--</time>
						<span><?php esc_html_e('No activity yet.', 'crt-terminal'); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="panel">
			<div class="panel-head">
				<h2><?php esc_html_e('Tags', 'crt-terminal'); ?></h2>
			</div>
			<div class="chips">
				<?php
				$tags = get_tags(array('number' => 10));
				if ($tags) :
					foreach ($tags as $tag) :
						?>
						<a class="chip" href="<?php echo esc_url(get_tag_link($tag)); ?>">
							#<?php echo esc_html($tag->name); ?>
						</a>
						<?php
					endforeach;
				else :
					?>
					<span class="chip"><?php esc_html_e('No tags', 'crt-terminal'); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
