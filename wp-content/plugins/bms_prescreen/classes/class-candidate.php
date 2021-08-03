<?php

namespace BMSPrescreen\Classes;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/prescreenAPIHelper.php');
use BMSPrescreen\Helpers\PrescreenAPIHelper;


class Candidate{

    protected $apiHelper;

    public function __construct()
    {
        $this->apiHelper = new PrescreenAPIHelper();

    }


    public function writeCandidate() {

        //ar_dump($files);die;

        $email      = sanitize_email( $_POST['email'] );
        $firstname  = sanitize_text_field( $_POST['firstname'] );
        $lastname   = sanitize_text_field( $_POST['lastname'] );
        $jobID      = sanitize_text_field( $_POST['job_id'] );

        $parameters = [
            'email'         =>  $email,
            'profile'       =>  [
                'firstname'         =>  $firstname,
                'lastname'          =>  $lastname
            ],
            'custom_fields' =>  [
                [
                    'custom_field_id'   => 224407,
                    'values'            =>  [[
                        'value' => ''
                    ]]
                ],
            ],
            'job_id'         =>  $jobID,
        ];

/*
        if(!empty($files))
        {
            $parameters['profile'] = [
                'firstname'         =>  $firstname,
                'lastname'          =>  $lastname,
                'avatar'    => [
                    'base64_content'    =>  $files['base64'],
                    'file_type'         =>  $files['type']
                ]
            ];
        }
*/

        // Star Ratings

        $skillstars = [];

        for($i = 1; $i < 9; $i++)
        {
            switch($i)
            {
                case 1:
                    $fieldId = 22781;
                    break;
                case 2:
                    $fieldId = 22784;
                    break;
                case 3:
                    $fieldId = 22785;
                    break;
                case 4:
                    $fieldId = 22786;
                    break;
                case 5:
                    $fieldId = 22787;
                    break;
                case 6:
                    $fieldId = 22788;
                    break;
            }
            $skill = sanitize_text_field( $_POST['skillStar' . $i] );
            if(empty($skill))
            {
                $skill = ' Keine Angabe';
            }
            $skillstars['skillStar' . $i] = [
                'skill'     =>      $skill,
                'field_id'  =>      $fieldId
            ];
        }

        foreach($skillstars as $skillstar)
        {
            $skillstarsField =    [
                'custom_field_id'   => $skillstar['field_id'],
                'values'            =>  [[
                    'value' => $skillstar['skill']
                ]]
            ];
            array_push($parameters['custom_fields'], $skillstarsField);
        }

        // Skill Switches

        $switches = [];

        for($i = 1; $i < 4; $i++)
        {
            switch($i)
            {
                case 1:
                    $fieldId = 22778;
                    break;
                case 2:
                    $fieldId = 22779;
                    break;
                case 3:
                    $fieldId = 22780;
                    break;
            }
            $skill = sanitize_text_field( $_POST['skillSwitch' . $i] );
            if(empty($skill))
            {
                $skill = ' Keine Angabe';
            }
            $switches['skillSwitch' . $i] = [
                'skill'     =>      $skill,
                'field_id'  =>      $fieldId
            ];
        }

        foreach($switches as $switch)
        {
            $switchesField =    [
                'custom_field_id'   => $switch['field_id'],
                'values'            =>  [[
                    'value' => $switch['skill']
                ]]
            ];
            array_push($parameters['custom_fields'], $switchesField);
        }

        // Sliders

        $sliders = [];

        for($i = 1; $i < 5; $i++)
        {
            switch($i)
            {
                case 1:
                    $fieldId = 22789;
                    break;
                case 2:
                    $fieldId = 22790;
                    break;
                case 3:
                    $fieldId = 22791;
                    break;
                case 4:
                    $fieldId = 22792;
                    break;
            }
            $skill = $_POST['slider' . $i];

            if(empty($skill))
            {
                $skill = ' Keine Angabe';
            }
            $sliders['slider' . $i] = [
                'skill'     =>      $skill,
                'field_id'  =>      $fieldId
            ];
        }

        foreach($sliders as $slider)
        {
            $slidersField =    [
                'custom_field_id'   => $slider['field_id'],
                'values'            =>  [[
                    'value' => $slider['skill']
                ]]
            ];
            array_push($parameters['custom_fields'], $slidersField);
        }

        // Personas

        $personas = '';
        for($i = 1; $i < 10; $i++)
        {
            if(!empty($_POST['personas' . $i]))
            {
                $personas .= sanitize_text_field( $_POST['personas' . $i]);
                if($i != 9)
                {
                    $personas .= ',';
                }
            }
        }

        if(empty($personas))
        {
            $personas = 'Keine Angaben';
        }

        $field = [
            'custom_field_id'   => 22795,
            'values'            =>  [[
                'value' => $personas
            ]]
        ];
        array_push($parameters['custom_fields'], $field);

        // CV File
        $files = $this->writeFile();

        if($files['filename'] != '')
        {

            $parameters['cv_file'] = [
                'base64_content'    =>  $files['base64'],
                'file_type'         =>  $files['type']
            ];
        }

        $parameters = json_encode($parameters);

        $response = $this->apiHelper->PrescreenAPI('candidate', 'POST', $parameters, '');

        //wp_redirect( '/danke' );
        echo json_encode($response, true);
        unlink($files['src']);
        die();
    }


