<?php
namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class TaxRate extends AbstractRule
{
  const MESSAGE = 'This input must be a decimal value with up to four decimals';
  const LABELED_MESSAGE = '{label} must be a decimal value with up to four decimals';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('/^[0-9]{1,4}+.[0-9]{4}$/', $value);

    return $this->success;
  }
}