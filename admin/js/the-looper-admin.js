(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    document.addEventListener( 'DOMContentLoaded', function(){
        // function forceLower(strInput) {
        //     return  strInput.value.toLowerCase();
        // }​
        var dashicon = document.querySelectorAll('.dashicons-col');
        for ( var i = 0; i < dashicon.length; i++ ) {
            dashicon[i].addEventListener('click', function(e){
                
                let input = document.querySelector('input[name="the_looper_manage_cpt[menu_icon]"]');
                let modal = document.querySelector('#postTypeIconPicker');
                let old_value = input.value;
                let icon_class = this.getAttribute('icon-class');

                input.value = icon_class;

                if (old_value !== input.value ) {
                    $('#postTypeIconPicker').modal('hide');
                }
            });
        }
        
        var post_slug_input;
        var taxonomies_slug_input;
        
        
        if (document.querySelector('input[name="the_looper_manage_cpt[slug]"]')) {
            post_slug_input = document.querySelector('input[name="the_looper_manage_cpt[slug]"]');
            if (post_slug_input.value != '' ) {
                $('<span class="d-none alert alert-danger mt-3 slug-changed">Slug has changed</span>').insertAfter(post_slug_input);
                post_slug_input.oninput = function(){
                    $('.slug-changed').removeClass('d-none');
                    $('.slug-changed').addClass('d-inline-block');
                    $('input[name="migrate"]').prop('checked',true);
                }
            }
            post_slug_input.onkeyup = function(){
                this.value = this.value.replace(/[^a-z0-9\s]/ig, '_').replace(' ', '_').toLowerCase();
            }
        }
        if (document.querySelector('input[name="the_looper_manage_taxonomies[slug]"]')) {
            taxonomies_slug_input = document.querySelector('input[name="the_looper_manage_taxonomies[slug]"]');
            if (taxonomies_slug_input.value != '' ) {
                $('<span class="d-none alert alert-danger mt-3 slug-changed">Slug has changed</span>').insertAfter(taxonomies_slug_input);
                taxonomies_slug_input.oninput = function(){
                    $('.slug-changed').removeClass('d-none');
                    $('.slug-changed').addClass('d-inline-block');
                    $('input[name="migrate"]').prop('checked',true);
                }
            }
            taxonomies_slug_input.onkeyup = function(){
                this.value = this.value.replace(/[^a-z0-9\s]/ig, '_').replace(' ', '_').toLowerCase();
            }
        }
        
    });

    $(document).ready(function(){

        var mediaUploader,attachment;

        $( '#upload_post_type_icon' ).on( 'click', function(e) {
            e.preventDefault();

            if ( mediaUploader ) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Upload Image Icon',
                button: {
                    text: 'Choose Picture',
                },
                multiple:false
            });

            mediaUploader.on('select', function(){
                attachment = mediaUploader.state().get('selection').first().toJSON();
                $('input[name="the_looper_manage_cpt[menu_icon]"]').val(attachment.url);
            });

            mediaUploader.open();
        });
        
        // Add Color Picker to all inputs that have 'color-field' class
        $('.color-field').wpColorPicker();
        
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();
    });

})( jQuery );
