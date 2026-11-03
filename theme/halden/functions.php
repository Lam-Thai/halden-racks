<?php
/**
 * Halden theme bootstrap. Each concern lives in its own file under inc/.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

define( 'HALDEN_VERSION', '0.1.0' );

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/assets.php';
require_once __DIR__ . '/inc/blocks.php';
require_once __DIR__ . '/seed/seed.php';

if ( 'local' === wp_get_environment_type() ) {
	require_once __DIR__ . '/inc/dev.php';
}
