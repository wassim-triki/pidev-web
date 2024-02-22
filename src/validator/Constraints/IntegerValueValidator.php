<?php 
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IntegerValueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value !== null && !is_int($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}