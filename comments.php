<?php
if (!defined('ABSPATH')) {
	exit;
}

if (post_password_required()) {
	return;
}
?>

<section id="comments" class="panel" style="margin-top:28px;">
	<div class="panel-head">
		<h2>
			<?php
			printf(
				esc_html(
					_n('%s Comment', '%s Comments', get_comments_number(), 'crt-terminal')
				),
				number_format_i18n(get_comments_number())
			);
			?>
		</h2>
	</div>

	<div class="log" style="padding-top:18px;">
		<?php if (have_comments()) : ?>
			<ol class="comment-list" style="list-style:none;margin:0;padding:0;">
				<?php
				wp_list_comments(array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 40,
				));
				?>
			</ol>

			<?php the_comments_navigation(); ?>
		<?php endif; ?>

		<?php if (!comments_open() && get_comments_number()) : ?>
			<p><?php esc_html_e('Comments are closed.', 'crt-terminal'); ?></p>
		<?php endif; ?>

		<div style="margin-top:22px;">
			<?php
			comment_form(array(
				'class_submit' => 'button primary',
				'title_reply'  => esc_html__('Leave a comment', 'crt-terminal'),
			));
			?>
		</div>
	</div>
</section>
