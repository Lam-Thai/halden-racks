<?php
/**
 * Plan comparison: the colocation plans side by side. A real <table> (one row per plan) that
 * style.css restacks into cards below 1000px using each cell's data-label. Each row's link
 * opens the plan picker on this page with that plan selected (view.js), or reloads with
 * ?plan=<id>#plans when there is no picker.
 *
 * rows[]: { id, name, footprint, access, power, price, best, fill (0..1, share of one cabinet), racks (cabinets drawn, default 1) }
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a    = $attributes;
$halden_rows = array_values( array_filter( (array) $halden_a['rows'], 'is_array' ) );
if ( ! $halden_rows ) {
	return;
}

$halden_col  = wp_parse_args(
	(array) $halden_a['columns'],
	array(
		'plan'      => 'Plan',
		'footprint' => 'Footprint',
		'access'    => 'Access',
		'power'     => 'Power',
		'price'     => 'From',
		'best'      => 'Best for',
	)
);
$halden_keys = array( 'footprint', 'access', 'power', 'price', 'best' );
$halden_hid  = wp_unique_id( 'cmp-h-' );
$halden_base = halden_url( $halden_a['planUrl'] );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'sec cmp-sec', 'aria-labelledby' => $halden_hid ) ); ?>>
<div class="wrap">
<div class="head head--split" data-reveal>
<div style="display:grid;gap:20px">
<?php if ( $halden_a['eyebrow'] ) : ?><p class="tag"><?php echo esc_html( $halden_a['eyebrow'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $halden_hid ); ?>"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
</div>
<?php if ( $halden_a['intro'] ) : ?><p><?php echo halden_inline( $halden_a['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
<div class="cmp" data-reveal style="--d:1">
<table class="cmp__t" aria-labelledby="<?php echo esc_attr( $halden_hid ); ?>">
<thead>
<tr>
<th scope="col"><?php echo esc_html( $halden_col['plan'] ); ?></th>
<?php foreach ( $halden_keys as $halden_k ) : ?>
<th scope="col" class="cmp__<?php echo esc_attr( $halden_k ); ?>"><?php echo esc_html( $halden_col[ $halden_k ] ); ?></th>
<?php endforeach; ?>
<th scope="col" class="cmp__go"><span class="cmp__sr"><?php echo esc_html( $halden_a['chooseLabel'] ); ?></span></th>
</tr>
</thead>
<tbody>
<?php
foreach ( $halden_rows as $halden_r ) :
	$halden_id    = sanitize_key( $halden_r['id'] ?? '' );
	$halden_fill  = isset( $halden_r['fill'] ) ? max( 0, min( 1, (float) $halden_r['fill'] ) ) : 0;
	$halden_racks = max( 1, min( 4, (int) ( $halden_r['racks'] ?? 1 ) ) );
	$halden_href  = $halden_id ? add_query_arg( 'plan', $halden_id, $halden_base ) . '#plans' : $halden_base;
	?>
<tr data-plan="<?php echo esc_attr( $halden_id ); ?>">
<th scope="row">
<span class="cmp__name"><?php echo esc_html( $halden_r['name'] ?? '' ); ?></span>
<?php if ( $halden_fill ) : ?>
<span class="cmp__rk" aria-hidden="true"><?php for ( $halden_i = 0; $halden_i < $halden_racks; $halden_i++ ) : ?><i style="--f:<?php echo esc_attr( (string) $halden_fill ); ?>;--k:<?php echo (int) $halden_i; ?>"></i><?php endfor; ?></span>
<?php endif; ?>
</th>
<?php foreach ( $halden_keys as $halden_k ) : ?>
<td class="cmp__<?php echo esc_attr( $halden_k ); ?>" data-label="<?php echo esc_attr( $halden_col[ $halden_k ] ); ?>"><?php echo halden_inline( $halden_r[ $halden_k ] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></td>
<?php endforeach; ?>
<td class="cmp__go"><a href="<?php echo esc_url( $halden_href ); ?>" data-plan="<?php echo esc_attr( $halden_id ); ?>"><span><?php echo esc_html( $halden_a['chooseLabel'] ); ?><span class="cmp__sr"> <?php echo esc_html( $halden_r['name'] ?? '' ); ?></span></span><i><?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></i></a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php if ( $halden_a['note'] ) : ?><p class="cmp__note"><?php echo halden_inline( $halden_a['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
</div>
</section>
