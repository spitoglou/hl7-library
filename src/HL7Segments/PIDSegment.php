<?php namespace Spitoglou\HL7\HL7Segments;

class PIDSegment extends Segment
{
    public function __construct()
    {
        parent::__construct();
        $this->header = 'PID';
    }

    protected function getDefinition()
    {
        return [
            ['patient.fname', 5, 1],
            ['patient.lname', 5, 0],
        ];
    }
}