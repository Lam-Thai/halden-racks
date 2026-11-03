<?php
/**
 * "Back to news" link. Points at the posts page, so it follows the page if it is renamed.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_page = (int) get_option( 'page_for_posts' );
$halden_href = $halden_page ? get_permalink( $halden_page ) : home_url( '/' );
?>
<p <?php echo get_block_wrapper_attributes( array( 'class' => 'news-back' ) ); ?>><a href="<?php echo esc_url( $halden_href ); ?>"><?php echo halden_icon( 'back' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><span><?php echo esc_html( $attributes['label'] ); ?></span></a></p>
