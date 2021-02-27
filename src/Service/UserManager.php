<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * @var EntityManager
     */
	private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
	public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string      $email
     * @param string|null $phoneNumber1
     * @param string|null $phoneNumber2
     * @param string|null $comment
     *
     * @return User|null
     */
	public function create(
		?string $firstName,
		?string $lastName,
		string $email,
		?string $phoneNumber1,
		?string $phoneNumber2,
		?string $comment
	) : ?User {
		$user = new User;
		$user
			->setFirstName($firstName)
			->setLastName($lastName)
			->setEmail($email)
			->setPhoneNumber1($phoneNumber1)
			->setPhoneNumber2($phoneNumber2)
			->setComment($comment)
			->setDoc(new \DateTime());

		try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log(
                sprintf(
                    'There was an exception in %s class %s function: %s',
                    self::class,
                    __FUNCTION__ . '()',
                    $e->getMessage()
                )
            );

            return null;
        }

        return $user;
	}

    /**
     * @param string $email
     *
     * @return bool
     */
	public function delete(string $email) : bool
    {
        $user = $this->find($email);
        if (!$user instanceof User) {
            return false;
        }

        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log(
                sprintf(
                    'There was an exception in %s class %s function: %s',
                    self::class,
                    __FUNCTION__ . '()',
                    $e->getMessage()
                )
            );

            return false;
        }

        return true;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function find(string $email) : ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function validateEmail(string $email) : bool
    {
        return preg_match('|^[a-zA-Z0-9!@#$%^&*()]+@[a-zA-Z0-9!@#$%^&*()]+\.com$|', $email);
    }
}