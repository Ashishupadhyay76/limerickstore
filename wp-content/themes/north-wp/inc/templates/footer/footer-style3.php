<?php
	$row_classes[] = 'row align-center';
	$row_classes[] = ot_get_option( 'footer_fullwidth', 'off' ) == 'on' ? 'full-width-row' : '';
	$footer_color  = ot_get_option( 'footer_color', 'light' );
	$copyright     = ot_get_option( 'copyright' );
?>
<footer class="footer style3 <?php echo esc_attr( $footer_color ); ?>">
	<?php do_action( 'thb_page_content', apply_filters( 'thb_footer_page_content', ot_get_option( 'footer_top_content' ) ) ); ?>
	<div class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>">
		<div class="small-12 medium-10 large-6 text-center columns">
			<?php do_action( 'thb_footer_social' ); ?>
			<?php if ( has_nav_menu( 'footer-menu' ) ) { ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-menu',
						'depth'          => 1,
						'container'      => false,
						'menu_class'     => 'thb-footer-menu',
					)
				);
				?>
			<?php } ?>
			<?php if ( $copyright ) { ?>
				<div class="thb-footer-copyright">
					<?php echo do_shortcode( $copyright ); ?>
				</div>
			<?php } ?>
			<?php do_action( 'thb_footer_payment' ); ?>
		</div>
	</div>
</footer>
