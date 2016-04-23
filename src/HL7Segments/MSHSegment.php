<?php namespace Spitoglou\HL7\HL7Segments;

class MSHSegment extends Segment
{

    public function __construct()
    {
        parent::__construct();
        $this->header = 'MSH';
    }
    protected function getDefinition()
    {
        return [
            ['message.class', 8, 0],
            ['message.subclass', 8, 1],
            ['message.id', 9, 0],
            ['message.timestamp', 6, 0],
        ];
    }


}