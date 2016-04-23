<?php namespace Spitoglou\HL7\HL7Messages;

use Carbon\Carbon;
use Spitoglou\HL7\HL7Segments\Segment;

class Message
{
    /** @var  array */
    protected $segments;
    protected $class;
    protected $subclass;

    public function __construct()
    {

    }

    public function create($attrsArray)
    {
        $attrsArray['message.class'] = $this->class;
        $attrsArray['message.subclass'] = $this->subclass;
        $attrsArray['message.id'] = $this->getMessageId();
        $attrsArray['message.timestamp'] = Carbon::now()->timestamp;

        $segmentArray = [];
        foreach ($this->segments as $segment) {
            $className = 'Spitoglou\\HL7\\HL7Segments\\' . $segment . 'Segment';
            /** @var Segment $segmentClass */
            $segmentClass = new $className;
            $segmentArray[] = $segmentClass->createSegmentFromAttrs($attrsArray);

        }
        return $segmentArray;
    }

    protected function getMessageId()
    {
        return uniqid();
    }
}