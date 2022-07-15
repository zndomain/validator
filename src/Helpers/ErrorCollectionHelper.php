<?php

namespace ZnDomain\Validator\Helpers;

use ZnCore\Arr\Helpers\ArrayHelper;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnDomain\Validator\Entities\ValidationErrorEntity;

class ErrorCollectionHelper
{

    public static function collectionToArray(Enumerable $errorCollection): array
    {
        $array = [];
        /** @var ValidationErrorEntity $ValidationErrorEntity */
        foreach ($errorCollection as $ValidationErrorEntity) {
            $array[] = [
                'field' => $ValidationErrorEntity->getField(),
                'message' => $ValidationErrorEntity->getMessage(),
            ];
        }
        return $array;
    }

    public static function itemArrayToCollection(array $errors): Enumerable
    {
        $errorCollection = new Collection();
        foreach ($errors as $error) {
            $validationErrorEntity = new ValidationErrorEntity($error['field'], $error['message']);
            $errorCollection->add($validationErrorEntity);
        }
        return $errorCollection;
    }

    public static function flatArrayToCollection(array $errorArray): Enumerable
    {
        $errorCollection = new Collection();
        foreach ($errorArray as $field => $message) {
            $messages = ArrayHelper::toArray($message);
            foreach ($messages as $messageItem) {
                $ValidationErrorEntity = new ValidationErrorEntity($field, $messageItem);
                $errorCollection->add($ValidationErrorEntity);
            }


            /*if (is_array($message)) {
                if (ArrayHelper::isAssociative($message)) {
                    $ValidationErrorEntity = new ValidationErrorEntity($message['field'], $message['message']);
                } else {
                    foreach ($message as $m) {
                        $ValidationErrorEntity = new ValidationErrorEntity($field, $m);
                        $errorCollection->add($ValidationErrorEntity);
                    }
                }
            } else {
                $ValidationErrorEntity = new ValidationErrorEntity($field, $message);
                $errorCollection->add($ValidationErrorEntity);
            }*/
        }
        return $errorCollection;
    }
}
