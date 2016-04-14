<?php

class Hl7MainTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function itCanParseTestSegment()
    {
        $segment = new \Spitoglou\HL7\TestSegment();

        $segmentString = 'PID||0493575^^^2^ID 1|454721||DOE^JOHN^^^^|DOE^JOHN^^^^|19480203|M||B|254 MYSTREET AVE^^MYTOWN^OH^44123^USA||(216)123-4567|||M|NON|400003403~1129086|';
        $segmentString = 'MSH|^~\&|EPIC|EPICADT|SMS|SMSADT|199912271408|CHARRIS|ADT^A04|1817457|D|2.5|';

        dd($segment->parseSegment($segmentString));
    }

    /** @test */
    public function itCanParseTestMessage()
    {
        $payload = 'MSH|^~\&|EPIC|EPICADT|SMS|SMSADT|199912271408|CHARRIS|ADT^A04|1817457|D|2.5|';
        $payload .= chr(10) . chr(13);
        $payload .= 'PID||0493575^^^2^ID 1|454721||DOE^JOHN^^^^|DOE^JOHN^^^^|19480203|M||B|254 MYSTREET AVE^^MYTOWN^OH^44123^USA||(216)123-4567|||M|NON|400003403~1129086|';
        $payload .= chr(10) . chr(13);
        $payload .= 'OBR |1 | |VS12340000 |28562-7 ^Vital Signs ^LN';
        $payload .= chr(10) . chr(13);
        $payload .= 'OBX |1 |NM |271649006 ^Systolic blood pressure ^SNOMED-CT | |132 |mm[Hg] |90-120 |H | | |F | | |20100511220525';

        $handler = new \Spitoglou\HL7\MessageHandler();
        $result = $handler->parseMessage($payload);
        print_r($result);

        static::assertEquals($result['patient.fname'], 'JOHN');
        static::assertEquals($result['observation.codeSystem.4'], 'SNOMED-CT');
        static::assertEquals($result['message.subClass'], 'A04');


    }
}
