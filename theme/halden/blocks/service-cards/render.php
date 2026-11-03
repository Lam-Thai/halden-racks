<?php
/**
 * Service cards ("Add to your colocation."): each card is one link.
 *
 * items[]: { type: image|stack, title, text, price, url, image, alt, width, height, stack: [ { k, v } ] }
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_items = array_values( array_filter( (array) $halden_a['items'], 'is_array' ) );
$halden_h_id  = wp_unique_id( 'also-h-' );
$halden_wrap  = array(
	'class'           => 'sec',
	'aria-labelledby' => $halden_h_id,
);
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrap ); ?>>
	<div class="wrap">
		<div class="head" data-reveal>
			<?php if ( $halden_a['eyebrow'] ) : ?>
				<p class="cap"><?php echo esc_html( $halden_a['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
		</div>
		<?php if ( $halden_items ) : ?>
			<div class="also">
				<?php foreach ( $halden_items as $halden_i => $halden_c ) : ?>
					<?php $halden_stack = 'stack' === ( $halden_c['type'] ?? '' ); ?>
					<a class="svc<?php echo $halden_stack ? ' svc--type' : ''; ?>" href="<?php echo esc_url( halden_url( $halden_c['url'] ?? '' ) ); ?>" data-reveal<?php echo $halden_i ? ' style="--d:' . (int) min( $halden_i, 3 ) . '"' : ''; ?>>
						<?php if ( $halden_stack ) : ?>
							<div class="svc__img" aria-hidden="true"><div class="stack"><?php foreach ( (array) ( $halden_c['stack'] ?? array() ) as $halden_row ) : ?><span><?php echo esc_html( $halden_row['k'] ?? '' ); ?> <b><?php echo esc_html( $halden_row['v'] ?? '' ); ?></b></span><?php endforeach; ?></div></div>
						<?php elseif ( ! empty( $halden_c['image'] ) ) : ?>
							<div class="svc__img"><picture><img src="<?php echo esc_url( halden_asset( 'images/' . $halden_c['image'] ) ); ?>" width="<?php echo (int) ( $halden_c['width'] ?? 1800 ); ?>" height="<?php echo (int) ( $halden_c['height'] ?? 1195 ); ?>" loading="lazy" alt="<?php echo esc_attr( $halden_c['alt'] ?? '' ); ?>"></picture></div>
						<?php endif; ?>
						<div class="svc__body"><h3><?php echo esc_html( $halden_c['title'] ?? '' ); ?></h3><?php if ( ! empty( $halden_c['text'] ) ) : ?><p><?php echo esc_html( $halden_c['text'] ); ?></p><?php endif; ?><p class="price"><span><?php echo esc_html( $halden_c['price'] ?? '' ); ?></span><span class="go" aria-hidden="true"><?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span></p></div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
