<?php
/* Adds custom classes to the array of body classes. */
function thb_body_classes( $classes ) {
	$thb_id                      = get_queried_object_id();
	$revslider_arrows            = ot_get_option( 'revslider_arrows', 'on' );
	$header_color                = get_post_meta( $thb_id, 'header_color', true );
	$global_notification         = 'global_notification_' . ot_get_option( 'global_notification', 'off' );
	$shop_product_ajax_addtocart = 'thb-single-product-ajax-' . ot_get_option( 'shop_product_ajax_addtocart', 'on' );

	$classes[] = $revslider_arrows === 'on' ? 'thb-arrows' : false;
	$classes[] = $global_notification;
	$classes[] = $shop_product_ajax_addtocart;
	$classes[] = 'subheader-full-width-' . ot_get_option( 'thb_subheader_full_width', 'off' );

	/* Header Color */

	if ( thb_wc_supported() ) {

		if ( is_shop() || is_product_tag() ) {

			if ( ot_get_option( 'shop_header_style', 'style1' ) === 'style2' ) {
				$header_color = ot_get_option( 'shop_menu_color', 'light-title' );
			} else {
				$header_color = false;
			}
		} elseif ( is_product_category() ) {
			$cat    = get_queried_object();
			$cat_id = $cat->term_id;
			if ( get_term_meta( $cat_id, 'header_id', true ) ) {
				$header_color = get_term_meta( $cat_id, 'shop_menu_color_cat', true );
			}
		}
	}
	$main_header_color = post_password_required() ? false : $header_color;
	$classes[]         = $main_header_color;
	return $classes;
}
add_filter( 'body_class', 'thb_body_classes' );

/* Change max image size */
function thb_max_srcset_image_width( $max_width, $size_array ) {
	$width = $size_array[0];
	return $width;
}
add_filter( 'max_srcset_image_width', 'thb_max_srcset_image_width', 10, 2 );

/* Filter Excerpt Length */
add_filter( 'excerpt_length', 'thb_default_excerpt_length' );
add_filter( 'excerpt_more', 'thb_excerpt_more' );

function thb_long_excerpt_length() {
	return 55;
}
function thb_short_excerpt_length() {
	return 35;
}
function thb_default_excerpt_length( $length ) {
	return 25;
}
function thb_excerpt_more( $more ) {
	return '&hellip;';
}
/* Translate Columns */
function thb_translate_columns( $size ) {
	if ( $size == 6 ) {
		return 'medium-2';
	} elseif ( $size == 5 ) {
		return 'medium-1/5';
	} elseif ( $size == 4 ) {
		return 'medium-3';
	} elseif ( $size == 3 ) {
		return 'medium-4';
	} elseif ( $size == 2 ) {
		return 'medium-6';
	} else {
		return 'medium-12';
	}
}
/* Youtube & Vimeo Embeds */
function thb_remove_youtube_controls( $code ) {
	$return = '';

	if ( strpos( $code, 'youtu.be' ) !== false || strpos( $code, 'youtube.com' ) !== false || strpos( $code, 'player.vimeo.com' ) !== false ) {
		if ( strpos( $code, 'youtu.be' ) !== false || strpos( $code, 'youtube.com' ) !== false ) {
			$return = preg_replace( "@src=(['\"])?([^'\">\s]*)@", 'src=$1$2&showinfo=0&rel=0&modestbranding=1', $code );
		} else {
			$return = $code;
		}
			$return = '<div class="flex-video widescreen' . ( strpos( $code, 'player.vimeo.com' ) !== false ? ' vimeo' : ' youtube' ) . '">' . $return . '</div>';
	} elseif ( strpos( $code, 'soundcloud.com' ) !== false ) {
			$return = '<div class="flex-video">' . $code . '</div>';
	} else {
			$return = $code;
	}
	return $return;
}

add_filter( 'embed_handler_html', 'thb_remove_youtube_controls' );
add_filter( 'embed_oembed_html', 'thb_remove_youtube_controls' );

