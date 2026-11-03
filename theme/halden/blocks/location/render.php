<?php
/**
 * Location: aerial map with pins + landmark, and the location cards. view.js zooms and
 * spotlights the chosen place ("location spotlight" in the sample).
 *
 * places[]: { title, kind, icon: server|office, text, tip, label (pin's screen-reader name),
 *             x, y (pin, % of image), fx, fy (zoom focus, defaults to x/y), zoom, r (spotlight %),
 *             color: red|lake }
 * landmark: { label, x, y }  (empty label hides it)
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a      = $attributes;
$halden_places = array_values( array_filter( (array) $halden_a['places'], 'is_array' ) );
$halden_h_id   = wp_unique_id( 'loc-h-' );
$halden_lm     = (array) $halden_a['landmark'];
$halden_num    = function ( $v, $fallback ) {
	return is_numeric( $v ) ? (float) $v : (float) $fallback;
};
$halden_color  = function ( $c ) {
	return 'lake' === $c ? 'var(--lake)' : 'var(--red)';
};
$halden_wrap   = array(
	'class'           => 'loc',
	'aria-labelledby' => $halden_h_id,
);
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrap ); ?>>
	<div class="wrap">
		<div class="loc__head" data-reveal>
			<div style="display:grid;gap:20px"><h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2></div>
			<?php if ( $halden_a['intro'] ) : ?>
				<p><?php echo halden_inline( $halden_a['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
		<div class="loc__map" data-reveal>
			<div class="loc__pan"><div class="loc__dim" aria-hidden="true"></div><picture><img src="<?php echo esc_url( halden_asset( 'images/' . $halden_a['image'] ) ); ?>" width="1780" height="1000" decoding="async" fetchpriority="low" alt="<?php echo esc_attr( $halden_a['imageAlt'] ); ?>"></picture>
				<?php if ( ! empty( $halden_lm['label'] ) ) : ?>
					<span class="lmark" aria-hidden="true" style="--x:<?php echo esc_attr( $halden_num( $halden_lm['x'] ?? null, 50 ) ); ?>%;--y:<?php echo esc_attr( $halden_num( $halden_lm['y'] ?? null, 50 ) ); ?>%"><svg viewBox="0 0 24 24"><path d="M8 3.1V7a4 4 0 0 0 8 0V3.1"/><path d="M9 19c-2.8 0-5-2.2-5-5v-4a8 8 0 0 1 16 0v4c0 2.8-2.2 5-5 5Z"/><path d="m8 19-2 3M16 19l2 3"/></svg><?php echo esc_html( $halden_lm['label'] ); ?></span>
				<?php endif; ?>
				<?php foreach ( $halden_places as $halden_k => $halden_p ) : ?>
					<?php
					$halden_x     = $halden_num( $halden_p['x'] ?? null, 50 );
					$halden_y     = $halden_num( $halden_p['y'] ?? null, 50 );
					$halden_style = '--x:' . $halden_x . '%;--y:' . $halden_y . '%' . ( 'lake' === ( $halden_p['color'] ?? '' ) ? ';--c:var(--lake)' : '' );
					$halden_label = ! empty( $halden_p['label'] ) ? $halden_p['label'] : trim( ( $halden_p['title'] ?? '' ) . ', ' . ( $halden_p['kind'] ?? '' ), ', ' );
					?>
					<button class="lpin" type="button" data-k="<?php echo (int) $halden_k; ?>" data-fx="<?php echo esc_attr( $halden_num( $halden_p['fx'] ?? null, $halden_x ) ); ?>" data-fy="<?php echo esc_attr( $halden_num( $halden_p['fy'] ?? null, $halden_y ) ); ?>" data-z="<?php echo esc_attr( $halden_num( $halden_p['zoom'] ?? null, 1.12 ) ); ?>" data-r="<?php echo esc_attr( $halden_num( $halden_p['r'] ?? null, 20 ) ); ?>" style="<?php echo esc_attr( $halden_style ); ?>" aria-pressed="false" aria-label="<?php echo esc_attr( $halden_label ); ?>"><?php if ( ! empty( $halden_p['tip'] ) ) : ?><span class="tip"><?php echo esc_html( $halden_p['tip'] ); ?></span><?php endif; ?></button>
				<?php endforeach; ?>
			</div>
			<?php if ( $halden_a['captionStart'] || $halden_a['captionEnd'] ) : ?>
				<p class="loc__cap" aria-hidden="true"><span><?php echo esc_html( $halden_a['captionStart'] ); ?></span><span><?php echo esc_html( $halden_a['captionEnd'] ); ?></span></p>
			<?php endif; ?>
		</div>
		<?php if ( $halden_places ) : ?>
			<ul class="lc">
				<?php foreach ( $halden_places as $halden_k => $halden_p ) : ?>
					<li><button type="button" data-k="<?php echo (int) $halden_k; ?>" aria-pressed="<?php echo 0 === $halden_k ? 'true' : 'false'; ?>" style="--c:<?php echo esc_attr( $halden_color( $halden_p['color'] ?? '' ) ); ?>"><span class="lc__k"><?php echo halden_icon( 'office' === ( $halden_p['icon'] ?? '' ) ? 'office' : 'server' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $halden_p['kind'] ?? '' ); ?></span><b><?php echo esc_html( $halden_p['title'] ?? '' ); ?></b><?php if ( ! empty( $halden_p['text'] ) ) : ?><span class="lc__d"><?php echo esc_html( $halden_p['text'] ); ?></span><?php endif; ?></button></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
