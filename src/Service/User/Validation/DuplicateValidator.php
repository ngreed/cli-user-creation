<?php

namespace App\Service\User\Validation;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DuplicateValidator
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
    public function isUnique(
        string $email,
		?string $firstName = null,
		?string $lastName = null,
		?string $phoneNumber1 = null,
		?string $phoneNumber2 = null,
		?string $comment = null
    ) : bool {
        return $this->isUniqueEmail($email)
            && $this->isUniqueSomethingElse();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isUniqueEmail(string $email) : bool
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isUniqueSomethingElse() : bool
    {
        return true;
    }
}