/* Icon Array */
function thb_getIconArray( $empty = true ) {
	$icons = array(
		'Arrows Anticlockwise'                  => 'arrows_anticlockwise.svg',
		'Arrows Anticlockwise Dashed'           => 'arrows_anticlockwise_dashed.svg',
		'Arrows Button Down'                    => 'arrows_button_down.svg',
		'Arrows Button Off'                     => 'arrows_button_off.svg',
		'Arrows Button On'                      => 'arrows_button_on.svg',
		'Arrows Button Up'                      => 'arrows_button_up.svg',
		'Arrows Check'                          => 'arrows_check.svg',
		'Arrows Circle Check'                   => 'arrows_circle_check.svg',
		'Arrows Circle Down'                    => 'arrows_circle_down.svg',
		'Arrows Circle Downleft'                => 'arrows_circle_downleft.svg',
		'Arrows Circle Downright'               => 'arrows_circle_downright.svg',
		'Arrows Circle Left'                    => 'arrows_circle_left.svg',
		'Arrows Circle Minus'                   => 'arrows_circle_minus.svg',
		'Arrows Circle Plus'                    => 'arrows_circle_plus.svg',
		'Arrows Circle Remove'                  => 'arrows_circle_remove.svg',
		'Arrows Circle Right'                   => 'arrows_circle_right.svg',
		'Arrows Circle Up'                      => 'arrows_circle_up.svg',
		'Arrows Circle Upleft'                  => 'arrows_circle_upleft.svg',
		'Arrows Circle Upright'                 => 'arrows_circle_upright.svg',
		'Arrows Clockwise'                      => 'arrows_clockwise.svg',
		'Arrows Clockwise Dashed'               => 'arrows_clockwise_dashed.svg',
		'Arrows Compress'                       => 'arrows_compress.svg',
		'Arrows Deny'                           => 'arrows_deny.svg',
		'Arrows Diagonal'                       => 'arrows_diagonal.svg',
		'Arrows Diagonal2'                      => 'arrows_diagonal2.svg',
		'Arrows Down'                           => 'arrows_down.svg',
		'Arrows Down Double-34'                 => 'arrows_down_double-34.svg',
		'Arrows Downleft'                       => 'arrows_downleft.svg',
		'Arrows Downright'                      => 'arrows_downright.svg',
		'Arrows Drag Down'                      => 'arrows_drag_down.svg',
		'Arrows Drag Down Dashed'               => 'arrows_drag_down_dashed.svg',
		'Arrows Drag Horiz'                     => 'arrows_drag_horiz.svg',
		'Arrows Drag Left'                      => 'arrows_drag_left.svg',
		'Arrows Drag Left Dashed'               => 'arrows_drag_left_dashed.svg',
		'Arrows Drag Right'                     => 'arrows_drag_right.svg',
		'Arrows Drag Right Dashed'              => 'arrows_drag_right_dashed.svg',
		'Arrows Drag Up'                        => 'arrows_drag_up.svg',
		'Arrows Drag Up Dashed'                 => 'arrows_drag_up_dashed.svg',
		'Arrows Drag Vert'                      => 'arrows_drag_vert.svg',
		'Arrows Exclamation'                    => 'arrows_exclamation.svg',
		'Arrows Expand'                         => 'arrows_expand.svg',
		'Arrows Expand Diagonal1'               => 'arrows_expand_diagonal1.svg',
		'Arrows Expand Horizontal1'             => 'arrows_expand_horizontal1.svg',
		'Arrows Expand Vertical1'               => 'arrows_expand_vertical1.svg',
		'Arrows Fit Horizontal'                 => 'arrows_fit_horizontal.svg',
		'Arrows Fit Vertical'                   => 'arrows_fit_vertical.svg',
		'Arrows Glide'                          => 'arrows_glide.svg',
		'Arrows Glide Horizontal'               => 'arrows_glide_horizontal.svg',
		'Arrows Glide Vertical'                 => 'arrows_glide_vertical.svg',
		'Arrows Hamburger 2'                    => 'arrows_hamburger 2.svg',
		'Arrows Hamburger1'                     => 'arrows_hamburger1.svg',
		'Arrows Horizontal'                     => 'arrows_horizontal.svg',
		'Arrows Info'                           => 'arrows_info.svg',
		'Arrows Keyboard Alt'                   => 'arrows_keyboard_alt.svg',
		'Arrows Keyboard Cmd-29'                => 'arrows_keyboard_cmd-29.svg',
		'Arrows Keyboard Delete'                => 'arrows_keyboard_delete.svg',
		'Arrows Keyboard Down-28'               => 'arrows_keyboard_down-28.svg',
		'Arrows Keyboard Left'                  => 'arrows_keyboard_left.svg',
		'Arrows Keyboard Return'                => 'arrows_keyboard_return.svg',
		'Arrows Keyboard Right'                 => 'arrows_keyboard_right.svg',
		'Arrows Keyboard Shift'                 => 'arrows_keyboard_shift.svg',
		'Arrows Keyboard Tab'                   => 'arrows_keyboard_tab.svg',
		'Arrows Keyboard Up'                    => 'arrows_keyboard_up.svg',
		'Arrows Left'                           => 'arrows_left.svg',
		'Arrows Left Double-32'                 => 'arrows_left_double-32.svg',
		'Arrows Minus'                          => 'arrows_minus.svg',
		'Arrows Move'                           => 'arrows_move.svg',
		'Arrows Move2'                          => 'arrows_move2.svg',
		'Arrows Move Bottom'                    => 'arrows_move_bottom.svg',
		'Arrows Move Left'                      => 'arrows_move_left.svg',
		'Arrows Move Right'                     => 'arrows_move_right.svg',
		'Arrows Move Top'                       => 'arrows_move_top.svg',
		'Arrows Plus'                           => 'arrows_plus.svg',
		'Arrows Question'                       => 'arrows_question.svg',
		'Arrows Remove'                         => 'arrows_remove.svg',
		'Arrows Right'                          => 'arrows_right.svg',
		'Arrows Right Double-31'                => 'arrows_right_double-31.svg',
		'Arrows Rotate'                         => 'arrows_rotate.svg',
		'Arrows Rotate Anti'                    => 'arrows_rotate_anti.svg',
		'Arrows Rotate Anti Dashed'             => 'arrows_rotate_anti_dashed.svg',
		'Arrows Rotate Dashed'                  => 'arrows_rotate_dashed.svg',
		'Arrows Shrink'                         => 'arrows_shrink.svg',
		'Arrows Shrink Diagonal1'               => 'arrows_shrink_diagonal1.svg',
		'Arrows Shrink Diagonal2'               => 'arrows_shrink_diagonal2.svg',
		'Arrows Shrink Horizonal2'              => 'arrows_shrink_horizonal2.svg',
		'Arrows Shrink Horizontal1'             => 'arrows_shrink_horizontal1.svg',
		'Arrows Shrink Vertical1'               => 'arrows_shrink_vertical1.svg',
		'Arrows Shrink Vertical2'               => 'arrows_shrink_vertical2.svg',
		'Arrows Sign Down'                      => 'arrows_sign_down.svg',
		'Arrows Sign Left'                      => 'arrows_sign_left.svg',
		'Arrows Sign Right'                     => 'arrows_sign_right.svg',
		'Arrows Sign Up'                        => 'arrows_sign_up.svg',
		'Arrows Slide Down1'                    => 'arrows_slide_down1.svg',
		'Arrows Slide Down2'                    => 'arrows_slide_down2.svg',
		'Arrows Slide Left1'                    => 'arrows_slide_left1.svg',
		'Arrows Slide Left2'                    => 'arrows_slide_left2.svg',
		'Arrows Slide Right1'                   => 'arrows_slide_right1.svg',
		'Arrows Slide Right2'                   => 'arrows_slide_right2.svg',
		'Arrows Slide Up1'                      => 'arrows_slide_up1.svg',
		'Arrows Slide Up2'                      => 'arrows_slide_up2.svg',
		'Arrows Slim Down'                      => 'arrows_slim_down.svg',
		'Arrows Slim Down Dashed'               => 'arrows_slim_down_dashed.svg',
		'Arrows Slim Left'                      => 'arrows_slim_left.svg',
		'Arrows Slim Left Dashed'               => 'arrows_slim_left_dashed.svg',
		'Arrows Slim Right'                     => 'arrows_slim_right.svg',
		'Arrows Slim Right Dashed'              => 'arrows_slim_right_dashed.svg',
		'Arrows Slim Up'                        => 'arrows_slim_up.svg',
		'Arrows Slim Up Dashed'                 => 'arrows_slim_up_dashed.svg',
		'Arrows Square Check'                   => 'arrows_square_check.svg',
		'Arrows Square Down'                    => 'arrows_square_down.svg',
		'Arrows Square Downleft'                => 'arrows_square_downleft.svg',
		'Arrows Square Downright'               => 'arrows_square_downright.svg',
		'Arrows Square Left'                    => 'arrows_square_left.svg',
		'Arrows Square Minus'                   => 'arrows_square_minus.svg',
		'Arrows Square Plus'                    => 'arrows_square_plus.svg',
		'Arrows Square Remove'                  => 'arrows_square_remove.svg',
		'Arrows Square Right'                   => 'arrows_square_right.svg',
		'Arrows Square Up'                      => 'arrows_square_up.svg',
		'Arrows Square Upleft'                  => 'arrows_square_upleft.svg',
		'Arrows Square Upright'                 => 'arrows_square_upright.svg',
		'Arrows Squares'                        => 'arrows_squares.svg',
		'Arrows Stretch Diagonal1'              => 'arrows_stretch_diagonal1.svg',
		'Arrows Stretch Diagonal2'              => 'arrows_stretch_diagonal2.svg',
		'Arrows Stretch Diagonal3'              => 'arrows_stretch_diagonal3.svg',
		'Arrows Stretch Diagonal4'              => 'arrows_stretch_diagonal4.svg',
		'Arrows Stretch Horizontal1'            => 'arrows_stretch_horizontal1.svg',
		'Arrows Stretch Horizontal2'            => 'arrows_stretch_horizontal2.svg',
		'Arrows Stretch Vertical1'              => 'arrows_stretch_vertical1.svg',
		'Arrows Stretch Vertical2'              => 'arrows_stretch_vertical2.svg',
		'Arrows Switch Horizontal'              => 'arrows_switch_horizontal.svg',
		'Arrows Switch Vertical'                => 'arrows_switch_vertical.svg',
		'Arrows Up'                             => 'arrows_up.svg',
		'Arrows Up Double'                      => 'arrows_up_double.svg',
		'Arrows Upright'                        => 'arrows_upright.svg',
		'Arrows Vertical'                       => 'arrows_vertical.svg',
		'Basic Accelerator'                     => 'basic_accelerator.svg',
		'Basic Alarm'                           => 'basic_alarm.svg',
		'Basic Anchor'                          => 'basic_anchor.svg',
		'Basic Anticlockwise'                   => 'basic_anticlockwise.svg',
		'Basic Archive'                         => 'basic_archive.svg',
		'Basic Archive Full'                    => 'basic_archive_full.svg',
		'Basic Ban'                             => 'basic_ban.svg',
		'Basic Battery Charge'                  => 'basic_battery_charge.svg',
		'Basic Battery Empty'                   => 'basic_battery_empty.svg',
		'Basic Battery Full'                    => 'basic_battery_full.svg',
		'Basic Battery Half'                    => 'basic_battery_half.svg',
		'Basic Bolt'                            => 'basic_bolt.svg',
		'Basic Book'                            => 'basic_book.svg',
		'Basic Book Pen'                        => 'basic_book_pen.svg',
		'Basic Book Pencil'                     => 'basic_book_pencil.svg',
		'Basic Bookmark'                        => 'basic_bookmark.svg',
		'Basic Calculator'                      => 'basic_calculator.svg',
		'Basic Calendar'                        => 'basic_calendar.svg',
		'Basic Cards Diamonds'                  => 'basic_cards_diamonds.svg',
		'Basic Cards Hearts'                    => 'basic_cards_hearts.svg',
		'Basic Case'                            => 'basic_case.svg',
		'Basic Chronometer'                     => 'basic_chronometer.svg',
		'Basic Clessidre'                       => 'basic_clessidre.svg',
		'Basic Clock'                           => 'basic_clock.svg',
		'Basic Clockwise'                       => 'basic_clockwise.svg',
		'Basic Cloud'                           => 'basic_cloud.svg',
		'Basic Clubs'                           => 'basic_clubs.svg',
		'Basic Compass'                         => 'basic_compass.svg',
		'Basic Cup'                             => 'basic_cup.svg',
		'Basic Diamonds'                        => 'basic_diamonds.svg',
		'Basic Display'                         => 'basic_display.svg',
		'Basic Download'                        => 'basic_download.svg',
		'Basic Elaboration Bookmark Checck'     => 'basic_elaboration_bookmark_checck.svg',
		'Basic Elaboration Bookmark Minus'      => 'basic_elaboration_bookmark_minus.svg',
		'Basic Elaboration Bookmark Plus'       => 'basic_elaboration_bookmark_plus.svg',
		'Basic Elaboration Bookmark Remove'     => 'basic_elaboration_bookmark_remove.svg',
		'Basic Elaboration Briefcase Check'     => 'basic_elaboration_briefcase_check.svg',
		'Basic Elaboration Briefcase Download'  => 'basic_elaboration_briefcase_download.svg',
		'Basic Elaboration Briefcase Flagged'   => 'basic_elaboration_briefcase_flagged.svg',
		'Basic Elaboration Briefcase Minus'     => 'basic_elaboration_briefcase_minus.svg',
		'Basic Elaboration Briefcase Plus'      => 'basic_elaboration_briefcase_plus.svg',
		'Basic Elaboration Briefcase Refresh'   => 'basic_elaboration_briefcase_refresh.svg',
		'Basic Elaboration Briefcase Remove'    => 'basic_elaboration_briefcase_remove.svg',
		'Basic Elaboration Briefcase Search'    => 'basic_elaboration_briefcase_search.svg',
		'Basic Elaboration Briefcase Star'      => 'basic_elaboration_briefcase_star.svg',
		'Basic Elaboration Briefcase Upload'    => 'basic_elaboration_briefcase_upload.svg',
		'Basic Elaboration Browser Check'       => 'basic_elaboration_browser_check.svg',
		'Basic Elaboration Browser Download'    => 'basic_elaboration_browser_download.svg',
		'Basic Elaboration Browser Minus'       => 'basic_elaboration_browser_minus.svg',
		'Basic Elaboration Browser Plus'        => 'basic_elaboration_browser_plus.svg',
		'Basic Elaboration Browser Refresh'     => 'basic_elaboration_browser_refresh.svg',
		'Basic Elaboration Browser Remove'      => 'basic_elaboration_browser_remove.svg',
		'Basic Elaboration Browser Search'      => 'basic_elaboration_browser_search.svg',
		'Basic Elaboration Browser Star'        => 'basic_elaboration_browser_star.svg',
		'Basic Elaboration Browser Upload'      => 'basic_elaboration_browser_upload.svg',
		'Basic Elaboration Calendar Check'      => 'basic_elaboration_calendar_check.svg',
		'Basic Elaboration Calendar Cloud'      => 'basic_elaboration_calendar_cloud.svg',
		'Basic Elaboration Calendar Download'   => 'basic_elaboration_calendar_download.svg',
		'Basic Elaboration Calendar Empty'      => 'basic_elaboration_calendar_empty.svg',
		'Basic Elaboration Calendar Flagged'    => 'basic_elaboration_calendar_flagged.svg',
		'Basic Elaboration Calendar Heart'      => 'basic_elaboration_calendar_heart.svg',
		'Basic Elaboration Calendar Minus'      => 'basic_elaboration_calendar_minus.svg',
		'Basic Elaboration Calendar Next'       => 'basic_elaboration_calendar_next.svg',
		'Basic Elaboration Calendar Noaccess'   => 'basic_elaboration_calendar_noaccess.svg',
		'Basic Elaboration Calendar Pencil'     => 'basic_elaboration_calendar_pencil.svg',
		'Basic Elaboration Calendar Plus'       => 'basic_elaboration_calendar_plus.svg',
		'Basic Elaboration Calendar Previous'   => 'basic_elaboration_calendar_previous.svg',
		'Basic Elaboration Calendar Refresh'    => 'basic_elaboration_calendar_refresh.svg',
		'Basic Elaboration Calendar Remove'     => 'basic_elaboration_calendar_remove.svg',
		'Basic Elaboration Calendar Search'     => 'basic_elaboration_calendar_search.svg',
		'Basic Elaboration Calendar Star'       => 'basic_elaboration_calendar_star.svg',
		'Basic Elaboration Calendar Upload'     => 'basic_elaboration_calendar_upload.svg',
		'Basic Elaboration Cloud Check'         => 'basic_elaboration_cloud_check.svg',
		'Basic Elaboration Cloud Download'      => 'basic_elaboration_cloud_download.svg',
		'Basic Elaboration Cloud Minus'         => 'basic_elaboration_cloud_minus.svg',
		'Basic Elaboration Cloud Noaccess'      => 'basic_elaboration_cloud_noaccess.svg',
		'Basic Elaboration Cloud Plus'          => 'basic_elaboration_cloud_plus.svg',
		'Basic Elaboration Cloud Refresh'       => 'basic_elaboration_cloud_refresh.svg',
		'Basic Elaboration Cloud Remove'        => 'basic_elaboration_cloud_remove.svg',
		'Basic Elaboration Cloud Search'        => 'basic_elaboration_cloud_search.svg',
		'Basic Elaboration Cloud Upload'        => 'basic_elaboration_cloud_upload.svg',
		'Basic Elaboration Document Check'      => 'basic_elaboration_document_check.svg',
		'Basic Elaboration Document Cloud'      => 'basic_elaboration_document_cloud.svg',
		'Basic Elaboration Document Download'   => 'basic_elaboration_document_download.svg',
		'Basic Elaboration Document Flagged'    => 'basic_elaboration_document_flagged.svg',
		'Basic Elaboration Document Graph'      => 'basic_elaboration_document_graph.svg',
		'Basic Elaboration Document Heart'      => 'basic_elaboration_document_heart.svg',
		'Basic Elaboration Document Minus'      => 'basic_elaboration_document_minus.svg',
		'Basic Elaboration Document Next'       => 'basic_elaboration_document_next.svg',
		'Basic Elaboration Document Noaccess'   => 'basic_elaboration_document_noaccess.svg',
		'Basic Elaboration Document Note'       => 'basic_elaboration_document_note.svg',
		'Basic Elaboration Document Pencil'     => 'basic_elaboration_document_pencil.svg',
		'Basic Elaboration Document Picture'    => 'basic_elaboration_document_picture.svg',
		'Basic Elaboration Document Plus'       => 'basic_elaboration_document_plus.svg',
		'Basic Elaboration Document Previous'   => 'basic_elaboration_document_previous.svg',
		'Basic Elaboration Document Refresh'    => 'basic_elaboration_document_refresh.svg',
		'Basic Elaboration Document Remove'     => 'basic_elaboration_document_remove.svg',
		'Basic Elaboration Document Search'     => 'basic_elaboration_document_search.svg',
		'Basic Elaboration Document Star'       => 'basic_elaboration_document_star.svg',
		'Basic Elaboration Document Upload'     => 'basic_elaboration_document_upload.svg',
		'Basic Elaboration Folder Check'        => 'basic_elaboration_folder_check.svg',
		'Basic Elaboration Folder Cloud'        => 'basic_elaboration_folder_cloud.svg',
		'Basic Elaboration Folder Document'     => 'basic_elaboration_folder_document.svg',
		'Basic Elaboration Folder Download'     => 'basic_elaboration_folder_download.svg',
		'Basic Elaboration Folder Flagged'      => 'basic_elaboration_folder_flagged.svg',
		'Basic Elaboration Folder Graph'        => 'basic_elaboration_folder_graph.svg',
		'Basic Elaboration Folder Heart'        => 'basic_elaboration_folder_heart.svg',
		'Basic Elaboration Folder Minus'        => 'basic_elaboration_folder_minus.svg',
		'Basic Elaboration Folder Next'         => 'basic_elaboration_folder_next.svg',
		'Basic Elaboration Folder Noaccess'     => 'basic_elaboration_folder_noaccess.svg',
		'Basic Elaboration Folder Note'         => 'basic_elaboration_folder_note.svg',
		'Basic Elaboration Folder Pencil'       => 'basic_elaboration_folder_pencil.svg',
		'Basic Elaboration Folder Picture'      => 'basic_elaboration_folder_picture.svg',
		'Basic Elaboration Folder Plus'         => 'basic_elaboration_folder_plus.svg',
		'Basic Elaboration Folder Previous'     => 'basic_elaboration_folder_previous.svg',
		'Basic Elaboration Folder Refresh'      => 'basic_elaboration_folder_refresh.svg',
		'Basic Elaboration Folder Remove'       => 'basic_elaboration_folder_remove.svg',
		'Basic Elaboration Folder Search'       => 'basic_elaboration_folder_search.svg',
		'Basic Elaboration Folder Star'         => 'basic_elaboration_folder_star.svg',
		'Basic Elaboration Folder Upload'       => 'basic_elaboration_folder_upload.svg',
		'Basic Elaboration Mail Check'          => 'basic_elaboration_mail_check.svg',
		'Basic Elaboration Mail Cloud'          => 'basic_elaboration_mail_cloud.svg',
		'Basic Elaboration Mail Document'       => 'basic_elaboration_mail_document.svg',
		'Basic Elaboration Mail Download'       => 'basic_elaboration_mail_download.svg',
		'Basic Elaboration Mail Flagged'        => 'basic_elaboration_mail_flagged.svg',
		'Basic Elaboration Mail Heart'          => 'basic_elaboration_mail_heart.svg',
		'Basic Elaboration Mail Next'           => 'basic_elaboration_mail_next.svg',
		'Basic Elaboration Mail Noaccess'       => 'basic_elaboration_mail_noaccess.svg',
		'Basic Elaboration Mail Note'           => 'basic_elaboration_mail_note.svg',
		'Basic Elaboration Mail Pencil'         => 'basic_elaboration_mail_pencil.svg',
		'Basic Elaboration Mail Picture'        => 'basic_elaboration_mail_picture.svg',
		'Basic Elaboration Mail Previous'       => 'basic_elaboration_mail_previous.svg',
		'Basic Elaboration Mail Refresh'        => 'basic_elaboration_mail_refresh.svg',
		'Basic Elaboration Mail Remove'         => 'basic_elaboration_mail_remove.svg',
		'Basic Elaboration Mail Search'         => 'basic_elaboration_mail_search.svg',
		'Basic Elaboration Mail Star'           => 'basic_elaboration_mail_star.svg',
		'Basic Elaboration Mail Upload'         => 'basic_elaboration_mail_upload.svg',
		'Basic Elaboration Message Check'       => 'basic_elaboration_message_check.svg',
		'Basic Elaboration Message Dots'        => 'basic_elaboration_message_dots.svg',
		'Basic Elaboration Message Happy'       => 'basic_elaboration_message_happy.svg',
		'Basic Elaboration Message Heart'       => 'basic_elaboration_message_heart.svg',
		'Basic Elaboration Message Minus'       => 'basic_elaboration_message_minus.svg',
		'Basic Elaboration Message Note'        => 'basic_elaboration_message_note.svg',
		'Basic Elaboration Message Plus'        => 'basic_elaboration_message_plus.svg',
		'Basic Elaboration Message Refresh'     => 'basic_elaboration_message_refresh.svg',
		'Basic Elaboration Message Remove'      => 'basic_elaboration_message_remove.svg',
		'Basic Elaboration Message Sad'         => 'basic_elaboration_message_sad.svg',
		'Basic Elaboration Smartphone Cloud'    => 'basic_elaboration_smartphone_cloud.svg',
		'Basic Elaboration Smartphone Heart'    => 'basic_elaboration_smartphone_heart.svg',
		'Basic Elaboration Smartphone Noaccess' => 'basic_elaboration_smartphone_noaccess.svg',
		'Basic Elaboration Smartphone Note'     => 'basic_elaboration_smartphone_note.svg',
		'Basic Elaboration Smartphone Pencil'   => 'basic_elaboration_smartphone_pencil.svg',
		'Basic Elaboration Smartphone Picture'  => 'basic_elaboration_smartphone_picture.svg',
		'Basic Elaboration Smartphone Refresh'  => 'basic_elaboration_smartphone_refresh.svg',
		'Basic Elaboration Smartphone Search'   => 'basic_elaboration_smartphone_search.svg',
		'Basic Elaboration Tablet Cloud'        => 'basic_elaboration_tablet_cloud.svg',
		'Basic Elaboration Tablet Heart'        => 'basic_elaboration_tablet_heart.svg',
		'Basic Elaboration Tablet Noaccess'     => 'basic_elaboration_tablet_noaccess.svg',
		'Basic Elaboration Tablet Note'         => 'basic_elaboration_tablet_note.svg',
		'Basic Elaboration Tablet Pencil'       => 'basic_elaboration_tablet_pencil.svg',
		'Basic Elaboration Tablet Picture'      => 'basic_elaboration_tablet_picture.svg',
		'Basic Elaboration Tablet Refresh'      => 'basic_elaboration_tablet_refresh.svg',
		'Basic Elaboration Tablet Search'       => 'basic_elaboration_tablet_search.svg',
		'Basic Elaboration Todolist 2'          => 'basic_elaboration_todolist_2.svg',
		'Basic Elaboration Todolist Check'      => 'basic_elaboration_todolist_check.svg',
		'Basic Elaboration Todolist Cloud'      => 'basic_elaboration_todolist_cloud.svg',
		'Basic Elaboration Todolist Download'   => 'basic_elaboration_todolist_download.svg',
		'Basic Elaboration Todolist Flagged'    => 'basic_elaboration_todolist_flagged.svg',
		'Basic Elaboration Todolist Minus'      => 'basic_elaboration_todolist_minus.svg',
		'Basic Elaboration Todolist Noaccess'   => 'basic_elaboration_todolist_noaccess.svg',
		'Basic Elaboration Todolist Pencil'     => 'basic_elaboration_todolist_pencil.svg',
		'Basic Elaboration Todolist Plus'       => 'basic_elaboration_todolist_plus.svg',
		'Basic Elaboration Todolist Refresh'    => 'basic_elaboration_todolist_refresh.svg',
		'Basic Elaboration Todolist Remove'     => 'basic_elaboration_todolist_remove.svg',
		'Basic Elaboration Todolist Search'     => 'basic_elaboration_todolist_search.svg',
		'Basic Elaboration Todolist Star'       => 'basic_elaboration_todolist_star.svg',
		'Basic Elaboration Todolist Upload'     => 'basic_elaboration_todolist_upload.svg',
		'Basic Exclamation'                     => 'basic_exclamation.svg',
		'Basic Eye'                             => 'basic_eye.svg',
		'Basic Eye Closed'                      => 'basic_eye_closed.svg',
		'Basic Female'                          => 'basic_female.svg',
		'Basic Flag1'                           => 'basic_flag1.svg',
		'Basic Flag2'                           => 'basic_flag2.svg',
		'Basic Floppydisk'                      => 'basic_floppydisk.svg',
		'Basic Folder'                          => 'basic_folder.svg',
		'Basic Folder Multiple'                 => 'basic_folder_multiple.svg',
		'Basic Gear'                            => 'basic_gear.svg',
		'Basic Geolocalize-01'                  => 'basic_geolocalize-01.svg',
		'Basic Geolocalize-05'                  => 'basic_geolocalize-05.svg',
		'Basic Globe'                           => 'basic_globe.svg',
		'Basic Gunsight'                        => 'basic_gunsight.svg',
		'Basic Hammer'                          => 'basic_hammer.svg',
		'Basic Headset'                         => 'basic_headset.svg',
		'Basic Heart'                           => 'basic_heart.svg',
		'Basic Heart Broken'                    => 'basic_heart_broken.svg',
		'Basic Helm'                            => 'basic_helm.svg',
		'Basic Home'                            => 'basic_home.svg',
		'Basic Info'                            => 'basic_info.svg',
		'Basic Ipod'                            => 'basic_ipod.svg',
		'Basic Joypad'                          => 'basic_joypad.svg',
		'Basic Key'                             => 'basic_key.svg',
		'Basic Keyboard'                        => 'basic_keyboard.svg',
		'Basic Laptop'                          => 'basic_laptop.svg',
		'Basic Life Buoy'                       => 'basic_life_buoy.svg',
		'Basic Lightbulb'                       => 'basic_lightbulb.svg',
		'Basic Link'                            => 'basic_link.svg',
		'Basic Lock'                            => 'basic_lock.svg',
		'Basic Lock Open'                       => 'basic_lock_open.svg',
		'Basic Magic Mouse'                     => 'basic_magic_mouse.svg',
		'Basic Magnifier'                       => 'basic_magnifier.svg',
		'Basic Magnifier Minus'                 => 'basic_magnifier_minus.svg',
		'Basic Magnifier Plus'                  => 'basic_magnifier_plus.svg',
		'Basic Mail'                            => 'basic_mail.svg',
		'Basic Mail Multiple'                   => 'basic_mail_multiple.svg',
		'Basic Mail Open'                       => 'basic_mail_open.svg',
		'Basic Mail Open Text'                  => 'basic_mail_open_text.svg',
		'Basic Male'                            => 'basic_male.svg',
		'Basic Map'                             => 'basic_map.svg',
		'Basic Message'                         => 'basic_message.svg',
		'Basic Message Multiple'                => 'basic_message_multiple.svg',
		'Basic Message Txt'                     => 'basic_message_txt.svg',
		'Basic Mixer2'                          => 'basic_mixer2.svg',
		'Basic Mouse'                           => 'basic_mouse.svg',
		'Basic Notebook'                        => 'basic_notebook.svg',
		'Basic Notebook Pen'                    => 'basic_notebook_pen.svg',
		'Basic Notebook Pencil'                 => 'basic_notebook_pencil.svg',
		'Basic Paperplane'                      => 'basic_paperplane.svg',
		'Basic Pencil Ruler'                    => 'basic_pencil_ruler.svg',
		'Basic Pencil Ruler Pen '               => 'basic_pencil_ruler_pen .svg',
		'Basic Photo'                           => 'basic_photo.svg',
		'Basic Picture'                         => 'basic_picture.svg',
		'Basic Picture Multiple'                => 'basic_picture_multiple.svg',
		'Basic Pin1'                            => 'basic_pin1.svg',
		'Basic Pin2'                            => 'basic_pin2.svg',
		'Basic Postcard'                        => 'basic_postcard.svg',
		'Basic Postcard Multiple'               => 'basic_postcard_multiple.svg',
		'Basic Printer'                         => 'basic_printer.svg',
		'Basic Question'                        => 'basic_question.svg',
		'Basic Rss'                             => 'basic_rss.svg',
		'Basic Server'                          => 'basic_server.svg',
		'Basic Server2'                         => 'basic_server2.svg',
		'Basic Server Cloud'                    => 'basic_server_cloud.svg',
		'Basic Server Download'                 => 'basic_server_download.svg',
		'Basic Server Upload'                   => 'basic_server_upload.svg',
		'Basic Settings'                        => 'basic_settings.svg',
		'Basic Share'                           => 'basic_share.svg',
		'Basic Sheet'                           => 'basic_sheet.svg',
		'Basic Sheet Multiple '                 => 'basic_sheet_multiple .svg',
		'Basic Sheet Pen'                       => 'basic_sheet_pen.svg',
		'Basic Sheet Pencil'                    => 'basic_sheet_pencil.svg',
		'Basic Sheet Txt '                      => 'basic_sheet_txt .svg',
		'Basic Signs'                           => 'basic_signs.svg',
		'Basic Smartphone'                      => 'basic_smartphone.svg',
		'Basic Spades'                          => 'basic_spades.svg',
		'Basic Spread'                          => 'basic_spread.svg',
		'Basic Spread Bookmark'                 => 'basic_spread_bookmark.svg',
		'Basic Spread Text'                     => 'basic_spread_text.svg',
		'Basic Spread Text Bookmark'            => 'basic_spread_text_bookmark.svg',
		'Basic Star'                            => 'basic_star.svg',
		'Basic Tablet'                          => 'basic_tablet.svg',
		'Basic Target'                          => 'basic_target.svg',
		'Basic Todo'                            => 'basic_todo.svg',
		'Basic Todo Pen '                       => 'basic_todo_pen .svg',
		'Basic Todo Pencil'                     => 'basic_todo_pencil.svg',
		'Basic Todo Txt'                        => 'basic_todo_txt.svg',
		'Basic Todolist Pen'                    => 'basic_todolist_pen.svg',
		'Basic Todolist Pencil'                 => 'basic_todolist_pencil.svg',
		'Basic Trashcan'                        => 'basic_trashcan.svg',
		'Basic Trashcan Full'                   => 'basic_trashcan_full.svg',
		'Basic Trashcan Refresh'                => 'basic_trashcan_refresh.svg',
		'Basic Trashcan Remove'                 => 'basic_trashcan_remove.svg',
		'Basic Upload'                          => 'basic_upload.svg',
		'Basic Usb'                             => 'basic_usb.svg',
		'Basic Video'                           => 'basic_video.svg',
		'Basic Watch'                           => 'basic_watch.svg',
		'Basic Webpage'                         => 'basic_webpage.svg',
		'Basic Webpage Img Txt'                 => 'basic_webpage_img_txt.svg',
		'Basic Webpage Multiple'                => 'basic_webpage_multiple.svg',
		'Basic Webpage Txt'                     => 'basic_webpage_txt.svg',
		'Basic World'                           => 'basic_world.svg',
		'Ecommerce Bag'                         => 'ecommerce_bag.svg',
		'Ecommerce Bag Check'                   => 'ecommerce_bag_check.svg',
		'Ecommerce Bag Cloud'                   => 'ecommerce_bag_cloud.svg',
		'Ecommerce Bag Download'                => 'ecommerce_bag_download.svg',
		'Ecommerce Bag Minus'                   => 'ecommerce_bag_minus.svg',
		'Ecommerce Bag Plus'                    => 'ecommerce_bag_plus.svg',
		'Ecommerce Bag Refresh'                 => 'ecommerce_bag_refresh.svg',
		'Ecommerce Bag Remove'                  => 'ecommerce_bag_remove.svg',
		'Ecommerce Bag Search'                  => 'ecommerce_bag_search.svg',
		'Ecommerce Bag Upload'                  => 'ecommerce_bag_upload.svg',
		'Ecommerce Banknote'                    => 'ecommerce_banknote.svg',
		'Ecommerce Banknotes'                   => 'ecommerce_banknotes.svg',
		'Ecommerce Basket'                      => 'ecommerce_basket.svg',
		'Ecommerce Basket Check'                => 'ecommerce_basket_check.svg',
		'Ecommerce Basket Cloud'                => 'ecommerce_basket_cloud.svg',
		'Ecommerce Basket Download'             => 'ecommerce_basket_download.svg',
		'Ecommerce Basket Minus'                => 'ecommerce_basket_minus.svg',
		'Ecommerce Basket Plus'                 => 'ecommerce_basket_plus.svg',
		'Ecommerce Basket Refresh'              => 'ecommerce_basket_refresh.svg',
		'Ecommerce Basket Remove'               => 'ecommerce_basket_remove.svg',
		'Ecommerce Basket Search'               => 'ecommerce_basket_search.svg',
		'Ecommerce Basket Upload'               => 'ecommerce_basket_upload.svg',
		'Ecommerce Bath'                        => 'ecommerce_bath.svg',
		'Ecommerce Cart'                        => 'ecommerce_cart.svg',
		'Ecommerce Cart Check'                  => 'ecommerce_cart_check.svg',
		'Ecommerce Cart Cloud'                  => 'ecommerce_cart_cloud.svg',
		'Ecommerce Cart Content'                => 'ecommerce_cart_content.svg',
		'Ecommerce Cart Download'               => 'ecommerce_cart_download.svg',
		'Ecommerce Cart Minus'                  => 'ecommerce_cart_minus.svg',
		'Ecommerce Cart Plus'                   => 'ecommerce_cart_plus.svg',
		'Ecommerce Cart Refresh'                => 'ecommerce_cart_refresh.svg',
		'Ecommerce Cart Remove'                 => 'ecommerce_cart_remove.svg',
		'Ecommerce Cart Search'                 => 'ecommerce_cart_search.svg',
		'Ecommerce Cart Upload'                 => 'ecommerce_cart_upload.svg',
		'Ecommerce Cent'                        => 'ecommerce_cent.svg',
		'Ecommerce Colon'                       => 'ecommerce_colon.svg',
		'Ecommerce Creditcard'                  => 'ecommerce_creditcard.svg',
		'Ecommerce Diamond'                     => 'ecommerce_diamond.svg',
		'Ecommerce Dollar'                      => 'ecommerce_dollar.svg',
		'Ecommerce Euro'                        => 'ecommerce_euro.svg',
		'Ecommerce Franc'                       => 'ecommerce_franc.svg',
		'Ecommerce Gift'                        => 'ecommerce_gift.svg',
		'Ecommerce Graph1'                      => 'ecommerce_graph1.svg',
		'Ecommerce Graph2'                      => 'ecommerce_graph2.svg',
		'Ecommerce Graph3'                      => 'ecommerce_graph3.svg',
		'Ecommerce Graph Decrease'              => 'ecommerce_graph_decrease.svg',
		'Ecommerce Graph Increase'              => 'ecommerce_graph_increase.svg',
		'Ecommerce Guarani'                     => 'ecommerce_guarani.svg',
		'Ecommerce Kips'                        => 'ecommerce_kips.svg',
		'Ecommerce Lira'                        => 'ecommerce_lira.svg',
		'Ecommerce Megaphone'                   => 'ecommerce_megaphone.svg',
		'Ecommerce Money'                       => 'ecommerce_money.svg',
		'Ecommerce Naira'                       => 'ecommerce_naira.svg',
		'Ecommerce Pesos'                       => 'ecommerce_pesos.svg',
		'Ecommerce Pound'                       => 'ecommerce_pound.svg',
		'Ecommerce Receipt'                     => 'ecommerce_receipt.svg',
		'Ecommerce Receipt Bath'                => 'ecommerce_receipt_bath.svg',
		'Ecommerce Receipt Cent'                => 'ecommerce_receipt_cent.svg',
		'Ecommerce Receipt Dollar'              => 'ecommerce_receipt_dollar.svg',
		'Ecommerce Receipt Euro'                => 'ecommerce_receipt_euro.svg',
		'Ecommerce Receipt Franc'               => 'ecommerce_receipt_franc.svg',
		'Ecommerce Receipt Guarani'             => 'ecommerce_receipt_guarani.svg',
		'Ecommerce Receipt Kips'                => 'ecommerce_receipt_kips.svg',
		'Ecommerce Receipt Lira'                => 'ecommerce_receipt_lira.svg',
		'Ecommerce Receipt Naira'               => 'ecommerce_receipt_naira.svg',
		'Ecommerce Receipt Pesos'               => 'ecommerce_receipt_pesos.svg',
		'Ecommerce Receipt Pound'               => 'ecommerce_receipt_pound.svg',
		'Ecommerce Receipt Rublo'               => 'ecommerce_receipt_rublo.svg',
		'Ecommerce Receipt Rupee'               => 'ecommerce_receipt_rupee.svg',
		'Ecommerce Receipt Tugrik'              => 'ecommerce_receipt_tugrik.svg',
		'Ecommerce Receipt Won'                 => 'ecommerce_receipt_won.svg',
		'Ecommerce Receipt Yen'                 => 'ecommerce_receipt_yen.svg',
		'Ecommerce Receipt Yen2'                => 'ecommerce_receipt_yen2.svg',
		'Ecommerce Recept Colon'                => 'ecommerce_recept_colon.svg',
		'Ecommerce Rublo'                       => 'ecommerce_rublo.svg',
		'Ecommerce Rupee'                       => 'ecommerce_rupee.svg',
		'Ecommerce Safe'                        => 'ecommerce_safe.svg',
		'Ecommerce Sale'                        => 'ecommerce_sale.svg',
		'Ecommerce Sales'                       => 'ecommerce_sales.svg',
		'Ecommerce Ticket'                      => 'ecommerce_ticket.svg',
		'Ecommerce Tugriks'                     => 'ecommerce_tugriks.svg',
		'Ecommerce Wallet'                      => 'ecommerce_wallet.svg',
		'Ecommerce Won'                         => 'ecommerce_won.svg',
		'Ecommerce Yen'                         => 'ecommerce_yen.svg',
		'Ecommerce Yen2'                        => 'ecommerce_yen2.svg',
		'Music Beginning Button'                => 'music_beginning_button.svg',
		'Music Bell'                            => 'music_bell.svg',
		'Music Cd'                              => 'music_cd.svg',
		'Music Diapason'                        => 'music_diapason.svg',
		'Music Eject Button'                    => 'music_eject_button.svg',
		'Music End Button'                      => 'music_end_button.svg',
		'Music Fastforward Button'              => 'music_fastforward_button.svg',
		'Music Headphones'                      => 'music_headphones.svg',
		'Music Ipod'                            => 'music_ipod.svg',
		'Music Loudspeaker'                     => 'music_loudspeaker.svg',
		'Music Microphone'                      => 'music_microphone.svg',
		'Music Microphone Old'                  => 'music_microphone_old.svg',
		'Music Mixer'                           => 'music_mixer.svg',
		'Music Mute'                            => 'music_mute.svg',
		'Music Note Multiple'                   => 'music_note_multiple.svg',
		'Music Note Single'                     => 'music_note_single.svg',
		'Music Pause Button'                    => 'music_pause_button.svg',
		'Music Play Button'                     => 'music_play_button.svg',
		'Music Playlist'                        => 'music_playlist.svg',
		'Music Radio Ghettoblaster'             => 'music_radio_ghettoblaster.svg',
		'Music Radio Portable'                  => 'music_radio_portable.svg',
		'Music Record'                          => 'music_record.svg',
		'Music Recordplayer'                    => 'music_recordplayer.svg',
		'Music Repeat Button'                   => 'music_repeat_button.svg',
		'Music Rewind Button'                   => 'music_rewind_button.svg',
		'Music Shuffle Button'                  => 'music_shuffle_button.svg',
		'Music Stop Button'                     => 'music_stop_button.svg',
		'Music Tape'                            => 'music_tape.svg',
		'Music Volume Down'                     => 'music_volume_down.svg',
		'Music Volume Up'                       => 'music_volume_up.svg',
		'Software Add Vectorpoint'              => 'software_add_vectorpoint.svg',
		'Software Box Oval'                     => 'software_box_oval.svg',
		'Software Box Polygon'                  => 'software_box_polygon.svg',
		'Software Box Rectangle'                => 'software_box_rectangle.svg',
		'Software Box Roundedrectangle'         => 'software_box_roundedrectangle.svg',
		'Software Character'                    => 'software_character.svg',
		'Software Crop'                         => 'software_crop.svg',
		'Software Eyedropper'                   => 'software_eyedropper.svg',
		'Software Font Allcaps'                 => 'software_font_allcaps.svg',
		'Software Font Baseline Shift'          => 'software_font_baseline_shift.svg',
		'Software Font Horizontal Scale'        => 'software_font_horizontal_scale.svg',
		'Software Font Kerning'                 => 'software_font_kerning.svg',
		'Software Font Leading'                 => 'software_font_leading.svg',
		'Software Font Size'                    => 'software_font_size.svg',
		'Software Font Smallcapital'            => 'software_font_smallcapital.svg',
		'Software Font Smallcaps'               => 'software_font_smallcaps.svg',
		'Software Font Strikethrough'           => 'software_font_strikethrough.svg',
		'Software Font Tracking'                => 'software_font_tracking.svg',
		'Software Font Underline'               => 'software_font_underline.svg',
		'Software Font Vertical Scale'          => 'software_font_vertical_scale.svg',
		'Software Horizontal Align Center'      => 'software_horizontal_align_center.svg',
		'Software Horizontal Align Left'        => 'software_horizontal_align_left.svg',
		'Software Horizontal Align Right'       => 'software_horizontal_align_right.svg',
		'Software Horizontal Distribute Center' => 'software_horizontal_distribute_center.svg',
		'Software Horizontal Distribute Left'   => 'software_horizontal_distribute_left.svg',
		'Software Horizontal Distribute Right'  => 'software_horizontal_distribute_right.svg',
		'Software Indent Firstline'             => 'software_indent_firstline.svg',
		'Software Indent Left'                  => 'software_indent_left.svg',
		'Software Indent Right'                 => 'software_indent_right.svg',
		'Software Lasso'                        => 'software_lasso.svg',
		'Software Layers1'                      => 'software_layers1.svg',
		'Software Layers2'                      => 'software_layers2.svg',
		'Software Layout-8boxes'                => 'software_layout-8boxes.svg',
		'Software Layout'                       => 'software_layout.svg',
		'Software Layout 2columns'              => 'software_layout_2columns.svg',
		'Software Layout 3columns'              => 'software_layout_3columns.svg',
		'Software Layout 4boxes'                => 'software_layout_4boxes.svg',
		'Software Layout 4columns'              => 'software_layout_4columns.svg',
		'Software Layout 4lines'                => 'software_layout_4lines.svg',
		'Software Layout Header'                => 'software_layout_header.svg',
		'Software Layout Header 2columns'       => 'software_layout_header_2columns.svg',
		'Software Layout Header 3columns'       => 'software_layout_header_3columns.svg',
		'Software Layout Header 4boxes'         => 'software_layout_header_4boxes.svg',
		'Software Layout Header 4columns'       => 'software_layout_header_4columns.svg',
		'Software Layout Header Complex'        => 'software_layout_header_complex.svg',
		'Software Layout Header Complex2'       => 'software_layout_header_complex2.svg',
		'Software Layout Header Complex3'       => 'software_layout_header_complex3.svg',
		'Software Layout Header Complex4'       => 'software_layout_header_complex4.svg',
		'Software Layout Header Sideleft'       => 'software_layout_header_sideleft.svg',
		'Software Layout Header Sideright'      => 'software_layout_header_sideright.svg',
		'Software Layout Sidebar Left'          => 'software_layout_sidebar_left.svg',
		'Software Layout Sidebar Right'         => 'software_layout_sidebar_right.svg',
		'Software Magnete'                      => 'software_magnete.svg',
		'Software Pages'                        => 'software_pages.svg',
		'Software Paintbrush'                   => 'software_paintbrush.svg',
		'Software Paintbucket'                  => 'software_paintbucket.svg',
		'Software Paintroller'                  => 'software_paintroller.svg',
		'Software Paragraph'                    => 'software_paragraph.svg',
		'Software Paragraph Align Left'         => 'software_paragraph_align_left.svg',
		'Software Paragraph Align Right'        => 'software_paragraph_align_right.svg',
		'Software Paragraph Center'             => 'software_paragraph_center.svg',
		'Software Paragraph Justify All'        => 'software_paragraph_justify_all.svg',
		'Software Paragraph Justify Center'     => 'software_paragraph_justify_center.svg',
		'Software Paragraph Justify Left'       => 'software_paragraph_justify_left.svg',
		'Software Paragraph Justify Right'      => 'software_paragraph_justify_right.svg',
		'Software Paragraph Space After'        => 'software_paragraph_space_after.svg',
		'Software Paragraph Space Before'       => 'software_paragraph_space_before.svg',
		'Software Pathfinder Exclude'           => 'software_pathfinder_exclude.svg',
		'Software Pathfinder Intersect'         => 'software_pathfinder_intersect.svg',
		'Software Pathfinder Subtract'          => 'software_pathfinder_subtract.svg',
		'Software Pathfinder Unite'             => 'software_pathfinder_unite.svg',
		'Software Pen'                          => 'software_pen.svg',
		'Software Pen Add'                      => 'software_pen_add.svg',
		'Software Pen Remove'                   => 'software_pen_remove.svg',
		'Software Pencil'                       => 'software_pencil.svg',
		'Software Polygonallasso'               => 'software_polygonallasso.svg',
		'Software Reflect Horizontal'           => 'software_reflect_horizontal.svg',
		'Software Reflect Vertical'             => 'software_reflect_vertical.svg',
		'Software Remove Vectorpoint'           => 'software_remove_vectorpoint.svg',
		'Software Scale Expand'                 => 'software_scale_expand.svg',
		'Software Scale Reduce'                 => 'software_scale_reduce.svg',
		'Software Selection Oval'               => 'software_selection_oval.svg',
		'Software Selection Polygon'            => 'software_selection_polygon.svg',
		'Software Selection Rectangle'          => 'software_selection_rectangle.svg',
		'Software Selection Roundedrectangle'   => 'software_selection_roundedrectangle.svg',
		'Software Shape Oval'                   => 'software_shape_oval.svg',
		'Software Shape Polygon'                => 'software_shape_polygon.svg',
		'Software Shape Rectangle'              => 'software_shape_rectangle.svg',
		'Software Shape Roundedrectangle'       => 'software_shape_roundedrectangle.svg',
		'Software Slice'                        => 'software_slice.svg',
		'Software Transform Bezier'             => 'software_transform_bezier.svg',
		'Software Vector Box'                   => 'software_vector_box.svg',
		'Software Vector Composite'             => 'software_vector_composite.svg',
		'Software Vector Line'                  => 'software_vector_line.svg',
		'Software Vertical Align Bottom'        => 'software_vertical_align_bottom.svg',
		'Software Vertical Align Center'        => 'software_vertical_align_center.svg',
		'Software Vertical Align Top'           => 'software_vertical_align_top.svg',
		'Software Vertical Distribute Bottom'   => 'software_vertical_distribute_bottom.svg',
		'Software Vertical Distribute Center'   => 'software_vertical_distribute_center.svg',
		'Software Vertical Distribute Top'      => 'software_vertical_distribute_top.svg',
		'Weather Aquarius'                      => 'weather_aquarius.svg',
		'Weather Aries'                         => 'weather_aries.svg',
		'Weather Cancer'                        => 'weather_cancer.svg',
		'Weather Capricorn'                     => 'weather_capricorn.svg',
		'Weather Cloud'                         => 'weather_cloud.svg',
		'Weather Cloud Drop'                    => 'weather_cloud_drop.svg',
		'Weather Cloud Lightning'               => 'weather_cloud_lightning.svg',
		'Weather Cloud Snowflake'               => 'weather_cloud_snowflake.svg',
		'Weather Downpour Fullmoon'             => 'weather_downpour_fullmoon.svg',
		'Weather Downpour Halfmoon'             => 'weather_downpour_halfmoon.svg',
		'Weather Downpour Sun'                  => 'weather_downpour_sun.svg',
		'Weather Drop'                          => 'weather_drop.svg',
		'Weather First Quarter '                => 'weather_first_quarter .svg',
		'Weather Fog'                           => 'weather_fog.svg',
		'Weather Fog Fullmoon'                  => 'weather_fog_fullmoon.svg',
		'Weather Fog Halfmoon'                  => 'weather_fog_halfmoon.svg',
		'Weather Fog Sun'                       => 'weather_fog_sun.svg',
		'Weather Fullmoon'                      => 'weather_fullmoon.svg',
		'Weather Gemini'                        => 'weather_gemini.svg',
		'Weather Hail'                          => 'weather_hail.svg',
		'Weather Hail Fullmoon'                 => 'weather_hail_fullmoon.svg',
		'Weather Hail Halfmoon'                 => 'weather_hail_halfmoon.svg',
		'Weather Hail Sun'                      => 'weather_hail_sun.svg',
		'Weather Last Quarter'                  => 'weather_last_quarter.svg',
		'Weather Leo'                           => 'weather_leo.svg',
		'Weather Libra'                         => 'weather_libra.svg',
		'Weather Lightning'                     => 'weather_lightning.svg',
		'Weather Mistyrain'                     => 'weather_mistyrain.svg',
		'Weather Mistyrain Fullmoon'            => 'weather_mistyrain_fullmoon.svg',
		'Weather Mistyrain Halfmoon'            => 'weather_mistyrain_halfmoon.svg',
		'Weather Mistyrain Sun'                 => 'weather_mistyrain_sun.svg',
		'Weather Moon'                          => 'weather_moon.svg',
		'Weather Moondown Full'                 => 'weather_moondown_full.svg',
		'Weather Moondown Half'                 => 'weather_moondown_half.svg',
		'Weather Moonset Full'                  => 'weather_moonset_full.svg',
		'Weather Moonset Half'                  => 'weather_moonset_half.svg',
		'Weather Move2'                         => 'weather_move2.svg',
		'Weather Newmoon'                       => 'weather_newmoon.svg',
		'Weather Pisces'                        => 'weather_pisces.svg',
		'Weather Rain'                          => 'weather_rain.svg',
		'Weather Rain Fullmoon'                 => 'weather_rain_fullmoon.svg',
		'Weather Rain Halfmoon'                 => 'weather_rain_halfmoon.svg',
		'Weather Rain Sun'                      => 'weather_rain_sun.svg',
		'Weather Sagittarius'                   => 'weather_sagittarius.svg',
		'Weather Scorpio'                       => 'weather_scorpio.svg',
		'Weather Snow'                          => 'weather_snow.svg',
		'Weather Snow Fullmoon'                 => 'weather_snow_fullmoon.svg',
		'Weather Snow Halfmoon'                 => 'weather_snow_halfmoon.svg',
		'Weather Snow Sun'                      => 'weather_snow_sun.svg',
		'Weather Snowflake'                     => 'weather_snowflake.svg',
		'Weather Star'                          => 'weather_star.svg',
		'Weather Storm-11'                      => 'weather_storm-11.svg',
		'Weather Storm-32'                      => 'weather_storm-32.svg',
		'Weather Storm Fullmoon'                => 'weather_storm_fullmoon.svg',
		'Weather Storm Halfmoon'                => 'weather_storm_halfmoon.svg',
		'Weather Storm Sun'                     => 'weather_storm_sun.svg',
		'Weather Sun'                           => 'weather_sun.svg',
		'Weather Sundown'                       => 'weather_sundown.svg',
		'Weather Sunset'                        => 'weather_sunset.svg',
		'Weather Taurus'                        => 'weather_taurus.svg',
		'Weather Tempest'                       => 'weather_tempest.svg',
		'Weather Tempest Fullmoon'              => 'weather_tempest_fullmoon.svg',
		'Weather Tempest Halfmoon'              => 'weather_tempest_halfmoon.svg',
		'Weather Tempest Sun'                   => 'weather_tempest_sun.svg',
		'Weather Variable Fullmoon'             => 'weather_variable_fullmoon.svg',
		'Weather Variable Halfmoon'             => 'weather_variable_halfmoon.svg',
		'Weather Variable Sun'                  => 'weather_variable_sun.svg',
		'Weather Virgo'                         => 'weather_virgo.svg',
		'Weather Waning Cresent'                => 'weather_waning_cresent.svg',
		'Weather Waning Gibbous'                => 'weather_waning_gibbous.svg',
		'Weather Waxing Cresent'                => 'weather_waxing_cresent.svg',
		'Weather Waxing Gibbous'                => 'weather_waxing_gibbous.svg',
		'Weather Wind'                          => 'weather_wind.svg',
		'Weather Wind E'                        => 'weather_wind_E.svg',
		'Weather Wind N'                        => 'weather_wind_N.svg',
		'Weather Wind NE'                       => 'weather_wind_NE.svg',
		'Weather Wind NW'                       => 'weather_wind_NW.svg',
		'Weather Wind S'                        => 'weather_wind_S.svg',
		'Weather Wind SE'                       => 'weather_wind_SE.svg',
		'Weather Wind SW'                       => 'weather_wind_SW.svg',
		'Weather Wind W'                        => 'weather_wind_W.svg',
		'Weather Wind Fullmoon'                 => 'weather_wind_fullmoon.svg',
		'Weather Wind Halfmoon'                 => 'weather_wind_halfmoon.svg',
		'Weather Wind Sun'                      => 'weather_wind_sun.svg',
		'Weather Windgust'                      => 'weather_windgust.svg',
	);
	if ( $empty ) {
		$icons = array( 'Empty' => '' ) + $icons;
	}
	return $icons;
}
// Thb Header Subscribe
function thb_header_subscribe() {
	$header_subscribe = ot_get_option( 'header_subscribe', 'on' );

	if ( 'off' === $header_subscribe ) {
		return;
	}
	?>
	<a href="#newsletter-popup" class="thb-header-subscribe" rel="inline" data-class="newsletter"><?php get_template_part( 'assets/img/svg/subscribe.svg' ); ?> <?php esc_html_e( 'SUBSCRIBE NOW', 'north' ); ?></a>
	<?php
}
add_action( 'thb_header_subscribe', 'thb_header_subscribe' );

