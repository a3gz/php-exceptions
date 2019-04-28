<?php
namespace A3gZ\PhpExceptions;

use Psr\Http\Message\ResponseInterface as Response;

class AbstractHttpSiriusException extends \Exception
{
  private $details = null;

  private $httpStatusCode;

  private $errorType;

  private $hint;

  protected $messages;

  protected $redirectUri = null;

  /**
   * Throw a new exception.
   *
   * @param string      $message        Error message
   * @param string      $errorType      Error type
   * @param int         $httpStatusCode HTTP status code to send (default = 400)
   * @param null|string|Sirius\Validation\Validator $hint           A helper hint
   * @param null|string $redirectUri    A HTTP URI to redirect the user back to
   */
  public function __construct($message, $errorType, $httpStatusCode = 400, $hint = null, $redirectUri = null) {
    parent::__construct($message, $httpStatusCode);
    $this->httpStatusCode = $httpStatusCode;
    $this->errorType = $errorType;
    $this->redirectUri = $redirectUri;

    if ($hint instanceof \Sirius\Validation\Validator) {
      $this->hint = null;
      $this->messages = $hint->getMessages();
      $parsedMessages = [];
      if ($messages) {
        $parsedMessages = [];
        foreach ($messages as $fieldName => $errors) {
          $parsedErrors = [];
          foreach ($errors as $anError) {
            $parsedErrors[] = (string)$anError;
          }
          $parsedMessages[$fieldName] = $parsedErrors;
        }
      }
    } else {
      $this->parsedMessages = [];
      $this->hint = $hint;
    }

    $this->details = [
      'code' => $code,
      'type' => $errorType,
      'httpStatusCode' => $httpStatusCode,
      'hint' => $hint,
      'messages' => $parsedMessages,
    ];
  }

  /**
   * Override the parent's method to inject the validation errors, 
   * if there are any.
   *
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

    $response->getBody()->write(json_encode($payload));

    $response = $response->withStatus($this->getHttpStatusCode());

    $messages = $this->getMessages();
    if (count($messages)) {
      $validation = [];
      foreach( $messages as $fieldName => $errors ) {
        $parsedErrors = [];
        foreach( $errors as $anError ) {
          $parsedErrors[] = (string)$anError;
        }
        $validation[] = [
          'fieldName' => $fieldName,
          'errors' => $parsedErrors,
        ];
      }
      $body = $response->getBody();
      $body->rewind();
      $payload = json_decode($body->read($body->getSize()), true);
      $payload = array_merge($payload, ['validation' => $validation]);
      $body->rewind();
      $body->write(json_encode($payload));
      $response = $response->withBody($body);
    }
    return $response;
  }

  public function getDetails() {
    return $this->details;
  }

  public function getErrorType() {
    return $this->errorType;
  }

  public function getHint() {
    return $this->hint;
  }

  /**
   * Get all headers that have to be send with the error response.
   *
   * @return array Array with header values
   */
  public function getHttpHeaders() {
    $headers = [
      'Content-type' => 'application/json',
    ];

    // Add "WWW-Authenticate" header
    //
    // RFC 6749, section 5.2.:
    // "If the client attempted to authenticate via the 'Authorization'
    // request header field, the authorization server MUST
    // respond with an HTTP 401 (Unauthorized) status code and
    // include the "WWW-Authenticate" response header field
    // matching the authentication scheme used by the client.
    // @codeCoverageIgnoreStart
    if ($this->errorType === 'invalid_client') {
      $authScheme = 'Basic';
      if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER) !== false
        && strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer') === 0
      ) {
        $authScheme = 'Bearer';
      }
      $headers['WWW-Authenticate'] = $authScheme . ' realm="OAuth"';
    }
    // @codeCoverageIgnoreEnd
    return $headers;
  }

  public function getMessages() {
    return $this->messages;
  }

  public function getHttpStatusCode() {
    return $this->httpStatusCode;
  }
}

// EOF
