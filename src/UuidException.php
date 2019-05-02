<?php
namespace A3gZ\PhpExceptions;

use Lukasoppermann\Httpstatus\Httpstatuscodes as HttpStatus;

class UuidException extends AbstractException
{
  public static function invalid($uuid = null) {
    return new static(
      "Invalid UUID: {$uuid}",
      'invalid_uuid',
      400
    );
  }
} // class

// EOF
