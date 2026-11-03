<?php
/**
 * Site header. Links come from the "Header navigation" menu so the client edits them in wp-admin.
 * Ids (#hdr, #drawer, #menuBtn) are used by assets/js/site.js; the block allows one instance.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_items = array();
$halden_locs  = get_nav_menu_locations();
if ( ! empty( $halden_locs['primary'] ) ) {
	foreach ( (array) wp_get_nav_menu_items( $halden_locs['primary'] ) as $halden_item ) {
		if ( empty( $halden_item->menu_item_parent ) ) {
			$halden_items[] = $halden_item;
		}
	}
}

// Current page URL, for aria-current. The news index and its posts count as "News".
if ( is_front_page() ) {
	$halden_here = home_url( '/' );
} elseif ( is_home() || is_singular( 'post' ) ) {
	$halden_here = (string) get_permalink( (int) get_option( 'page_for_posts' ) );
} else {
	$halden_here = is_singular() ? (string) get_permalink( get_queried_object_id() ) : '';
}
$halden_is_current = function ( $url ) use ( $halden_here ) {
	return '' !== $halden_here && trailingslashit( strtok( $url, '?#' ) ) === trailingslashit( $halden_here );
};
?>
<header class="hdr" id="hdr">
	<div class="wrap hdr__in">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Halden, home', 'halden' ); ?>"><?php echo halden_logo_mark(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><span class="logo__word">Halden<span>Racks</span></span></a>
		<nav aria-label="<?php esc_attr_e( 'Primary', 'halden' ); ?>">
			<ul class="nav">
				<?php foreach ( $halden_items as $halden_item ) : ?>
					<?php $halden_class = implode( ' ', array_filter( (array) $halden_item->classes ) ); ?>
					<li><a<?php echo $halden_class ? ' class="' . esc_attr( $halden_class ) . '"' : ''; ?> href="<?php echo esc_url( $halden_item->url ); ?>"<?php echo $halden_is_current( $halden_item->url ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $halden_item->title ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div class="hdr__act">
			<a class="portal" href="<?php echo esc_url( halden_url( $attributes['portalUrl'] ) ); ?>"><?php echo esc_html( $attributes['portalLabel'] ); ?></a>
			<a class="btn btn--red btn--sm" href="<?php echo esc_url( halden_url( $attributes['ctaUrl'] ) ); ?>"><?php echo esc_html( $attributes['ctaLabel'] ); ?></a>
			<button class="menu-btn" id="menuBtn" type="button" aria-expanded="false" aria-controls="drawer" aria-label="<?php esc_attr_e( 'Open menu', 'halden' ); ?>"><?php echo halden_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
		</div>
	</div>
</header>
<div class="drawer" id="drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'halden' ); ?>">
	<?php foreach ( $halden_items as $halden_item ) : ?>
		<a href="<?php echo esc_url( $halden_item->url ); ?>"><?php echo esc_html( $halden_item->title ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	<?php endforeach; ?>
	<a href="<?php echo esc_url( halden_url( $attributes['portalUrl'] ) ); ?>"><?php echo esc_html( $attributes['portalLabel'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	<a class="btn btn--red" href="<?php echo esc_url( halden_url( $attributes['ctaUrl'] ) ); ?>"><?php echo esc_html( $attributes['ctaLabel'] ); ?></a>
</div>
