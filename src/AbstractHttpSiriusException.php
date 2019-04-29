<?php
namespace A3gZ\PhpExceptions;

use Psr\Http\Message\ResponseInterface as Response;

class AbstractHttpSiriusException extends AbstractHttpException
{
  private $details = null;

  protected $messages;

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
    parent::__construct($message, $errorType, $httpStatusCode, $hint, $redirectUri);

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

    $response = $response->withJson($payload);

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
      $response = $response->withJson($body);
    }
    return $response;
  }

  public function getDetails() {
    return $this->details;
  }
  public function getMessages() {
    return $this->messages;
  }
}

// EOF
