<?php namespace Spitoglou\HL7;

class MessageHandler
{

    private $segmentDelimiter;
    private $supportedSegments;
    private $repeatedSegments;
    private $messageSpecs;
    private $attributes;

    public function __construct()
    {
        $this->segmentDelimiter = chr(10) . chr(13);
        $this->supportedSegments = explode(',', env('SUPPORTED_SEGMENTS', 'MSH,PID,OBX'));
        $this->repeatedSegments = explode(',', env('REPEATED_SEGMENTS', 'OBX'));
        $this->attributes = [];
    }

    public function parseMessage($messagePayload)
    {
        $segments = explode($this->segmentDelimiter, $messagePayload);
        $i = 0;
        foreach ($segments as $segment) {
            $i++;
            $segmentHead = substr($segment, 0, 3);
            if (($i === 1) && ($segmentHead !== 'MSH')) {
                throw new \Exception('Message does not begin with MSH segment');
            }

            if (in_array($segmentHead, $this->supportedSegments, true)) {
                $className = 'Spitoglou\\HL7\\' . $segmentHead . 'Segment';
                /** @var Segment $segmentClass */
                $segmentClass = new $className;
                $segmentAttrs = $segmentClass->getAttrs($segment);
                if (in_array($segmentHead, $this->repeatedSegments)) {
                    foreach ($segmentAttrs as $key => $value) {
                        $segmentAttrs[$key . '.' . $i] = $value;
                        unset($segmentAttrs[$key]);
                    }
                }
                $this->attributes = array_merge($segmentAttrs, $this->attributes);

            }

        }
        dd($this->attributes);
    }
}