/* Thb Mobile Toggle */
function thb_mobile_toggle() {
	?>
	<div class="mobile-toggle-holder <?php echo esc_attr( ot_get_option( 'mobile_menu_icon_style', 'style1' ) ); ?>">
		<div class="mobile-toggle">
			<span></span><span></span><span></span>
		</div>
		<?php if ( 'on' === ot_get_option( 'mobile_menu_text', 'off' ) ) { ?>
			<strong>
				<span class="menu-label"><?php esc_html_e( 'Menu', 'north' ); ?></span>
			</strong>
		<?php } ?>
	</div>
	<?php
}
add_action( 'thb_mobile_toggle', 'thb_mobile_toggle', 3 );

// Thb Secondary Area.
function thb_secondary_area() {
	$header_secondary_style = ot_get_option( 'header_secondary_style', 'style1' );

	if ( 'style1' === $header_secondary_style ) {
		if ( has_nav_menu( 'acc-menu-in' ) && is_user_logged_in() ) {
			wp_nav_menu(
				array(
					'theme_location' => 'acc-menu-in',
					'depth'          => 2,
					'container'      => false,
					'menu_class'     => 'secondary-menu',
				)
			);
		} elseif ( has_nav_menu( 'acc-menu-out' ) && ! is_user_logged_in() ) {
			wp_nav_menu(
				array(
					'theme_location' => 'acc-menu-out',
					'depth'          => 2,
					'container'      => false,
					'menu_class'     => 'secondary-menu',
				)
			);
		}
		if ( 'on' === ot_get_option( 'thb_ls', 'on' ) ) {
			do_action( 'thb_language_switcher' );
		}
	} else {
		if ( 'on' === ot_get_option( 'thb_ls', 'on' ) ) {
			do_action( 'thb_language_switcher' );
		}
		if ( 'on' === ot_get_option( 'header_myaccount', 'on' ) ) {
			do_action( 'thb_quick_profile' );
		}
	}
	do_action( 'thb_quick_search', $header_secondary_style );
	do_action( 'thb_quick_cart', $header_secondary_style );
}
add_action( 'thb_secondary_area', 'thb_secondary_area' );

