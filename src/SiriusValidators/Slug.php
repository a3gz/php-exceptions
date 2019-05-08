<?php
namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class Slug extends AbstractRule
{
  const MESSAGE = 'This input can contain only digits, letters and dashes';
  const LABELED_MESSAGE = '{label} can contain only digits, letters and dashes';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('#^[-_0-9a-zA-Z]*$#', $value);

    return $this->success;
  }
}