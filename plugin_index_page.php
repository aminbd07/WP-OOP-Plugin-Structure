<?php

/*
 * Plugin Name: Minimum OOP WP plugin strucure 
 * Version: 1.0.0
 * Plugin URI: 
 * Description:  this is minimum structure for a new plugin development, just copy this plugin and change your self and add more thing as yu need. 
 * Author: Nurul Amin
 * Author URI: http://nurulamin.me
 * Requires at least: 4.0        
 * Tested up to:  
 * License: GPL2
 * Text Domain: yourtextdomain [find this text and replace with your text]
 * Domain Path: /lang/
 *
 */

class YourUniqueClassName {
    public $version             = '1.0.0';
    public $db_version          = '1.0.0';
    public $text_domain         = 'yourtextdomain';  // Must chnage this text 
    public $custom_post_name         = 'my_costom_p_type';  // Must chnage this text 
    protected static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {

        $this->init_actions();

        $this->define_constants();
        spl_autoload_register( array( $this, 'autoload' ) );
        // Include required files


        register_activation_hook( __FILE__, array( $this, 'install' ) );
        //Do some thing after load this plugin

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

        do_action( 'YOUR_CLASS_LOAD_ACTION_HOOKS_NAME' ); // you can chnage it with your unique hooks name
    }

    function install() {
        
    }

    function init_actions() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'plugins_loaded', array( $this, 'register_post_type' ) ); // If you register new post type for your plugin
    }

    function autoload( $class ) {
        $name = explode( '_', $class );
        if ( isset( $name[ 1 ] ) ) {
            $class_name = strtolower( $name[ 1 ] );
            $filename   = dirname( __FILE__ ) . '/class/' . $class_name . '.php';
            if ( file_exists( $filename ) ) {
                require_once $filename;
            }
        }
    }

    public function define_constants() {

        $this->define( 'YOUR_PLUGIN_VERSION', $this->version ); // Chnage text 'YOUR_PLUGIN' 
        $this->define( 'YOUR_PLUGIN_DB_VERSION', $this->db_version );// Chnage text 'YOUR_PLUGIN' 
        $this->define( 'YOUR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );// Chnage text 'YOUR_PLUGIN' 
        $this->define( 'YOUR_PLUGIN_URL', plugins_url( '', __FILE__ ) );// Chnage text 'YOUR_PLUGIN' 
    }

    public function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    function register_post_type() {
        $name = "TITLE OF YOUR POST" ;  //Change as your NEED
        $labels        = array(
            'name'               => __( $name , 'post type general name', $this->text_domain ), 
            'singular_name'      => __( $name, 'post type singular name', $this->text_domain ),
            'add_new'            => __( 'Add New', $name, $this->text_domain ),
            'add_new_item'       => __( 'Add New '.$name, $this->text_domain ),
            'edit_item'          => __( 'Edit '.$name, $this->text_domain ),
            'new_item'           => __( 'New ' .$name, $this->text_domain ),
            'view_item'          => __( 'View '. $name, $this->text_domain ),
            'search_items'       => __( 'Search ' .$name, $this->text_domain ),
            'not_found'          => __( 'Nothing found', $this->text_domain ),
            'not_found_in_trash' => __( 'Nothing found in Trash', $this->text_domain ),
            'parent_item_colon'  => __( $name, $this->text_domain ),
        );
        $post_type_agr = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'capability_type' => 'post',
            'menu_position' => false,
            'show_in_menu'  => true,
            'supports'      => array( 'title', 'editor', 'thumbnail' ),
            'hierarchical'  => false,
            'rewrite'       => false,
            'query_var'     => false,
            'show_in_nav_menus' => false,
        );
        register_post_type( $this->custom_post_name, $post_type_agr );   
    }

    function load_textdomain() {
        load_plugin_textdomain( $this->text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    }

    static function admin_scripts() {

        wp_enqueue_script( 'jquery' ); // For jQuery
        wp_enqueue_script( 'jquery-ui-core' ); // for jQuery UI
        wp_enqueue_script( 'bootstrap', plugins_url( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', __FILE__ ), '', false, true ); // For Bootstrap
        wp_enqueue_script( 'YOUR_PLUGIN_NAME_admin', plugins_url( 'assets/js/script.js', __FILE__ ), '', false, true );// For Your Custom JS
        wp_localize_script( 'YOUR_PLUGIN_NAME_admin', 'YOUR_PLUGIN_NAME__Vars', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'YOUR_PLUGIN_NAME_admin_nonce' ),
            'pluginURL' => YOUR_PLUGIN_URL,
        ) );

        wp_enqueue_style( 'bootstrap', plugins_url( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', __FILE__ ) ); // Bootstrap CSS
        wp_enqueue_style( 'YOUR_PLUGIN_NAME_admin', plugins_url( '/assets/css/style.css', __FILE__ ) ); // Your Custom CSS File 

        wp_enqueue_style( 'dashicons' ); // For WP Desh Iocn 
        do_action( 'UNIQUENAME_OF_NEW_SCRIPT_LOAD_ACTION_HOOKS_NAME' ); // Unique Hooks Name for your plugin action hooks to add new script
    }


    function admin_menu() {
        $capability = 'read'; //minimum level: subscriber

        add_submenu_page( 'edit.php?post_type='.$this->custom_post_name, __( 'TITLE OF YOUR SUBMENU ', $this->text_domain ), __( 'YOUR SUB MENU NAME', $this->text_domain ), $capability, $this->custom_post_name, array( $this, 'ACTION_METHOD_NAME_FOR_SUBMENU' ) );

        do_action( 'UNIQUENAME_OF_SUBMENU_ADD_ACTION_HOOKS_NAME', $capability, $this );
    }

    function ACTION_METHOD_NAME_FOR_SUBMENU() {
        require ( YOUR_PLUGIN_PATH . '/view/page_name.php' );
    }

}

function YourClassInit() {
    return YourUniqueClassName::instance();
}

//Class  instance.
$YourUniqueClassName = YourClassInit();
