<?php
namespace A3gZ\PhpExceptions\SiriusValidators;

use Sirius\Validation\Rule\AbstractRule;

class CustomRegex extends AbstractRule
{
  const MESSAGE = 'This input must match the pattern {pattern}.';
  const LABELED_MESSAGE = '{label} must match {pattern}.';
  
  protected $optionsIndexMap = [
    'pattern'
  ];

  public function validate($value, $valueIdentifier = null) {
    $this->value   = $value;
    $this->success = preg_match($this->options['pattern'], $value);

    return $this->success;
  }
}