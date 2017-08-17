<?php
add_action( 'admin_menu', 'wp_slideshow_create_menu' );
// Set Flex slider's menu.
function wp_slideshow_create_menu() {
	// create new top-level menu.
	add_menu_page( 'slideshow Settings', 'slideshow Settings', 'administrator', __FILE__, 'wp_slideshow_settings_page' );
}

/**
 * function is for create slideshow settings
 * @function Name:wp_slideshow_settings_page
 *
 * @param : none
 *
 * @return : none
 */
function wp_slideshow_settings_page() {
	if ( isset( $_POST['create_sideshow'] ) && wp_verify_nonce( $_POST['slideshow_image_upload_nonce'], 'slideshow_images' ) ):
		$slideshow_images = $_FILES['slideshow_images'];
		// include dependency.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		foreach ( $slideshow_images['name'] as $key => $value ) {

			if ( $slideshow_images['name'][ $key ] ) {
				$file = array(
					'name'     => $slideshow_images['name'][ $key ],
					'type'     => $slideshow_images['type'][ $key ],
					'tmp_name' => $slideshow_images['tmp_name'][ $key ],
					'error'    => $slideshow_images['error'][ $key ],
					'size'     => $slideshow_images['size'][ $key ]
				);

				$_FILES                 = array( "slideshow_images_attachments" => $file );
				$slideshow_images_array = array();
				$attach_id              = media_handle_upload( 'slideshow_images_attachments', 0 );
				if ( ! is_wp_error( $attach_id ) ) {
					// There was an error uploading the image.
					$slideshow_images_array = get_option( "slideshow_images_array", true );
					if ( ! empty( $slideshow_images_array ) && is_array( $slideshow_images_array ) ) {
						$slideshow_images_array[] = $attach_id;
					} else {
						$slideshow_images_array = array( $attach_id );
					}
					update_option( "slideshow_images_array", $slideshow_images_array );

				} else {
					// The image was uploaded successfully.
				}
			}
		}
	endif;
	?>
    <div class='container wrap'>
        <h1>Slideshow Setting Page</h1>
        <h2>If u want to add slideshow in page please use [front_slide_show] short code.</h2>
        <h2>you can also change image order and save it .</h2>
        <h2>You can change order of slides by dragging up and down.</h2>

        <form method="post" style='border: 2px solid #ccc;width: 300px;padding: 10px;' enctype="multipart/form-data">
            <label>
                Add Images:
                <input type="file" required name='slideshow_images[]' multiple/>
            </label>
			<?php wp_nonce_field( 'slideshow_images', 'slideshow_image_upload_nonce' ); ?>
            <label><input type='submit' class='button-primary' name='create_sideshow' value='Submit'/></label>
        </form>
        <div class='slideshow_images'>
            <div class='wrap_img'>
				<?php
				/**
				 * Show slides_images
				 * */
				$slideshow_images_array = get_option( "slideshow_images_array", true );
				if ( ! empty( $slideshow_images_array ) && is_array( $slideshow_images_array ) ):
					echo "<ul class='images_lists connectedSortable' id='sortable'>";
					foreach ( $slideshow_images_array as $slide_key => $slide_value ) {
						echo "<li id='item-" . $slide_key . "' data-img='" . $slide_value . "' style='list-style: none;float:left;margin-left:40px' class='removeClass'>";
						echo "<span style='border: 1px solid #ccc;  float:left;padding: 3px;background-color: black;color: white;font-size: 16px;'>" . ( $slide_key + 1 ) . "</span>";
						echo '<img class="wrapImage" src="' . esc_url( plugins_url( 'img/remove.png', dirname( __FILE__ ) ) ) . '" style="width:20px;height:20px" data-id="' . $slide_value . '" > ';
						echo wp_get_attachment_image( $slide_value, 'thumbnail' );
						echo "</li>";

					}
					echo "</ul>";
				endif;
				?>
            </div>
			<?php if ( ! empty( $slideshow_images_array ) && is_array( $slideshow_images_array ) ): ?>
                <div class='img_container'>
					<?php
					$slideshow_images_array = get_option( "slideshow_images_array", true );
					if ( ! is_array( $slideshow_images_array ) ):
						$slideshow_images_array = array();
					endif;
					?>
                    <input type='hidden' class='slideshow_image_order'
                           value='<?php echo json_encode( $slideshow_images_array ); ?>'>
                    <a href='#' class='save_slides_order btn button button-large button-primary'>Save Order</a>
                    <div class='loading_image_show'>
                        <span style='margin: 10px;font-size: 12px;font-weight: bold;color: green;'>Reordering...</span>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
}

/**
 * function is for remove slides
 * @function Name:remove_current_slide_callback
 *
 * @param : none
 *
 */
function remove_current_slide_callback() {
	$slide_id = $_POST['slide_id'];
	$response = array();
	if ( ! empty( $slide_id ) ):
		$slideshow_images_array = get_option( "slideshow_images_array", true );

		if ( ( $key = array_search( $slide_id, $slideshow_images_array ) ) !== false ) {
			unset( $slideshow_images_array[ $key ] );
			$slideshow_images_array = array_values( $slideshow_images_array );
			update_option( "slideshow_images_array", $slideshow_images_array );

			$response['success'] = "removed";
		} else {
			$response['error'] = "Something went wrong. please try again.";
		}
	endif;
	die( json_encode( $response ) );
}

/**
 * function is for order slides
 * @function Name:new_slide_order_ajax_callback
 *
 * @param : none
 *
 */
function new_slide_order_ajax_callback() {
	$new_slide_order = $_POST['new_slide_order'];
	$response        = array();
	if ( ! empty( $new_slide_order ) ):
		update_option( "slideshow_images_array", json_decode( $new_slide_order ) );
		$response['success'] = "changed";
	else:
		$response['error'] = "Something went wrong. please try again.";
	endif;
	die( json_encode( $response ) );
}

