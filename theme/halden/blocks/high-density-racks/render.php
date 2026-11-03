<?php
/**
 * Rack density comparison: the same cluster ("servers" × "serverKw") placed in racks with
 * different power budgets. Each row draws the cabinets it needs; filled slots light up
 * bottom-up when the row scrolls into view (CSS only, keyed on site.js's .is-in).
 *
 * rows[]: { label, kw, note, tone: red|muted }
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a       = $attributes;
$halden_kw_srv  = max( 0.1, (float) $halden_a['serverKw'] );
$halden_servers = max( 1, (int) $halden_a['servers'] );
$halden_slots   = max( 1, min( 24, (int) $halden_a['slots'] ) );
$halden_max_cab = 12;
$halden_h_id    = wp_unique_id( 'dens-h-' );

// Work out each row: servers per rack (power-bound, then space-bound) and racks needed.
$halden_rows = array();
$halden_top  = 0;
foreach ( (array) $halden_a['rows'] as $halden_r ) {
	if ( ! is_array( $halden_r ) ) {
		continue;
	}
	$halden_kw  = max( 0, (float) ( $halden_r['kw'] ?? 0 ) );
	$halden_per = max( 1, min( $halden_slots, (int) floor( $halden_kw / $halden_kw_srv ) ) );
	$halden_rows[] = array(
		'label' => (string) ( $halden_r['label'] ?? '' ),
		'note'  => (string) ( $halden_r['note'] ?? '' ),
		'tone'  => 'red' === ( $halden_r['tone'] ?? '' ) ? 'red' : 'muted',
		'kw'    => $halden_kw,
		'per'   => $halden_per,
		'racks' => (int) ceil( $halden_servers / $halden_per ),
	);
	$halden_top = max( $halden_top, $halden_kw );
}

// "4×" when the last row needs clearly more racks than the first.
$halden_ratio = '';
if ( count( $halden_rows ) > 1 ) {
	$halden_x = end( $halden_rows )['racks'] / max( 1, $halden_rows[0]['racks'] );
	if ( $halden_x >= 1.5 ) {
		$halden_ratio = ( floor( $halden_x ) == $halden_x ? (string) (int) $halden_x : number_format_i18n( $halden_x, 1 ) ) . '×'; // phpcs:ignore Universal.Operators.StrictComparisons
	}
}

$halden_num = function ( $n ) {
	return floor( $n ) == $n ? (string) (int) $n : number_format_i18n( $n, 1 ); // phpcs:ignore Universal.Operators.StrictComparisons
};
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'sec paper dens', 'aria-labelledby' => $halden_h_id ) ); ?>>
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

		<ul class="dens__legend" data-reveal>
			<li><i class="dens-u on" aria-hidden="true"></i><?php echo halden_inline( $halden_a['serverLabel'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
			<?php if ( $halden_a['emptyLabel'] ) : ?>
				<li><i class="dens-u" aria-hidden="true"></i><?php echo halden_inline( $halden_a['emptyLabel'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
			<?php endif; ?>
			<li class="dens__count"><?php echo esc_html( sprintf( '%d servers × %s kW', $halden_servers, $halden_num( $halden_kw_srv ) ) ); ?></li>
		</ul>

		<div class="dens__rows">
			<?php foreach ( $halden_rows as $halden_i => $halden_r ) : ?>
				<?php
				$halden_label = sprintf(
					/* translators: 1: racks, 2: servers per rack, 3: empty slots per rack. */
					'%1$d racks with %2$d servers each, %3$d slots left empty in each',
					$halden_r['racks'],
					$halden_r['per'],
					$halden_slots - $halden_r['per']
				);
				$halden_left  = $halden_servers;
				$halden_n     = 0;
				?>
				<div class="dens-row dens-row--<?php echo esc_attr( $halden_r['tone'] ); ?>" data-reveal<?php echo $halden_i ? ' style="--d:' . (int) min( $halden_i, 3 ) . '"' : ''; ?>>
					<div class="dens-row__meta">
						<p class="dens-row__k"><?php echo esc_html( $halden_r['label'] ); ?></p>
						<p class="dens-row__kw"><?php echo esc_html( $halden_num( $halden_r['kw'] ) ); ?><small>kW per rack</small></p>
						<span class="dens-meter" aria-hidden="true"><i style="--w:<?php echo esc_attr( $halden_top ? round( $halden_r['kw'] / $halden_top, 3 ) : 0 ); ?>"></i></span>
						<?php if ( $halden_r['note'] ) : ?>
							<p class="dens-row__note"><?php echo halden_inline( $halden_r['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php endif; ?>
					</div>
					<div class="dens-row__racks" role="img" aria-label="<?php echo esc_attr( $halden_label ); ?>">
						<?php for ( $halden_c = 0; $halden_c < min( $halden_r['racks'], $halden_max_cab ); $halden_c++ ) : ?>
							<span class="dens-cab">
								<?php
								$halden_fill = min( $halden_r['per'], $halden_left );
								$halden_left = max( 0, $halden_left - $halden_fill );
								for ( $halden_s = 0; $halden_s < $halden_slots; $halden_s++ ) {
									if ( $halden_s < $halden_fill ) {
										echo '<i class="dens-u on" style="--i:' . (int) min( $halden_n++, 24 ) . '"></i>';
									} else {
										echo '<i class="dens-u"></i>';
									}
								}
								?>
							</span>
						<?php endfor; ?>
						<?php if ( $halden_r['racks'] > $halden_max_cab ) : ?>
							<span class="dens-more">+<?php echo (int) ( $halden_r['racks'] - $halden_max_cab ); ?></span>
						<?php endif; ?>
					</div>
					<p class="dens-row__total"><b><?php echo (int) $halden_r['racks']; ?></b><span><?php echo esc_html( 1 === $halden_r['racks'] ? 'rack' : 'racks' ); ?></span><span><?php echo esc_html( sprintf( '%d servers per rack', $halden_r['per'] ) ); ?></span></p>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $halden_ratio || $halden_a['footnote'] ) : ?>
			<div class="dens__sum" data-reveal>
				<?php if ( $halden_ratio ) : ?>
					<p class="dens__ratio"><b><?php echo esc_html( $halden_ratio ); ?></b> <?php echo halden_inline( $halden_a['ratioText'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
				<?php if ( $halden_a['footnote'] ) : ?>
					<p class="dens__foot"><?php echo halden_inline( $halden_a['footnote'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
