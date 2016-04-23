<?php namespace Spitoglou\HL7\HL7Messages;

class A01Message extends Message
{
    public function __construct()
    {
        parent::__construct();

        $this->segments = ['MSH', 'PID'];
        $this->class = 'ADT';
        $this->subclass = 'A01';
    }
}