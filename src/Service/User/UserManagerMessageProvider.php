<?php

namespace App\Service\User;

use App\Entity\User;

class UserManagerMessageProvider
{
    const SUCCESS_CREATE_SINGLE  = 'User has been created.';
    const SUCCESS_CREATE_MULTIPLE  = "Users with following email addresses have been created:\r\n%s";
    const SUCCESS_DELETE  = 'User has been deleted.';
    const SUCCESS_FIND  = "User data: \r\nFirst Name: %s\r\nLast Name: %s\r\nEmail: %s \r\n"
        . "Phone1: %s\r\nPhone2: %s\r\nComment: %s\r\nDate Created: %s";
    const ERROR_GENERIC_SINGLE = 'Error.';
    const ERROR_GENERIC_MULTIPLE = "There was an error creating following users:\r\n%s.";
    const ERROR_FIND = 'User could not be found';
    const ERROR_INVALID_SINGLE = 'The data entered is invalid!';
    const ERROR_INVALID_MULTIPLE = "These users have invalid data:\r\n%s";
    const ERROR_DUPLICATE_SINGLE = 'A user like this already exists.';
    const ERROR_DUPLICATE_MULTIPLE = "Users like these already exist:\r\n%s";
    const ERROR_FILE = 'Could not open the file.';

    const MAP_CREATE = [
        UserManager::INDEX_SUCCESS => self::SUCCESS_CREATE_SINGLE,
        UserManager::INDEX_ERROR => self::ERROR_GENERIC_SINGLE,
        UserManager::INDEX_DUPLICATE => self::ERROR_DUPLICATE_SINGLE,
        UserManager::INDEX_INVALID => self::ERROR_INVALID_SINGLE,
    ];
    const MAP_DELETE = [
        UserManager::INDEX_SUCCESS => self::SUCCESS_DELETE,
        UserManager::INDEX_ERROR => self::ERROR_GENERIC_SINGLE,
        UserManager::INDEX_NOT_FOUND => self::ERROR_FIND,
    ];
    const MAP_IMPORT = [
        UserManager::INDEX_SUCCESS => self::SUCCESS_CREATE_MULTIPLE,
        UserManager::INDEX_ERROR => self::ERROR_GENERIC_MULTIPLE,
        UserManager::INDEX_DUPLICATE => self::ERROR_DUPLICATE_MULTIPLE,
        UserManager::INDEX_INVALID => self::ERROR_INVALID_MULTIPLE,
    ];

    /**
     * @param array $userData
     *
     * @return string
     */
    public function getCreateMessage(array $userData) : string
    {
        return self::MAP_CREATE[array_key_first($userData)];
    }

    /**
     * @param array $userData
     *
     * @return string
     */
    public function getDeleteMessage(array $userData) : string
    {
        return self::MAP_DELETE[array_key_first($userData)];
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

    /**
     * @param array|null $userData
     *
     * @return string
     */
    public function getImportMessage(?array $userData) : string
    {
        if (is_null($userData)) {
            return self::ERROR_FILE;
        }

        $message = '';
        foreach ($userData as $key => $value) {
            $message .= sprintf(self::MAP_IMPORT[$key], implode(', ', (array)$value)) . "\r\n";
        }

        return $message;
    }
}