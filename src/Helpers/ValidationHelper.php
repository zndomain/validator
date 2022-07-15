<?php

namespace ZnDomain\Validator\Helpers;

use Symfony\Component\Validator\ConstraintViolationList;
use ZnCore\Container\Helpers\ContainerHelper;
use ZnDomain\Validator\Entities\ValidationErrorEntity;
use ZnDomain\Validator\Libs\Validators\ChainValidator;

class ValidationHelper
{

    public static function validateEntity(object $entity): void
    {
        $container = ContainerHelper::getContainer();
        $validator = $container->get(ChainValidator::class);
        $validator->validateEntity($entity);

//        $errorCollection = self::validate($entity);
//        if ($errorCollection && $errorCollection->count() > 0) {
//            $exception = new UnprocessibleEntityException;
//            $exception->setErrorCollection($errorCollection);
//            throw $exception;
//        }
    }

//    /**
//     * @return array | \ZnCore\Collection\Interfaces\Enumerable | ValidationErrorEntity[]
//     */
//    private static function validate(object $data): ?Enumerable
//    {
//        if ($data instanceof ValidateDynamicEntityInterface) {
//            return DynamicEntityValidationHelper::validate($data);
//        } elseif ($data instanceof ValidationByMetadataInterface) {
//            return SymfonyValidationHelper::validate($data);
//        } else {
//            return null;
//        }
//    }

    /**
     * @return array | \ZnCore\Collection\Interfaces\Enumerable | ValidationErrorEntity[]
     */
    public static function validateValue($value, array $rules): ConstraintViolationList
    {
        $validator = SymfonyValidationHelper::createValidator();
        $violations = $validator->validate($value, $rules);
        return $violations;
    }
}
