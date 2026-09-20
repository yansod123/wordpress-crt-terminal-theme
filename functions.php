<?php
if (!defined('ABSPATH')) {
	exit;
}

define('NBU_T_VER', '1.22.0');

function nbu_t_setup() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	));
	add_theme_support('custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	));
	add_theme_support('custom-background');
	add_theme_support('custom-header');

	register_nav_menus(array(
		'primary' => __('主导航', 'crt-terminal'),
	));
}
add_action('after_setup_theme', 'nbu_t_setup');

function nbu_t_assets() {
	wp_enqueue_style('nbu-terminal', get_stylesheet_uri(), array(), NBU_T_VER);
	wp_enqueue_script('nbu-terminal', get_template_directory_uri() . '/assets/js/app.js', array(), NBU_T_VER, true);

	if (file_exists(get_template_directory() . '/assets/js/crt-companion.js')) {
		wp_enqueue_script('nbu-terminal-crt', get_template_directory_uri() . '/assets/js/crt-companion.js', array(), NBU_T_VER, true);
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'nbu_t_assets');

function nbu_t_login_style() {
	if (file_exists(get_template_directory() . '/assets/css/login.css')) {
		wp_enqueue_style('nbu-terminal-login', get_template_directory_uri() . '/assets/css/login.css', array(), NBU_T_VER);
	}
}
add_action('login_enqueue_scripts', 'nbu_t_login_style');

function nbu_t_login_brand($url) {
	return home_url('/');
}
add_filter('login_headerurl', 'nbu_t_login_brand');

function nbu_t_login_title($title) {
	return esc_html(get_bloginfo('name')) . ' — Sign in';
}
add_filter('login_headertext', 'nbu_t_login_title');

if (class_exists('Walker_Nav_Menu') && !class_exists('NBU_T_Nav_Walker')) {
	class NBU_T_Nav_Walker extends Walker_Nav_Menu {
		public function start_lvl(&$output, $depth = 0, $args = null) {}
		public function end_lvl(&$output, $depth = 0, $args = null) {}

		public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
			$classes  = empty($item->classes) ? array() : (array) $item->classes;
			$is_active = in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true) || in_array('current-menu-ancestor', $classes, true);

			$atts = '';
			$atts .= !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
			$atts .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
			$atts .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
			$atts .= !empty($item->url) ? ' href="' . esc_url($item->url) . '"' : '';

			$output .= '<a' . ($is_active ? ' class="active"' : '') . $atts . '>' . esc_html($item->title) . '</a>';
		}

		public function end_el(&$output, $item, $depth = 0, $args = null) {}
	}
}

function nbu_t_menu_fallback() {
	echo '<a href="' . esc_url(home_url('/')) . '" class="' . (is_front_page() ? 'active' : '') . '">' . esc_html__('Home', 'crt-terminal') . '</a>';
	echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('Journal', 'crt-terminal') . '</a>';
	echo '<a href="' . esc_url(home_url('/#projects')) . '">' . esc_html__('Projects', 'crt-terminal') . '</a>';
}

function nbu_t_customizer_admin_css() {
	wp_add_inline_style(
		'customize-controls',
		'.customize-control-nbu_t_range input[type=range]{width:100%}.customize-control-nbu_t_range output{display:block;text-align:right;font-size:11px;color:#777}.nbu-t-font-source{display:flex;gap:14px;margin:6px 0}.nbu-t-font-panel{margin-top:6px}.nbu-t-font-check-result{margin-left:8px;font-size:12px}.nbu-t-font-check-result.ok{color:#2e7d32}.nbu-t-font-check-result.fail{color:#c0392b}.nbu-t-font-filename{font-size:12px;color:#777;margin-left:6px}'
	);
}
add_action('customize_controls_print_styles', 'nbu_t_customizer_admin_css');

