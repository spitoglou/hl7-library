<?php namespace Spitoglou\HL7;

class OBXSegment extends Segment
{
    protected function getDefinition()
    {
        return [
            ['observation.code', 3, 0],
            ['observation.name', 3, 1],
            ['observation.codeSystem', 3, 2],
            ['observation.result', 5, 0],
            ['observation.units', 6, 0],
        ];
    }
}