<?php
/**
 * sfIdentifiedRequestFileLogger のテスト
 *
 * @author Issei Murasawa
 * @since 2012/09/11
 */
require_once dirname(__FILE__) . '/../../bootstrap/unit.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);
////// TEST DATA ///////

$logPath = sfConfig::get('sf_app_cache_dir') . '/test/test.log';
if (file_exists($logPath)) {
    unlink($logPath);
}

$baseOptions = array(
    'file' => $logPath
);

$dispatcher = new sfEventDispatcher();

////// TEST DATA ///////
$t = new lime_test(4, new lime_output_color());


////////////////////////////////////////////////////////////////////////////////
$t->diag('->initialize(), ->getRequestId()');

$logger = new sfIdentifiedRequestFileLogger($dispatcher, $baseOptions);
$t->ok($logger instanceof sfIdentifiedRequestLoggerInterface, 'logger implemented sfIdentifierRequestLoggerInterface');
$t->is($logger->getRequestId(), 'none', 'RequestId is none');
unset($logger);


////////////////////////////////////////////////////////////////////////////////
$t->diag('');
$t->diag('->setRequestId()');

$logger = new sfIdentifiedRequestFileLogger($dispatcher, $baseOptions);
$logger->setRequestId('1234567890');
$t->is($logger->getRequestId(), '1234567890', 'RequestId is correct');
unset($logger);


////////////////////////////////////////////////////////////////////////////////
$t->diag('');
$t->diag('->doLog()');

$options = array_merge($baseOptions, array('format' => '%type%,%request_id%,%message%,%time%,%priority%%EOL%', 'time_format' => '%Y%m%d'));
$logger = new sfIdentifiedRequestFileLogger($dispatcher, $options);
$logger->log('MESSAGE');
$t->is(file_get_contents($logPath), 'symfony,none,MESSAGE,' . date('Ymd') . ',info' . PHP_EOL, 'logfile was written correctly');
unset($logger);
