<div class="shop_bar">
	<div class="row">
		<div class="small-4 medium-6 columns category_bar">
				<a href="#" id="thb-shop-filters"><?php get_template_part( 'assets/img/svg/filter.svg' ); ?> <?php esc_html_e( 'Filter', 'north' ); ?></a>
				<?php do_action( 'thb_breadcrumbs' ); ?>
		</div>
		<div class="small-8 medium-6 columns ordering">
			<?php if ( have_posts() ) : ?>
				<?php do_action( 'thb_before_shop_loop_result_count' ); ?>
				<?php do_action( 'thb_before_shop_loop_catalog_ordering' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
