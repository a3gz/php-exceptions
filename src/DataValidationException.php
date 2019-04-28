<?php
namespace A3gZ\PhpExceptions;

class DataValidationException extends AbstractHttpSiriusException
{
  public static function invalidData($validator) {
    $e = new static(
      'Invalid data',
      'invalid_data',
      HttpStatus::HTTP_BAD_REQUEST,
      $validator
    );
    return $e;
  }
} // class

// EOF
