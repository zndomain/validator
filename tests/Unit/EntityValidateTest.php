<?php

namespace ZnDomain\Validator\Tests\Unit;

use ZnDomain\Validator\Exceptions\UnprocessibleEntityException;
use ZnDomain\Entity\Helpers\EntityHelper;
use ZnDomain\Validator\Helpers\ValidationHelper;
use ZnDomain\Validator\Tests\Libs\AccessEntity;
use ZnTool\Test\Base\BaseTest;

//include __DIR__ . '/../Libs/AccessEntity.php';

final class EntityValidateTest extends BaseTest
{

    public function testSuccess()
    {
        $entity = new AccessEntity;
        $entity->setProjectId(1);
        $entity->setUserId(2);

        ValidationHelper::validateEntity($entity);
        $this->assertTrue(true);
    }

    public function testRequired()
    {
        $entity = new AccessEntity;

        //$this->expectException(UnprocessibleEntityException::class);

        $expected = [
            [
                'field' => 'userId',
                //'message' => 'This value should not be blank.',
            ],
            [
                'field' => 'projectId',
                //'message' => 'This value should not be blank.',
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

    public function testInvalidType()
    {
        $entity = new AccessEntity;
        $entity->setProjectId('qwer');
        $entity->setUserId(2);

        $expected = [
            [
                "field" => "projectId",
//                "message" => "Значение должно быть положительным.",
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

    public function testInvalidRange()
    {
        $entity = new AccessEntity;
        $entity->setProjectId(-3);
        $entity->setUserId(2);

        $expected = [
            [
                'field' => 'projectId',
//                'message' => 'Значение должно быть положительным.',
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

}