// Thb Header Search.
function thb_quick_search( $header_secondary_style ) {
	if ( 'on' === ot_get_option( 'header_search', 'on' ) ) {
		?>
		<a href="#searchpopup" rel="inline" data-class="quick-search" id="quick_search">
			<?php
			if ( 'style1' === $header_secondary_style ) {
				esc_html_e( 'Search', 'north' );
			} else {
				get_template_part( 'assets/img/svg/header-search.svg' );
			}
			?>
		</a>

		<?php
		function thb_add_searchform() {
			?>
		<aside id="searchpopup" class="mfp-hide" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_autocomplete_ajax' ) ); ?>">
			<div class="thb-close-text"><?php esc_html_e( 'PRESS ESC TO CLOSE', 'north' ); ?></div>
			<div class="row align-center">
				<div class="small-12 medium-8 columns">
				<?php
				if ( thb_wc_supported() ) {
					if ( ! defined( 'YITH_WCAS' ) ) {
						get_product_search_form();
					} else {
						echo do_shortcode( '[yith_woocommerce_ajax_search]' );
					}
				} else {
					get_search_form();
				}
				?>
					<div class="thb-autocomplete-wrapper"></div>
				</div>
			</div>
		</aside>
			<?php
		}
		add_action( 'wp_footer', 'thb_add_searchform', 100 );
	}
}
add_action( 'thb_quick_search', 'thb_quick_search', 1, 1 );

