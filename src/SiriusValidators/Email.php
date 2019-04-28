<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Email extends AbstractRule
{
  const MESSAGE = 'This input must be a valid email address';
  const LABELED_MESSAGE = '{label} must be a valid email address';

  public function validate($value, $valueIdentifier = null) {
    $this->value = $value;
    $this->success = preg_match('/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/', $value);
    return $this->success;
  }
}

