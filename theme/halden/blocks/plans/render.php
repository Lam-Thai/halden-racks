<?php
/**
 * Plan picker (sample: section#plans). The first plan is rendered here so the section reads
 * without JavaScript and in the editor preview; view.js takes over from the data-plans JSON
 * (tabs, stepper, 3D rack via window.Rack3D with the 2D rack as fallback, quote link).
 *
 * Plan data: id, tab, from, name, badge, who, list[], pricing {mode: perUnit|fixed|quoted,
 * amount, unit}, optional step {min, max, start, label, unitWord, one}, and for the
 * rack diagram either comps/open/word/size (sections of one cabinet) or suite:true.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a     = $attributes;
$halden_plans = array_values( array_filter( (array) $halden_a['plans'], 'is_array' ) );
if ( ! $halden_plans ) {
	return;
}

$halden_uid   = wp_unique_id( 'plans-' );
$halden_first = $halden_plans[0];
$halden_step  = ! empty( $halden_first['step'] ) ? (array) $halden_first['step'] : null;
$halden_qty   = $halden_step ? (int) ( $halden_step['start'] ?? $halden_step['min'] ?? 1 ) : 0;

// Same wording as view.js price()/unit().
$halden_price = function ( $p, $n ) {
	$pr     = isset( $p['pricing'] ) ? (array) $p['pricing'] : array();
	$mode   = $pr['mode'] ?? 'quoted';
	$amount = (float) ( $pr['amount'] ?? 0 );
	if ( 'perUnit' === $mode ) {
		$word = $p['step']['unitWord'] ?? 'U';
		return array(
			'$' . number_format( $amount * $n ),
			$n > 1 ? sprintf( 'CAD / month, %d %s at $%s each', $n, $word, number_format( $amount ) ) : 'CAD / month',
		);
	}
	if ( 'fixed' === $mode ) {
		return array( '$' . number_format( $amount ), $pr['unit'] ?? 'CAD / month' );
	}
	return array( $pr['label'] ?? 'Quoted', $pr['unit'] ?? '' );
};
list( $halden_p0, $halden_u0 ) = $halden_price( $halden_first, $halden_qty );

$halden_wrapper = array(
	'class'           => 'sec paper',
	'aria-labelledby' => $halden_uid . '-h',
);
if ( empty( $halden_a['anchor'] ) ) {
	$halden_wrapper['id'] = 'plans';
}

$halden_contact = halden_url( $halden_a['contactUrl'] );
$halden_query   = array( 'plan' => rawurlencode( (string) ( $halden_first['id'] ?? '' ) ) );
if ( $halden_step ) {
	$halden_query['qty'] = $halden_qty;
}
$halden_cta = add_query_arg( $halden_query, $halden_contact );
?>
<section <?php echo get_block_wrapper_attributes( $halden_wrapper ); ?>>
<div class="wrap">
<div class="head head--split" data-reveal>
<div style="display:grid;gap:20px">
<h2 id="<?php echo esc_attr( $halden_uid ); ?>-h"><?php echo halden_inline( $halden_a['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h2>
</div>
<?php if ( $halden_a['intro'] ) : ?><p><?php echo halden_inline( $halden_a['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
<div class="plans" data-plans="<?php echo esc_attr( wp_json_encode( $halden_plans ) ); ?>" data-contact-url="<?php echo esc_url( $halden_contact ); ?>">
<div class="plans__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Colocation plans', 'halden' ); ?>" data-reveal>
<?php foreach ( $halden_plans as $halden_i => $halden_p ) : ?>
<button type="button" role="tab" id="<?php echo esc_attr( $halden_uid . '-tab-' . ( $halden_p['id'] ?? $halden_i ) ); ?>" aria-controls="<?php echo esc_attr( $halden_uid ); ?>-panel" aria-selected="<?php echo 0 === $halden_i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $halden_i ? '0' : '-1'; ?>"><b><?php echo esc_html( $halden_p['tab'] ?? '' ); ?></b><span><?php echo esc_html( $halden_p['from'] ?? '' ); ?></span></button>
<?php endforeach; ?>
</div>
<div class="plan" id="<?php echo esc_attr( $halden_uid ); ?>-panel" role="tabpanel" aria-labelledby="<?php echo esc_attr( $halden_uid . '-tab-' . ( $halden_first['id'] ?? 0 ) ); ?>" data-reveal style="--d:1">
<div class="plan__media rkm">
<div class="r3" role="img" aria-label="<?php esc_attr_e( 'Rack diagram', 'halden' ); ?>"><div class="r3lab" aria-hidden="true"></div></div>
<div class="rkstage" role="img" aria-label="<?php esc_attr_e( 'Rack diagram', 'halden' ); ?>"></div>
<p class="rkcap" aria-live="polite"></p>
<span class="badge"><?php echo esc_html( $halden_first['badge'] ?? '' ); ?></span>
</div>
<div class="plan__body">
<div style="display:grid;gap:12px">
<h3 class="plan__name"><?php echo esc_html( $halden_first['name'] ?? '' ); ?></h3>
<p class="plan__who"><?php echo esc_html( $halden_first['who'] ?? '' ); ?></p>
</div>
<div class="plan__price" aria-live="polite"><b><?php echo esc_html( $halden_p0 ); ?></b><span><?php echo esc_html( $halden_u0 ); ?></span></div>
<div class="stepper"<?php echo $halden_step ? '' : ' hidden'; ?>>
<p><?php echo esc_html( $halden_step['label'] ?? '' ); ?></p>
<button type="button" data-step="-1" aria-label="<?php echo esc_attr( 'Remove one ' . ( $halden_step['one'] ?? '' ) ); ?>"><?php echo halden_icon( 'minus' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
<output aria-live="polite"><?php echo esc_html( $halden_step ? $halden_qty . ' ' . ( $halden_step['unitWord'] ?? '' ) : '' ); ?></output>
<button type="button" data-step="1" aria-label="<?php echo esc_attr( 'Add one ' . ( $halden_step['one'] ?? '' ) ); ?>"><?php echo halden_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
</div>
<ul class="plan__list">
<?php foreach ( (array) ( $halden_first['list'] ?? array() ) as $halden_li ) : ?>
<li><?php echo halden_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $halden_li ); ?></li>
<?php endforeach; ?>
</ul>
<?php if ( $halden_a['priceMatch'] ) : ?>
<div class="pm"><?php echo halden_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><p><?php echo halden_inline( $halden_a['priceMatch'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
<?php endif; ?>
<div class="plan__cta">
<a class="btn btn--ink plan__quote" href="<?php echo esc_url( $halden_cta . '#quote' ); ?>"><?php echo esc_html( $halden_a['ctaLabel'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
<?php if ( $halden_a['compareLabel'] ) : ?>
<a class="btn btn--line" href="<?php echo esc_url( halden_url( $halden_a['compareUrl'] ) ); ?>"><?php echo esc_html( $halden_a['compareLabel'] ); ?></a>
<?php endif; ?>
</div>
<?php if ( $halden_a['note'] ) : ?><p class="plan__note"><?php echo halden_inline( $halden_a['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
</div>
</div>
</div>
</section>
