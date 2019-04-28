<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Price extends AbstractRule
{
  const MESSAGE = 'This input must be a decimal value with up to two decimals';
  const LABELED_MESSAGE = '{label} must be a decimal value with up to two decimals';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('/^[0-9]{1,4}+.[0-9]{2}$/', $value);

    return $this->success;
  }
}