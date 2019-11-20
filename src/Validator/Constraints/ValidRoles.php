<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidRoles extends Constraint
{
    public $message = 'Role Does not Exist';
}
