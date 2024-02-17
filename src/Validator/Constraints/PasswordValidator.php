<?php

// src/Validator/Constraints/CustomPasswordValidator.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, Password::class);
        }

//        if (null === $value || '' === $value) {
//            $this->context->buildViolation($constraint->message)
//                ->addViolation();
//            return;
//        }
//
//        if (!is_string($value)) {
//            throw new UnexpectedValueException($value, 'string');
//        }

        $length = strlen($value);

        if ($length < $constraint->minLength && $length > 0) {
            $this->context->buildViolation($constraint->minMessage)
                ->setParameter('{{ limit }}', $constraint->minLength)
                ->addViolation();
            return;
        }

        if ($length > $constraint->maxLength) {
            $this->context->buildViolation($constraint->maxMessage)
                ->setParameter('{{ limit }}', $constraint->maxLength)
                ->addViolation();
            return;
        }

        if (!preg_match('/\d/', $value ) && $length > 0) {
            $this->context->buildViolation($constraint->numberMessage)
                ->addViolation();
            return;
        }

        if (!preg_match('/[a-zA-Z]/', $value) && $length > 0) {
            $this->context->buildViolation($constraint->characterMessage)
                ->addViolation();
            return;
        }
    }
}
