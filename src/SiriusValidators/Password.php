<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Password extends AbstractRule
{
  const MESSAGE = 'This input must be between 4 and 255 characters long.';
  const LABELED_MESSAGE = '{label} must be between 4 and 255 characters long.';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('/^.{4,255}$/', $value);

    return $this->success;
  }
}