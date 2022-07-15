<?php

namespace ZnDomain\Validator\Helpers;

use Psr\Container\ContainerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnCore\Container\Helpers\ContainerHelper;
use ZnDomain\Validator\Entities\ValidationErrorEntity;
use ZnDomain\Validator\Interfaces\ValidationByMetadataInterface;

class SymfonyValidationHelper
{

    /**
     * @return array | \ZnCore\Collection\Interfaces\Enumerable | ValidationErrorEntity[]
     */
    public static function validate(ValidationByMetadataInterface $entity): Enumerable
    {
        $validator = self::createValidator();
        /** @var ConstraintViolationList $violationsList */
        $violationsList = $validator->validate($entity);
        if ($violationsList->count()) {
            $violations = (array)$violationsList->getIterator();
        }
        return self::prepareUnprocessible2($violationsList);
//        return self::validateByMetadata($data);
    }

    public static function createValidator(): ValidatorInterface
    {
        $validatorBuilder = self::createValidatorBuilder();
        $validator = $validatorBuilder->getValidator();
        return $validator;
    }

    private static function createValidatorBuilder(): ValidatorBuilder
    {
        $container = ContainerHelper::getContainer();
        $validatorBuilder = $container->get(ValidatorBuilder::class);
        $validatorBuilder->addMethodMapping('loadValidatorMetadata');
        if ($container instanceof ContainerInterface && $container->has(TranslatorInterface::class)) {
            $translator = $container->get(TranslatorInterface::class);
        } else {
            $translator = new Translator('en');
        }
        $validatorBuilder->setTranslator($translator);
        return $validatorBuilder;
    }

    /**
     * @todo перенести в ErrorCollectionHelper::violationsToCollection
     */
    private static function prepareUnprocessible2(ConstraintViolationList $violationList): Enumerable
    {
        $collection = new Collection;
        foreach ($violationList->getIterator() as $violation) {
            $name = $violation->getPropertyPath();

            $violation->getCode();
            $entity = new ValidationErrorEntity;
            $entity->setField($name);
            $message = $violation->getMessage();

            /*$id = $violation->getMessageTemplate();
            $parametersI18Next = TranslatorHelper::paramsToI18Next($violation->getParameters());
            $id = TranslatorHelper::getSingularFromId($id);
            $key = 'message.' . TranslatorHelper::messageToHash($id);
            $transtatedMessage = I18Next::t('symfony', $key, $parametersI18Next);
            if ($transtatedMessage == $key) {
                //$entity->setMessage($message);
            } else {
//                $entity->setMessage($transtatedMessage);
                $message = $transtatedMessage;
            }*/
            $entity->setMessage($message);
            $entity->setViolation($violation);
            $collection->add($entity);
        }
        return $collection;
    }
}