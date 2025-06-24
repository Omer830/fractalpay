<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ErrorException extends Exception
{
    protected $customCode;
    protected $customMessage;
    protected $customName;
    protected $statusCode;
    private ?Throwable $previous;
    private bool $success;
    private array|null $messageArray;

    public function __construct(string $message, $code = 400, Throwable $previous = null)
    {

        $this->messageArray = $messageArray = $this->findMessage($message);

        $this->customMessage = $messageArray['message'] ?? null;
        $this->customCode = $messageArray['code'] ?? null;
        $this->customName = $messageArray['name'] ?? null;
        $this->statusCode = $messageArray['statusCode'] ?? $code;
        $this->success = $messageArray['success'] ?? false;
        $this->previous = $previous;

        parent::__construct($this->customMessage, $code, $previous);

    }

    public function findMessage($message)
    {
        $messages = collect(config('flashMessages'));

        if($messageArray = $messages->get($message)) {
           return $messageArray;
        }

        return $messages->get('DEFAULT_MESSAGE');

    }

    public function getCustomCode()
    {
        return $this->previous ? $this->previous->getCode() : $this->customCode;
    }

    public function getStatusCode($code = 404)
    {
        return $this->statusCode ?? $code;
    }

    public function getCustomMessage()
    {

        return [
            'name' => $this->customName,
            'code' => $this->getErrorCode(),
            'message' => $this->getMessageText(),
            'success'   =>  $this->success,
        ];
    }

    public function getMessageText()
    {

        if($this->previous) {

            if(method_exists($this->previous, 'getAwsErrorMessage')) {
                return $this->previous->getAwsErrorMessage();
            }

            return $this->previous->getMessage();
        }
        return $this->customMessage;
    }

    private function getErrorCode()
    {

        if($this->previous) {
            if(method_exists($this->previous, 'getAwsErrorCode')) {
                return $this->previous->getAwsErrorCode();
            }
            return $this->previous->getCode();
        }

        return $this->customCode;

    }

    public function getErrors()
    {
        return $this->messageArray['errors'] ?? [];
    }


}
