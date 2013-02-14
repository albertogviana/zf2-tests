<?php
namespace Disco;

// Add these import statements:
use Disco\Model\Disco;
use Disco\Model\DiscoTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Disco\Model\DiscoTable' =>  function($sm) {
                    $tableGateway = $sm->get('DiscoTableGateway');
                    $table = new DiscoTable($tableGateway);
                    return $table;
                },
                'DiscoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Disco());
                    return new TableGateway('disco', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