if (class_exists('WP_Customize_Control') && !class_exists('NBU_T_Range_Control')) {
	class NBU_T_Range_Control extends WP_Customize_Control {
		public $type = 'nbu_t_range';
		public $input_attrs = array();

		public function render_content() {
			$min  = isset($this->input_attrs['min']) ? $this->input_attrs['min'] : 0;
			$max  = isset($this->input_attrs['max']) ? $this->input_attrs['max'] : 100;
			$step = isset($this->input_attrs['step']) ? $this->input_attrs['step'] : 1;
			?>
			<label>
				<?php if (!empty($this->label)) : ?>
					<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
				<?php endif; ?>
				<?php if (!empty($this->description)) : ?>
					<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
				<?php endif; ?>
				<input
					type="range"
					min="<?php echo esc_attr($min); ?>"
					max="<?php echo esc_attr($max); ?>"
					step="<?php echo esc_attr($step); ?>"
					value="<?php echo esc_attr($this->value()); ?>"
					<?php $this->link(); ?>
					oninput="this.nextElementSibling.textContent=this.value"
				>
				<output><?php echo esc_html($this->value()); ?></output>
			</label>
			<?php
		}
	}
}

if (class_exists('WP_Customize_Control') && !class_exists('NBU_T_Font_Control')) {
	class NBU_T_Font_Control extends WP_Customize_Control {
		public $type = 'nbu_t_font';

		public function enqueue() {
			if (file_exists(get_template_directory() . '/assets/js/font-control.js')) {
				wp_enqueue_script(
					'nbu-t-font-control',
					get_template_directory_uri() . '/assets/js/font-control.js',
					array('customize-controls', 'jquery'),
					NBU_T_VER,
					true
				);
				wp_localize_script('nbu-t-font-control', 'nbuTFontControl', array(
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('nbu_t_font_check'),
				));
			}
		}

		public function render_content() {
			$source_key = $this->id . '_source';
			$url_key    = $this->id . '_url';
			$source     = get_theme_mod($source_key, 'upload');
			$url        = get_theme_mod($url_key, '');
			$att_id     = absint($this->value());
			$file_name  = $att_id ? basename(get_attached_file($att_id)) : '';
			?>
			<label class="nbu-t-font-label">
				<?php if (!empty($this->label)) : ?>
					<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
				<?php endif; ?>
				<?php if (!empty($this->description)) : ?>
					<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
				<?php endif; ?>
			</label>

			<div class="nbu-t-font-source" data-field-id="<?php echo esc_attr($this->id); ?>">
				<label><input type="radio" class="nbu-t-font-source-radio" name="<?php echo esc_attr($this->id); ?>_source_radio" value="upload" <?php checked($source, 'upload'); ?>> 本地上传</label>
				<label><input type="radio" class="nbu-t-font-source-radio" name="<?php echo esc_attr($this->id); ?>_source_radio" value="url" <?php checked($source, 'url'); ?>> 在线链接</label>
			</div>

			<div class="nbu-t-font-panel nbu-t-font-panel-upload" style="<?php echo $source === 'upload' ? '' : 'display:none'; ?>">
				<input type="hidden" class="nbu-t-font-attachment-field" data-sync-field="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($att_id); ?>">
				<button type="button" class="button nbu-t-font-upload">选择文件</button>
				<span class="nbu-t-font-filename"><?php echo esc_html($file_name); ?></span>
			</div>

			<div class="nbu-t-font-panel nbu-t-font-panel-url" style="<?php echo $source === 'url' ? '' : 'display:none'; ?>">
				<input type="url" class="nbu-t-font-url-input" data-sync-field="<?php echo esc_attr($url_key); ?>" placeholder="https://example.com/font.woff2" value="<?php echo esc_attr($url); ?>" style="width:100%">
				<button type="button" class="button nbu-t-font-check" style="margin-top:6px">检测链接</button>
				<span class="nbu-t-font-check-result" aria-live="polite"></span>
			</div>

			<input type="hidden" class="nbu-t-font-source-field" data-sync-field="<?php echo esc_attr($source_key); ?>" value="<?php echo esc_attr($source); ?>">
			<?php
		}
	}
}

