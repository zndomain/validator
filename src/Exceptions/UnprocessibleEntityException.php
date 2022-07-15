<?php

namespace ZnDomain\Validator\Exceptions;

use Error;
use Symfony\Component\Validator\ConstraintViolation;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnDomain\Validator\Entities\ValidationErrorEntity;

class UnprocessibleEntityException extends Error
{

    public function __construct(Enumerable $errorCollection = null)
    {
        if ($errorCollection) {
            $this->setErrorCollection($errorCollection);
        }
    }

    /**
     * @var array | Enumerable | ValidationErrorEntity[]
     */
    private $errorCollection;

    public function setErrorCollection(Enumerable $errorCollection)
    {
        $this->errorCollection = $errorCollection;
        $this->updateMessage();
    }

    /**
     * @return array | Enumerable | ValidationErrorEntity[] | null
     */
    public function getErrorCollection(): ?Enumerable
    {
        if ($this->errorCollection) {
            foreach ($this->errorCollection as $errorEntity) {
                if (!$errorEntity->getViolation()) {
                    $violation = new ConstraintViolation($errorEntity->getMessage(), null, [], null, $errorEntity->getField(), null);
                    $errorEntity->setViolation($violation);
                }
            }
        }
        return $this->errorCollection;
    }

    public function add(string $field, string $message): UnprocessibleEntityException
    {
        if (!isset($this->errorCollection)) {
            $this->errorCollection = new Collection();
        }
        $this->errorCollection[] = new ValidationErrorEntity($field, $message);
        $this->updateMessage();
        return $this;
    }

    protected function updateMessage()
    {
        $message = '';
        foreach ($this->errorCollection as $errorEntity) {
            $message .= $errorEntity->getField() . ': ' . $errorEntity->getMessage();
        }
        $this->message = $message;
    }
}
