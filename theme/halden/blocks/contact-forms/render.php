<?php
/**
 * Contact forms: Quote / Tour / Support tabs. The panels carry the ids quote, tour and support,
 * so links to /contact/#tour land on the right tab (view.js selects it, on load and on hashchange).
 *
 * Without JavaScript the tabs are hidden and the three panels stack, each with its own heading.
 * The forms are a prototype: view.js validates and shows a confirmation, nothing is sent.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_a = $attributes;
$halden_u = wp_unique_id( 'cf' );

// Plans offered in the quote form. ids match the plan picker (?plan=<id>) plus gpu and unsure.
$halden_plans = array(
	array( 'id' => 'shared', 'label' => 'Shared rack', 'qty' => 'Rack units', 'unit' => 'U', 'one' => 'U', 'many' => 'U', 'min' => 1, 'max' => 8, 'tip' => 'More than 8 U? A quarter rack is the next size up.' ),
	array( 'id' => 'quarter', 'label' => 'Quarter rack', 'qty' => 'Quarter racks', 'unit' => '× 10 U', 'one' => 'quarter rack', 'many' => 'quarter racks', 'min' => 1, 'max' => 8 ),
	array( 'id' => 'half', 'label' => 'Half rack', 'qty' => 'Half racks', 'unit' => '× 21 U', 'one' => 'half rack', 'many' => 'half racks', 'min' => 1, 'max' => 8 ),
	array( 'id' => 'full', 'label' => 'Full rack', 'qty' => 'Full racks', 'unit' => '× 42 U', 'one' => 'full rack', 'many' => 'full racks', 'min' => 1, 'max' => 20 ),
	array( 'id' => 'suite', 'label' => 'Private suite', 'qty' => 'Racks in your suite', 'unit' => 'racks', 'one' => 'rack', 'many' => 'racks', 'min' => 2, 'max' => 12 ),
	array( 'id' => 'gpu', 'label' => 'AI/GPU (HAL2)', 'qty' => 'GPU racks', 'unit' => 'racks', 'one' => 'GPU rack', 'many' => 'GPU racks', 'min' => 1, 'max' => 20 ),
	array( 'id' => 'unsure', 'label' => 'Not sure yet', 'qty' => 'Racks, best guess', 'unit' => 'racks', 'one' => 'rack', 'many' => 'racks', 'min' => 1, 'max' => 20 ),
);
$halden_timelines = array( 'Within 30 days', '1 to 3 months', '3 to 6 months', '6 months or more', 'Just comparing options' );

$halden_tabs = array(
	'quote'   => array( $halden_a['quoteTab'], $halden_a['quoteTabNote'] ),
	'tour'    => array( $halden_a['tourTab'], $halden_a['tourTabNote'] ),
	'support' => array( $halden_a['supportTab'], $halden_a['supportTabNote'] ),
);

$halden_tel   = 'tel:' . preg_replace( '/[^0-9+]/', '', $halden_a['phone'] );
$halden_email = sanitize_email( $halden_a['supportEmail'] );
$halden_proto = $halden_a['protoNote'] ? '<p class="cf__proto">' . esc_html( $halden_a['protoNote'] ) . '</p>' : '';
$halden_ok    = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/></svg>';
$halden_id    = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.6-1.4 1.7-2 3-2s2.4.6 3 2M15 10h3M15 13h3"/></svg>';
$halden_mail  = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>';

/** Error line for a field; view.js fills it and links it with aria-describedby. */
$halden_err = function ( $id ) {
	return '<p class="cf__err" id="' . esc_attr( $id ) . '-err" hidden></p>';
};
/** "optional" tag after a label. */
$halden_opt = '<span class="cf__opt">' . esc_html__( 'optional', 'halden' ) . '</span>';
/** One text-like field: label, input, error line. */
$halden_text = function ( $id, $name, $label, $args = array() ) use ( $halden_err, $halden_opt ) {
	$args  = wp_parse_args(
		$args,
		array( 'type' => 'text', 'required' => true, 'auto' => '', 'missing' => '', 'bad' => '', 'short' => $label, 'extra' => '' )
	);
	$attrs = sprintf(
		'id="%1$s" name="%2$s" type="%3$s" data-short="%4$s"%5$s%6$s%7$s%8$s%9$s',
		esc_attr( $id ),
		esc_attr( $name ),
		esc_attr( $args['type'] ),
		esc_attr( $args['short'] ),
		$args['required'] ? ' required' : '',
		$args['auto'] ? ' autocomplete="' . esc_attr( $args['auto'] ) . '"' : '',
		$args['missing'] ? ' data-missing="' . esc_attr( $args['missing'] ) . '"' : '',
		$args['bad'] ? ' data-bad="' . esc_attr( $args['bad'] ) . '"' : '',
		$args['extra'] ? ' ' . $args['extra'] : ''
	);
	return '<div class="cf__f"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ( $args['required'] ? '' : $halden_opt ) . '</label><input ' . $attrs . '>' . $halden_err( $id ) . '</div>';
};
/** Number with − / + buttons and a unit after it (quantity, visitors). */
$halden_stepper = function ( $id, $name, $label, $unit, $min, $max, $value, $missing, $short ) use ( $halden_err ) {
	ob_start();
	?>
	<div class="cf__f" data-stepper>
		<label for="<?php echo esc_attr( $id ); ?>" data-qty-label><?php echo esc_html( $label ); ?></label>
		<div class="cf__step">
			<button type="button" data-step="-1" aria-controls="<?php echo esc_attr( $id ); ?>" aria-label="<?php esc_attr_e( 'One less', 'halden' ); ?>"><?php echo halden_icon( 'minus' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
			<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="number" inputmode="numeric" min="<?php echo (int) $min; ?>" max="<?php echo (int) $max; ?>" step="1" value="<?php echo (int) $value; ?>" required data-short="<?php echo esc_attr( $short ); ?>" data-missing="<?php echo esc_attr( $missing ); ?>">
			<button type="button" data-step="1" aria-controls="<?php echo esc_attr( $id ); ?>" aria-label="<?php esc_attr_e( 'One more', 'halden' ); ?>"><?php echo halden_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
			<span class="cf__unit" data-qty-unit aria-hidden="true"><?php echo esc_html( $unit ); ?></span>
		</div>
		<?php echo $halden_err( $id ); // phpcs:ignore WordPress.Security.EscapeOutput -- built from esc_attr. ?>
	</div>
	<?php
	return ob_get_clean();
};

$halden_wrapper = array(
	'class'               => 'cf',
	'data-plans'          => wp_json_encode( $halden_plans ),
	'data-colocation-url' => halden_url( $halden_a['colocationUrl'] ),
	'data-gpu-url'        => halden_url( $halden_a['gpuUrl'] ),
);
?>
<div <?php echo get_block_wrapper_attributes( $halden_wrapper ); ?>>
<noscript><style>.cf .cf__tabs{display:none}.cf .cf__panel[hidden]{display:block}.cf .cf__panel+.cf__panel{margin-top:24px}</style></noscript>

<div class="cf__tabs" role="tablist" aria-label="<?php echo esc_attr( $halden_a['tabsLabel'] ); ?>" data-reveal>
<?php foreach ( array_keys( $halden_tabs ) as $halden_i => $halden_key ) : ?>
<button type="button" role="tab" id="<?php echo esc_attr( $halden_key ); ?>-tab" aria-controls="<?php echo esc_attr( $halden_key ); ?>" aria-selected="<?php echo 0 === $halden_i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $halden_i ? '0' : '-1'; ?>"><b><?php echo esc_html( $halden_tabs[ $halden_key ][0] ); ?></b><?php if ( $halden_tabs[ $halden_key ][1] ) : ?><span><?php echo esc_html( $halden_tabs[ $halden_key ][1] ); ?></span><?php endif; ?></button>
<?php endforeach; ?>
</div>

<?php /* ---------------- Quote ---------------- */ ?>
<div class="cf__panel" id="quote" role="tabpanel" aria-labelledby="quote-tab" data-reveal style="--d:1">
<div class="cf__body">
<div class="cf__intro">
<h3 id="<?php echo esc_attr( $halden_u ); ?>-quote-h"><?php echo halden_inline( $halden_a['quoteTitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- kses-filtered. ?></h3>
<?php if ( $halden_a['quoteIntro'] ) : ?><p><?php echo halden_inline( $halden_a['quoteIntro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
<p class="cf__picked" hidden><?php echo $halden_ok; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><span class="cf__picked-k"><?php esc_html_e( 'You picked', 'halden' ); ?></span><b data-picked></b><span aria-hidden="true">·</span><a href="<?php echo esc_url( halden_url( $halden_a['colocationUrl'] ) ); ?>" data-picked-link><?php esc_html_e( 'Change', 'halden' ); ?></a></p>
<form class="cf__form" data-kind="quote" method="post" novalidate aria-labelledby="<?php echo esc_attr( $halden_u ); ?>-quote-h">
<div class="cf__errors" role="alert"></div>
<div class="cf__grid">
<?php
// phpcs:disable WordPress.Security.EscapeOutput -- helpers escape every value.
echo $halden_text( $halden_u . '-q-name', 'name', __( 'Your name', 'halden' ), array( 'auto' => 'name', 'missing' => __( 'Enter your name', 'halden' ), 'short' => __( 'Name', 'halden' ) ) );
echo $halden_text( $halden_u . '-q-company', 'company', __( 'Company', 'halden' ), array( 'auto' => 'organization', 'missing' => __( 'Enter your company name', 'halden' ) ) );
echo $halden_text( $halden_u . '-q-email', 'email', __( 'Work email', 'halden' ), array( 'type' => 'email', 'auto' => 'email', 'missing' => __( 'Enter your work email', 'halden' ), 'bad' => __( 'Enter an email address like name@example.com', 'halden' ), 'short' => __( 'Email', 'halden' ), 'extra' => 'spellcheck="false"' ) );
echo $halden_text( $halden_u . '-q-phone', 'phone', __( 'Phone', 'halden' ), array( 'type' => 'tel', 'required' => false, 'auto' => 'tel', 'bad' => __( 'Enter a phone number, like 800 555 0142', 'halden' ), 'extra' => 'pattern="[0-9\+\(\)\-\.\s]{7,}"' ) );
// phpcs:enable
?>
<div class="cf__f">
<label for="<?php echo esc_attr( $halden_u ); ?>-q-plan"><?php esc_html_e( 'Plan', 'halden' ); ?></label>
<select id="<?php echo esc_attr( $halden_u ); ?>-q-plan" name="plan" required data-plan data-short="<?php esc_attr_e( 'Plan', 'halden' ); ?>" data-missing="<?php esc_attr_e( 'Choose a plan, or “Not sure yet”', 'halden' ); ?>">
<option value=""><?php esc_html_e( 'Choose a plan', 'halden' ); ?></option>
<?php foreach ( $halden_plans as $halden_p ) : ?>
<option value="<?php echo esc_attr( $halden_p['id'] ); ?>"><?php echo esc_html( $halden_p['label'] ); ?></option>
<?php endforeach; ?>
</select>
<?php echo $halden_err( $halden_u . '-q-plan' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</div>
<?php echo $halden_stepper( $halden_u . '-q-qty', 'qty', __( 'How many', 'halden' ), '', 1, 20, 1, __( 'Enter how many you need', 'halden' ), __( 'Quantity', 'halden' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
<div class="cf__f">
<label for="<?php echo esc_attr( $halden_u ); ?>-q-kw"><?php esc_html_e( 'Power per rack', 'halden' ); ?><?php echo $halden_opt; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
<div class="cf__suffix"><input id="<?php echo esc_attr( $halden_u ); ?>-q-kw" name="kw" type="number" inputmode="decimal" min="0.5" max="25" step="0.5" data-short="<?php esc_attr_e( 'Power per rack', 'halden' ); ?>" data-range="<?php esc_attr_e( 'Enter 0.5 to 25 kW. Need more? Tell us in the message.', 'halden' ); ?>" aria-describedby="<?php echo esc_attr( $halden_u ); ?>-q-kw-hint"><span aria-hidden="true">kW</span></div>
<p class="cf__hint" id="<?php echo esc_attr( $halden_u ); ?>-q-kw-hint"><?php esc_html_e( 'Up to 25 kW per rack in HAL2. Leave blank if unsure.', 'halden' ); ?></p>
<?php echo $halden_err( $halden_u . '-q-kw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</div>
<div class="cf__f">
<label for="<?php echo esc_attr( $halden_u ); ?>-q-when"><?php esc_html_e( 'Move-in timeline', 'halden' ); ?><?php echo $halden_opt; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
<select id="<?php echo esc_attr( $halden_u ); ?>-q-when" name="timeline">
<option value=""><?php esc_html_e( 'Choose one', 'halden' ); ?></option>
<?php foreach ( $halden_timelines as $halden_t ) : ?>
<option><?php echo esc_html( $halden_t ); ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="cf__f cf__f--wide">
<label for="<?php echo esc_attr( $halden_u ); ?>-q-msg"><?php esc_html_e( 'Anything else we should know?', 'halden' ); ?><?php echo $halden_opt; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
<textarea id="<?php echo esc_attr( $halden_u ); ?>-q-msg" name="message" rows="4" aria-describedby="<?php echo esc_attr( $halden_u ); ?>-q-msg-hint"></textarea>
<p class="cf__hint" id="<?php echo esc_attr( $halden_u ); ?>-q-msg-hint"><?php esc_html_e( 'Your current provider, network and cross connect needs, when your contract ends.', 'halden' ); ?></p>
</div>
</div>
<div class="cf__submit">
<button class="btn btn--red" type="submit"><?php echo esc_html( $halden_a['quoteButton'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
<?php echo $halden_proto; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?>
</div>
</form>
</div>
<div class="cf__done" hidden></div>
</div>

<?php /* ---------------- Tour ---------------- */ ?>
<div class="cf__panel" id="tour" role="tabpanel" aria-labelledby="tour-tab" hidden>
<div class="cf__body">
<div class="cf__intro">
<h3 id="<?php echo esc_attr( $halden_u ); ?>-tour-h"><?php echo halden_inline( $halden_a['tourTitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
<?php if ( $halden_a['tourIntro'] ) : ?><p><?php echo halden_inline( $halden_a['tourIntro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
<form class="cf__form" data-kind="tour" method="post" novalidate aria-labelledby="<?php echo esc_attr( $halden_u ); ?>-tour-h">
<div class="cf__errors" role="alert"></div>
<div class="cf__grid">
<?php
// phpcs:disable WordPress.Security.EscapeOutput -- helpers escape every value.
echo $halden_text( $halden_u . '-t-name', 'name', __( 'Your name', 'halden' ), array( 'auto' => 'name', 'missing' => __( 'Enter your name', 'halden' ), 'short' => __( 'Name', 'halden' ) ) );
echo $halden_text( $halden_u . '-t-company', 'company', __( 'Company', 'halden' ), array( 'auto' => 'organization', 'missing' => __( 'Enter your company name', 'halden' ) ) );
echo $halden_text( $halden_u . '-t-email', 'email', __( 'Email', 'halden' ), array( 'type' => 'email', 'auto' => 'email', 'missing' => __( 'Enter your email', 'halden' ), 'bad' => __( 'Enter an email address like name@example.com', 'halden' ), 'extra' => 'spellcheck="false"' ) );
echo $halden_stepper( $halden_u . '-t-people', 'visitors', __( 'Visitors', 'halden' ), __( 'people', 'halden' ), 1, 10, 1, __( 'Enter how many people are coming', 'halden' ), __( 'Visitors', 'halden' ) );
// phpcs:enable
?>
<div class="cf__f">
<label for="<?php echo esc_attr( $halden_u ); ?>-t-date"><?php esc_html_e( 'Preferred date', 'halden' ); ?></label>
<input id="<?php echo esc_attr( $halden_u ); ?>-t-date" name="date" type="date" required data-weekday data-short="<?php esc_attr_e( 'Date', 'halden' ); ?>" data-missing="<?php esc_attr_e( 'Choose a date', 'halden' ); ?>" aria-describedby="<?php echo esc_attr( $halden_u ); ?>-t-date-hint">
<p class="cf__hint" id="<?php echo esc_attr( $halden_u ); ?>-t-date-hint"><?php echo halden_inline( $halden_a['tourDaysHint'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
<?php echo $halden_err( $halden_u . '-t-date' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</div>
<fieldset class="cf__f cf__choices" id="<?php echo esc_attr( $halden_u ); ?>-t-time" data-group="time" data-short="<?php esc_attr_e( 'Time', 'halden' ); ?>" data-missing="<?php esc_attr_e( 'Choose morning or afternoon', 'halden' ); ?>">
<legend><?php esc_html_e( 'Time of day', 'halden' ); ?></legend>
<div class="cf__choice-row">
<label class="cf__choice"><input type="radio" name="time" value="Morning" required><span><?php esc_html_e( 'Morning', 'halden' ); ?></span></label>
<label class="cf__choice"><input type="radio" name="time" value="Afternoon" required><span><?php esc_html_e( 'Afternoon', 'halden' ); ?></span></label>
</div>
<?php echo $halden_err( $halden_u . '-t-time' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</fieldset>
<div class="cf__f cf__f--wide">
<label for="<?php echo esc_attr( $halden_u ); ?>-t-note"><?php esc_html_e( 'What would you like to see?', 'halden' ); ?><?php echo $halden_opt; // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
<textarea id="<?php echo esc_attr( $halden_u ); ?>-t-note" name="note" rows="3" aria-describedby="<?php echo esc_attr( $halden_u ); ?>-t-note-hint"></textarea>
<p class="cf__hint" id="<?php echo esc_attr( $halden_u ); ?>-t-note-hint"><?php esc_html_e( 'For example the meet-me room, HAL2 high-density racks, or the cooling plant.', 'halden' ); ?></p>
</div>
</div>
<?php if ( $halden_a['tourIdNote'] ) : ?>
<div class="cf__note"><?php echo $halden_id; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><p><?php echo halden_inline( $halden_a['tourIdNote'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
<?php endif; ?>
<div class="cf__submit">
<button class="btn btn--red" type="submit"><?php echo esc_html( $halden_a['tourButton'] ); ?> <?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
<?php echo $halden_proto; // phpcs:ignore WordPress.Security.EscapeOutput ?>
</div>
</form>
</div>
<div class="cf__done" hidden></div>
</div>

<?php /* ---------------- Support ---------------- */ ?>
<div class="cf__panel" id="support" role="tabpanel" aria-labelledby="support-tab" hidden>
<div class="cf__intro">
<h3><?php echo halden_inline( $halden_a['supportTitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
<?php if ( $halden_a['supportIntro'] ) : ?><p><?php echo halden_inline( $halden_a['supportIntro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p><?php endif; ?>
</div>
<a class="cf__call" href="<?php echo esc_url( $halden_tel ); ?>">
<span class="cf__call-k"><?php echo halden_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Operations line, 24/7', 'halden' ); ?></span>
<b><?php echo esc_html( $halden_a['phone'] ); ?></b>
<?php echo halden_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</a>
<div class="cf__sup">
<?php if ( $halden_email ) : ?>
<div>
<p class="cf__k"><?php echo $halden_mail; // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Email support', 'halden' ); ?></p>
<p><a href="<?php echo esc_url( 'mailto:' . $halden_email ); ?>"><?php echo esc_html( $halden_email ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
</div>
<?php endif; ?>
<?php if ( $halden_a['portalNote'] ) : ?>
<div>
<p class="cf__k"><?php echo halden_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Client portal', 'halden' ); ?></p>
<p><?php echo halden_inline( $halden_a['portalNote'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
</div>
<?php endif; ?>
</div>
<?php if ( ! empty( $halden_a['readyItems'] ) ) : ?>
<div class="cf__ready">
<h4><?php esc_html_e( 'Have this ready when you call', 'halden' ); ?></h4>
<ul class="plan__list">
<?php foreach ( (array) $halden_a['readyItems'] as $halden_li ) : ?>
<li><?php echo halden_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo halden_inline( $halden_li ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
</div>
</div>
