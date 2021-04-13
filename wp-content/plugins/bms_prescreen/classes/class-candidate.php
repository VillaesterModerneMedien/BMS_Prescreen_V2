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
        $files = $this->writeFile();
        //ar_dump($files);die;

        $email      = sanitize_email( $_POST['email'] );
        $firstname  = sanitize_text_field( $_POST['firstname'] );
        $lastname   = sanitize_text_field( $_POST['lastname'] );

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
            ]
        ];

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

        if(!empty($files))
        {
            $parameters['cv_file'] = [
                'base64_content'    =>  $files['base64'],
                'file_type'         =>  $files['type']
            ];
        }

        $parameters = json_encode($parameters);
        //print_r($parameters);
        // var_dump($this->apiHelper);die;
        $response = $this->apiHelper->PrescreenAPI('candidate', 'POST', $parameters);
        //$response = $this->apiHelper->PrescreenAPI('job', 'GET', 'id', $id);

        //wp_redirect( '/danke' );
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

        /* Location */
        $location = "../wp-content/uploads/".$filename;

        $return_arr = array();

        /* Upload file */
        if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
            $return_arr = [
                'name'      =>  $filename,
                'size'      =>  $filesize,
                'base64'    =>  base64_encode(file_get_contents($location)),
                'src'       =>  $location,
                'type'      =>  $fileType
            ];
        }

        return $return_arr;
    }

    public function patchApplication()
    {
    }

}
