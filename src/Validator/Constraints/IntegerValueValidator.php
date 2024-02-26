<?php 
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
class IntegerValueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!is_float($value) && !is_numeric($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}