<?php
$logo                = ot_get_option( 'logo', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo-light.png' );
$logo_dark           = ot_get_option( 'logo_dark', Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/logo-dark.png' );
$fixed_header_shadow = ot_get_option( 'fixed_header_shadow' );
$row_classes[]       = 'row align-middle';
$row_classes[]       = ot_get_option( 'header_fullwidth', 'off' ) == 'on' ? 'full-width-row' : '';

$header_class[] = 'header style4';
$header_class[] = $fixed_header_shadow;
?>
<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
	<div class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>">
		<div class="small-3 medium-3 columns toggle-holder">
			<?php do_action( 'thb_mobile_toggle' ); ?>
		</div>
		<div class="small-6 large-6 columns logo">
			<div class="logo-holder">
				<a href="<?php echo esc_url( home_url() ); ?>" class="logolink">
					<img src="<?php echo esc_attr( $logo ); ?>" class="logoimg bg--light" alt="<?php bloginfo( 'name' ); ?>"/>
					<img src="<?php echo esc_attr( $logo_dark ); ?>" class="logoimg bg--dark" alt="<?php bloginfo( 'name' ); ?>"/>
				</a>
			</div>
		</div>
		<div class="small-3 medium-3 columns account-holder">
			<?php do_action( 'thb_secondary_area' ); ?>
		</div>
	</div>
</header>
