<?php
namespace A3gZ\PhpExceptions;

use Psr\Http\Message\ResponseInterface as Response;

class AbstractSiriusException extends AbstractException
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

    $parsedMessages = [];
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
    $response = parent::generateHttpResponse($response);
    if ($this->redirectUri !== null) {
      return $response;
    }

    $messages = $this->getMessages();
    if (count($messages)) {
      $validation = [];
      foreach ($messages as $fieldName => $errors) {
        $parsedErrors = [];
        foreach ($errors as $anError) {
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
      $body->write(json_encode($payload));
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
