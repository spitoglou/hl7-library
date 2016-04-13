<?php

namespace Spitoglou\HL7;

abstract class Segment
{

    protected $fieldDelimiter = '|';
    protected $subFieldDelimiter = '^';

    public function __construct()
    {

    }

    public function getAttrs($segmentString)
    {
        $segmentData = $this->parseSegment($segmentString);
        $attrs = [];
        foreach ($this->getDefinition() as $definition) {
            $attrs[$definition[0]] = trim($segmentData[$definition[1]][$definition[2]]);
        }

        return $attrs;
    }

    public function parseSegment($segmentString)
    {
        $segmentData = [];
        $fields = explode($this->fieldDelimiter, $segmentString);
        foreach ($fields as $fKey => $fValue) {
            $subFields = explode($this->subFieldDelimiter, $fValue);
            foreach ($subFields as $sKey => $sValue) {
                $segmentData[$fKey][$sKey] = $sValue;
            }
        }

        return $segmentData;
    }

}