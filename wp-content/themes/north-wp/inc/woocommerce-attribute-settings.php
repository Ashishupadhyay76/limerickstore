<?php

class THB_Product_Attributes {
	public function __construct() { }

	public static function instance() {
		static $instance = null;

		// Only run these methods if they haven't been run previously
		if ( null === $instance ) {
			$instance = new THB_Product_Attributes();
			$instance->init();
		}

		// Always return the instance
		return $instance;
	}
	/**
	 * Initialize module
	 */
	public function init() {
		if ( is_admin() ) {
			global $pagenow;

			if ( 'edit.php' === $pagenow || 'term.php' === $pagenow ) {
				add_action( 'admin_print_scripts', array( $this, 'thb_enqueue_scripts' ) );
				add_filter( 'product_attributes_type_selector', array( $this, 'thb_attribute_settings' ) );
				add_action( 'admin_init', array( $this, 'thb_create_attribute_settings' ) );
			}
			add_action( 'edit_term', array( $this, 'thb_save_term_meta' ), 10, 2 );
		}

		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'thb_change_swatch_html' ), 100, 2 );
	}
	public function thb_enqueue_scripts() {
		$screen = get_current_screen();
		if ( strpos( $screen->id, 'edit-pa_' ) === false ) {
			return;
		}
		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

	}
	public function thb_attribute_settings( $array ) {
		$array['thb_color'] = esc_html__( 'Color / Image - North', 'north' );
		$array['thb_text']  = esc_html__( 'Text - North', 'north' );
		$array['thb_box']   = esc_html__( 'Box - North', 'north' );
		return $array;
	}
	public function thb_create_attribute_settings() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach ( $attribute_taxonomies as $tax ) {
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'thb_edit_attribute_fields' ), 50, 2 );
		}
	}
	public function thb_edit_attribute_fields( $term, $taxonomy ) {
		$thb_color = get_term_meta( $term->term_id, 'thb_color', true );
		$thb_image = get_term_meta( $term->term_id, 'thb_image', true );

		if ( $thb_image ) {
			$image = wp_get_attachment_image_url( $thb_image, 'thumbnail' );
		} else {
			$image = wc_placeholder_img_src();
		}
		?>
		<tr class="form-field">
			<th scope="row"><h2><?php esc_html_e( 'North Settings', 'north' ); ?></h2></th>
		</tr>
		<tr class="form-field">
			<th scope="row"><label><?php esc_html_e( 'Color', 'north' ); ?></label></th>
			<td><input type="text" id="thb-term-color" name="thb_color" value="<?php echo esc_attr( $thb_color ); ?>" /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label><?php esc_html_e( 'Image', 'north' ); ?></label></th>
			<td>
				<div id="thb_term_image_holder" style="float:left;margin-right:10px;">
					<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" />
				</div>
				<div style="line-height: 60px;">
					<input type="hidden" id="thb-term-image" name="thb_image" value="<?php echo esc_attr( $thb_image ); ?>" />
					<button type="submit" class="thb_upload_header button"><?php esc_html_e( 'Upload/Add image', 'north' ); ?></button>
					<button type="submit" class="thb_remove_header button"><?php esc_html_e( 'Remove image', 'north' ); ?></button>
				</div>
			</td>
		</tr>
		<?php
	}
	public function thb_save_term_meta( $term_id, $tt_id ) {
		$thb_color = !empty($_POST['thb_color']) ? sanitize_text_field( $_POST['thb_color'] ) : '';
		$thb_image = !empty($_POST['thb_image']) ? sanitize_text_field( $_POST['thb_image'] ) : '';

		if ( isset( $thb_color ) ) {
			update_term_meta( $term_id, 'thb_color', $thb_color );
		}
		if ( isset( $thb_image ) ) {
			update_term_meta( $term_id, 'thb_image', $thb_image );
		}
	}
	public function thb_change_swatch_html( $html, $args ) {
		$attribute_id = wc_attribute_taxonomy_id_by_name( sanitize_title( $args['attribute'] ) );
		$attribute    = wc_get_attribute( $attribute_id );
		if ( 'thb_color' === $attribute->type || 'thb_text' === $attribute->type || 'thb_box' === $attribute->type ) {
			if ( ! empty( $args['options'] ) ) {
				$product  = $args['product'];
				$terms    = wc_get_product_terms( $product->get_id(), $attribute->slug, array( 'fields' => 'all' ) );
				$swatches = '';

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $args['options'] ) ) {
						$swatches .= $this->thb_render_swatch( $attribute->type, $term, $args['selected'], $attribute->slug, false );
					}
				}
				$html = '<div class="hide hidden">' . $html . '</div><div class="thb-swatches ' . esc_attr( $attribute->type ) . '-swatch" data-attribute_name="attribute_' . esc_attr( $attribute->slug ) . '">' . $swatches . '</div>';
				return $html;
			}
		} else {
			return $html;
		}
	}
	public function thb_render_swatch( $type, $term, $selected, $attribute_slug, $available_variations ) {
		$swatches       = '';
		$attribute_slug = mb_strtolower( $attribute_slug );
		if ( $available_variations ) {
			foreach ( $available_variations as $variation ) {
				$option_variation = array();
				$attr_key         = 'attribute_' . $attribute_slug;
				if ( ! isset( $variation['attributes'][ $attr_key ] ) ) {
					return;
				}
				if ( $variation['attributes'][ $attr_key ] == $term->slug ) {
					if ( ! empty( $variation['image']['thumb_src'] ) ) {
						$option_variation = array(
							'variation_id' => $variation['variation_id'],
							'image_src'    => $variation['image']['thumb_src'],
						);
						break;
					}
				}
			}
		}
		switch ( $type ) {
			case 'thb_color':
				$color    = get_term_meta( $term->term_id, 'thb_color', true );
				$color    = '' === $color ? '#000' : $color;
				$image    = get_term_meta( $term->term_id, 'thb_image', true );
				$image    = $image ? wp_get_attachment_image_url( $image, 'thumbnail' ) : '';
				$options  = ! empty( $option_variation ) ? $option_variation : '';
				$swatches = sprintf(
					'<span class="thb-swatch swatch-color swatch-%s %s" title="%s" data-value="%s" data-variation="%s"><span style="background-color:%s;background-image:url(%s)">%s</span></span>',
					esc_attr( $term->slug ),
					sanitize_title( $selected ) == $term->slug ? 'selected' : '',
					esc_attr( $term->name ),
					esc_attr( $term->slug ),
					esc_attr( json_encode( $options ) ),
					esc_attr( $color ),
					esc_attr( $image ),
					esc_html( $term->name )
				);
				break;
			case 'thb_text':
				$options  = ! empty( $option_variation ) ? $option_variation : '';
				$swatches = sprintf(
					'<span class="thb-swatch swatch-text swatch-%s %s" title="%s" data-value="%s" data-variation="%s">%s</span>',
					esc_attr( $term->slug ),
					sanitize_title( $selected ) == $term->slug ? 'selected' : '',
					esc_attr( $term->name ),
					esc_attr( $term->slug ),
					esc_attr( json_encode( $options ) ),
					esc_html( $term->name )
				);
				break;
			case 'thb_box':
				$options  = ! empty( $option_variation ) ? $option_variation : '';
				$swatches = sprintf(
					'<span class="thb-swatch swatch-box swatch-%s %s" title="%s" data-value="%s" data-variation="%s">%s</span>',
					esc_attr( $term->slug ),
					sanitize_title( $selected ) == $term->slug ? 'selected' : '',
					esc_attr( $term->name ),
					esc_attr( $term->slug ),
					esc_attr( json_encode( $options ) ),
					esc_html( $term->name )
				);
				break;
		}
		return $swatches;
	}
}
function THB_Product_Attributes() {
	return THB_Product_Attributes::instance();
}
THB_Product_Attributes();
