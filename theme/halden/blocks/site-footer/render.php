<?php
/**
 * Site footer: the sample's four columns, pointed at the consolidated page set.
 *
 * @package Halden
 */

defined( 'ABSPATH' ) || exit;

$halden_cols = array(
	__( 'Colocation', 'halden' )    => array(
		__( 'All plans', 'halden' )     => '/colocation/#plans',
		__( 'Shared rack', 'halden' )   => '/colocation/?plan=shared#plans',
		__( 'Full rack', 'halden' )     => '/colocation/?plan=full#plans',
		__( 'Private cage', 'halden' )  => '/colocation/?plan=suite#plans',
		__( 'AI and GPU', 'halden' )    => '/high-density/',
	),
	__( 'More services', 'halden' ) => array(
		__( 'Dedicated servers', 'halden' ) => '/services/#dedicated-servers',
		__( 'Cloud Connect', 'halden' )     => '/services/#cloud-connect',
		__( 'À La Carte', 'halden' )        => '/services/#a-la-carte',
		__( 'Cross connects', 'halden' )    => '/services/#cross-connects',
		__( 'Remote hands', 'halden' )      => '/services/#remote-hands',
	),
);
$halden_legal = array(
	__( 'Acceptable use', 'halden' )           => '/legal/#acceptable-use',
	__( 'Service level agreement', 'halden' )  => '/legal/#sla',
	__( 'Privacy', 'halden' )                  => '/legal/#privacy',
	__( 'Data centre', 'halden' )              => '/data-centre/',
	__( 'About', 'halden' )                    => '/about/',
	__( 'News', 'halden' )                     => '/news/',
	__( 'Careers', 'halden' )                  => '/about/#careers',
);
$halden_tel = 'tel:' . preg_replace( '/[^0-9+]/', '', $attributes['phone'] );
?>
<footer class="ftr">
	<div class="wrap">
		<div class="ftr__cols">
			<div>
				<p class="fh"><?php esc_html_e( 'Talk to sales', 'halden' ); ?></p>
				<ul>
					<li><a class="tel" href="<?php echo esc_url( $halden_tel ); ?>"><?php echo esc_html( $attributes['phone'] ); ?></a></li>
					<li><a href="<?php echo esc_url( halden_url( '/contact/#quote' ) ); ?>"><?php esc_html_e( 'Get a quote', 'halden' ); ?></a></li>
					<li><a href="<?php echo esc_url( halden_url( '/contact/#tour' ) ); ?>"><?php esc_html_e( 'Book a tour', 'halden' ); ?></a></li>
				</ul>
			</div>
			<?php foreach ( $halden_cols as $halden_head => $halden_links ) : ?>
				<div>
					<p class="fh"><?php echo esc_html( $halden_head ); ?></p>
					<ul>
						<?php foreach ( $halden_links as $halden_label => $halden_path ) : ?>
							<li><a href="<?php echo esc_url( halden_url( $halden_path ) ); ?>"><?php echo esc_html( $halden_label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
			<div>
				<p class="fh"><?php esc_html_e( 'Data centre', 'halden' ); ?></p>
				<address>200 Foundry Row<br>Port Alder</address>
				<p class="fh" style="margin-top:32px"><?php esc_html_e( 'Head office', 'halden' ); ?></p>
				<address>18 Quay Street, Suite 400<br>Port Alder</address>
			</div>
		</div>
		<div class="ftr__big" aria-hidden="true"><?php echo esc_html( $attributes['bigWord'] ); ?></div>
		<div class="ftr__legal">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Halden Racks Inc.</span>
			<span><?php esc_html_e( 'Halden Racks is a fictional company. This site is a design prototype.', 'halden' ); ?></span>
			<?php foreach ( $halden_legal as $halden_label => $halden_path ) : ?>
				<a href="<?php echo esc_url( halden_url( $halden_path ) ); ?>"><?php echo esc_html( $halden_label ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</footer>
