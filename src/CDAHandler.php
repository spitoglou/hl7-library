<?php

namespace Spitoglou\HL7;

class CDAHandler
{
    protected $parsedData;
    protected $xml;

    public function __construct($payload)
    {
        $this->xml = new \SimpleXMLElement($payload);
    }

    /**
     * @return \SimpleXMLElement
     * @internal param $payload
     */
    public function parseCDA()
    {
        $this->parseHeader();
        $this->parsePatient();
        $this->parseAuthor();

        if ($this->parsedData['document']['type'] === 'ePrescription') {
            $this->parsePrescription();
        }

        return $this->parsedData;
    }

    protected function parseHeader()
    {
        $this->parsedData['document']['code'] = (string)$this->xml->code['code'];
        $this->parsedData['document']['type'] = (string)$this->xml->code['displayName'];
    }

    protected function parsePatient()
    {
        $patientExtensions = $this->getPatientExtensions();
        foreach ($this->xml->recordTarget->patientRole->id as $item) {
            $root = (string)$item['root'];
            if (array_key_exists($root, $patientExtensions)) {
                $attr = $patientExtensions[$root];
                $this->parsedData['patient'][$attr] = (string)$item['extension'];
            }
        }
        $this->parsedData['patient']['fname'] = (string)$this->xml->recordTarget->patientRole->patient->name->given;
        $this->parsedData['patient']['lname'] = (string)$this->xml->recordTarget->patientRole->patient->name->family;
        $this->parsedData['patient']['street'] = (string)$this->xml->recordTarget->patientRole->addr->streetAddressLine;
        $this->parsedData['patient']['city'] = (string)$this->xml->recordTarget->patientRole->addr->city;
        $this->parsedData['patient']['postalCode'] = (string)$this->xml->recordTarget->patientRole->addr->postalCode;
        $this->parsedData['patient']['state'] = (string)$this->xml->recordTarget->patientRole->addr->state;
        $this->parsedData['patient']['country'] = (string)$this->xml->recordTarget->patientRole->addr->country;

        $this->processAttrs(
            $this->xml->recordTarget->patientRole->telecom,
            'patient',
            $this->getTelecomUses(),
            'value',
            'use'
        );
    }

    public function getPatientExtensions()
    {
        return [
            '1.10.1' => 'AMKA',
            '1.10.30.1' => 'TameioID',
            '1.10.30.2' => 'Tameio',
            '1.10.2' => 'AMA',
            '1.20.1' => 'SecType',
            '1.30.1' => 'LastUpdate',
            '1.30.2' => 'SecExpires',
        ];
    }

    protected function processAttrs($path, $root, $data, $valueAttr = 'extension', $attribute = 'root')
    {
        foreach ($path as $item) {
            $key = (string)$item[$attribute];
            if (array_key_exists($key, $data)) {
                $attr = $data[$key];
                $this->parsedData[$root][$attr] = (string)$item[$valueAttr];
            }
        }
    }

    public function getTelecomUses()
    {
        return [
            'MC' => 'Tel',
            'HP' => 'e-mail',
        ];
    }

    protected function parseAuthor()
    {
        $this->parsedData['author']['function'] = $this->xml->author->functionCode['displayName'];
        $this->processAttrs(
            $this->xml->author->assignedAuthor->id,
            'author',
            $this->getAuthorExtensions()
        );

        $this->processAttrs(
            $this->xml->author->assignedAuthor->telecom,
            'author',
            $this->getTelecomUses(),
            'value',
            'use'
        );

        $this->parsedData['author']['function'] = (string)$this->xml->author->functionCode['displayName'];
        $this->parsedData['author']['fname'] = (string)$this->xml->author->assignedAuthor->assignedPerson->name->given;
        $this->parsedData['author']['lname'] = (string)$this->xml->author->assignedAuthor->assignedPerson->name->family;
    }

    public function getAuthorExtensions()
    {
        return [
            '1.18' => 'DoctorId',
            '1.18.1' => 'PharmacistId',
            '1.19' => 'DoctorAMKA',
            '1.20' => 'DoctorETAA',
        ];
    }

    protected function parsePrescription()
    {
        $component = $this->xml->component->structuredBody->component->section[0];
        $this->parsedData['prescription']['narrative'] = $component->text->asXML();
        $entries = $component->entry->count();
        $prescriptionHeader = $component->entry[0];

        $this->processAttrs(
            $prescriptionHeader->act->id,
            'prescription',
            $this->getPrescriptionExtensions()
        );
        $count = $prescriptionHeader->entryRelationship->count();
        for ($i = 0; $i < $count; $i++) {
            $entryRel = $prescriptionHeader->entryRelationship[$i];
            if (((string)$entryRel['typeCode']) === 'RSON') {
                $this->parsedData['prescription']['diagnoseText'] = (string)$entryRel->observation->text;
                $this->parsedData['prescription']['diagnoseCode'] = (string)$entryRel->observation->value['code'];
                $this->parsedData['prescription']['diagnoseSystem'] = (string)$entryRel->observation->value['codeSystemName'];
                $this->parsedData['prescription']['diagnoseCodeDisplay'] = (string)$entryRel->observation->value['displayName'];
            }
        }

        for ($i = 1; $i < $entries; $i++) {

        }


    }

    public function getPrescriptionExtensions()
    {
        return [
            '1.1.3' => 'Type',
            '1.1.4' => 'Recursive',
            '1.22' => 'Id',
            '1.1.4.1' => 'Recursive',
            '1.1.4.2' => '1stBarcode',
            '1.1.4.3' => 'StartDateRecursive',
            '1.1.3.1' => 'OrderedWithCommercialName',
            '1.1.3.2' => 'CommercialOrderReason',
            '1.1.3.3' => 'CommercialOrderComments',
            '1.1.6' => 'Id0Simmetoxi',
            '1.4.11' => 'Mosodosiako',
            '1.1.7' => 'HighCost',
            '1.1.8' => 'DeSensitivitationInjection',
            '1.1.9' => 'OnlyInEOPYYStores',
            '1.1.10' => '???',
            '1.1.11' => 'ContainsDrug',
            '1.1.12' => 'OnlyByHispitals',
            '1.1.13' => 'SpecialAntibiotic',
            '1.1.14' => 'Law3816',
            '1.1.15' => 'IFET',
            '1.1.16' => 'ApaiteitaiProegkrisiEOPYY',
            '1.1.17' => 'EktosDapanisEOPYY',
            '1.1.18' => 'PeriptosiEktelesis',
            '1.1.19' => 'ExecutionCount',
            '1.1.2.1' => 'PosoSimmetAsfForeaAst0%',
            '1.1.2.2' => 'PosoSimmetAsfForeaAst10%',
            '1.1.2.3' => 'PosoSimmetAsfForeaAst25%',
            '1.1.2.4' => 'SinoloSimmetAsth',
            '1.1.2.4.1' => 'SinoloTimonAnaforas',
            '1.2.1.4' => 'SinoloDiaforasAsfForea',
            '1.2.1.5' => 'SinoloDiaforas',
            '1.2.1.6' => 'SinoloDiaforasAsthGiaTiSuntagi',
            '1.1.2.5' => 'SinoloSimmetAsfForea',
            '1.1.2.1' => 'IFET',
            '1.1.2.1' => 'IFET',
            '1.1.2.1' => 'IFET',
        ];
    }

    public function getPractitionerFunctions()
    {
        return [
            '221' => 'Medical Doctor',
            '2262' => 'Pharmacist',
        ];
    }
}