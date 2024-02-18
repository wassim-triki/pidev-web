<?php

// src/Validator/Constraints/CustomPassword.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
#[\Attribute] class Password extends Constraint
{
    public $message = 'Password is required.';
    public $minMessage = 'Your password must be at least {{ limit }} characters long.';
    public $maxMessage = 'Your password cannot be longer than {{ limit }} characters.';
    public $numberMessage = 'Your password must contain at least one number.';
    public $characterMessage = 'Your password must contain at least one letter.';

    public $minLength = 6;
    public $maxLength = 4096;
}
