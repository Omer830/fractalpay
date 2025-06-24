<?php

namespace App\Exceptions;


class Handler {

    private $errorException;

    public function __construct($errorException)
    {
        $this->errorException = $errorException;
    }

    public function response()
    {

        if($this->errorException instanceof ErrorException) {
            return response()->json($this->errorException->getCustomMessage(), $this->errorException->getStatusCode());
        }

        throw new ErrorException(message: 'ERROR', previous: $this->errorException);

    }

}
