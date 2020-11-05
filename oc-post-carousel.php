<?php
/**
 * Plugin Name: Post Slider Carousel & Custom Post Grids
 * Description: This plugin allows you to display your posts with a slider ( All slider customize options ).
 * Version: 1.0
 * Copyright: 2019 
 */


if (!defined('ABSPATH')) {
    die('-1');
}
if (!defined('PSCCPG_PLUGIN_NAME')) {
    define('PSCCPG_PLUGIN_NAME', 'Post Carousel');
}
if (!defined('PSCCPG_PLUGIN_VERSION')) {
    define('PSCCPG_PLUGIN_VERSION', '1.0.0');
}
if (!defined('PSCCPG_PLUGIN_FILE')) {
    define('PSCCPG_PLUGIN_FILE', __FILE__);
}
if (!defined('PSCCPG_PLUGIN_DIR')) {
    define('PSCCPG_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('PSCCPG_BASE_NAME')) {
    define('PSCCPG_BASE_NAME', plugin_basename(PSCCPG_PLUGIN_DIR));
}
if (!defined('PSCCPG_DOMAIN')) {
    define('PSCCPG_DOMAIN', 'psccpg');
}

//Main class
//Load required js,css and other files

if (!class_exists('PSCCPG')) {

    class PSCCPG {

        protected static $instance;

        //Load all includes files
        function includes() {

            //Admn site Layout
            include_once('includes/psccpg-backend.php');

            //Update all Option Data
            include_once('includes/psccpg-backend-updatemeta.php');

            //create shortcode for display post slider
            include_once('includes/psccpg-shortcode.php');
        }


        function init() {
            add_action( 'admin_enqueue_scripts', array($this, 'PSCCPG_load_admin_script_style'));
            add_action( 'wp_enqueue_scripts',  array($this, 'PSCCPG_load_script_style'));
            add_filter( 'plugin_row_meta', array( $this, 'PSCCPG_plugin_row_meta' ), 10, 2 );
            add_image_size( 'post_slider_img', 350, 270, false ); // (cropped)
        }


        function PSCCPG_plugin_row_meta( $links, $file ) {
            if ( PSCCPG_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  '<a href="https://wordpress.org/support/plugin/post-slider-by-oc/reviews/#new-post" target="_blank"><img src="'.PSCCPG_PLUGIN_DIR.'/asset/images/star.png" class="ocpc_rating_div"></a>',
                );

                return array_merge( $links, $row_meta );
            }

            return (array) $links;
        }


        //Add JS and CSS on Frontend
        function PSCCPG_load_script_style() {
            wp_enqueue_style( 'owlcarousel-min', PSCCPG_PLUGIN_DIR . '/asset/owlcarousel/assets/owl.carousel.min.css', false, '1.0.0' );
            wp_enqueue_style( 'owlcarousel-theme', PSCCPG_PLUGIN_DIR . '/asset/owlcarousel/assets/owl.theme.default.min.css', false, '1.0.0' );
            wp_enqueue_script( 'owlcarousel', PSCCPG_PLUGIN_DIR . '/asset/owlcarousel/owl.carousel.js', false, '1.0.0' );
            wp_enqueue_script( 'masonrypost', PSCCPG_PLUGIN_DIR . '/asset/js/masonry.pkgd.min.js', false, '1.0.0' );
            wp_enqueue_script( 'ocpcfront_js', PSCCPG_PLUGIN_DIR . '/asset/js/ocpc-front-js.js', false, '1.0.0' );
            wp_enqueue_style( 'ocpcfront_css', PSCCPG_PLUGIN_DIR . '/asset/css/ocpc-front-style.css', false, '1.0.0' );
            wp_enqueue_script( 'masonrypostimage',PSCCPG_PLUGIN_DIR . '/asset/js/imagesloaded.pkgd.min.js', false,'1.0.0');
        }


        //Add JS and CSS on Backend
        function PSCCPG_load_admin_script_style() {
            wp_enqueue_style( 'ocpcadmin_css', PSCCPG_PLUGIN_DIR . '/asset/css/ocpc-admin-style.css', false, '1.0.0' );
            wp_enqueue_script( 'ocpcadmin_js', PSCCPG_PLUGIN_DIR . '/asset/js/ocpc-admin-js.js', false, '1.0.0' );
            wp_enqueue_script( 'media_uploader', PSCCPG_PLUGIN_DIR . '/asset/js/media-uploader.js', false, '1.0.0' );
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
                self::$instance->includes();
            }
            return self::$instance;
        }
    }
    add_action('plugins_loaded', array('PSCCPG', 'instance'));
}