function nbu_t_ajax_check_font_url() {
	check_ajax_referer('nbu_t_font_check', 'nonce');

	if (!current_user_can('customize')) {
		wp_send_json_error(array('message' => '权限不足'));
	}

	$url = isset($_POST['url']) ? sanitize_text_field(wp_unslash($_POST['url'])) : '';
	if (!$url || !wp_http_validate_url($url)) {
		wp_send_json_error(array('message' => '链接格式无效'));
	}

	$valid_types = array(
		'font/woff2',
		'font/woff',
		'font/ttf',
		'font/otf',
		'application/font-woff2',
		'application/font-woff',
		'application/x-font-woff',
		'application/octet-stream',
	);

	$check_response = function ($resp) use ($valid_types, $url) {
		if (is_wp_error($resp)) {
			return null;
		}

		$code = wp_remote_retrieve_response_code($resp);
		$type = wp_remote_retrieve_header($resp, 'content-type');
		$type_ok = false;

		foreach ($valid_types as $t) {
			if ($type && stripos($type, $t) !== false) {
				$type_ok = true;
				break;
			}
		}

		if (!$type_ok && preg_match('/\.(woff2|woff|ttf|otf)$/i', $url)) {
			$type_ok = true;
		}

		return array(
			'code'    => $code,
			'typeOk'  => $type_ok,
		);
	};

	$resp   = wp_remote_head($url, array('timeout' => 8, 'redirection' => 3));
	$result = $check_response($resp);

	if (!$result || $result['code'] < 200 || $result['code'] >= 400 || !$result['typeOk']) {
		$get_resp   = wp_remote_get($url, array(
			'timeout'     => 8,
			'redirection' => 3,
			'headers'     => array('Range' => 'bytes=0-1023'),
		));
		$get_result = $check_response($get_resp);

		if ($get_result && $get_result['code'] >= 200 && $get_result['code'] < 400 && $get_result['typeOk']) {
			wp_send_json_success(array('message' => '链接可用'));
		}

		wp_send_json_error(array('message' => '未检测到有效字体文件，字体可能无法正常加载'));
	}

	wp_send_json_success(array('message' => '链接可用'));
}
add_action('wp_ajax_nbu_t_check_font_url', 'nbu_t_ajax_check_font_url');

