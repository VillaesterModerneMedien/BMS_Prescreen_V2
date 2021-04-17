<?php

namespace BMSPrescreen\Classes;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/prescreenAPIHelper.php');
use BMSPrescreen\Helpers\PrescreenAPIHelper;


class Joblist{

    protected $apiHelper;
    protected $counter;

    public function __construct()
    {
        $this->apiHelper = new PrescreenAPIHelper();
        add_shortcode( 'joblist',  array( $this, 'shortcodeJoblist' ));
        // $this->counter = 0;
    }

    /**
     * Make Shortcode
     * --> set Preloader
     *
     */

    function shortcodeJoblist( $atts, $content, $tag ){
        $html = '<div class="joblistContainer">
                    <div class="preloader">
                        <div class="spinner-border" role="status">
                            <span class="sr-only"><img src="/wp-content/plugins/bms_prescreen/assets/images/spinner-joblist.gif"></span>
                        </div>
                        <p>Daten werden geladen</p>
                    </div>
                </div>';
        return $html;
    }

    /**
     * Make Shortcode callable via Ajax
     *
     */

    function getJoblist()
    {
        $this->counter++;
        // Set page limit
        // maybe implement pagination soon

        $parameters = [
            'page_size' => 100,
            'is_published_on_widget' => true
        ];

        $response = $this->apiHelper->PrescreenAPI('job', 'GET', $parameters);
        $output = $this->templateLoader( BMSPRE_PLUGIN_DIR . '/templates/job-list.php', array('data' => $response->data), false);
    }


    /**
     * Load template for shortcode
     *
     */

    function templateLoader($filePath, $variables = array(), $print = true)
    {
        $output = NULL;
        if(file_exists($filePath)){
            // Extract the variables to a local namespace
            extract($variables);

            // Start output buffering
            ob_start();

            // Include the template file
            include $filePath;

            // End buffering and return its contents
            $output = ob_get_flush();
            exit();
        }
    }

}
