<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidDate extends Constraint
{
    public $message = 'the date is not a valid date it should be greater than the current date.';
}