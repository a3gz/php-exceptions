<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Time extends AbstractRule
{
  const MESSAGE = 'Invalid time';
  const LABELED_MESSAGE = '{label} must be a valid time';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('#^([01]\d|2[0-3]):?([0-5]\d)$#', $value);

    return $this->success;
  }
}