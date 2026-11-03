<?php
/**
 * Scroll-scrubbed video hero (sample: section.aw). view.js extracts frames from the
 * video and paints them to the canvas as the visitor scrolls through three "beats".
 * Beat 1's headline is the page h1; beats 2–3 are paragraphs styled the same.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a = $attributes;

// A media field takes a full URL or a file name in assets/images/ (videos: assets/video/).
$halden_media = function ( $value ) {
	$value = (string) $value;
	if ( '' === $value || preg_match( '#^(https?:)?//#', $value ) ) {
		return $value;
	}
	$folder = preg_match( '/\.(mp4|webm)$/i', $value ) ? 'video/' : 'images/';
	return halden_asset( $folder . ltrim( $value, '/' ) );
};

$halden_hid   = wp_unique_id( 'aw-h-' );
$halden_beats = array(
	array( $halden_a['eyebrow1'], $halden_a['title1'], true ),
	array( $halden_a['eyebrow2'], $halden_a['title2'], false ),
	array( $halden_a['eyebrow3'], $halden_a['title3'], true ),
);

$halden_cta = function () use ( $halden_a ) {
	if ( ! $halden_a['primaryLabel'] && ! $halden_a['secondaryLabel'] ) {
		return;
	}
	echo '<div class="aw__cta">';
	if ( $halden_a['primaryLabel'] ) {
		echo '<a class="btn btn--red" href="' . esc_url( halden_url( $halden_a['primaryUrl'] ) ) . '">' . esc_html( $halden_a['primaryLabel'] ) . ' ' . halden_icon( 'arrow' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
	}
	if ( $halden_a['secondaryLabel'] ) {
		echo '<a class="btn btn--ghost" href="' . esc_url( halden_url( $halden_a['secondaryUrl'] ) ) . '">' . esc_html( $halden_a['secondaryLabel'] ) . '</a>';
	}
	echo '</div>';
};

$halden_poster_d = $halden_media( $halden_a['posterDesktop'] );
$halden_poster_m = $halden_media( $halden_a['posterMobile'] );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'aw', 'aria-labelledby' => $halden_hid ) ); ?>>
<div class="aw__st">
<picture><?php if ( $halden_poster_d ) : ?><source media="(min-aspect-ratio: 6/5)" srcset="<?php echo esc_url( $halden_poster_d ); ?>" width="1564" height="880"><?php endif; ?><img class="aw__poster" src="<?php echo esc_url( $halden_poster_m ? $halden_poster_m : $halden_poster_d ); ?>" width="1924" height="1076" fetchpriority="high" alt="<?php echo esc_attr( $halden_a['posterAlt'] ); ?>"></picture>
<video class="aw__v" muted playsinline preload="auto" crossorigin="anonymous" data-d="<?php echo esc_url( $halden_media( $halden_a['videoDesktop'] ) ); ?>" data-m="<?php echo esc_url( $halden_media( $halden_a['videoMobile'] ? $halden_a['videoMobile'] : $halden_a['videoDesktop'] ) ); ?>" aria-hidden="true" tabindex="-1"></video>
<canvas class="aw__cv" aria-hidden="true"></canvas>
<div class="aw__vig" aria-hidden="true"></div>
<div class="wrap aw__body">
<div class="aw__copy">
<?php foreach ( $halden_beats as $halden_i => $halden_beat ) : ?>
<div class="aw__beat<?php echo 0 === $halden_i ? ' is-on' : ''; ?>">
<?php if ( $halden_beat[0] ) : ?><p class="aw__k"><?php echo esc_html( $halden_beat[0] ); ?></p><?php endif; ?>
<?php if ( 0 === $halden_i ) : ?>
<h1 id="<?php echo esc_attr( $halden_hid ); ?>" class="aw__h"><?php echo halden_inline( $halden_beat[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h1>
<?php else : ?>
<p class="aw__h"><?php echo halden_inline( $halden_beat[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></p>
<?php endif; ?>
<?php
if ( $halden_beat[2] ) {
	$halden_cta();
}
?>
</div>
<?php endforeach; ?>
</div>
</div>
<?php if ( $halden_a['cue'] ) : ?><div class="aw__cue" aria-hidden="true"><?php echo esc_html( $halden_a['cue'] ); ?></div><?php endif; ?>
<?php if ( $halden_a['note'] ) : ?><p class="aw__note" aria-hidden="true"><?php echo esc_html( $halden_a['note'] ); ?></p><?php endif; ?>
</div>
</section>
