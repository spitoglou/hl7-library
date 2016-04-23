<?php namespace Spitoglou\HL7;

use Spitoglou\HL7\Exceptions\HL7MessageException;
use Spitoglou\HL7\HL7Messages\Message;
use Spitoglou\HL7\HL7Segments\Segment;

class MessageHandler
{

    private $segmentDelimiter;
    private $supportedSegments;
    private $supportedMessages;
    private $repeatedSegments;
    private $messageSpecs;
    private $attributes;

    public function __construct()
    {
        $this->segmentDelimiter = chr(13);
        $this->supportedSegments = explode(',', config('hl7.SUPPORTED_SEGMENTS', 'MSH,PID,OBX,OBR'));
        $this->supportedMessages = explode(',', config('hl7.SUPPORTED_MESSAGES', 'A01'));
        $this->repeatedSegments = explode(',', config('hl7.REPEATED_SEGMENTS', 'OBX'));
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
                throw new HL7MessageException('Message does not begin with MSH segment');
            }

            if (in_array($segmentHead, $this->supportedSegments, true)) {
                $className = 'Spitoglou\\HL7\\HL7Segments\\' . $segmentHead . 'Segment';
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

            } else {
                throw new HL7MessageException("Segment {$segmentHead} is not supported");
            }

        }
        return $this->attributes;
    }

    public function createMessage($messageType, $attrsArray)
    {
        if (in_array($messageType, $this->supportedMessages, true)) {
            $className = 'Spitoglou\\HL7\\HL7Messages\\' . $messageType . 'Message';
            /** @var Message $message */
            $message = new $className;
        } else {
            throw new HL7MessageException('Message Type Not Supported');
        }

        return implode($this->segmentDelimiter, $message->create($attrsArray));
    }
}
