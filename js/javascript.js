/**
 * @package   RW Blog Umzug
 * @author    Joachim Happel
 * @license   GPL-2.0+
 * @link      https://github.com/rpi-virtuell/rw-blog-umzug
 */


//create a jquery plugin and protect the $ alias and add scope
(function ( $ ) {


    //@TODO create jquery plugins (this is an example)
    //@see https://learn.jquery.com/plugins/basic-plugin-creation/


    //Add a single Dom Elem after the selector, if it didn't exists
    $.fn.rw_addDom = function( id , tag, type ){
        tag = tag || 'div';
        if( $('#'+id).length < 1){
            if(type){ //create a Wordpress like Admin Notice Box (valid types: info, warning, error, success)
                $('<' + tag + ' class="notice notice-'+ type +'"><p id="'+id+'"></p></' + tag + '>').insertAfter(this);
            }else{
                $('<' + tag + ' id="'+id+'"></' + tag + '>').insertAfter(this);
            }
        }
        return this;
    }




}( jQuery ));


jQuery(document).ready(function($){



    
	if($('body h1:first').length>0){
		$('body h1:first').rw_addDom('splash_note')
	}else{
		$('.entry-title:first').rw_addDom('splash_note')	
	}

    $('#splash_note').html($('#splash').html());
	

	$('#splash').remove();

    

});

