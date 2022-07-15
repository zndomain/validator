<?php

namespace ZnDomain\Validator\Libs\Validators;

use ZnDomain\Validator\Helpers\SymfonyValidationHelper;
use ZnDomain\Validator\Interfaces\ValidationByMetadataInterface;
use ZnDomain\Validator\Interfaces\ValidatorInterface;

class ClassMetadataValidator extends BaseValidator implements ValidatorInterface
{

    public function validateEntity(object $entity): void
    {
        $errorCollection = SymfonyValidationHelper::validate($entity);
        $this->handleResult($errorCollection);
    }

    public function isMatch(object $entity): bool
    {
        return $entity instanceof ValidationByMetadataInterface;
    }
}
