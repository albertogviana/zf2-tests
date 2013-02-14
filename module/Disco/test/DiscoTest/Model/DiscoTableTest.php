<?php
namespace DiscoTest\Model;

use Disco\Model\DiscoTable;
use Disco\Model\Disco;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class DiscoTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllDiscos()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $discoTable = new DiscoTable($mockTableGateway);

        $this->assertSame($resultSet, $discoTable->fetchAll());
    }

public function testCanRetrieveAnDiscoByItsId()
{
    $disco = new Disco();
    $disco->exchangeArray(array('id'     => 123,
                                'artist' => 'The Military Wives',
                                'title'  => 'In My Dreams'));

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Disco());
    $resultSet->initialize(array($disco));

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $discoTable = new DiscoTable($mockTableGateway);

    $this->assertSame($disco, $discoTable->getDisco(123));
}

public function testCanDeleteAnDiscoByItsId()
{
    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('delete')
                     ->with(array('id' => 123));

    $discoTable = new DiscoTable($mockTableGateway);
    $discoTable->deleteDisco(123);
}

public function testSaveDiscoWillInsertNewAlbumsIfTheyDontAlreadyHaveAnId()
{
    $discoData = array('artist' => 'The Military Wives', 'title' => 'In My Dreams');
    $disco     = new Disco();
    $disco->exchangeArray($discoData);

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('insert')
                     ->with($discoData);

    $discoTable = new DiscoTable($mockTableGateway);
    $discoTable->saveDisco($disco);
}

public function testSaveDiscoWillUpdateExistingAlbumsIfTheyAlreadyHaveAnId()
{
    $discoData = array('id' => 123, 'artist' => 'The Military Wives', 'title' => 'In My Dreams');
    $disco     = new Disco();
    $disco->exchangeArray($discoData);

    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Disco());
    $resultSet->initialize(array($disco));

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                       array('select', 'update'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));
    $mockTableGateway->expects($this->once())
                     ->method('update')
                     ->with(array('artist' => 'The Military Wives', 'title' => 'In My Dreams'),
                            array('id' => 123));

    $discoTable = new DiscoTable($mockTableGateway);
    $discoTable->saveDisco($disco);
}

public function testExceptionIsThrownWhenGettingNonexistentDisco()
{
    $resultSet = new ResultSet();
    $resultSet->setArrayObjectPrototype(new Disco());
    $resultSet->initialize(array());

    $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
    $mockTableGateway->expects($this->once())
                     ->method('select')
                     ->with(array('id' => 123))
                     ->will($this->returnValue($resultSet));

    $discoTable = new DiscoTable($mockTableGateway);

    try
    {
        $discoTable->getDisco(123);
    }
    catch (\Exception $e)
    {
        $this->assertSame('Could not find row 123', $e->getMessage());
        return;
    }

    $this->fail('Expected exception was not thrown');
}


}
