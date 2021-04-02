<?php
namespace A3gZ\PhpExceptions;

use Psr\Http\Message\ResponseInterface as Response;

class AbstractException extends \Exception
{
  private $httpStatusCode;

  private $errorType;

  private $hint;

  protected $redirectUri = null;

  /**
   * Throw a new exception.
   *
   * @param string      $message        Error message
   * @param string      $errorType      Error type
   * @param int         $httpStatusCode HTTP status code to send (default = 400)
   * @param null|string $hint           A helper hint
   * @param null|string $redirectUri    A HTTP URI to redirect the user back to
   */
  public function __construct($message, $errorType, $httpStatusCode = 400, $hint = null, $redirectUri = null) {
    parent::__construct($message, $httpStatusCode);
    $this->httpStatusCode = $httpStatusCode;
    $this->errorType = $errorType;
    $this->redirectUri = $redirectUri;
    $this->hint = $hint;

    $this->details = [
      'type' => $errorType,
      'httpStatusCode' => $httpStatusCode,
      'hint' => $hint,
    ];
  }

  /**
   * @return Psr\Http\Message\ResponseInterface
   */
  public function generateHttpResponse(Response $response) {
    $headers = $this->getHttpHeaders();

    $payload = [
      'error'   => $this->getErrorType(),
      'message' => $this->getMessage(),
    ];
    if ($this->hint !== null) {
      $payload['hint'] = $this->hint;
    }
    if ($this->redirectUri !== null) {
      if ($useFragment === true) {
        $this->redirectUri .= (strstr($this->redirectUri, '#') === false) ? '#' : '&';
      } else {
        $this->redirectUri .= (strstr($this->redirectUri, '?') === false) ? '?' : '&';
      }
      return $response
        ->withStatus(302)
        ->withHeader('Location', $this->redirectUri . http_build_query($payload));
    }

    foreach ($headers as $header => $content) {
      $response = $response->withHeader($header, $content);
    }
    $response->getBody()->write($payload);
    $response = $response
      ->withStatus($this->getHttpStatusCode())
      ->withHeader('Content-Type', 'application/json');
    return $response;
  }

  public function getErrorType() {
    return $this->errorType;
  }

  public function getHint() {
    return $this->hint;
  }

  public function getHttpHeaders() {
    $headers = [
      'Content-type' => 'application/json',
    ];
    return $headers;
  }

  public function getHttpStatusCode() {
    return $this->httpStatusCode;
  }
}

// EOF
