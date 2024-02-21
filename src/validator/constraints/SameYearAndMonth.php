<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SameYearAndMonth extends Constraint
{
    public $message = 'The expiration date must be in the same year and month as the current system date.';
}
