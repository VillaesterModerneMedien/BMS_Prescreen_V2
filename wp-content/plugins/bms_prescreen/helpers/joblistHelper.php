<?php

namespace BMSPrescreen\Helpers;
require_once(BMSPRE_PLUGIN_DIR . '/helpers/prescreenAPIHelper.php');
use BMSPrescreen\Helpers\PrescreenAPIHelper;

class JoblistHelper
{
    protected $apiHelper;

    public function __construct(){
        $this->apiHelper = new PrescreenAPIHelper();
    }

    public function customFields($customFields)
    {
        $fields = array();
        if(!empty($customFields)){
            foreach ($customFields as $field){
                $fields[$field->name] = $field->values[0]->value;
            }
        }

        return $fields;
    }

    public function setUnternehmen($unternehmenID)
    {
        switch ($unternehmenID) {
            case "BMS_banking":
                $unternehmen = [
                    'name'  =>  'BMS Consulting',
                    'url'   =>  'https://www.bms-consulting.de',
                    'type'  =>  'Finanzdienstleister'
                ];
                break;
            case "BMS_public":
                $unternehmen = [
                    'name'  =>  'BMS Consulting',
                    'url'   =>  'https://www.bms-consulting.de',
                    'type'  =>  'Non Profit Organisationen'
                ];
                break;
            case "BMS":
                $unternehmen = [
                    'name'  =>  'BMS Consulting',
                    'url'   =>  'https://www.bms-consulting.de',
                    'type'  =>  'Verwaltung'
                ];
                break;
            case "ESAG":
                $unternehmen = [
                    'name'  =>  'Eudemonia Solutions',
                    'url'   =>  'https://www.eudemonia-solutions.de',
                    'type'  =>  'Finanzdienstleister'
                ];
                break;
            case "NPO":
                $unternehmen = [
                    'name'  =>  'NPO Applications',
                    'url'   =>  'https://www.npo-applications.de',
                    'type'  =>  'Finanzdienstleister'
                ];
                break;
            case "BMSUG":
                $unternehmen = [
                    'name'  =>  'BMS Consulting Unternehmensgruppe',
                    'url'   =>  'https://www.bms-consulting.de',
                    'type'  =>  'Consulting'
                ];
                break;
            case "BMSOD":
                $unternehmen = [
                    'name'  =>  'BMS Orga & Design',
                    'url'   =>  'https://www.bms-od.de',
                    'type'  =>  'Design'
                ];
                break;
            case "BMSTC":
                $unternehmen = [
                    'name'  =>  'BMS Training & Coaching',
                    'url'   =>  'https://www.bms-tc.de',
                    'type'  =>  'Training'
                ];
                break;
            default:
                $unternehmen = [
                    'name'  =>  '',
                    'url'   =>  'https://www.bms-consulting.de',
                    'type'  =>  ''
                ];
        }

        return $unternehmen;
    }

    /**
     * This method processes a string and replaces all accented UTF-8 characters by unaccented
     * ASCII-7 "equivalents", whitespaces are replaced by hyphens and the string is lowercase.
     *
     * @param   string  $string    String to process
     *
     * @return  string  Processed string
     *
     * @since   1.7.0
     */
    public function stringURLSafe($string)
    {
        // Remove any '-' from the string since they will be used as concatenaters
        $str = str_replace('-', ' ', $string);

        $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
        $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");

        $str = str_replace($search, $replace, $str);
        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(strtolower($str));

        // Remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $str);

        // Trim dashes at beginning and end of alias
        $str = trim($str, '-');

        return $str;
    }

    /**
     * Get the cities / locations for searchlist
     *
     * BMS fetches Cities by custom fields -> TODO: Generalize and fetch original cities for other customers.
     */

    public function getCities()
    {
        $parameters = [
            'page_size' => 100,
            'is_published_on_widget' => true
        ];

        $jobs = $this->apiHelper->PrescreenAPI('job', 'GET', $parameters, '');
        $cities = array();

        foreach ($jobs->data as $job){
            foreach ($job->custom_fields as $field){

                // CF Field 23319 = 01a_standort_der_stelle

                if($field->custom_field_id === 23319)
                {
                    $cities[] = $field->values[0]->value;
                }
            }
        }
        return array_unique($cities);
    }

    /**
     * Get the cities / locations for searchlist
     *
     * BMS fetches Cities by custom fields -> TODO: Generalize and fetch original cities for other customers.
     */

    public function getCompaniesSelect($data)
    {
        $unternehmen = [];
        foreach ($data as $anzeige){
            $customFields = $this->customFields($anzeige->custom_fields);
            $unternehmen[] = $this->setUnternehmen($customFields['00_wer_schreibt_aus'])['name'];
        }
        return array_unique($unternehmen);
    }
}
