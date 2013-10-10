<?php

/**
 * The test for sfMailLogger class.
 *
 * @author Issei.M <issei.m7@gmail.com>
 * @since  2012/09/12
 */

require_once dirname(__FILE__) . '/../../bootstrap/unit.php';

$dispatcher = new sfEventDispatcher();
$options = array(
    'sender'      => 'sender@test.localhost',
    'recipients'  => 'recipient@test.localhost',
    'subject'     => 'An error occurred!!',
    'format'      => '"%priority%","%time%","%message%"',
    'time_format' => '%Y%m%d',
);

class myMailLogger extends sfMailLogger {
    public function getMailer() {
        return $this->mailer;
    }
}

// mock
class sfContext {
    public static function getInstance() {
        return new self();
    }
    public function getMailer() {
        global $dispatcher;
        require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/classes/Swift.php';

        Swift::registerAutoload();
        sfMailer::initialize();

        return new sfMailer($dispatcher, array(
            'logging' => true,
            'delivery_strategy' => 'none',
        ));
    }
}

$t = new lime_test(null, new lime_output_color());


////////////////////////////////////////////////////////////////////////////////
$t->diag('->initialize()');

try {
    $logger = new sfMailLogger($dispatcher);
    $t->fail('throws a sfConfigurationException if was not provided "sender" parameter');
} catch (sfConfigurationException $e) {
    $t->pass('throws a sfConfigurationException if was not provided "sender" parameter');
}

try {
    $logger = new sfMailLogger($dispatcher, array('sender' => 'sender@localhost'));
    $t->fail('throws a sfConfigurationException if was not provided "recipients" parameter');
} catch (sfConfigurationException $e) {
    $t->pass('throws a sfConfigurationException if was not provided "recipients" parameter');
}

$logger = new myMailLogger($dispatcher, $options);
$t->ok($logger instanceof sfLogger, 'logger is extended by sfLogger');
$t->ok(!$logger->log('MESSAGE'), '->doLog() returns false if does not have mailer');


////////////////////////////////////////////////////////////////////////////////
$t->diag('->configureMailer()');
$t->ok(is_null($logger->getMailer()), 'logger does not have a mailer');
$dispatcher->notify(new sfEvent(sfContext::getInstance(), 'context.load_factories'));
$t->ok($logger->getMailer() instanceof sfMailer, 'logger has a mailer after was notified "context.load_factories"');


////////////////////////////////////////////////////////////////////////////////
$t->diag('->doLog()');
$logger->log('MESSAGE');
$messages = $logger->getMailer()->getLogger()->getMessages();
$t->is(count($messages), 1, 'the email has been sent');
$t->is($messages[0]->getFrom(), array($options['sender'] => null), 'email [from] is correct');
$t->is($messages[0]->getTo(), array($options['recipients'] => null), 'email [to] is correct');
$t->is($messages[0]->getSubject(), $options['subject'], 'email [subject] is correct');
$t->is($messages[0]->getBody(), '"info",' . date('"Ymd"') . ',"MESSAGE"', 'email [body] is correct');


////////////////////////////////////////////////////////////////////////////////
$t->diag('->shutdown()');
$logger->shutdown();
$t->ok(is_null($logger->getMailer()), 'logger does not have a mailer after shutdown');


////////////////////////////////////////////////////////////////////////////////
unset($logger);