function nbu_t_customize($c) {
	$c->add_section('nbu_t', array(
		'title'    => '新欧拉主题外观',
		'priority' => 30,
	));

	$c->add_setting('nbu_t_palette', array(
		'default'           => 'termius',
		'sanitize_callback' => 'sanitize_key',
	));
	$c->add_control('nbu_t_palette', array(
		'section'     => 'nbu_t',
		'label'       => '默认配色方案',
		'description' => '全站通用。访客端无法修改。',
		'type'        => 'select',
		'choices'     => array(
			'termius'    => 'Termius 深色',
			'nord'       => 'Nord 深色',
			'dracula'    => 'Dracula',
			'monokai'    => 'Monokai',
			'hackerblue' => '黑客蓝',
			'hackergreen'=> '黑客绿',
			'flexoki'    => 'Flexoki 深色',
			'light'      => '浅色工作台',
		),
	));

	$c->add_setting('nbu_t_radius', array(
		'default'           => '16',
		'sanitize_callback' => 'absint',
	));
	$c->add_control('nbu_t_radius', array(
		'section' => 'nbu_t',
		'label'   => '面板圆角',
		'type'    => 'select',
		'choices' => array(
			'10' => '10px',
			'14' => '14px',
			'16' => '16px',
			'20' => '20px',
		),
	));

	$c->add_setting('nbu_t_density', array(
		'default'           => 'comfortable',
		'sanitize_callback' => 'sanitize_key',
	));
	$c->add_control('nbu_t_density', array(
		'section' => 'nbu_t',
		'label'   => '界面密度',
		'type'    => 'select',
		'choices' => array(
			'compact'     => '紧凑',
			'comfortable' => '适中',
			'spacious'    => '宽松',
		),
	));

	$c->add_section('nbu_t_crt', array(
		'title'    => 'CRT 显示器伴侣',
		'priority' => 31,
	));

	$c->add_setting('nbu_t_crt_color', array(
		'default'           => '#00ff41',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$c->add_control(new WP_Customize_Color_Control($c, 'nbu_t_crt_color', array(
		'section' => 'nbu_t_crt',
		'label'   => '屏幕显示颜色',
	)));

	$c->add_setting('nbu_t_crt_case_color', array(
		'default'           => '#26272b',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$c->add_control(new WP_Customize_Color_Control($c, 'nbu_t_crt_case_color', array(
		'section' => 'nbu_t_crt',
		'label'   => '显示器外壳（边框）颜色',
	)));

	$c->add_setting('nbu_t_crt_ledon_color', array(
		'default'           => '#2ee06a',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$c->add_control(new WP_Customize_Color_Control($c, 'nbu_t_crt_ledon_color', array(
		'section'     => 'nbu_t_crt',
		'label'       => '显示器 LED 指示灯颜色',
		'description' => '机身左上角 ON 指示灯',
	)));

	$c->add_setting('nbu_t_crt_mode', array(
		'default'           => 'scanline',
		'sanitize_callback' => 'sanitize_key',
	));
	$c->add_control('nbu_t_crt_mode', array(
		'section'     => 'nbu_t_crt',
		'label'       => '效果模式',
		'description' => '切换屏幕上叠加的复古纹理效果类型',
		'type'        => 'select',
		'choices'     => array(
			'scanline' => '横向扫描线',
			'pixel'    => '像素颗粒',
		),
	));

	$range_settings = array(
		'nbu_t_crt_scanlines'          => array('扫描线密度', '0 为完全关闭，数值越大扫描线越明显', 35, 0, 100, 1),
		'nbu_t_crt_scanline_thickness' => array('扫描线粗细', '控制每条扫描线本身的像素厚度', 2, 1, 6, 1),
		'nbu_t_crt_scan_alpha'         => array('扫描线透明度', '独立控制扫描线的深浅，数值越大扫描线越黑越明显', 35, 0, 100, 5),
		'nbu_t_crt_pixel_size'         => array('像素块密度', '数值越大像素颗粒越密集越细小', 50, 0, 100, 1),
		'nbu_t_crt_pixel_thickness'    => array('像素块网格粗细', '控制像素块之间网格线的像素厚度', 1, 1, 6, 1),
		'nbu_t_crt_pixel_alpha'        => array('像素块透明度', '独立控制像素颗粒的深浅，数值越大颗粒感越明显', 35, 0, 100, 5),
		'nbu_t_crt_glow'               => array('整体色泽强度', '影响绫亮度和屏幕内壁整体辉光', 50, 0, 150, 5),
		'nbu_t_crt_edge_glow'          => array('屏幕边缘内发光强度', '单独控制屏幕四周内壁辉光的明亮程度，数值越大边缘发光越明显', 60, 0, 150, 5),
		'nbu_t_crt_eye_width'          => array('眼睛宽度', '占屏幕宽度的百分比，数值越大眼睛越宽', 40, 5, 80, 1),
		'nbu_t_crt_eye_height'         => array('眼睛高度', '占屏幕高度的百分比，数值越大眼睛越高。宽高相等即为正方形/圆形，不等则为长方形', 40, 5, 80, 1),
		'nbu_t_crt_eye_radius'         => array('眼睛圆角', '0为直角方形，50为完全圆形，中间数值为圆角矩形', 50, 0, 50, 1),
	);

	foreach ($range_settings as $key => $config) {
		$c->add_setting($key, array(
			'default'           => $config[2],
			'sanitize_callback' => 'absint',
		));
		$c->add_control(new NBU_T_Range_Control($c, $key, array(
			'section'     => 'nbu_t_crt',
			'label'       => $config[0],
			'description' => $config[1],
			'input_attrs' => array(
				'min'  => $config[3],
				'max'  => $config[4],
				'step' => $config[5],
			),
		)));
	}

	$c->add_section('nbu_t_led', array(
		'title'       => '顶部状态 LED',
		'priority'    => 32,
		'description' => '主页右上角的网络终端主题顶栏状态指示灯',
	));

	$c->add_setting('nbu_t_led_color', array(
		'default'           => '#51d6a7',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$c->add_control(new WP_Customize_Color_Control($c, 'nbu_t_led_color', array(
		'section'     => 'nbu_t_led',
		'label'       => 'LED 颜色',
		'description' => '就绪状态下的指示灯颜色',
	)));

	$c->add_setting('nbu_t_led_brightness', array(
		'default'           => 100,
		'sanitize_callback' => 'absint',
	));
	$c->add_control(new NBU_T_Range_Control($c, 'nbu_t_led_brightness', array(
		'section'     => 'nbu_t_led',
		'label'       => 'LED 亮度',
		'input_attrs' => array('min' => 10, 'max' => 200, 'step' => 5),
	)));

	$c->add_setting('nbu_t_led_speed', array(
		'default'           => 26,
		'sanitize_callback' => 'absint',
	));
	$c->add_control(new NBU_T_Range_Control($c, 'nbu_t_led_speed', array(
		'section'     => 'nbu_t_led',
		'label'       => '闪烁频率',
		'description' => '微微呼吸效果的周期，数值越小闪烁越快（单位：0.1秒）',
		'input_attrs' => array('min' => 5, 'max' => 60, 'step' => 1),
	)));

	$c->add_section('nbu_t_footer', array(
		'title'       => '悬浮页脚',
		'priority'    => 34,
		'description' => '页面底部悬浮工具条',
	));

	$c->add_setting('nbu_t_footer_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	));
	$c->add_control('nbu_t_footer_enable', array(
		'section' => 'nbu_t_footer',
		'label'   => '启用悬浮页脚',
		'type'    => 'checkbox',
	));

	$c->add_setting('nbu_t_footer_text', array(
		'default'           => '← Back to Home',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$c->add_control('nbu_t_footer_text', array(
		'section'     => 'nbu_t_footer',
		'label'       => '左侧文字',
		'description' => '留空则不显示该链接文字',
		'type'        => 'text',
	));

	$c->add_setting('nbu_t_footer_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	));
	$c->add_control('nbu_t_footer_link', array(
		'section'     => 'nbu_t_footer',
		'label'       => '左侧链接地址',
		'description' => '留空则默认跳转到网站首页',
		'type'        => 'url',
	));

	$c->add_setting('nbu_t_footer_center_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$c->add_control('nbu_t_footer_center_text', array(
		'section'     => 'nbu_t_footer',
		'label'       => '中间文字',
		'description' => '留空则自动显示站点标题',
		'type'        => 'text',
	));

	$c->add_setting('nbu_t_footer_clock_enable', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	));
	$c->add_control('nbu_t_footer_clock_enable', array(
		'section' => 'nbu_t_footer',
		'label'   => '显示右侧时钟',
		'type'    => 'checkbox',
	));

	$c->add_setting('nbu_t_footer_clock_label', array(
		'default'           => 'CST',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$c->add_control('nbu_t_footer_clock_label', array(
		'section'     => 'nbu_t_footer',
		'label'       => '时钟前缀标签',
		'description' => '显示在时间前面的文字，例如 CST、北京时间',
		'type'        => 'text',
	));

	$c->add_setting('nbu_t_footer_clock_tz', array(
		'default'           => 'Asia/Shanghai',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$c->add_control('nbu_t_footer_clock_tz', array(
		'section' => 'nbu_t_footer',
		'label'   => '时钟时区',
		'type'    => 'select',
		'choices' => array(
			'Asia/Shanghai'     => '北京时间 (UTC+8)',
			'Asia/Tokyo'        => '东京时间 (UTC+9)',
			'Asia/Singapore'    => '新加坡时间 (UTC+8)',
			'Europe/London'     => '伦敦时间',
			'America/New_York'  => '纽约时间',
			'America/Los_Angeles' => '洛杉矶时间',
			'UTC'               => 'UTC 协调世界时',
		),
	));
}
add_action('customize_register', 'nbu_t_customize');

function nbu_t_body_class($classes) {
	$mode = get_theme_mod('nbu_t_crt_mode', 'scanline');

	if ($mode === 'pixel') {
		$classes[] = 'crt-mode-pixel';
	}

	if (!get_theme_mod('nbu_t_footer_enable', true)) {
		$classes[] = 'nbu-t-footer-off';
	}

	return $classes;
}
add_filter('body_class', 'nbu_t_body_class');

function nbu_t_vars() {
	$palettes = array(
		'termius'    => array('#171924','#1d2033','#282b3d','#30344b','#ddddea','#a9abbb','#777b92','#7c83ff','#51d6a7','#f5c76e'),
		'nord'       => array('#2e3440','#292e39','#3b4252','#434c5e','#eceff4','#d8dee9','#81a1c1','#88c0d0','#a3be8c','#ebcb8b'),
		'dracula'    => array('#282a36','#21222c','#343746','#414558','#f8f8f2','#d9d5ef','#8b89a6','#bd93f9','#50fa7b','#f1fa8c'),
		'monokai'    => array('#272822','#20211d','#35362f','#414339','#f8f8f2','#d5d6c8','#8e9283','#a6e22e','#a6e22e','#e6db74'),
		'hackerblue' => array('#071824','#0a2131','#0d2b3e','#12354b','#d8f1ff','#a9c9db','#668da2','#32a7ff','#2fe0a1','#f4cd70'),
		'hackergreen'=> array('#07130d','#0b1b12','#102519','#163322','#d8f7df','#a5ccb0','#5f8e6c','#4eea83','#4eea83','#e6d75d'),
		'flexoki'    => array('#1c1b1a','#242321','#302e2b','#3a3835','#cecdc3','#b7b5ac','#87847b','#d0a215','#879a39','#d0a215'),
		'light'      => array('#f4f5f8','#ffffff','#ffffff','#edf0f7','#20222a','#515767','#7a8090','#5765d9','#248b64','#a96b13'),
	);

	$v = isset($palettes[get_theme_mod('nbu_t_palette', 'termius')]) ? $palettes[get_theme_mod('nbu_t_palette', 'termius')] : $palettes['termius'];
	$n = array('--bg','--side','--panel','--raise','--text','--sub','--muted','--accent','--ok','--warn');

	$x = ':root{';
	foreach ($n as $i => $k) {
		$x .= $k . ':' . $v[$i] . ';';
	}
	$x .= '--radius:' . absint(get_theme_mod('nbu_t_radius', 16)) . 'px;';
	$d = get_theme_mod('nbu_t_density', 'comfortable');
	$x .= '--density:' . ($d === 'compact' ? '.84' : ($d === 'spacious' ? '1.18' : '1')) . ';';

	$x .= '--crt-color:' . (sanitize_hex_color(get_theme_mod('nbu_t_crt_color', '#00ff41')) ?: '#00ff41') . ';';
	$x .= '--crt-case-color:' . (sanitize_hex_color(get_theme_mod('nbu_t_crt_case_color', '#26272b')) ?: '#26272b') . ';';
	$x .= '--crt-ledon-color:' . (sanitize_hex_color(get_theme_mod('nbu_t_crt_ledon_color', '#2ee06a')) ?: '#2ee06a') . ';';

	$scan_density = absint(get_theme_mod('nbu_t_crt_scanlines', 35));
	$scan_gap = max(2, round(6 - ($scan_density / 100) * 4));
	$x .= '--crt-scan-gap:' . $scan_gap . 'px;';
	$x .= '--crt-scan-thickness:' . absint(get_theme_mod('nbu_t_crt_scanline_thickness', 2)) . 'px;';
	$x .= '--crt-scan-alpha:' . absint(get_theme_mod('nbu_t_crt_scan_alpha', 35)) . '%;';

	$pixel_size_pct = absint(get_theme_mod('nbu_t_crt_pixel_size', 50));
	$pixel_gap = max(2, round(10 - ($pixel_size_pct / 100) * 8));
	$x .= '--crt-pixel-gap:' . $pixel_gap . 'px;';
	$x .= '--crt-pixel-thickness:' . absint(get_theme_mod('nbu_t_crt_pixel_thickness', 1)) . 'px;';
	$x .= '--crt-pixel-alpha:' . absint(get_theme_mod('nbu_t_crt_pixel_alpha', 35)) . '%;';

	$glow_pct = absint(get_theme_mod('nbu_t_crt_glow', 50));
	$edge_pct = absint(get_theme_mod('nbu_t_crt_edge_glow', 60));
	$x .= '--crt-glow-mult:' . round($glow_pct / 50, 2) . ';';
	$x .= '--crt-edge-glow-mult:' . round($edge_pct / 50, 2) . ';';
	$x .= '--crt-eye-w:' . absint(get_theme_mod('nbu_t_crt_eye_width', 40)) . 'cqw;';
	$x .= '--crt-eye-h:' . absint(get_theme_mod('nbu_t_crt_eye_height', 40)) . 'cqh;';
	$x .= '--crt-eye-radius:' . absint(get_theme_mod('nbu_t_crt_eye_radius', 50)) . '%;';

	$led_brightness = absint(get_theme_mod('nbu_t_led_brightness', 100));
	$led_speed = absint(get_theme_mod('nbu_t_led_speed', 26));
	$x .= '--led-brightness:' . round($led_brightness / 100, 2) . ';';
	$x .= '--led-speed:' . round($led_speed / 10, 2) . 's;';
	$x .= '--led-color:' . (sanitize_hex_color(get_theme_mod('nbu_t_led_color', '#51d6a7')) ?: '#51d6a7') . ';';

	$sans_source = get_theme_mod('nbu_t_font_sans_source', 'upload');
	$mono_source = get_theme_mod('nbu_t_font_mono_source', 'upload');
	$sans_url = '';
	$mono_url = '';

	if ($sans_source === 'url') {
		$sans_url = esc_url_raw(get_theme_mod('nbu_t_font_sans_url', ''));
	} else {
		$sid = absint(get_theme_mod('nbu_t_font_sans', 0));
		if ($sid) {
			$sans_url = wp_get_attachment_url($sid);
		}
	}

	if ($mono_source === 'url') {
		$mono_url = esc_url_raw(get_theme_mod('nbu_t_font_mono_url', ''));
	} else {
		$mid = absint(get_theme_mod('nbu_t_font_mono', 0));
		if ($mid) {
			$mono_url = wp_get_attachment_url($mid);
		}
	}

	$face_css = '';

	if ($sans_url) {
		$face_css .= "@font-face{font-family:'NBUCustomSans';src:url('" . esc_url($sans_url) . "') format('woff2');font-weight:100 900;font-style:normal;font-display:swap;}";
		$x .= "--font:'NBUCustomSans',-apple-system,BlinkMacSystemFont,\"Segoe UI\",\"PingFang SC\",\"Hiragino Sans GB\",\"Microsoft YaHei\",sans-serif;";
	}

	if ($mono_url) {
		$face_css .= "@font-face{font-family:'NBUCustomMono';src:url('" . esc_url($mono_url) . "') format('woff2');font-weight:100 900;font-style:normal;font-display:swap;}";
		$x .= "--mono:'NBUCustomMono','SFMono-Regular',Consolas,\"Liberation Mono\",monospace;";
	}

	$x .= '}';

	if ($face_css) {
		$x = $face_css . $x;
	}

	wp_add_inline_style('nbu-terminal', $x);
}
add_action('wp_enqueue_scripts', 'nbu_t_vars', 20);

function nbu_t_block_editor_support() {
	add_theme_support('wp-block-styles');
	add_theme_support('align-wide');
	add_theme_support('responsive-embeds');
	add_theme_support('editor-styles');
	add_editor_style('style.css');
}
add_action('after_setup_theme', 'nbu_t_block_editor_support');
