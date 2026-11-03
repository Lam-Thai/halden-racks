<?php
/**
 * Closing CTA band (sample: section.finale.finale--type) with the decorative aerial crop and pin.
 * The crop and the pin's ping are pure CSS (site.css .fin__*), so there is no view script.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a   = $attributes;
$halden_hid = wp_unique_id( 'fin-h-' );
$halden_img = (string) $halden_a['image'];
if ( '' !== $halden_img && ! preg_match( '#^(https?:)?//#', $halden_img ) ) {
	$halden_img = halden_asset( 'images/' . ltrim( $halden_img, '/' ) );
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'finale finale--type', 'aria-labelledby' => $halden_hid ) ); ?>>
<?php if ( $halden_img ) : ?>
<div class="fin__img" aria-hidden="true"><div class="fin__pan"><picture><img src="<?php echo esc_url( $halden_img ); ?>" width="1780" height="1000" loading="lazy" decoding="async" alt=""></picture><?php if ( $halden_a['pinLabel'] ) : ?><span class="fin__pin"><i></i><b><?php echo esc_html( $halden_a['pinLabel'] ); ?></b></span><?php endif; ?></div></div>
<?php endif; ?>
<div class="wrap">
<h2 id="<?php echo esc_attr( $halden_hid ); ?>" data-reveal><?php echo halden_inline( $halden_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
<div class="finale__row" data-reveal style="--d:1">
<div style="display:grid;gap:24px">
<?php if ( $halden_a['text'] ) : ?><p><?php echo halden_inline( $halden_a['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
<?php if ( $halden_a['primaryLabel'] || $halden_a['secondaryLabel'] ) : ?>
<div style="display:flex;flex-wrap:wrap;gap:12px">
<?php if ( $halden_a['primaryLabel'] ) : ?>
<a class="btn btn--red" href="<?php echo esc_url( halden_url( $halden_a['primaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['primaryLabel'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
<?php endif; ?>
<?php if ( $halden_a['secondaryLabel'] ) : ?>
<a class="btn btn--ghost" href="<?php echo esc_url( halden_url( $halden_a['secondaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['secondaryLabel'] ); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php if ( $halden_a['priceMatch'] ) : ?>
<div class="pm"><?php echo halden_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><p><?php echo halden_inline( $halden_a['priceMatch'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
<?php endif; ?>
</div>
</div>
</section>
