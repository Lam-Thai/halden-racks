<?php
/**
 * Story: sticky media (desktop) beside scrolling steps, then the carriers band.
 * Markup is the sample's ("What your rack runs on."); view.js switches the active step.
 *
 * steps[]: { key, num, unit, title, text, chips[], image, alt, cap, line: power|waves|audit|net|conv|"" }
 * carriers[]: { name, kind }   carriersMore: { value, label }   Empty carriers hides the band.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_steps = array_values( array_filter( (array) $halden_a['steps'], 'is_array' ) );
$halden_h_id  = wp_unique_id( 'runs-h-' );
$halden_first = $halden_steps ? (string) ( $halden_steps[0]['cap'] ?? '' ) : '';
$halden_more  = (array) $halden_a['carriersMore'];

/** Image key used by the sample's data-k (file name without extension). */
$halden_key = function ( $image ) {
	return sanitize_html_class( pathinfo( (string) $image, PATHINFO_FILENAME ) );
};
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'sec', 'aria-labelledby' => $halden_h_id ) ); ?>>
	<div class="wrap">
		<div class="head head--split" data-reveal>
			<div style="display:grid;gap:20px">
				<h2 id="<?php echo esc_attr( $halden_h_id ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
			</div>
			<?php if ( $halden_a['intro'] ) : ?>
				<p><?php echo halden_inline( $halden_a['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $halden_steps ) : ?>
			<div class="story">
				<div class="story__media">
					<?php foreach ( $halden_steps as $halden_i => $halden_s ) : ?>
						<?php if ( ! empty( $halden_s['image'] ) ) : ?>
							<picture><img<?php echo 0 === $halden_i ? ' class="is-on"' : ''; ?> data-i="<?php echo (int) $halden_i; ?>" data-k="<?php echo esc_attr( $halden_key( $halden_s['image'] ) ); ?>" src="<?php echo esc_url( halden_asset( 'images/' . $halden_s['image'] ) ); ?>" width="1568" height="1041" loading="lazy" alt="<?php echo esc_attr( $halden_s['alt'] ?? '' ); ?>"></picture>
						<?php endif; ?>
					<?php endforeach; ?>
					<div class="cap" aria-hidden="true"><span class="story__cap"><?php echo esc_html( $halden_first ); ?></span><div class="bars"><?php foreach ( $halden_steps as $halden_i => $halden_s ) : ?><i<?php echo 0 === $halden_i ? ' class="is-on"' : ''; ?>></i><?php endforeach; ?></div></div>
				</div>
				<div class="story__steps">
					<?php foreach ( $halden_steps as $halden_i => $halden_s ) : ?>
						<article class="step<?php echo 0 === $halden_i ? ' is-on' : ''; ?>" data-i="<?php echo (int) $halden_i; ?>" data-cap="<?php echo esc_attr( $halden_s['cap'] ?? '' ); ?>">
							<?php if ( ! empty( $halden_s['key'] ) ) : ?>
								<p class="k"><?php echo esc_html( $halden_s['key'] ); ?></p>
							<?php endif; ?>
							<?php if ( isset( $halden_s['num'] ) && '' !== (string) $halden_s['num'] ) : ?>
								<span class="num"><?php echo esc_html( $halden_s['num'] ); ?><?php if ( ! empty( $halden_s['unit'] ) ) : ?><small><?php echo esc_html( $halden_s['unit'] ); ?></small><?php endif; ?></span>
							<?php endif; ?>
							<h3><?php echo halden_inline( $halden_s['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
							<?php if ( ! empty( $halden_s['text'] ) ) : ?>
								<p><?php echo halden_inline( $halden_s['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $halden_s['chips'] ) ) : ?>
								<ul class="chips"><?php foreach ( (array) $halden_s['chips'] as $halden_chip ) : ?><li><?php echo esc_html( $halden_chip ); ?></li><?php endforeach; ?></ul>
							<?php endif; ?>
							<?php
							switch ( $halden_s['line'] ?? '' ) {
								case 'power':
									echo '<canvas class="l-power" aria-hidden="true"></canvas>';
									break;
								case 'waves':
									echo '<canvas class="waves" aria-hidden="true"></canvas>';
									break;
								case 'audit':
									echo '<canvas class="sline l-audit2" aria-hidden="true"></canvas>';
									break;
								case 'net':
									echo '<canvas class="l-net" aria-hidden="true"></canvas>';
									break;
								case 'conv':
									echo '<div class="conv"><svg class="sline l-conv" data-line="conv" aria-hidden="true"></svg><span class="conv__pt"><i></i><b class="conv__lbl">' . esc_html( $halden_a['convLabel'] ) . '</b></span></div>';
									break;
							}
							?>
							<?php if ( ! empty( $halden_s['image'] ) ) : ?>
								<div class="mimg"><picture><img src="<?php echo esc_url( halden_asset( 'images/' . $halden_s['image'] ) ); ?>" width="1568" height="1041" loading="lazy" alt=""></picture></div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $halden_a['carriers'] ) ) : ?>
			<div class="netband" data-reveal>
				<div>
					<h3><?php echo halden_inline( $halden_a['carriersTitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
					<?php if ( $halden_a['carriersText'] ) : ?>
						<p><?php echo halden_inline( $halden_a['carriersText'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
				</div>
				<ul class="carriers">
					<?php foreach ( (array) $halden_a['carriers'] as $halden_c ) : ?>
						<li><b><?php echo esc_html( $halden_c['name'] ?? '' ); ?></b><span><?php echo esc_html( $halden_c['kind'] ?? '' ); ?></span></li>
					<?php endforeach; ?>
					<?php if ( ! empty( $halden_more['value'] ) ) : ?>
						<li class="more"><b><?php echo esc_html( $halden_more['value'] ); ?></b><span><?php echo esc_html( $halden_more['label'] ?? '' ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
