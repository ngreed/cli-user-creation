<?php

namespace App\Service\User\Validation;

class DataValidator
{
    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber1
     * @param string $phoneNumber2
     * @param string $comment
     *
     * @return bool
     */
    public function validate(
        string $email,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phoneNumber1 = null,
        ?string $phoneNumber2 = null,
        ?string $comment = null
    ) : bool {
        return $this->validateEmail($email)
            && $this->validateSomethingElse();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function validateEmail(string $email) : bool
    {
        return preg_match('|^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.com$|', $email);
    }

    /**
     * @return bool
     */
    private function validateSomethingElse() : bool
    {
        return true;
    }
}