/* Thb Newsletter Popup */
function thb_newsletter() {
	if ( ! is_admin() && ( ot_get_option( 'newsletter' ) === 'on' || ( ot_get_option( 'header_style' ) === 'style5' && ot_get_option( 'header_subscribe', 'on' ) ) ) ) {
		$newsletter_content        = ot_get_option( 'newsletter_content' );
		$newsletter_form           = ot_get_option( 'newsletter_form', 'theme-form' );
		$newsletter_image          = ot_get_option( 'newsletter_image' );
		$newsletter_form_shortcode = ot_get_option( 'newsletter_form_shortcode' );
		?>
		<aside id="newsletter-popup" class="mfp-hide theme-popup newsletter-popup">
			<?php
			if ( $newsletter_image ) {
				$image = wp_get_attachment_image_src( $newsletter_image, 'full' );
				?>
				<figure class="newsletter-image" style="background-image: url(<?php echo esc_attr( $image[0] ); ?>)"></figure>
			<?php } ?>
			<div class="newsletter-content">
				<?php
				if ( $newsletter_content ) {
					echo do_shortcode( $newsletter_content ); }
				?>
				<?php if ( 'theme-form' === $newsletter_form ) { ?>
					<div class="thb_subscribe">
						<form id="newsletter-form" class="newsletter-form" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_subscription' ) ); ?>">
							<input type="text" name="email" id="widget_subscribe" class="full widget_subscribe" placeholder="<?php esc_attr_e( 'Your e-mail address', 'north' ); ?>">
							<input type="submit" name="submit" class="btn" value="<?php esc_attr_e( 'Subscribe Now', 'north' ); ?>" />
						</form>
						<?php do_action( 'thb_after_newsletter_form' ); ?>
					</div>
					<?php
				} else {
					echo do_shortcode( $newsletter_form_shortcode ); }
				?>
			</div>
		</aside>
		<?php
	}
}
add_action( 'wp_footer', 'thb_newsletter' );

