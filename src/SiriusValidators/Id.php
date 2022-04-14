<?php

namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class Id extends AbstractRule {
  const MESSAGE = 'This input can contain only digits';
  const LABELED_MESSAGE = '{label} can contain only digits';

  public function validate($value, $valueIdentifier = null): bool {
    $this->value   = $value;
    $this->success = preg_match('/^[0-9]*$/', $value);

    return $this->success;
  }
}
