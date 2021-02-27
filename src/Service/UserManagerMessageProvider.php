<?php

namespace App\Service;

use App\Entity\User;

class UserManagerMessageProvider
{
    const SUCCESS_CREATE  = 'User has been created.';
    const SUCCESS_DELETE  = 'User has been deleted.';
    const SUCCESS_FIND  = "User data: \r\nFirst Name: %s\r\nLast Name: %s\r\nEmail: %s \r\n"
        . "Phone1: %s\r\nPhone2: %s\r\nComment: %s\r\nDate Created: %s";
    const ERROR_CREATE = 'There was an error creating the user.';
    const ERROR_DELETE = 'There was an error deleting the user.';
    const ERROR_FIND = 'User could not be found';
    const ERROR_EMAIL = 'Email Address is not valid!';

    /**
     * @param User|null $user
     *
     * @return string
     */
    public function getCreateMessage(?User $user) : string
    {
        return $user instanceof User
            ? self::SUCCESS_CREATE
            : self::ERROR_CREATE;
    }

    /**
     * @param bool $isDeleted
     *
     * @return string
     */
    public function getDeleteMessage(bool $isDeleted) : string
    {
        return $isDeleted
            ? self::SUCCESS_DELETE
            : self::ERROR_DELETE;
    }

    /**
     * @param User|null $user
     *
     * @return string
     */
    public function getFindMessage(?User $user): string
    {
        return $user instanceof User
            ? sprintf(
                self::SUCCESS_FIND,
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                $user->getPhoneNumber1(),
                $user->getPhoneNumber2(),
                $user->getComment(),
                $user->getDoc()->format('Y-m-d')
            )
            : self::ERROR_FIND;
    }
}