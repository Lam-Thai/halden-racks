<?php
/**
 * Services index: numbered rows that jump to each service's section on the page.
 *
 * items[]: { title, text, price, url }   url is usually an in-page anchor ("#cloud-connect").
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_items = array_values( array_filter( (array) $halden_a['items'], 'is_array' ) );
$halden_h_id  = wp_unique_id( 'sx-h-' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'sec paper sx', 'aria-labelledby' => $halden_h_id ) ); ?>>
	<div class="wrap">
		<div class="head head--split" data-reveal>
			<div style="display:grid;gap:20px">
				<?php if ( $halden_a['eyebrow'] ) : ?>
					<p class="tag"><?php echo esc_html( $halden_a['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
			</div>
			<?php if ( $halden_a['intro'] ) : ?>
				<p><?php echo halden_inline( $halden_a['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $halden_items ) : ?>
			<ol class="sx__list">
				<?php foreach ( $halden_items as $halden_i => $halden_it ) : ?>
					<li data-reveal<?php echo $halden_i ? ' style="--d:' . (int) min( $halden_i, 4 ) . '"' : ''; ?>>
						<a class="sx__row" href="<?php echo esc_url( halden_url( $halden_it['url'] ?? '' ) ); ?>">
							<span class="sx__n" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $halden_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<h3 class="sx__t"><?php echo esc_html( $halden_it['title'] ?? '' ); ?></h3>
							<?php if ( ! empty( $halden_it['text'] ) ) : ?>
								<span class="sx__d"><?php echo halden_inline( $halden_it['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<?php endif; ?>
							<span class="sx__p"><?php echo halden_inline( $halden_it['price'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="go" aria-hidden="true"><?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</div>
</section>
