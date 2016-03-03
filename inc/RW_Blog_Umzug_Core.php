<?php
/**
 * Class RW_Blog_Umzug_Core
 *
 * Autoloader for the plugin
 *
 * @package   RW Blog Umzug
 * @author    Joachim Happel
 * @license   GPL-2.0+
 * @link      https://github.com/rpi-virtuell/rw-blog-umzug
 */
class RW_Blog_Umzug_Core {


    //TODO setting
    static $new_host = 'blogs.rpi-virtuell.de';
    static $new_sheme =  'http';

    /**
     * Constructor
     *
     * @since   0.0.1
     * @access  public
     */
    function __construct() {

    }
    /**
     * runs on action hook init
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     * @use_action: init
     */
    public static function init() {


        add_action( "wp_footer", array('RW_Blog_Umzug_Core','print_splash'));



    }

    public static function print_splash(){
        $trans = self::get_blogs_infos();
        if(!$trans) return;
        ?>
        <div id="splash" style="display: none">
            <div class="splash-border">
                <h2 class="warning">Wir ziehen um auf einen neuen Server</h2>
                <p>Hier geht es zur neuen Seite: <a href="<?php echo $trans->new->siteurl;?>"><?php echo $trans->new->siteurl;?></a></p>
            </div>
        </div>
        <?php
    }



    /**
     * runs on action hook init
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  class
     */
    static function get_blogs_infos() {

        $old_blog = $new_blog = get_blog_details();
        $status = '404';

        if($new_blog->blog_id > 1){
            $new_blog->main_domain = DOMAIN_CURRENT_SITE;

            if( $new_blog->main_domain != $new_blog->domain && $new_blog->path == '/'  ){

                $new_blog->path = '/'.str_replace('.'.$new_blog->main_domain, $new_blog->domain).'/';

                $new_blog->domain = $new_blog->path.'.'.self::$new_host;


            }else{
                $new_blog->domain = self::$new_host;
            }

            $new_blog->main_domain = self::$new_host;

            $new_blog->siteurl = $new_blog->home = self::$new_sheme.'://'.$new_blog->domain.$new_blog->path;

        }
        //only for test
        $new_blog->siteurl = 'http://blogs.rpi-virtuell.de/openreli4chris/';

        $get = wp_remote_get( $new_blog->siteurl );
        if( is_array($get) ) {
            $status = $get['response']['code']; // array of http header lines
        }

        if($status>200){
            return false;
        }else{
            return (object) array(
                'new'=>$new_blog,
                'old'=>$old_blog
            );
        }


    }

    /**
     * Load custom Stylesheet
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     * @use_action: wp_enqueue_scripts
     */
    public static function enqueue_style() {
        wp_enqueue_style( 'customStyle',RW_Blog_Umzug::$plugin_url . '/css/style.css' );
    }

    /**
     * Load custom javascript
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     * @use_action: wp_enqueue_scripts
     */
    public static function enqueue_js() {
       # wp_register_script( 'rw_blog_umzug_ajax_script', get_stylesheet_directory_uri() . '/js/my-script.js' );

        wp_enqueue_script( 'rw_blog_umzug_ajax_script',RW_Blog_Umzug::$plugin_url . '/js/javascript.js' ,array(),false,true);

    }
    

    
    //TODO: add wp_ajax-response-actions in rootfile
    //TODO: add javasript ajax calls
    //TODO: add corresponding response functions like this
    /**
     * Example of Ajax response
     *
     * @since   0.0.1
     * @access  public
     * @static
     * @return  void
     * @use_action: wp_ajax_rw_blog_umzug_core_ajaxresponse
     */
    public static function ajaxresponse(){

        echo json_encode(
            array(
                    'success' =>  true
                ,   'msg'=>'Ajax Example. <em>Users time on click</em> : <b>'.
                            $_POST['message'] .
                            '</b> ( scripts locatet in: inc/'.
                            basename(__FILE__).' (Line: '.__LINE__.
                            ') | js/javascript.js )'
            )
        );

        die();

    }

    public static  function the_widget_content(){
        $trans = self::get_blogs_infos();
        if(!$trans) return;
        ?>
            <div class="splash-border" style="background-color: #ffc106; padding: 3px">
                <h2 class="warning">Lieber Admin!</h2>
                <p>Im Zuge der Neuentwicklung von rpi-virtuell müssen wir auch mit allen bestehenden Blogs  auf einen neuen Server umziehen.</p>
                <p>Aus sicherheitstechnischen Gründen haben wir die Blogs dazu neu aufgesetzt.
                    Die Inhalte wurden automatisch importiert.</p>
                    <p>Ganz neue Inhalte sind allerdings nicht mehr dabei. Du kannst die aber leicht über das  Werkzeug Menü exportieren und in deinem neuen Blog importieren.
                    <br><strong>Bitte schreibe neue Inhalte ab jetzt in deinem neuen Blog</strong>
                    </p>
                    <p>Ein paar Kleinigkeiten bleiben trotzdem noch zu tun: Logos und Bilder,
                        die direkt in der Konfiguration des Themes eingebunden waren, müssen neu verlinkt werden.
                        Ebenso  müssen wahrscheinlich die Menüs neu gesetzt werden.
                    </p>

                <p>Schau dir deine neue Seite in Ruhe an: <a href="<?php echo $trans->new->siteurl;?>"><?php echo $trans->new->siteurl;?></a>.
                <p>Wenn du noch Hilfe brauchst oder es Probleme gibt, sind wir natürlich gerne da. <br>
                <br>Weiterhin viel Spass beim Bloggen<br> Das Team von rpi-virtuell </p>



            </div>

        <?php
    }
    
    /**
     * Add a custom Dashboard widget to the rop of the widgets
     * @use_action wp_dashboard_setup
     *
     * @link https://codex.wordpress.org/Dashboard_Widgets_API
     */
    public static  function dashboard_widgets(){
        global $wp_meta_boxes;

        //@TODO set title
        wp_add_dashboard_widget('rw_blog_umzug_widget',  __( 'Sever Umzug' , RW_Blog_Umzug::get_textdomain()), function(){

            self::the_widget_content();

        });

        $origin_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
        $my_widget = array( 'example_dashboard_widget' => $origin_dashboard['rw_blog_umzug_widget'] );

        unset( $origin_dashboard['rw_blog_umzug_widget'] );
        $new_dashboard = array_merge( $my_widget, $origin_dashboard );
        // Save the sorted array back into the original metaboxes
        $wp_meta_boxes['dashboard']['normal']['core'] = $new_dashboard;

        //remove wordpress feeds widget
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    }
    
}