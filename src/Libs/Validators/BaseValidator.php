<?php

namespace ZnDomain\Validator\Libs\Validators;

use ZnCore\Collection\Interfaces\Enumerable;
use ZnDomain\Validator\Exceptions\UnprocessibleEntityException;

class BaseValidator
{

    protected function handleResult(?Enumerable $errorCollection): void
    {
        if ($errorCollection && $errorCollection->count() > 0) {
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
    }
}
