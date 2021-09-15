<?php

//namespace BMSPrescreen\Helpers;


class JobdetailsHelper
{

    public function getCustomFields($fields)
    {
        $customFields = [];
        foreach ($fields as $field)
        {
            $customFields[$field->name] = $field->values[0]->value;
        }
        return $customFields;
    }

    public function getListElements($html)
    {

        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $liList = $doc->getElementsByTagName('li');
        $liValues = array();
        foreach ($liList as $li) {
            $liValues[] = $li->nodeValue;
        }
        return $liValues;
    }

    public function getByID($html, $id)
    {
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $element = $doc->getElementByID($id);
        return $element->nodeValue;
    }

    public function imageRenaming($value)
    {
        $value = str_replace(', ', '_', $value);
        $value = preg_replace('/\s+/', '_', $value);
        $value = str_replace(',', '_', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace('&', '_', $value);
        $value = str_replace("ä", "ae", $value);
        $value = str_replace("_auml;", "ae", $value);
        $value = str_replace("ü", "ue", $value);
        $value = str_replace("_uuml;", "ue", $value);
        $value = str_replace("ö", "oe", $value);
        $value = str_replace("_ouml;", "oe", $value);
        $value = str_replace("Ä", "Ae", $value);
        $value = str_replace("Ü", "Ue", $value);
        $value = str_replace("Ö", "Oe", $value);
        $value = str_replace("ß", "ss", $value);
        $value = strtolower($value);
        $value = strip_tags($value);
        //var_dump($value);
        return $value;
    }

    function getRecruitainmentLi($html, $toggleCount, $fieldType) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadHTML($html);
        $lis = $dom->getElementsByTagName('li');

        $counter = 0;

        $elements = [];
        foreach ($lis as $li){
            $counter++;
            if($fieldType === 'skill' && $counter > $toggleCount)
            {
                $elements[] = $li->nodeValue;
            }
            if($fieldType === 'switch' && $counter <= $toggleCount)
            {
                $elements[] = $li->nodeValue;
            }

        }

        return $elements;
    }
}
