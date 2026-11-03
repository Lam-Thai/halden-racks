<?php
/**
 * Quotes: big testimonial + scrolling rail. view.js wires the previous/next buttons.
 *
 * items[]: { result, quote, name, role }   Empty quote hides the big quote; empty items hides the rail.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_items = array_values( array_filter( (array) $halden_a['items'], 'is_array' ) );
$halden_wrap  = array( 'class' => 'sec proof' );
if ( $halden_a['label'] ) {
	$halden_wrap['aria-label'] = $halden_a['label'];
}
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrap ); ?>>
	<div class="wrap">
		<?php if ( $halden_a['quote'] ) : ?>
			<figure class="bigq" data-reveal>
				<svg class="qm" viewBox="0 0 72 72" aria-hidden="true"><path fill="currentColor" d="M8 44c0-14 7-25 22-30l3 6c-8 4-12 9-12 15h11v21H8zm34 0c0-14 7-25 22-30l3 6c-8 4-12 9-12 15h11v21H42z"/></svg>
				<div style="display:grid;gap:28px">
					<blockquote><?php echo esc_html( $halden_a['quote'] ); ?></blockquote>
					<figcaption><b><?php echo esc_html( $halden_a['name'] ); ?></b><?php if ( $halden_a['role'] ) : ?><span><?php echo esc_html( $halden_a['role'] ); ?></span><?php endif; ?><?php if ( $halden_a['result'] ) : ?><span class="res"><?php echo esc_html( $halden_a['result'] ); ?></span><?php endif; ?></figcaption>
				</div>
			</figure>
		<?php endif; ?>
		<?php if ( $halden_items ) : ?>
			<div class="qrail">
				<div class="qrail__head">
					<h3><?php echo esc_html( $halden_a['railTitle'] ); ?></h3>
					<div class="qrail__nav"><button type="button" data-dir="-1" aria-label="<?php esc_attr_e( 'Previous quote', 'halden' ); ?>"><?php echo halden_icon( 'back' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button><button type="button" data-dir="1" aria-label="<?php esc_attr_e( 'Next quote', 'halden' ); ?>"><?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button></div>
				</div>
				<ul class="qrail__list">
					<?php foreach ( $halden_items as $halden_q ) : ?>
						<li><figure class="qc"><?php if ( ! empty( $halden_q['result'] ) ) : ?><p class="res"><?php echo esc_html( $halden_q['result'] ); ?></p><?php endif; ?><blockquote><?php echo esc_html( $halden_q['quote'] ?? '' ); ?></blockquote><figcaption><b><?php echo esc_html( $halden_q['name'] ?? '' ); ?></b><?php echo esc_html( $halden_q['role'] ?? '' ); ?></figcaption></figure></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
