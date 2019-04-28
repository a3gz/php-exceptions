<?php
namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class Language extends AbstractRule
{
  const MESSAGE = 'Invalid language code';
  const LABELED_MESSAGE = '{label} must be: English, French or Spanish';
  
  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match('#^(en|fr|es)$#', $value);

    return $this->success;
  }
}