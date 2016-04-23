<?php

class Hl7MainTest extends TestCase
{


    /** @test */
    public function itCanParseTestSegment()
    {
        $segment = new \Spitoglou\HL7\HL7Segments\TestSegment();

        $segmentString = $this->getTestSegment('PID');
        static::assertContains([0 => 'PID'], $segment->parseSegment($segmentString));

        $segmentString = $this->getTestSegment('A04');
        static::assertContains([0 => 'MSH'], $segment->parseSegment($segmentString));
    }

    public function getTestSegment($head)
    {
        switch ($head) {
            case 'A04':
                return 'MSH|^~\&|EPIC|EPICADT|SMS|SMSADT|199912271408|CHARRIS|ADT^A04|1817457|D|2.5|';
                break;
            case 'PID':
                return 'PID||0493575^^^2^ID 1|454721||DOE^JOHN^^^^|DOE^JOHN^^^^' .
                '|19480203|M||B|254 MYSTREET AVE^^MYTOWN^OH^44123^USA||(216)123-4567|||M|NON|400003403~1129086|';
                break;
            case 'OBR':
                return 'OBR |1 | |VS12340000 |28562-7 ^Vital Signs ^LN';
                break;
            case 'OBX1':
                return 'OBX |1 |NM |271649006 ^Systolic blood pressure ^SNOMED-CT ' .
                '| |132 |mm[Hg] |90-120 |H | | |F | | |20100511220525';
                break;

        }

        return false;
    }

    /** @test */
    public function itCanParseTestMessage()
    {
        $payload = $this->getTestSegment('A04');
        $payload .= chr(13);
        $payload .= $this->getTestSegment('PID');
        $payload .= chr(13);
        $payload .= $this->getTestSegment('OBR');
        $payload .= chr(13);
        $payload .= $this->getTestSegment('OBX1');

        $handler = new \Spitoglou\HL7\MessageHandler();
        //$handler = App::make('HL7Handler');
        $result = $handler->parseMessage($payload);
        print_r($result);

        static::assertEquals($result['patient.fname'], 'JOHN');
        static::assertEquals($result['observation.codeSystem.4'], 'SNOMED-CT');
        static::assertEquals($result['message.subclass'], 'A04');
    }

    /** @test */
    public function itChecksThatMessageBeginsWithMSHSegment()
    {
        $payload = $this->getTestSegment('PID');
        $payload .= chr(13);
        $payload .= $this->getTestSegment('OBR');
        $payload .= chr(13);

        static::setExpectedException(Spitoglou\HL7\Exceptions\HL7MessageException::class);

        $handler = new \Spitoglou\HL7\MessageHandler();
        $handler->parseMessage($payload);

    }

    /** @test */
    public function itCanCreateSegmentFromArray()
    {
        $array = [];
        $array['patient.lname'] = 'Pitoglou';
        $array['patient.fname'] = 'Stavros';

        $segment = new \Spitoglou\HL7\HL7Segments\PIDSegment();
        static::assertEquals(
            'PID|||||Pitoglou^Stavros|||||||||||||||||||||||||||||||||||||||||||||',
            $segment->createSegmentFromAttrs($array)
        );
    }

    /** @test */
    public function itCanCreateMessageFromArray()
    {
        $array = [];
        $array['patient.lname'] = 'Pitoglou';
        $array['patient.fname'] = 'Stavros';

        $message = new \Spitoglou\HL7\MessageHandler();
        //dd($message->createMessage('A01', $array));
        static::assertContains(
            'ADT^A01',
            $message->createMessage('A01', $array)
        );
        static::assertContains(
            'Pitoglou^Stavros',
            $message->createMessage('A01', $array)
        );
    }
}
