<?php
namespace A3gZ\PhpExceptions;

class HttpException extends AbstractHttpException
{
  public static function badRequest($msg = 'Bad Request') {
    return new static($msg, 400);
  }

  public static function internalServerError($msg = 'Internal server error') {
    return new static($msg, 500);
  }

  public static function notFound($msg = 'Not found') {
    return new static($msg, 404);
  }
} // class

// EOF
