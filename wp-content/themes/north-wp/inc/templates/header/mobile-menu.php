<!-- Start Mobile Menu -->
<nav id="mobile-menu" class="side-panel">
	<header>
		<h6><?php esc_html_e( 'Menu', 'north' ); ?></h6>
		<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
	</header>
	<div class="side-panel-content custom_scroll">
		<?php
		if ( ot_get_option( 'mobile_menu_search', 'on' ) === 'on' ) {
			if ( thb_wc_supported() ) {
				get_product_search_form();
			} else {
				get_search_form();
			}
		}
		?>
		<?php if ( has_nav_menu( 'mobile-menu' ) ) { ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'mobile-menu',
					'depth'          => 4,
					'container'      => false,
					'menu_class'     => 'mobile-menu',
					'walker'         => new thb_mobileDropdown(),
				)
			);
			?>
		<?php } ?>
		<?php if ( has_nav_menu( 'acc-menu-in' ) && is_user_logged_in() ) { ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'acc-menu-in',
					'depth'          => 1,
					'container'      => false,
					'menu_class'     => 'mobile-secondary-menu',
					'walker'         => new thb_mobileDropdown(),
				)
			);
			?>
		<?php } elseif ( has_nav_menu( 'acc-menu-out' ) && ! is_user_logged_in() ) { ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'acc-menu-out',
					'depth'          => 1,
					'container'      => false,
					'menu_class'     => 'mobile-secondary-menu',
					'walker'         => new thb_mobileDropdown(),
				)
			);
			?>
		<?php } ?>
		<div class="social-links">
			<?php do_action( 'thb_social' ); ?>
		</div>

	</div>
	<div class="thb-mobile-menu-switchers">
		<?php do_action( 'thb_language_switcher_mobile' ); ?>
		<?php do_action( 'thb_currency_switcher_mobile' ); ?>
	</div>
</nav>
<!-- End Mobile Menu -->
