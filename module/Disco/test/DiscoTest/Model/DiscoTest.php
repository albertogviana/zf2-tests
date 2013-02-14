<?php
namespace DiscoTest\Model;

use Disco\Model\Disco;
use PHPUnit_Framework_TestCase;

class DiscoTest extends PHPUnit_Framework_TestCase
{
    public function testDiscoInitialState()
    {
        $disco = new Disco();

        $this->assertNull($disco->artist, '"artist" should initially be null');
        $this->assertNull($disco->id, '"id" should initially be null');
        $this->assertNull($disco->title, '"title" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $disco = new Disco();
        $data  = array('artist' => 'some artist',
                       'id'     => 123,
                       'title'  => 'some title');

        $disco->exchangeArray($data);

        $this->assertSame($data['artist'], $disco->artist, '"artist" was not set correctly');
        $this->assertSame($data['id'], $disco->id, '"id" was not set correctly');
        $this->assertSame($data['title'], $disco->title, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $disco = new Disco();

        $disco->exchangeArray(array('artist' => 'some artist',
                                    'id'     => 123,
                                    'title'  => 'some title'));
        $disco->exchangeArray(array());

        $this->assertNull($disco->artist, '"artist" should have defaulted to null');
        $this->assertNull($disco->id, '"id" should have defaulted to null');
        $this->assertNull($disco->title, '"title" should have defaulted to null');
    }
}
