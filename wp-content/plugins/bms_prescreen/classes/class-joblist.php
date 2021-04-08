<?php

namespace BMSPrescreen\Classes;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/prescreenAPIHelper.php');
use BMSPrescreen\Helpers\PrescreenAPIHelper;


class Joblist{

    protected $apiHelper;

    public function __construct()
    {
        $this->apiHelper = new PrescreenAPIHelper();
        add_shortcode( 'joblist',  array( $this, 'shortcodeJoblist' ));
    }

    /**
     * Make Shortcode
     *
     */

    function shortcodeJoblist( $atts, $content, $tag ){

        // Set page limit
        // maybe implement pagination soon

        $parameters = [
            'page_size' => 100,
            'is_published_on_widget' => true
        ];


        $response = $this->apiHelper->PrescreenAPI('job', 'GET', $parameters);

        $output = $this->templateLoader( BMSPRE_PLUGIN_DIR . '/templates/job-list.php', array('data' => $response->data), false);
        return $output;
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
            $output = ob_get_clean();
        }
        if ($print) {
            print $output;
        }
        return $output;
    }

}
