<?php namespace Spitoglou\HL7;

class PIDSegment extends Segment
{
    protected function getDefinition()
    {
        return [
            ['patient.fname', 5, 1],
            ['patient.lname', 5, 0],
        ];
    }
}