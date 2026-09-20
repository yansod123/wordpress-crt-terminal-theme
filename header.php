<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'crt-terminal'); ?></a>

<div class="app">
	<aside class="sidebar">
		<?php if (has_custom_logo()) : ?>
			<div class="brand"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a class="crt-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<div class="crt-case">
					<div class="crt-notch"></div>
					<div class="crt-topbar">
						<span class="crt-rec"><span class="crt-rec-dot"></span> REC</span>
						<span class="crt-model">CRT TERMINAL</span>
						<span class="crt-on"><span class="crt-on-dot"></span> ON</span>
					</div>
					<div class="crt-screen">
						<div class="crt-corner crt-corner-tl">SYS</div>
						<div class="crt-corner crt-corner-tr">OK</div>
						<div class="crt-corner crt-corner-bl">L:01</div>
						<div class="crt-corner crt-corner-br">V1.22</div>
						<div class="crt-face">
							<div class="crt-eye"></div>
							<div class="crt-eye"></div>
						</div>
						<div class="crt-zzz">zzz</div>
						<div class="crt-vignette"></div>
						<div class="crt-scanlines"></div>
						<div class="crt-static"></div>
						<div class="crt-glitch"></div>
					</div>
					<div class="crt-plate"><?php bloginfo('name'); ?></div>
				</div>
			</a>
		<?php endif; ?>

		<div class="label"><?php esc_html_e('Navigation', 'crt-terminal'); ?></div>
		<nav class="nav" aria-label="<?php esc_attr_e('Sidebar navigation', 'crt-terminal'); ?>">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">
				<span class="dot"></span><?php esc_html_e('Home', 'crt-terminal'); ?>
			</a>
			<a href="<?php echo esc_url(home_url('/#projects')); ?>">
				<span class="dot"></span><?php esc_html_e('Projects', 'crt-terminal'); ?>
			</a>
			<a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/')); ?>">
				<span class="dot"></span><?php esc_html_e('Journal', 'crt-terminal'); ?>
			</a>
		</nav>

		<?php
		$cats = get_categories(array(
			'hide_empty' => true,
			'number'     => 8,
		));
		if (!empty($cats)) :
		?>
			<div class="label label-categories"><?php esc_html_e('Categories', 'crt-terminal'); ?></div>
			<div class="collection">
				<?php foreach ($cats as $cat) : ?>
					<a href="<?php echo esc_url(home_url('/#projects')); ?>" data-category-link="<?php echo esc_attr($cat->slug); ?>">
						<i></i><?php echo esc_html($cat->name); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="bottom">
			<a class="side-link" href="<?php echo esc_url(admin_url()); ?>">
				<?php esc_html_e('Dashboard', 'crt-terminal'); ?>
				<b>WP</b>
			</a>
		</div>
	</aside>

	<div class="main">
		<header class="topbar">
			<button class="menu" type="button" aria-label="<?php esc_attr_e('Toggle menu', 'crt-terminal'); ?>">☰</button>

			<nav class="topbar-nav" aria-label="<?php esc_attr_e('Primary menu', 'crt-terminal'); ?>">
				<?php
				wp_nav_menu(array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 1,
					'fallback_cb'    => 'nbu_t_menu_fallback',
					'walker'         => class_exists('NBU_T_Nav_Walker') ? new NBU_T_Nav_Walker() : '',
				));
				?>
			</nav>

			<div class="led-status" aria-live="polite">
				<span class="led"></span>
				<span class="state-loading"><?php esc_html_e('Booting...', 'crt-terminal'); ?></span>
				<span class="state-ready"><?php esc_html_e('Ready', 'crt-terminal'); ?></span>
			</div>
		</header>

		<main id="content" class="canvas">
