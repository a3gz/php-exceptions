<?php

namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class FileName extends AbstractRule {
  const MESSAGE = 'This input must be a valid file name';
  const LABELED_MESSAGE = '{label} must be a valid file name';

  public function validate($value, $valueIdentifier = null): bool {
    $this->value   = $value;
    $this->success = preg_match('#^[- _.0-9a-zA-Z]*$#', $value);

    return $this->success;
  }
}
