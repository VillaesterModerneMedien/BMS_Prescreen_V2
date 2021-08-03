<?php

namespace BMSPrescreen\Helpers;

class PrescreenAPIHelper
{

    protected $apiKey;
    protected $prescreenBaseURL = 'https://api.prescreenapp.io/v1/';
    public $results;

    public function __construct(){
        $this->apiKey = esc_html( get_option( 'apiKey' ) );
    }

    /**
     * Get Data from the Prescren API
     *
     * @param string $type // application, job, candidate, other, user
     * @param string $method // GET, POST, DELETE, PATCH
     * @param array $parameters
     *
     * @return object $results
     */

    public function PrescreenAPI($type, $method, $parameters, $id){

        $curl = curl_init();

        $curlOptions =  [
            CURLOPT_URL => $this->_PrescreenURLMatcher($type, $method, $parameters, $id),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'apiKey: ' . $this->apiKey
            )
        ];

        // Set value form
        if($method === 'POST' && $type == 'candidate')
        {
            $curlOptions[10015] = $parameters;
        }
        else if($method === 'PATCH' && $type == 'application'){
            //var_dump($parameters);
            $curlOptions[10015] = $parameters;
        }
        else{
            //var_dump($method);
            //var_dump($type);
            //var_dump($id);
            //var_dump($parameters);
            //var_dump($this->_PrescreenURLMatcher($type, $method, $parameters, $id));
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    protected function _PrescreenURLMatcher($type, $name, $parameters, $id){
        $parameter = '';

        $counter = 0;
        foreach($parameters as $name => $value){
            $counter++;
            if($counter < count($parameters)){
                $parameter .= $name . '=' . $value . '&';
            }
            else{
                $parameter .= $name . '=' . $value;
            }
        }


        switch ($type) {
            case "job":
                if(!empty($parameter)){
                    $url  = $this->prescreenBaseURL . $type . '?' . $parameter;
                }
                else{
                    $url  = $this->prescreenBaseURL . $type;
                }
                break;
            case "application":
                if(!empty($parameter)){
                    $url  = $this->prescreenBaseURL . $type . '/' . $id .  '?' . $parameter;
                }
                else{
                    $url  = $this->prescreenBaseURL . $type . '/' . $id;
                }
                break;
            default:
                $url  = $this->prescreenBaseURL . $type . '?' . $parameter;
        }

        return $url;

    }
}
