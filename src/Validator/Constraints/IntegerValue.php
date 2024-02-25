<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IntegerValue extends Constraint
{
    public $message = 'The value "{{ value }}" is not a valid integer.';
}