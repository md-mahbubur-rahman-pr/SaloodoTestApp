<?php

namespace App\Validator\Constraints;

use App\Enums\UserRoleEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class ValidRolesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        $roles = UserRoleEnum::getConstants();

        if (\is_array($value)) {
            foreach ($value as $key => $role) {
                if (!\in_array($role, $roles)) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        } else {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