add_action( 'wp_ajax_ajax_action', 'thb_newsletter' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_ajax_action', 'thb_newsletter' ); // ajax for not logged in users

/* Newsletter */
function thb_after_newsletter_form() {
	$newsletter_privacy_checkbox = ot_get_option( 'newsletter_privacy_checkbox', 'on' );

	$rand = wp_rand( 0, 1000 );
	if ( 'on' === $newsletter_privacy_checkbox ) {
		$newsletter_privacy_checkbox_checked = ot_get_option( 'newsletter_privacy_checkbox_checked', 'on' );
		?>
		<div class="thb-custom-checkbox">
			<input type="checkbox" id="thb-newsletter-privacy-<?php echo esc_attr( $rand ); ?>" name="thb-newsletter-privacy" class="thb-newsletter-privacy" <?php checked( 'on', $newsletter_privacy_checkbox_checked, true ); ?>>
			<label for="thb-newsletter-privacy-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e( 'I would like to receive news and special offers.', 'north' ); ?></label>
		</div>
		<?php
	}
}
add_filter( 'thb_after_newsletter_form', 'thb_after_newsletter_form' );

/* THB Social Icons */
function thb_social() {
	?>
	<?php if ( ot_get_option( 'fb_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'fb_link' ); ?>" target="_blank" class="facebook icon-1x"><i class="fa fa-facebook"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'pinterest_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'pinterest_link' ); ?>" target="_blank" class="pinterest icon-1x"><i class="fa fa-pinterest"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'twitter_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'twitter_link' ); ?>" target="_blank" class="twitter icon-1x"><i class="fa fa-twitter"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'linkedin_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'linkedin_link' ); ?>" target="_blank" class="linkedin icon-1x"><i class="fa fa-linkedin"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'instragram_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'instragram_link' ); ?>" target="_blank" class="instagram icon-1x"><i class="fa fa-instagram"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'yt_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'yt_link' ); ?>" target="_blank" class="youtube icon-1x"><i class="fa fa-youtube"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'xing_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'xing_link' ); ?>" target="_blank" class="xing icon-1x"><i class="fa fa-xing"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'tumblr_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'tumblr_link' ); ?>" target="_blank" class="tumblr icon-1x"><i class="fa fa-tumblr"></i></a>
	<?php } ?>
	<?php if ( ot_get_option( 'vk_link' ) ) { ?>
	<a href="<?php echo ot_get_option( 'vk_link' ); ?>" target="_blank" class="vk icon-1x"><i class="fa fa-vk"></i></a>
	<?php } ?>
	<?php
}
add_action( 'thb_social', 'thb_social', 3 );

