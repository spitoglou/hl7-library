<?php

namespace Spitoglou\HL7\HL7Segments;

abstract class Segment
{

    protected $header;
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

    /**
     * getDefinition
     * @return array
     */
    abstract protected function getDefinition();

    public function createSegmentFromAttrs($attrArray)
    {
        $field = 0;
        $definitionArray = $this->getDefinition();
        $segmentString = '';
        while ($field < 50) {
            $subfield = 0;
            if ($field === 0) {
                $segmentString .= $this->header;
            }
            while ($subfield < 10) {
                foreach ($definitionArray as $item) {
                    if (($item[1] === $field) && ($item[2] === $subfield) && array_key_exists($item[0], $attrArray)) {
                        if ($subfield > 0) {
                            $segmentString .= '^';
                        }
                        $segmentString .= $attrArray[$item[0]];
                    }
                }
                $subfield++;
            }
            $segmentString .= '|';
            $field++;
        }

        return $segmentString;
    }
}
