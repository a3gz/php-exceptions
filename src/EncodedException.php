<?php
namespace A3gZ\PhpExceptions;

use Psr\Log\LoggerInterface;

class EncodedException extends \Exception
{
  protected $hint = null;
  protected $redirectUri = null;

  public function __construct($exception, LoggerInterface $logger, $redirectUri = null) {
    $ip = @$_SERVER['HTTP_CLIENT_IP'] ?: @$_SERVER['HTTP_X_FORWARDED_FOR'] ?: @$_SERVER['REMOTE_ADDR'];
    $now = time();
    $id = md5("{$ip}.{$now}");
    $this->hint = "Mention this code: {$id}";
    $this->redirectUri = $redirectUri;

    $logger->error($id, [
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'code' => $exception->getCode(),
      'message' => $exception->getMessage(),
    ]);

    $msg = "Encoded exception: {$id}";
    parent::__construct($msg, 500);
  }

  public function getHttpHeaders() {
    $headers = [
      'Content-type' => 'application/json',
    ];
    return $headers;
  }

  /**
   * Return an error with a message that doesn't disclose sensitive information.
   * The message includes a hint (see constructor) that helps identify the exact
   * log entry that describes the error fully.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function response($response, $redirectUri = null) {
    $headers = $this->getHttpHeaders();
    if ($redirectUri === null) {
      $redirectUri = $this->redirectUri;
    }

    $payload = [
      'error'   => 'encoded_exception',
      'message' => $this->getMessage(),
      'hint' => $this->hint,
    ];
    if ($redirectUri !== null) {
      if ($useFragment === true) {
        $redirectUri .= (strstr($redirectUri, '#') === false) ? '#' : '&';
      } else {
        $redirectUri .= (strstr($redirectUri, '?') === false) ? '?' : '&';
      }
      return $response
        ->withStatus(302)
        ->withHeader('Location', $redirectUri . http_build_query($payload));
    }

    foreach ($headers as $header => $content) {
      $response = $response->withHeader($header, $content);
    }
    $response->getBody()->write(json_encode($payload));
    $response = $response
      ->withStatus(500)
      ->withHeader('Content-Type', 'application/json');
    return $response;
  }
} // class

// EOF
