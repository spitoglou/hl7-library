<?php namespace Spitoglou\HL7\HL7Segments;

class OBRSegment extends Segment
{

    public function __construct()
    {
        parent::__construct();
        $this->header = 'OBR';
    }

    protected function getDefinition()
    {
        return [

        ];
    }
}