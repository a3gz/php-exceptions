<?php
namespace A3gZ\PhpExceptions;

class HttpException extends AbstractException
{
  public static function accessDenied($msg = 'Access denied', $redirectUrl = null) {
    return new static($msg, 'http_access_denied', 403, null, $redirectUrl);
  }

  public static function badRequest($msg = 'Bad Request', $redirectUrl = null) {
    return new static($msg, 'http_bad_request', 400, null, $redirectUrl);
  }

  public static function conflict($msg = 'Conflict', $redirectUrl = null) {
    return new static($msg, 'http_conflict', 409, null, $redirectUrl);
  }

  public static function internalServerError($msg = 'Internal server error', $redirectUrl = null) {
    return new static($msg, 'http_internal_server_error', 500, null, $redirectUrl);
  }

  public static function notFound($msg = 'Not found', $redirectUrl = null) {
    return new static($msg, 'http_not_found', 404, null, $redirectUrl);
  }
} // class

// EOF
