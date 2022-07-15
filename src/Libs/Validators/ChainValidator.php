<?php

namespace ZnDomain\Validator\Libs\Validators;

use Psr\Container\ContainerInterface;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnCore\Container\Traits\ContainerAwareAttributeTrait;
use ZnCore\Instance\Libs\Resolvers\InstanceResolver;
use ZnDomain\Validator\Interfaces\ValidatorInterface;

class ChainValidator implements ValidatorInterface
{

    use ContainerAwareAttributeTrait;

    /** @var Enumerable | ValidatorInterface[] */
    private $validators = [];

    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function setValidators(array $validators): void
    {
        $instances = new Collection();
        $instanceResolver = new InstanceResolver($this->getContainer());
        foreach ($validators as $validatorDefinition) {
            $validatorInstance = $instanceResolver->ensure($validatorDefinition);
            $instances->add($validatorInstance);
        }
        $this->validators = $instances;
    }

    public function validateEntity(object $entity): void
    {
        foreach ($this->validators as $validatorInstance) {
            if ($validatorInstance->isMatch($entity)) {
                $validatorInstance->validateEntity($entity);
            }
        }
    }

    public function isMatch(object $entity): bool
    {
        return true;
    }
}
