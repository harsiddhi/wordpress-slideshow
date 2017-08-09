var ajaxurl = admin_ajax_object.ajaxurl;
var ajax_state;
jQuery(document).ready(function( jQuery ) {

    /* Add some script related to shorting */
    jQuery( "#sortable" ).sortable({
        connectWith: ".connectedSortable",
        change: function( event, ui ) {
            var id = ui.item.attr("id");
        },
        stop: function( event, ui ) {
            var new_order_array = [];
            jQuery(".images_lists li").each(function(key,value){
                new_order_array.push(jQuery(this).data("img"));
                jQuery(this).find('span').text(key+1);
            });

            jQuery(".img_container .slideshow_image_order").val(JSON.stringify(new_order_array));

        }
    }).disableSelection();


    /*Lets save new order of image here with ajax*/
    ajax_state = jQuery(".slideshow_images .img_container .save_slides_order").click(function(event){
        event.preventDefault();
        var new_slide_order = jQuery(".img_container .slideshow_image_order").val();
        var handle = jQuery(this);

        /*Stop another ajax request if previous ajax is not completed.*/
        if(ajax_state !== undefined && ajax_state.readyState < 4){
            return false;
        }
        jQuery(".slideshow_images .img_container .loading_image_show span").text("Reordering...");
        jQuery(".slideshow_images .img_container .loading_image_show").show();
        ajax_state = jQuery.ajax({
            type: "POST",
            url:  ajaxurl,
            data: {
                new_slide_order: new_slide_order,
                action:"new_slide_order_ajax"
            },
            success:function( response ){
                jQuery(".slideshow_images .img_container .loading_image_show span").text("Done!");
                setTimeout(function(){jQuery(".slideshow_images .img_container .loading_image_show").fadeOut();}, 2000);
                if(response !==undefined){
                    var response = jQuery.parseJSON(response);
                    if(response.error !== undefined){
                        alert(response.error);
                    }
                    if(response.success !== undefined){

                    }
                }

            },
            error:function( response ){
                jQuery(".slideshow_images .img_container .loading_image_show span").text("Done!");
                setTimeout(function(){jQuery(".slideshow_images .img_container .loading_image_show").fadeOut();}, 2000);
                alert("There is some problem.");
            }

        });
    });
});



jQuery(document).ready(function( jQuery ) {
    jQuery(".wrapImage").click(function(event){
        event.preventDefault();
        var slide_id = jQuery(this).data("id");
        var handle = jQuery(this);
        if(ajax_state !== undefined && ajax_state.readyState < 4){
            return false;
        }
        ajax_state = jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                slide_id: slide_id,
                action:"remove_current_slide"
            },
            success:function( response ){
                if(response !==undefined){
                    var response = jQuery.parseJSON(response);
                    if(response.error !== undefined){
                        alert(response.error);
                    }if(response.success !== undefined){
                        handle.parents("li").remove();
                       // handle.remove("li").remove();
                      // handle.remove(".removeClass");
                        jQuery(".images_lists li").each(function(key,value){
                            jQuery(this).find('span').text(key+1);
                        })
                    }
                }

            },
            error:function( response ){
                alert(response);
            }

        });





        });

});