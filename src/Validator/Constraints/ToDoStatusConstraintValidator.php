<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ToDoStatusConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $validStatuses = ['new', 'reviewed', 'important', 'completed'];

        // Перевірка чи статус є одним з валідних значень
        if (!in_array($value, $validStatuses)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ status }}', $value)
                ->addViolation();
        }

        // Тут ви також можете додати логіку для перевірки, чи є завдання "Важливим"
        // наприклад, перевіряючи дату створення завдання і порівнюючи її з поточною датою
    }
}
