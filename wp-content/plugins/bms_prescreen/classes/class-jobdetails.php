<?php

namespace BMSPrescreen\Classes;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/prescreenAPIHelper.php');

use BMSPrescreen\Helpers\PrescreenAPIHelper;


class Jobdetails{


    protected $apiHelper;

    public function __construct()
    {
        $this->apiHelper = new PrescreenAPIHelper();

    }

    /**
     * Add custom Jobdetails Template
     *
     */

    public function templateJobDetail( $template ) {
        $id = get_query_var( 'jobid' );
        //var_dump($id);
        $idPos = (int) strrpos($id, '-') + 1;
        $id = (int) substr($id, $idPos, 6);
        $slug = get_query_var( 'jobid' );

        $parameters = [
            'id' => $id
        ];

        $response = $this->apiHelper->PrescreenAPI('job', 'GET', $parameters);
        if ( $id ) {
            $args = [
                'id'        =>  $id,
                'response'  =>  $response,
                'slug'      =>  $slug
            ];
            load_template(  BMSPRE_PLUGIN_DIR . '/templates/job-detail.php',  $require_once = true,  $args);
            exit;
        }
        return $template;
    }
}
