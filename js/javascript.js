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




    //bind an ajax cmd to a dom obj.
    $.fn.rw_wpAjax=function(options){

        var defaults ={
            type: 'POST',
            url: ajaxurl,
            notice:false,
            tag: 'div',
            data:{
                action: false
            },
            fn:function(data,options){
                console.log( data, options  );
            }
        }

        var settings = $.extend( {}, defaults, options );

        if(options.hook){
            settings.data.action = options.hook.replace('wp_ajax_','');
        }

        $(this).rw_addDom(settings.data.action,settings.tag,settings.notice);



        $.ajax({                            // @use_action: wp_ajax_action
            type: settings.type,
            url: settings.url,
            data: settings.data,
            success: function (data, textStatus, XMLHttpRequest) {

                readData = $.parseJSON(data);
                if( typeof readData.success != 'undefined' && readData.success == true ){
                    settings.fn(  readData,settings.data.action  );
                }else{
                     $('#' + settings.data.action).html('Ajax Fehler aufgetreten.' );
                    console.log('ajax error', data);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    };

    

}( jQuery ));


jQuery(document).ready(function($){



    $('body h1:first').rw_addDom('splash_note')
        $('#splash_note').html($('#splash').html());

    //@TODO remove the examples
    jQuery('.menupop').rw_addDom('test-button','span');
    jQuery('#test-button').html('Teste Ajax').css('color','red');

    
    $('#test-button' ).on( 'click', function() {

        var d=new Date();

        //insert Ajax response after the first h1 Tag
        $('h1:first').rw_wpAjax({
            hook: 'wp_ajax_rw_blog_umzug_core_ajaxresponse',       //required
            notice: 'success',                                      //optional
            data:{                                                  //data will be send to remote server
                message: d.toString()
            },
            fn:function( data, action ){                            //do a function with the ajaxresponded data

                $('#'+ action).html( data.msg );                    //e.g. display data in a DIV
            }
        });

    });

    

});

