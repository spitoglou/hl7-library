<?php namespace Spitoglou\HL7\HL7Segments;

class TestSegment extends Segment
{
    public function __construct()
    {
        parent::__construct();
        $this->header = 'TST';
    }

    protected function getDefinition()
    {
        return [
            ['test.test', 1, 0],
        ];
    }
}