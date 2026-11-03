<?php
/**
 * Service feature: one service as a full section, copy beside a visual.
 *
 * Visual: a photo (image + alt, optional chip) or, with no image, a stack of rows adding up to
 * a total (stack[]: { k, v }, stackTotal: { k, v }). Either can carry a network part below it:
 * a line graphic from assets/js/lines.js (line: net | conv | "") and a grid of networks
 * (nodes[]: { name, kind }, more: { value, label }).
 * bullets[] are strings (inline <em>/<mark class="verify"> allowed).
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a       = $attributes;
$halden_h_id    = wp_unique_id( 'sf-h-' );
$halden_paper   = ! empty( $halden_a['paper'] );
$halden_image   = (string) $halden_a['image'];
$halden_stack   = array_values( array_filter( (array) $halden_a['stack'], 'is_array' ) );
$halden_total   = (array) $halden_a['stackTotal'];
$halden_nodes   = array_values( array_filter( (array) $halden_a['nodes'], 'is_array' ) );
$halden_more    = (array) $halden_a['more'];
$halden_line    = in_array( $halden_a['line'], array( 'net', 'conv' ), true ) ? $halden_a['line'] : '';
$halden_bullets = array_values( array_filter( (array) $halden_a['bullets'], 'is_string' ) );
$halden_has_net = $halden_line || $halden_nodes;
$halden_arrow   = halden_icon( 'arrow' );

$halden_class = 'sec sf' . ( $halden_paper ? ' paper' : '' ) . ( ! empty( $halden_a['flip'] ) ? ' sf--flip' : '' );
$halden_media = 'sf__media' . ( $halden_image ? '' : ' sf__media--panel' ) . ( $halden_has_net ? ' sf__media--net' : '' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => $halden_class, 'aria-labelledby' => $halden_h_id ) ); ?>>
	<div class="wrap">
		<div class="sf__grid">
			<div class="sf__copy" data-reveal>
				<?php if ( $halden_a['eyebrow'] ) : ?>
					<p class="tag"><?php echo esc_html( $halden_a['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
				<?php if ( $halden_a['text'] ) : ?>
					<p class="sf__text"><?php echo halden_inline( $halden_a['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
				<?php if ( '' !== (string) $halden_a['price'] ) : ?>
					<p class="sf__price">
						<?php if ( $halden_a['priceLead'] ) : ?><span class="sf__lead"><?php echo halden_inline( $halden_a['priceLead'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php endif; ?>
						<b><?php echo halden_inline( $halden_a['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></b>
						<?php if ( $halden_a['priceUnit'] ) : ?><span><?php echo halden_inline( $halden_a['priceUnit'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php endif; ?>
					</p>
				<?php endif; ?>
				<?php if ( $halden_bullets ) : ?>
					<ul class="sf__list">
						<?php foreach ( $halden_bullets as $halden_b ) : ?>
							<li><?php echo halden_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><span><?php echo halden_inline( $halden_b ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $halden_a['ctaLabel'] || $halden_a['secondaryLabel'] ) : ?>
					<div class="sf__cta">
						<?php if ( $halden_a['ctaLabel'] ) : ?>
							<a class="btn <?php echo $halden_paper ? 'btn--ink' : 'btn--red'; ?>" href="<?php echo esc_url( halden_url( $halden_a['ctaUrl'] ) ); ?>"><?php echo esc_html( $halden_a['ctaLabel'] ); ?> <?php echo $halden_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
						<?php endif; ?>
						<?php if ( $halden_a['secondaryLabel'] ) : ?>
							<a class="btn <?php echo $halden_paper ? 'btn--line' : 'btn--ghost'; ?>" href="<?php echo esc_url( halden_url( $halden_a['secondaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['secondaryLabel'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="<?php echo esc_attr( $halden_media ); ?>" data-reveal style="--d:1">
				<?php if ( $halden_image ) : ?>
					<figure class="sf__frame">
						<picture><img src="<?php echo esc_url( halden_asset( 'images/' . ltrim( $halden_image, '/' ) ) ); ?>" width="1568" height="1041" loading="lazy" decoding="async" alt="<?php echo esc_attr( $halden_a['alt'] ); ?>"></picture>
						<?php if ( $halden_a['chipValue'] ) : ?>
							<figcaption class="sf__chip"><b><?php echo esc_html( $halden_a['chipValue'] ); ?></b><?php if ( $halden_a['chipLabel'] ) : ?><span><?php echo esc_html( $halden_a['chipLabel'] ); ?></span><?php endif; ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php elseif ( $halden_stack ) : ?>
					<div class="sf__stack" aria-hidden="true">
						<?php
						$halden_d = 0;
						foreach ( $halden_stack as $halden_i => $halden_row ) :
							if ( $halden_i ) :
								?>
								<i class="sf__op" data-reveal style="--d:<?php echo (int) ++$halden_d; ?>">+</i>
							<?php endif; ?>
							<span class="sf__row" data-reveal style="--d:<?php echo (int) ( $halden_i ? ++$halden_d : $halden_d ); ?>"><?php echo esc_html( $halden_row['k'] ?? '' ); ?> <b><?php echo esc_html( $halden_row['v'] ?? '' ); ?></b></span>
						<?php endforeach; ?>
						<?php if ( ! empty( $halden_total['v'] ) ) : ?>
							<i class="sf__op" data-reveal style="--d:<?php echo (int) ++$halden_d; ?>">=</i>
							<span class="sf__row sf__row--total" data-reveal style="--d:<?php echo (int) ++$halden_d; ?>"><?php echo esc_html( $halden_total['k'] ?? '' ); ?> <b><?php echo esc_html( $halden_total['v'] ); ?></b></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $halden_has_net ) : ?>
					<div class="sf__net">
						<?php if ( $halden_a['nodesTitle'] ) : ?>
							<p class="sf__k"><?php echo esc_html( $halden_a['nodesTitle'] ); ?></p>
						<?php endif; ?>
						<?php if ( 'net' === $halden_line ) : ?>
							<canvas class="l-net" aria-hidden="true"></canvas>
						<?php elseif ( 'conv' === $halden_line ) : ?>
							<div class="conv"><svg class="sline l-conv" data-line="conv" aria-hidden="true"></svg><span class="conv__pt" aria-hidden="true"><i></i><b class="conv__lbl"><?php echo esc_html( $halden_a['lineLabel'] ); ?></b></span></div>
						<?php endif; ?>
						<?php if ( $halden_nodes ) : ?>
							<ul class="carriers">
								<?php foreach ( $halden_nodes as $halden_n ) : ?>
									<li><b><?php echo esc_html( $halden_n['name'] ?? '' ); ?></b><span><?php echo esc_html( $halden_n['kind'] ?? '' ); ?></span></li>
								<?php endforeach; ?>
								<?php if ( ! empty( $halden_more['value'] ) ) : ?>
									<li class="more"><b><?php echo esc_html( $halden_more['value'] ); ?></b><span><?php echo esc_html( $halden_more['label'] ?? '' ); ?></span></li>
								<?php endif; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
