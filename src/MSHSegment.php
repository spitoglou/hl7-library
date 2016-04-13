<?php namespace Spitoglou\HL7;

class MSHSegment extends Segment
{
    protected function getDefinition()
    {
        return [
            ['message.class', 8, 0],
            ['message.subClass', 8, 1],
            ['message.id', 9, 0],
            ['message.timeStamp', 6, 0],
        ];
    }


}