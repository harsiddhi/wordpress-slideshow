<?php
add_shortcode( "front_slide_show", 'slideshow_front_view' );
/**
 * slideshow_front_view display slideshow
 * @function Name:slideshow_front_view
 *
 * @param  none
 *
 * @return image slider html
 */
function slideshow_front_view() {
	ob_start();
	$slideshow_images_array = get_option( "slideshow_images_array", true );
	if ( ! empty( $slideshow_images_array ) ):
		/*echo"<div class='flex-container'>";*/
		echo "<div class='flexslider'>";
		echo "<ul class='slides' style='list-style:none'>";
		foreach ( $slideshow_images_array as $key => $slide_item ) {
			echo "<li>" . wp_get_attachment_image( $slide_item, 'full' ) . "</li>";

		}
		echo "</ul>";
		echo "</div>";
		/*		echo "</div>";*/

		?>
        <script>

            jQuery(document).ready(function () {
                jQuery('.flexslider').flexslider({
                    animation: "slide"
                    //  controlNav: "thumbnails"
                });
            });
        </script>

		<?php
	endif;
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

