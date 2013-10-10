<?php

/**
 * The test for sfIsseiBaseForm class.
 *
 * @author Issei.M <issei.m7@gmail.com>
 * @since  2012/10/08
 */

require_once dirname(__FILE__) . '/../../bootstrap/unit.php';

sfForm::disableCSRFProtection();

$form = new sfIsseiBaseForm();
$form->setWidgets(array(
    'field1' => new sfWidgetFormInputText(),
    'field2' => new sfWidgetFormInputHidden(),
    'depth2' => new sfWidgetFormSchema(array(
        'field1' => new sfWidgetFormInputText(),
        'field2' => new sfWidgetFormInputHidden(),
        'depth3' => new sfWidgetFormSchema(array(
            'field1' => new sfWidgetFormInputText(),
            'field2' => new sfWidgetFormInputHidden(),
        )),
    )),
));
$form->setValidators(array(
    'field1' => new sfValidatorString(array('required' => true)),
    'field2' => new sfValidatorString(array('required' => true)),
    'depth2' => new sfValidatorSchema(array(
        'field1' => new sfValidatorString(array('required' => true)),
        'field2' => new sfValidatorString(array('required' => true)),
        'depth3' => new sfValidatorSchema(array(
            'field1' => new sfValidatorString(array('required' => true)),
            'field2' => new sfValidatorString(array('required' => true)),
        )),
    )),
));

$t = new lime_test(null, new lime_output_color());


////////////////////////////////////////////////////////////////////////////////
$t->diag('->__construct()');
$t->ok($form instanceof sfFormSymfony, 'returns form that is extended by sfFormSymfony');


////////////////////////////////////////////////////////////////////////////////
$t->diag('->isConfirmable() ->setConfirmable()');
$t->ok(is_bool($form->isConfirmable()), '->isConfirmable() returns boolean value');
$t->ok(!$form->isConfirmable(), 'confirmable mode is setting off by default');
$form->setConfirmable(true);
$t->ok($form->isConfirmable(), '->setConfirmable() turns on confirmable mode');


////////////////////////////////////////////////////////////////////////////////
$t->diag('->prepareConfirm()');
try {
    $form->prepareConfirm();
    $t->fail('throws a LogicException if form is not valid');
} catch (LogicException $e) {
    $t->pass('throws a LogicException if form is not valid');
}
$form->bind(array(
    'field1' => 'FIELD1',
    'field2' => 'FIELD2',
    'depth2' => array(
        'field1' => 'FIELD1',
        'field2' => 'FIELD2',
        'depth3' => array(
            'field1' => 'FIELD1',
            'field2' => 'FIELD2',
        )
    )
));

$validedForm = clone $form;
$validedForm->prepareConfirm();
$t->ok($validedForm['field1']->getWidget() instanceof sfWidgetFormConfirm, 'the field widget has been converted');
$t->ok(!$validedForm['field2']->getWidget() instanceof sfWidgetFormConfirm, 'the hidden-field widget has not been converted');
$t->ok($validedForm['depth2']['field1']->getWidget() instanceof sfWidgetFormConfirm, 'the field widget has been converted by depth 2');
$t->ok(!$validedForm['depth2']['field2']->getWidget() instanceof sfWidgetFormConfirm, 'the hidden-field widget has not been converted by depth 2');
$t->ok($validedForm['depth2']['depth3']['field1']->getWidget() instanceof sfWidgetFormConfirm, 'the field widget has been converted by depth 3');
$t->ok(!$validedForm['depth2']['depth3']['field2']->getWidget() instanceof sfWidgetFormConfirm, 'the hidden-field widget has not been converted by depth 3');
unset($validedForm);

$form->setConfirmable(false);
try {
    $form->prepareConfirm();
    $t->fail('throws a LogicException if form is not confirmable');
} catch (LogicException $e) {
    $t->pass('throws a LogicException if form is not confirmable');
}


////////////////////////////////////////////////////////////////////////////////
unset($form);
