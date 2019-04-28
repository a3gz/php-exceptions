<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;
use Lib\UUID as LibUuid;

class Uuid extends AbstractRule
{
  const MESSAGE = 'This input is not a valid UUID';
  const LABELED_MESSAGE = '{label} is not a valid UUID';

  protected function isValid($uuid) {
    return (boolean)preg_match(
      '/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i',
      $uuid
    );
  }

  public function validate($value, $valueIdentifier = null) {
    $this->value = $value;
    $this->success = $this->isValid($value);
    return $this->success;
  }
}

