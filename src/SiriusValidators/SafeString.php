<?php
namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class SafeString extends AbstractRule
{
  const MESSAGE = 'Only allowed alphanumeric characters and the following symbols: _.!?()@#-';
  const LABELED_MESSAGE = '{label} can contain only alphanumeric characters and the following symbols: _.!?()@#-';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('/^[ 0-9a-zA-Z_.!?()@#-]*$/', $value);

    return $this->success;
  }
}