// Move Array Keys.
function thb_moveKeyBefore( $arr, $find, $move ) {
	if ( ! isset( $arr[ $find ], $arr[ $move ] ) ) {
		return $arr;
	} else {
		$elem  = array( $move => $arr[ $move ] );
		$start = array_splice( $arr, 0, array_search( $find, array_keys( $arr ) ) );
		return $start + $elem + $arr;
	}
}

/* Footer Social Icons */
function thb_footer_social() {
	$footer_social_icons = ot_get_option( 'footer_social_icons' );

	if ( ! empty( $footer_social_icons ) ) {
		?>
	<div class="footer-social-icons">
		<?php foreach ( $footer_social_icons as $social ) { ?>
		<a href="<?php echo esc_attr( $social['href'] ); ?>" class="social <?php echo esc_attr( $social['social_network'] ); ?>" target="_blank"><i class="fa fa-<?php echo esc_attr( $social['social_network'] ); ?>"></i></a>
	<?php } ?>
	</div>
		<?php
	}
}
add_action( 'thb_footer_social', 'thb_footer_social' );

/* Custom Password Protect Form */
function thb_password_form() {
	$o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
	<p class="password-text">' . esc_html__( 'This is a protected area. Please enter your password:', 'north' ) . '</p>
	<input name="post_password" type="password" class="box" placeholder="' . esc_html__( 'Password', 'north' ) . '"/><input type="submit" name="Submit" class="btn" value="' . esc_attr__( 'Submit', 'north' ) . '" /></form>
	';
	return $o;
}
add_filter( 'the_password_form', 'thb_password_form' );

/* Payment Icons */
function thb_footer_payment() {
	$footer_payment_icons        = ot_get_option( 'footer_payment_icons', array() );
	$footer_payment_icons_custom = ot_get_option( 'footer_payment_icons_custom', array() );

	if ( ! count( $footer_payment_icons ) && ! count( $footer_payment_icons_custom ) ) {
		return;
	}
	?>
	<div class="footer-payment-icons">
		<?php
		if ( count( $footer_payment_icons ) ) {
			foreach ( $footer_payment_icons as $payment ) {
				?>
			<figure class="paymenttypes <?php echo esc_attr( $payment['payment_type'] ); ?>"><?php get_template_part( 'assets/img/svg/payment-types/' . $payment['payment_type'] . '.svg' ); ?></figure>
				<?php
			}
		}
		?>
		<?php
		if ( count( $footer_payment_icons_custom ) ) {
			foreach ( $footer_payment_icons_custom as $payment ) {
				?>
				<figure class="paymenttypes thb-custom-payment-icon thb-<?php echo esc_attr( sanitize_title_with_dashes( $payment['title'] ) ); ?>"><img src="<?php echo esc_attr( $payment['icon_image'] ); ?>" alt="<?php echo esc_attr( $payment['title'] ); ?>" /></figure>
				<?php
			}
		}
		?>
	</div>
	<?php
}
add_action( 'thb_footer_payment', 'thb_footer_payment', 3 );

/* Swiper Navigation */
function thb_arrow_nav() {
	if ( ot_get_option( 'revslider_arrows', 'on' ) === 'on' ) {
		?>
		<div class="thb-cursor-area left">
			<?php get_template_part( 'assets/img/svg/thb-arrow-left.svg' ); ?>

		</div>
		<div class="thb-cursor-area right">
			<?php get_template_part( 'assets/img/svg/thb-arrow-right.svg' ); ?>
		</div>
		<?php
	}
}
add_action( 'thb_arrow_nav', 'thb_arrow_nav', 10 );

/* Post Categories Array */
function thb_blogCategories() {
	$blog_categories = get_categories();
	$out             = array();
	foreach ( $blog_categories as $category ) {
		$out[ $category->name ] = $category->cat_ID;
	}
	return $out;
}

/* Product Categories Array */
function thb_productCategories() {
	if ( thb_wc_supported() ) {
		$cache_name = 'thb_wc_product_categories';

		// Get Cache
		$data = get_transient( $cache_name );

		if ( $data ) {
			return $data;
		}
		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => '0',
		);

		$product_categories = get_terms( 'product_cat', $args );
		$out                = array();
		if ( ! isset( $product_categories->errors ) ) {
			foreach ( $product_categories as $product_category ) {
				$out[ $product_category->name ] = $product_category->slug;
			}
		}
		// Set Cache
		set_transient( $cache_name, $out, HOUR_IN_SECONDS );
		return $out;
	}
}


/* Human time */
function thb_human_time_diff_enhanced( $duration = 60 ) {

	$post_time  = get_the_time( 'U' );
	$human_time = '';

	$time_now = gmdate( 'U' );

	// use human time if less that $duration days ago (60 days by default)
	// 60 seconds * 60 minutes * 24 hours * $duration days
	if ( $post_time > $time_now - ( 60 * 60 * 24 * $duration ) ) {
		$human_time = sprintf( __( '%s ago', 'north' ), human_time_diff( $post_time, current_time( 'timestamp' ) ) );
	} else {
		$human_time = get_the_date();
	}

	return $human_time;

}
/* Blog Pagination */
function thb_blog_pagination() {
	$blog_pagination_style = ot_get_option( 'blog_pagination_style', 'style1' );

	if ( 'style1' === $blog_pagination_style || is_archive() ) {
		?>
		<div class="row align-center">
			<div class="small-12 medium-10 large-9 columns">
				<?php
				the_posts_pagination(
					array(
						'prev_text' => '<span>&larr; ' . esc_html__( 'Prev', 'north' ) . '</span>',
						'next_text' => '<span>' . esc_html__( 'Next', 'north' ) . ' &rarr;</span>',
						'mid_size'  => 1,
					)
				);
				?>
			</div>
		</div>
		<?php
	} elseif ( 'style2' === $blog_pagination_style ) {
		?>
	<div class="row pagination-space">
		<div class="small-12 columns text-center">
			<a href="#" class="thb_load_more btn" title="<?php esc_attr_e( 'Load More', 'north' ); ?>"><?php esc_html_e( 'Load More', 'north' ); ?></a>
		</div>
	</div>
		<?php
	}
}
add_action( 'thb_blog_pagination', 'thb_blog_pagination', 3 );

