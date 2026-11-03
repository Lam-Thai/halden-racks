<?php
/**
 * AI and GPU: dark section with badge, gradient headline, CTAs, framed image and spec rows
 * whose line graphics (power arc, fibre) come from assets/js/lines.js.
 *
 * specs[]: { num, unit, title, text, line: power|net|waves|audit|"" }
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_h_id  = wp_unique_id( 'ai-h-' );
$halden_arrow = halden_icon( 'arrow' );
$halden_wrap  = array(
	'class'           => 'ai',
	'aria-labelledby' => $halden_h_id,
);
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrap ); ?>>
	<div class="ai__rule" aria-hidden="true"></div>
	<div class="wrap">
		<div class="ai__top">
			<div class="ai__copy" data-reveal>
				<?php if ( $halden_a['badge'] ) : ?>
					<p><span class="ai-badge"><span class="ai-dot" aria-hidden="true"></span><?php echo esc_html( $halden_a['badge'] ); ?></span></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
				<?php if ( $halden_a['text'] ) : ?>
					<p><?php echo halden_inline( $halden_a['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
				<?php if ( $halden_a['primaryLabel'] || $halden_a['secondaryLabel'] ) : ?>
					<div class="ai__cta">
						<?php if ( $halden_a['primaryLabel'] ) : ?>
							<a class="btn btn--red" href="<?php echo esc_url( halden_url( $halden_a['primaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['primaryLabel'] ); ?> <?php echo $halden_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
						<?php endif; ?>
						<?php if ( $halden_a['secondaryLabel'] ) : ?>
							<a class="btn btn--ai" href="<?php echo esc_url( halden_url( $halden_a['secondaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['secondaryLabel'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $halden_a['image'] ) : ?>
				<figure class="ai__visual" data-reveal style="--d:1">
					<div class="frame">
						<picture><img src="<?php echo esc_url( halden_asset( 'images/' . $halden_a['image'] ) ); ?>" width="1800" height="1195" loading="lazy" alt="<?php echo esc_attr( $halden_a['imageAlt'] ); ?>"></picture>
						<div class="glow" aria-hidden="true"></div>
						<?php if ( $halden_a['chipValue'] ) : ?>
							<p class="chip chip--b"><b><?php echo esc_html( $halden_a['chipValue'] ); ?></b><span><?php echo esc_html( $halden_a['chipLabel'] ); ?></span></p>
						<?php endif; ?>
					</div>
				</figure>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $halden_a['specs'] ) ) : ?>
			<ol class="specs2">
				<?php foreach ( (array) $halden_a['specs'] as $halden_s ) : ?>
					<?php
					if ( ! is_array( $halden_s ) ) {
						continue;
					}
					?>
					<li class="srow" data-reveal>
						<p class="srow__n"><?php echo esc_html( $halden_s['num'] ?? '' ); ?><?php if ( ! empty( $halden_s['unit'] ) ) : ?><small><?php echo esc_html( $halden_s['unit'] ); ?></small><?php endif; ?></p>
						<div class="srow__t"><h3><?php echo esc_html( $halden_s['title'] ?? '' ); ?></h3><?php if ( ! empty( $halden_s['text'] ) ) : ?><p><?php echo halden_inline( $halden_s['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?></div>
						<?php
						$halden_lines = array(
							'power' => 'l-power',
							'net'   => 'l-net',
							'waves' => 'waves',
							'audit' => 'l-audit2',
						);
						$halden_line  = $halden_s['line'] ?? '';
						if ( isset( $halden_lines[ $halden_line ] ) ) {
							echo '<canvas class="' . esc_attr( $halden_lines[ $halden_line ] ) . '" aria-hidden="true"></canvas>';
						}
						?>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
		<?php if ( $halden_a['endText'] || $halden_a['endLabel'] ) : ?>
			<div class="specs2__end" data-reveal>
				<p><?php echo halden_inline( $halden_a['endText'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php if ( $halden_a['endLabel'] ) : ?>
					<a class="btn btn--red" href="<?php echo esc_url( halden_url( $halden_a['endUrl'] ) ); ?>"><?php echo esc_html( $halden_a['endLabel'] ); ?> <?php echo $halden_arrow; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
