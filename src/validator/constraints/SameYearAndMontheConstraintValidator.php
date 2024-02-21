<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SameYearAndMonthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof SameYearAndMonth) {
            throw new UnexpectedTypeException($constraint, SameYearAndMonth::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // Validate if the expiration date is in the same year and month as the current system date
        $currentYear = date('Y');
        $currentMonth = date('m');
        $expirationYear = $value->format('Y');
        $expirationMonth = $value->format('m');

        if ($currentYear !== $expirationYear || $currentMonth !== $expirationMonth) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
