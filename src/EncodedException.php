<?php
namespace A3gZ\PhpExceptions;

use Psr\Log\LoggerInterface;

class EncodedException extends \Exception
{
  public function __construct($exception, LoggerInterface $logger) {
    $ip = @$_SERVER['HTTP_CLIENT_IP'] ?: @$_SERVER['HTTP_X_FORWARDED_FOR'] ?: @$_SERVER['REMOTE_ADDR'];
    $now = time();
    $id = md5("{$ip}.{$now}");

    $code = $exception->getCode();
    $logger->error($exception->getMessage(), [
      'code' => $code,
      'hint' => $id,
      'exception' => $exception,
    ]);

    parent::__construct("Encoded exception: {$id}", $code);
  }

  /**
   * Return an error with a message that doesn't disclose sensitive information.
   * The message includes a hint (see constructor) that helps identify the exact
   * log entry that describes the error fully.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function response($response) {
    $body = $response->getBody();
    $body->write($this->getMessage());

    $statusCode = $this->getCode();
    if (($statusCode < 100) || ($statusCode > 500)) {
      $statusCode = 500;
    }
    return $response->withStatus($statusCode)->withBody($body);
  }
} // class

// EOF