    public function patchCandidate() {
        $files = $this->writeAddFiles();

        $candidateID        = sanitize_text_field( $_POST['candidate_id'] );
        $jobID              = sanitize_text_field( $_POST['job_id'] );
        $applicationID      = sanitize_text_field( $_POST['application_id'] );

        $parameters = [
            'candidate_id'          =>  $candidateID,
            'job_id'                =>  $jobID,
            'is_finished'           =>  true,
            'recruiter_files'       =>  array()
        ];

        // Sonstige Files

        if(!empty($files))
        {
            foreach($files as $file){
                $parameters['recruiter_files'][] = [
                    'filename'                  =>  $file['filename'],
                    'base64_content'            =>  $file['base64'],
                    'is_visible_for_candidate'  =>  true,
                    'source'                    =>  'candidate',
                    'upload_context'            =>  'application'
                ];
            }
        }

        $parameters = json_encode($parameters);
        $response = $this->apiHelper->PrescreenAPI('application', 'PATCH', $parameters, $applicationID);

        echo json_encode($response, true);
        unlink($files['src']);
        die();
    }

    function writeFile() {
        /* Getting file name */
        $filename = $_FILES['file']['name'];
        $file = $_FILES['file']['tmp_name'];

        //var_dump($filename);
        $fileType = substr($filename, strrpos($filename, '.') + 1, 3);

        /* Getting File size */
        $filesize = $_FILES['file']['size'];

        $return_arr = array();

        $return_arr = [
            'filename'      =>  $filename,
            'size'          =>  $filesize,
            'base64'        =>  base64_encode(file_get_contents($_FILES['file']['tmp_name'])),
            'type'          =>  $fileType,
            'tmp_name'      =>  $_FILES['file']['tmp_name']
        ];

        return $return_arr;
    }

    function writeAddFiles() {
        /* Getting file name */

        $filesAdd = $_FILES['filesAdditional'];
        $filesAddCounter = count($_FILES['filesAdditional']['name']);

        $return_arr = array();
        //echo '<pre>';
        //print_r($filesAdd);

        for($i = 0; $i < $filesAddCounter; $i++) {
            $return_arr[] = [
                'filename'  =>  $filesAdd['name'][$i],
                'type'      =>  $filesAdd['type'][$i],
                'tmp_name'  =>  $filesAdd['tmp_name'][$i],
                'base64'    =>  base64_encode(file_get_contents($filesAdd['tmp_name'][$i])),
                'error'     =>  $filesAdd['error'][$i],
                'size'      =>  $filesAdd['size'][$i],
            ];
        }
       // print_r($return_arr);

        return $return_arr;
    }
}
