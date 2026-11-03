<?php
/**
 * Front-end and editor assets.
 *
 * Global: site.css + site.js. Per page: assets/css/pages/<slug>.css when it exists.
 * Per block: each block.json declares its own style/viewScript, so heavy scripts
 * (Three.js, canvas animations) load only on pages that contain those blocks.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

/** Cache-busting version: file modification time, so edits show without a hard refresh. */
function halden_ver( $relative ) {
	$file = get_theme_file_path( $relative );
	return file_exists( $file ) ? (string) filemtime( $file ) : HALDEN_VERSION;
}

/** Slug used to pick the page stylesheet: front page → home, blog index and posts → news. */
function halden_page_style_slug() {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_home() || is_singular( 'post' ) || is_archive() ) {
		return 'news';
	}
	if ( is_page() ) {
		return get_post_field( 'post_name', get_queried_object_id() );
	}
	return '';
}

// Shared script handles that block.json files list in "viewScript". Registered early so blocks can resolve them.
add_action(
	'init',
	function () {
		$args = array( 'in_footer' => true, 'strategy' => 'defer' );
		wp_register_script( 'halden-three', get_theme_file_uri( 'assets/js/vendor/three.min.js' ), array(), '158', $args );
		wp_register_script( 'halden-rack3d', get_theme_file_uri( 'assets/js/vendor/rack3d.js' ), array( 'halden-three' ), halden_ver( 'assets/js/vendor/rack3d.js' ), $args );
		wp_register_script( 'halden-lines', get_theme_file_uri( 'assets/js/lines.js' ), array(), halden_ver( 'assets/js/lines.js' ), $args );
	},
	5
);

// Block view scripts get "?ver=<WordPress version>" by default; use the file's mtime so edits show at once.
add_filter(
	'script_loader_src',
	function ( $src ) {
		if ( preg_match( '#/themes/halden/(blocks/[^?]+\.js)#', $src, $m ) ) {
			$src = add_query_arg( 'ver', halden_ver( $m[1] ), $src );
		}
		return $src;
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'halden-site', get_theme_file_uri( 'assets/css/site.css' ), array(), halden_ver( 'assets/css/site.css' ) );

		$slug = halden_page_style_slug();
		$page = 'assets/css/pages/' . sanitize_file_name( $slug ) . '.css';
		if ( $slug && file_exists( get_theme_file_path( $page ) ) ) {
			wp_enqueue_style( 'halden-page', get_theme_file_uri( $page ), array( 'halden-site' ), halden_ver( $page ) );
		}

		wp_enqueue_script( 'halden-site', get_theme_file_uri( 'assets/js/site.js' ), array(), halden_ver( 'assets/js/site.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
);

// Fonts are self-hosted; preload the two used above the fold.
add_action(
	'wp_head',
	function () {
		foreach ( array( 'inter-100-900.woff2', 'poppins-600.woff2' ) as $font ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( halden_asset( 'fonts/' . $font ) ) );
		}
	},
	2
);

// Editor: live server previews + sidebar fields for every halden/* block (see assets/js/editor.js).
add_action(
	'enqueue_block_editor_assets',
	function () {
		$blocks = array();
		foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $type ) {
			if ( 0 !== strpos( $name, 'halden/' ) ) {
				continue;
			}
			$fields = array();
			foreach ( (array) $type->attributes as $key => $schema ) {
				$t = isset( $schema['type'] ) ? $schema['type'] : '';
				if ( in_array( $t, array( 'string', 'boolean', 'number', 'integer' ), true ) ) {
					$fields[ $key ] = $t;
				}
			}
			$blocks[ $name ] = $fields;
		}
		wp_enqueue_script(
			'halden-editor',
			get_theme_file_uri( 'assets/js/editor.js' ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ),
			halden_ver( 'assets/js/editor.js' ),
			true
		);
		wp_add_inline_script( 'halden-editor', 'window.haldenBlocks = ' . wp_json_encode( $blocks ) . ';', 'before' );
	}
);
