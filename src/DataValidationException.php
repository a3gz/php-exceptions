<?php
namespace A3gZ\PhpExceptions;

class DataValidationException extends AbstractHttpSiriusException
{
  public static function invalidData($validator) {
    $e = new static(
      'Invalid data',
      'invalid_data',
      400,
      $validator
    );
    return $e;
  }
} // class

// EOF
