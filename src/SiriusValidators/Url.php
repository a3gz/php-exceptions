<?php
namespace Lib\Validators;

use Sirius\Validation\Rule\AbstractRule;

class Url extends AbstractRule
{
  const MESSAGE = 'This input is not a valid URL';
  const LABELED_MESSAGE = '{label} is not a valid URL';

  public function validate($value, $valueIdentifier = null) {
    $this->value = $value;
    $this->success = preg_match('#^https?:\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?$#', $value);
    return $this->success;
  }
}