/* Sharing */
function thb_share() {
	$id           = get_the_ID();
	$permalink    = get_permalink( $id );
	$title        = the_title_attribute(
		array(
			'echo' => 0,
			'post' => $id,
		)
	);
	$image_id     = get_post_thumbnail_id( $id );
	$image        = wp_get_attachment_image_src( $image_id, 'full' );
	$twitter_user = ot_get_option( 'twitter_bar_username', 'anteksiler' );
	$sharing_type = ot_get_option( 'sharing_buttons' ) ? ot_get_option( 'sharing_buttons' ) : array();

	if ( ! empty( $sharing_type ) ) {
		?>
	<aside class="share-article">
		<a class="thb_share">
			<?php get_template_part( 'assets/img/svg/share.svg' ); ?>
			<?php esc_html_e( 'Share This', 'north' ); ?>
		</a>
		<div class="icons">
			<div class="inner">
				<?php if ( in_array( 'facebook', $sharing_type ) ) { ?>
				<a href="<?php echo 'http://www.facebook.com/sharer.php?u=' . urlencode( esc_url( $permalink ) ) . ''; ?>" class="facebook social"><i class="fa fa-facebook"></i></a>
				<?php } ?>
				<?php if ( in_array( 'twitter', $sharing_type ) ) { ?>
				<a href="<?php echo 'https://twitter.com/intent/tweet?text=' . htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( esc_url( $permalink ) ) . '&via=' . esc_attr( get_bloginfo( 'name' ) ) . ''; ?>" class="twitter social "><i class="fa fa-twitter"></i></a>
				<?php } ?>
				<?php if ( in_array( 'pinterest', $sharing_type ) ) { ?>
				<a href="<?php echo 'http://pinterest.com/pin/create/link/?url=' . esc_url( $permalink ) . '&amp;media=' . ( ! empty( $image[0] ) ? esc_url( $image[0] ) : '' ) . ''; ?>" class="pinterest social" nopin="nopin" data-pin-no-hover="true"><i class="fa fa-pinterest"></i></a>
				<?php } ?>
				<?php if ( in_array( 'vk', $sharing_type ) ) { ?>
				<a href="<?php echo 'http://vkontakte.ru/share.php?url=' . esc_url( $permalink ); ?>" class="vk social"><i class="fa fa-vk"></i></a>
				<?php } ?>
				<?php if ( in_array( 'linkedin', $sharing_type ) ) { ?>
				<a href="<?php echo 'https://www.linkedin.com/cws/share?url=' . esc_url( $permalink ) . ''; ?>" class="linkedin social"><i class="fa fa-linkedin"></i></a>
				<?php } ?>
				<?php if ( in_array( 'whatsapp', $sharing_type ) ) { ?>
				<a href="<?php echo 'whatsapp://send?text=' . htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . ' ' . esc_url( $permalink ); ?>" class="whatsapp social" data-href="<?php echo esc_url( $permalink ); ?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i></a>
				<?php } ?>
			</div>
		</div>
	</aside>
		<?php
	}
}
add_action( 'thb_share', 'thb_share' );

/* Product Category Grid Sizes */
function thb_get_product_cat_grid_size( $style, $i ) {
	if ( 'style1' === $style ) {
		switch ( $i ) {
			case 1:
			case 11:
			case 21:
				$article_size = 'medium-8';
				break;
			case 3:
			case 13:
			case 23:
				$article_size = 'medium-4 double-height';
				break;
			default:
				$article_size = 'medium-4 grid-sizer';
				break;
		}
	} elseif ( 'style2' === $style ) {

		switch ( $i ) {
			case 1:
			case 13:
				$article_size = 'medium-6';
				break;
			case 2:
			case 4:
			case 5:
			case 6:
			case 9:
			case 8:
			case 10:
			case 11:
			case 14:
			case 15:
			default:
				$article_size = 'medium-3 grid-sizer';
				break;
			case 3:
			case 7:
			case 12:
				$article_size = 'medium-3';
				break;
		}
	} elseif ( 'style3' === $style ) {

		switch ( $i ) {
			case 1:
			case 2:
			case 6:
			case 7:
			case 11:
			case 12:
				$article_size = 'medium-6';
				break;
			case 3:
			case 4:
			case 5:
			default:
				$article_size = 'medium-4';
				break;
		}
	} elseif ( 'style4' === $style ) {

		switch ( $i ) {
			case 1:
			case 6:
			case 7:
			case 10:
			case 11:
				$article_size = 'medium-8 large-size';
				break;
			case 2:
			case 3:
			case 4:
			case 8:
			case 9:
			case 12:
			case 13:
			default:
				$article_size = 'medium-4 grid-sizer';
				break;
			case 5:
				$article_size = 'medium-8 wide-size';
				break;
		}
	} elseif ( 'style5' === $style ) {

		switch ( $i ) {
			case 1:
				$article_size = 'medium-6 wide-size';
				break;
			default:
				$article_size = 'medium-3 grid-sizer';
				break;
			case 3:
				$article_size = 'medium-3 tall-size';
				break;
		}
	}
	return $article_size;
}
/* Remove VC-added P tags */
function thb_remove_vc_added_p( $content ) {
	if ( substr( $content, 0, 4 ) === '</p>' ) {
		$content = substr( $content, 4 );
	}
	if ( substr( $content, -3 ) === '<p>' ) {
		$content = substr( $content, 0, -3 );
	}
	return $content;
}

/* Remove Empty P tags */
function thb_remove_p( $content ) {
	$to_remove = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']',
	);

	$content = strtr( $content, $to_remove );
	return $content;
}

add_filter( 'the_content', 'thb_remove_p' );

/**
 * Shorten large numbers into abbreviations (i.e. 1,500 = 1.5k)
 *
 * @param int $number  Number to shorten
 * @return String   A number with a symbol
 */
function thb_numberAbbreviation( $number ) {
		$abbrevs = array(
			12 => 'T',
			9  => 'B',
			6  => 'M',
			3  => 'K',
			0  => '',
		);

		if ( $number > 999 ) {
			foreach ( $abbrevs as $exponent => $abbrev ) {
				if ( $number >= pow( 10, $exponent ) ) {
					$display_num = $number / pow( 10, $exponent );
					$decimals    = ( $exponent >= 3 && round( $display_num ) < 100 ) ? 1 : 0;
						return number_format( $display_num, $decimals ) . $abbrev;
				}
			}
		} else {
			return $number;
		}
}

/* Portfolio Prev/Next */
function thb_post_nav() {
	if ( 'on' !== ot_get_option( 'blog_nav', 'on' ) ) {
		return; }
	$prev = get_previous_post();
	$next = get_next_post();
	?>
	<div class="thb_post_nav">
		<div class="row full-width-row">
			<div class="small-4 medium-5 columns">
				<?php
				if ( $prev ) {
					?>
					<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post_nav_link prev">
					<?php get_template_part( 'assets/img/svg/prev_arrow.svg' ); ?>
						<strong>
						<?php esc_html_e( 'Previous Article', 'north' ); ?>
						</strong>
						<span><?php echo esc_html( $prev->post_title ); ?></span>
					</a>
					<?php
				}
				?>
			</div>
			<div class="small-3 medium-2 columns center_link">
				<?php do_action( 'thb_share' ); ?>
			</div>
			<div class="small-4 medium-5 columns">
				<?php
				if ( $next ) {
					?>
					<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post_nav_link next">
						<?php get_template_part( 'assets/img/svg/next_arrow.svg' ); ?>
						<strong>
							<?php esc_html_e( 'Next Article', 'north' ); ?>
						</strong>
						<span><?php echo esc_html( $next->post_title ); ?></span>

					</a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'thb_post_nav', 'thb_post_nav' );

/* Cookie Bar */
function thb_add_cookiebar() {
	get_template_part( 'inc/templates/header/cookie-bar' );
}
add_action( 'wp_footer', 'thb_add_cookiebar', 10 );

/* WooCommerce Check */
function thb_wc_supported() {
	return class_exists( 'WooCommerce' );
}
function thb_is_woocommerce() {
	if ( ! thb_wc_supported() ) {
		return false;
	}
	return ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
}
/* Load Template */
function thb_load_template_part( $template_name ) {
		ob_start();
		get_template_part( $template_name );
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
}
/* Add Shortcode */
function thb_add_short( $name, $call ) {

	$func = 'add' . '_shortcode';
	return $func( $name, $call );

}

/* Encoding */
function thb_encode( $value ) {

	$func = 'base64' . '_encode';
	return $func( $value );

}
function thb_decode( $value ) {

	$func = 'base64' . '_decode';
	return $func( $value );

}

function thb_footer_items() {
	get_template_part( 'inc/templates/header/mobile-menu' );

	do_action( 'thb_side_cart' );

	do_action( 'thb_shop_filters' );

	?>
	<div class="click-capture"></div>
	<?php
	// Single Product Quick View.
	if ( 'on' === ot_get_option( 'shop_product_quickview', 'on' ) ) {
		?>
		<div class="thb-quickview-wrapper">
			<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
			<div class="thb-quickview-content"></div>
		</div>
		<?php
	}
}
add_action( 'thb_wrapper_end', 'thb_footer_items' );

/* DNS Prefetch */
function thb_dns_prefetch() {
	echo '<meta http-equiv="x-dns-prefetch-control" content="on">
	<link rel="dns-prefetch" href="//fonts.googleapis.com" />
	<link rel="dns-prefetch" href="//fonts.gstatic.com" />
	<link rel="dns-prefetch" href="//0.gravatar.com/" />
	<link rel="dns-prefetch" href="//2.gravatar.com/" />
	<link rel="dns-prefetch" href="//1.gravatar.com/" />';
}
add_action( 'wp_head', 'thb_dns_prefetch', 0 );

// Page Content.
function thb_page_content( $page ) {
	if ( $page ) {
		$args = array(
			'page_id'     => $page,
			'post_status' => 'publish',
		);

		$page_query = new WP_Query( $args );

		if ( $page_query->have_posts() ) :
			while ( $page_query->have_posts() ) :
				$page_query->the_post();
				the_content();
			endwhile;
		endif;

		echo '<style>';
		echo get_post_meta( $page, '_wpb_shortcodes_custom_css', true );
		echo '</style>';

		wp_reset_query();
	}
}
add_action( 'thb_page_content', 'thb_page_content', 99, 1 );

/* FOOTER TYPE EDIT */
function thb_footer_admin() {
	echo sprintf(
		esc_html__( 'Thank you for choosing %1$sFuel Themes%2$s', 'north' ),
		'<a href="https://wpthemestudios.com/?ref=wp_footer" target="blank">',
		'</a>'
	);
}
add_filter( 'admin_footer_text', 'thb_footer_admin' );
