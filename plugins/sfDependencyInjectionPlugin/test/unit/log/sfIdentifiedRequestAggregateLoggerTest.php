<?php
/**
 * sfIdentifiedRequestAggregateLogger のテスト
 *
 * @author Issei Murasawa
 * @since 2012/09/11
 */
require_once dirname(__FILE__) . '/../../bootstrap/unit.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);
////// TEST DATA ///////

class testLogger extends sfLogger {
    protected function doLog($message, $priority) {}
}
class implementedLogger extends sfLogger implements sfIdentifiedRequestLoggerInterface {
    protected $requestId;
    public function getRequestId() {return $this->requestId;}
    public function setRequestId($requestId) {$this->requestId = $requestId;}
    protected function doLog($message, $priority) {return false;}
}

$dispatcher = new sfEventDispatcher();

////// TEST DATA ///////
$t = new lime_test(6, new lime_output_color());


////////////////////////////////////////////////////////////////////////////////
$t->diag('->initialize(), ->getRequestId()');

$logger = new sfIdentifiedRequestAggregateLogger($dispatcher);
$t->ok($logger instanceof sfIdentifiedRequestLoggerInterface, 'logger implemented sfIdentifierRequestLoggerInterface');
$t->is(strlen($logger->getRequestId()), 40, 'length of RequestId is 40');
unset($logger);

$options = array('id_trim_size' => 20);
$logger = new sfIdentifiedRequestAggregateLogger($dispatcher, $options);
$t->is(strlen($logger->getRequestId()), 20, 'length of RequestId is 20, after set 20');
unset($logger);


////////////////////////////////////////////////////////////////////////////////
$t->diag('');
$t->diag('->addLogger()');

$dispatcher = $dispatcher;
$logger = new sfIdentifiedRequestAggregateLogger($dispatcher);
$logger->addLogger(new testLogger($dispatcher));
$logger->addLogger(new implementedLogger($dispatcher));
$loggers = $logger->getLoggers();
$t->is($logger->getRequestId(), $loggers[1]->getRequestId(), 'requestId: master and slave is same');


////////////////////////////////////////////////////////////////////////////////
$t->diag('');
$t->diag('->setRequestId()');

$logger->setRequestId('1234567890');
$t->is($logger->getRequestId(), '1234567890', 'requestId(master) is correct');
$t->is($loggers[1]->getRequestId(), '1234567890', 'requestId(slave) is correct');

unset($logger, $loggers);
