<?php

namespace ZnDomain\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BooleanValidator extends BaseValidator
{

    protected $constraintClass = Boolean::class;

    public function validate($value, Constraint $constraint)
    {
        $this->checkConstraintType($constraint);
        if ($this->isEmptyStringOrNull($value)) {
            return;
        }

        if (!is_bool($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'boolean');
        }
    }
}
