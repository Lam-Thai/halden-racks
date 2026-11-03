<?php
/**
 * Journey timeline ("From first call to a live rack in days.").
 *
 * steps[]: { time, title, text }
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_steps = array_values( array_filter( (array) $halden_a['steps'], 'is_array' ) );
$halden_h_id  = wp_unique_id( 'j-h-' );
$halden_wrap  = array(
	'class'           => 'sec',
	'aria-labelledby' => $halden_h_id,
);
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrap ); ?>>
	<div class="wrap">
		<div class="head" data-reveal>
			<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
		</div>
		<?php if ( $halden_steps ) : ?>
			<ol class="journey">
				<?php foreach ( $halden_steps as $halden_i => $halden_s ) : ?>
					<li data-reveal<?php echo $halden_i ? ' style="--d:' . (int) min( $halden_i, 3 ) . '"' : ''; ?>><p class="t"><?php echo esc_html( $halden_s['time'] ?? '' ); ?></p><h3><?php echo esc_html( $halden_s['title'] ?? '' ); ?></h3><p><?php echo halden_inline( $halden_s['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</div>
</section>
