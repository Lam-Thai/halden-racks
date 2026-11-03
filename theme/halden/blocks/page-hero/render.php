<?php
/**
 * Page hero for inner pages. "facts" is a list of { "value": "25 kW", "label": "per rack" }.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a = $attributes;
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'phero' ) ); ?>>
	<?php if ( $halden_a['image'] ) : ?>
		<div class="phero__img" aria-hidden="true"><img src="<?php echo esc_url( halden_asset( 'images/' . $halden_a['image'] ) ); ?>" alt="" fetchpriority="high" decoding="async"></div>
	<?php endif; ?>
	<div class="wrap">
		<div class="phero__in">
			<?php if ( $halden_a['eyebrow'] ) : ?>
				<p class="phero__k"><?php echo esc_html( $halden_a['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1><?php echo halden_inline( $halden_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h1>
			<?php if ( $halden_a['lede'] ) : ?>
				<p class="phero__lede"><?php echo halden_inline( $halden_a['lede'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<?php if ( $halden_a['primaryLabel'] || $halden_a['secondaryLabel'] ) : ?>
				<div class="phero__cta">
					<?php if ( $halden_a['primaryLabel'] ) : ?>
						<a class="btn btn--red" href="<?php echo esc_url( halden_url( $halden_a['primaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['primaryLabel'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endif; ?>
					<?php if ( $halden_a['secondaryLabel'] ) : ?>
						<a class="btn btn--ghost" href="<?php echo esc_url( halden_url( $halden_a['secondaryUrl'] ) ); ?>"><?php echo esc_html( $halden_a['secondaryLabel'] ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $halden_a['facts'] ) ) : ?>
				<ul class="phero__facts">
					<?php foreach ( (array) $halden_a['facts'] as $halden_fact ) : ?>
						<li><b><?php echo halden_inline( $halden_fact['value'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></b><span><?php echo esc_html( $halden_fact['label'] ?? '' ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
