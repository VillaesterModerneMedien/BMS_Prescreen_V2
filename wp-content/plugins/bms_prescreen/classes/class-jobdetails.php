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

        //Post mit ID Startseite holen --> Startseite
        // alles soweit entfernen und das YOOTheme JSON holen

        $startseitenPost = get_post( 2 );
        $companyGridLength = strlen($startseitenPost->post_content);
        $companyGridStart = stripos ( $startseitenPost->post_content, '<!--more-->' );
        $companyGrid = substr( $startseitenPost->post_content, $companyGridStart + 11, $companyGridLength);
        $companyGrid = substr( $companyGrid, 5, strlen($companyGrid));
        $companyGrid = substr( $companyGrid, 0, -3);
        $companyGrid = json_decode( $companyGrid, false );

        //Company Grid Item
        // achtung --> 5 scheint ROW zu sein, bei Umbau der Startseite muss ggf hier angepasst werden

        $numOfItems = count($companyGrid->children[5]->children[0]->children[1]->children[1]->children);

        $companyInformations = array();
        for($i = 0; $i < $numOfItems; $i++){
            $item = $companyGrid->children[5]->children[0]->children[1]->children[1]->children[$i]->props;
            $company        = $item->title;
            $webseiteLink   = $item->webseiteLink;
            $jobLink        = $item->jobLink;
            $logo           = $item->image;
            $headline       = $item->headline;
            $content        = $item->content;

            $companyInformations[$company] = [
                'company'       =>  $company,
                'content'       =>  $content,
                'webseiteLink'  =>  $webseiteLink,
                'jobLink'       =>  $jobLink,
                'logo'          =>  $logo,
                'headline'      =>  $headline
            ];
        }

        if ( $id ) {
            $args = [
                'id'        =>  $id,
                'response'  =>  $response,
                'slug'      =>  $slug,
                'company'   =>  $companyInformations
            ];
            load_template(  BMSPRE_PLUGIN_DIR . '/templates/job-detail.php',  $require_once = true,  $args);
            exit;
        }
        return $template;
    }
}
