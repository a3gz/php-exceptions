<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Image extends AbstractRule
{
  const MESSAGE = 'This input doesn\'t allow the following symbols: <>';
  const LABELED_MESSAGE = '{label} doesn\'t allow the following symbols: <>';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('#^[^<>]*$#', $value);

    return $this->success;
  }
}