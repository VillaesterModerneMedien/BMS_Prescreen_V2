<?php
/*
Plugin Name: BMS Prescreen REST API
Plugin URI: https://villaester.de
Description: Plugin für die Prescreen REST API
Version: 1.0
Author: Kiki Schuelling
Author URI: https://villaester.de
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('BMSPRE_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

require_once(BMSPRE_PLUGIN_DIR . '/classes/class-jobdetails.php');
require_once(BMSPRE_PLUGIN_DIR . '/classes/class-candidate.php');
require_once(BMSPRE_PLUGIN_DIR . '/classes/class-joblist.php');
use BMSPrescreen\Classes\Jobdetails;
use BMSPrescreen\Classes\Candidate;
use BMSPrescreen\Classes\Joblist;
use BMSPrescreen\Helpers\PrescreenAPIHelper as PrescreenAPIHelper;

class BMS_Prescreen_Plugin {

    protected $pluginPath;
    protected $apiHelper;
    protected $jobdetails;
    protected $joblist;
    protected $candidate;
    protected $options;

    public function __construct() {
        // registriert den neuen custom post type
        //add_action( 'init', array( $this, 'register_custom_post_type' ) );

        add_action( 'init', array( &$this, 'init' ) );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        $this->options = $this->_getPluginOptions();
        $options = $this->options;
    }

    public function init() {
        $options = $this->options;

        // When Request URI = rewriteSlugJobDetails
        // --> Jobdetails
        // rewrite only when URL matches and no # is in URL
        //var_dump($_SERVER['REQUEST_URI']);
        if(!empty($_SERVER['REQUEST_URI']) && !empty($options['rewriteSlugJobdetails']))
        {
            if (strpos($_SERVER['REQUEST_URI'], $options['rewriteSlugJobdetails']) !== false && strpos('#', $options['rewriteSlugJobdetails']) === false) {
                $this->jobdetails =  new Jobdetails();
                $this->candidate =  new Candidate();
                add_action( 'init', array( $this, 'add_rewrite_rules' ), 1 );
                add_filter( 'query_vars', array( $this, 'add_query_vars' ), 1 );
                add_filter( 'template_include', array( $this->jobdetails, 'templateJobDetail' ), 1 );
                add_action('admin_post_sendFormTest',  array( $this->candidate, 'writeCandidate' ));
                add_action('admin_post_nopriv_sendFormTest', array( $this->candidate, 'writeCandidate' ));
                add_action('wp_enqueue_scripts',array( $this, 'jobdetails_scripts' ));
            }
        }

        $this->joblist =  new Joblist();

        add_action( 'wp_enqueue_scripts', array( $this, 'joblist_scripts' ));

        add_action('wp_ajax_getJoblist',  array( $this->joblist, 'getJoblist' ));
        add_action('wp_ajax_nopriv_getJoblist', array( $this->joblist, 'getJoblist' ));


        add_action( 'wp_ajax_writeCandidate', array( $this, 'candidate_ajax_callback' ));
        add_action( 'wp_ajax_nopriv_writeCandidate', array( $this, 'candidate_ajax_callback' ));
    }

    /**
     * Load JS and CSS for Frontend
     * and pass ajax url to script -> calling search
     *
     */

    function jobdetails_scripts() {
        // CSS
        //wp_enqueue_style( 'style-css', plugins_url( '/style.css', __FILE__ ));
        wp_enqueue_style( 'bootstrap-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ));
        wp_enqueue_style( 'jobdetails-css', plugins_url( 'assets/css/jobdetails.css', __FILE__ ));

        // JavaScript
        wp_enqueue_script( 'bootstrap-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ),array('jquery'));
        wp_enqueue_script( 'recaptcha-js', 'https://www.google.com/recaptcha/api.js',array('jquery'));
        wp_enqueue_script( 'bms-jobdetails', plugins_url( 'assets/js/jobdetails.js', __FILE__ ),array('jquery'));

        // Pass ajax_url to script.js
        wp_localize_script( 'bms-jobdetails', 'plugin_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Load JS and CSS for Frontend Joblist
     * when shortcode 'joblist' is present on page
     *
     */

    function joblist_scripts() {
        global $post, $wpdb;

        $shortcode_found = false;
        if ( has_shortcode($post->post_content, 'joblist') ) {
            $shortcode_found = true;
        } else if ( isset($post->ID) ) {
            $result = $wpdb->get_var( $wpdb->prepare(
                "SELECT count(*) FROM $wpdb->postmeta " .
                "WHERE post_id = %d and meta_value LIKE '%%joblist%%'", $post->ID ) );
            $shortcode_found = ! empty( $result );
        }

        if ( $shortcode_found ) {
            // CSS
            wp_enqueue_style( 'datatables-css', plugins_url( 'assets/css/dataTables.min.css', __FILE__ ));
            wp_enqueue_style( 'joblist-css', plugins_url( 'assets/css/joblist.css', __FILE__ ));

            // JavaScript
            wp_enqueue_script ('datatables' , plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ) , '' , '' , true);
            wp_enqueue_script ('ellipsis' , plugins_url( 'assets/js/ellipsis.js', __FILE__ ) , '' , '' , true);
            wp_enqueue_script( 'joblist', plugins_url( 'assets/js//joblist.js', __FILE__ ),array('jquery'));
        }
    }

    ## Fetch all records

    function candidate_ajax_callback() {
        $this->candidate =  new Candidate();
        $this->candidate->writeCandidate();
    }


    /*******************************************************************************************************************
     *******************************************************************************************************************
     * Admin
     *******************************************************************************************************************
     *******************************************************************************************************************/


    /**
     * Registers options in the backend
     *
     */
    function admin_init() {

        // Add options to database if they don't already exist
        add_option("apiKey", "", "", "yes");
        add_option("googleApiKey", "", "", "yes");
        add_option("BMSRewriteSlugJobdetail", "", "", "yes");

        // Register settings that this form is allowed to update
        register_setting('bms-prescreen-api', 'apiKey', []);
        register_setting('bms-prescreen-api', 'googleApiKey', []);
        register_setting('bms-prescreen-api', 'BMSRewriteSlugJobdetail', []);

    }

    /**
     * Adds admin menu
     *
     */
    function admin_menu() {
        $page = add_submenu_page( 'options-general.php', esc_html__('BMS Prescreen API', 'bms-prescreen-api'), esc_html__('BMS Prescreen API', 'bms-prescreen-api'), 'manage_options', __FILE__, array( &$this, 'BMSPrescreenAPIOptionsPanel' ) );
    }

    /**
     * Load Options-Template
     *
     */

    public function BMSPrescreenAPIOptionsPanel() {
        // Load options page
        require_once(BMSPRE_PLUGIN_DIR . '/inc/options.php');
    }

    /**
     * Get plugin options from the Backend
     *
     */

    protected function _getPluginOptions(){
        $options = [
            'apiKey'                    => esc_html( get_option( 'apiKey' ) ),
            'googleApiKey'              => esc_html( get_option( 'googleApiKey' ) ),
            'rewriteSlugJobdetails'     => esc_html( get_option( 'BMSRewriteSlugJobdetail' ) ),
        ];
        return $options;
    }



    /*******************************************************************************************************************
     *******************************************************************************************************************
     * Frontend
     *******************************************************************************************************************
     *******************************************************************************************************************/



    /**
     * Add rewrite rules
     *
     */

    public function add_rewrite_rules() {
        // Jobdetails -> get Job by id
        $options = $this->options;
        //var_dump($options['rewriteSlugJobdetails']);die;
        flush_rewrite_rules();
        add_rewrite_rule( $options['rewriteSlugJobdetails'] . '/(.*)$', 'index.php?jobid=$matches[1]', 'top');
    }

    public function add_query_vars( $vars ) {
        $vars[] = 'jobid';
        return $vars;
    }




}
$bms_plugin = new BMS_Prescreen_Plugin();
$bms_plugin->init();
