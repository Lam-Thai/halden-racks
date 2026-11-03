<?php
/**
 * Category chips: "All" (the posts page) + each category with posts. The current view gets aria-current.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_page    = (int) get_option( 'page_for_posts' );
$halden_all     = $halden_page ? get_permalink( $halden_page ) : home_url( '/' );
$halden_cats    = get_categories( array( 'hide_empty' => true, 'orderby' => 'name' ) );
$halden_current = is_category() ? (int) get_queried_object_id() : 0;
$halden_total   = (int) wp_count_posts( 'post' )->publish;
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'news-filter', 'aria-label' => $attributes['ariaLabel'] ) ); ?>>
	<ul>
		<li><a href="<?php echo esc_url( $halden_all ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $attributes['allLabel'] ); ?> <span class="mono"><?php echo (int) $halden_total; ?></span></a></li>
		<?php foreach ( $halden_cats as $halden_cat ) : ?>
			<li><a href="<?php echo esc_url( get_category_link( $halden_cat ) ); ?>"<?php echo $halden_current === (int) $halden_cat->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $halden_cat->name ); ?> <span class="mono"><?php echo (int) $halden_cat->count; ?></span></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
