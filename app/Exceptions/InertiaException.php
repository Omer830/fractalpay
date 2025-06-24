<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InertiaException extends \Error
{

    public function __construct(protected string $component, protected Throwable $exception, protected $code = 403)
    {
        parent::__construct();
    }

    public function getComponent()
    {
        return $this->component;
    }

    public function getMessages()
    {
        return $this->exception->getMessage();
    }

    public function getErrors()
    {
        return ['errors' => $this->exception->getErrors()];
    }

}
