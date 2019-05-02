<?php
namespace A3gZ\PhpExceptions;

class HttpException extends AbstractException
{
  public static function badRequest($msg = 'Bad Request') {
    return new static($msg, 'http_bad_request', 400);
  }

  public static function internalServerError($msg = 'Internal server error') {
    return new static($msg, 'http_internal_server_error', 500);
  }

  public static function notFound($msg = 'Not found') {
    return new static($msg, 'http_not_found', 404);
  }
} // class

// EOF
