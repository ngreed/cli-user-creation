<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserManager
{
    const ERROR_DOCTRINE = 'There was an error with persisting the changes to the database';
    const ERROR_FIND = "Couldn't find this user.";
    const SUCCESS_ADDED  = 'User has been created.';
    const SUCCESS_DELETED  = 'User has been deleted.';

    /**
     * @var EntityManager
     */
	private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
	public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string $email
     * @param string|null $phoneNumber1
     * @param string|null $phoneNumber2
     * @param string|null $comment
     *
     * @return string
     */
	public function create(
		?string $firstName,
		?string $lastName,
		string $email,
		?string $phoneNumber1,
		?string $phoneNumber2,
		?string $comment
	) : string {
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
            return self::ERROR_DOCTRINE;
        }

        return self::SUCCESS_ADDED;
	}

    /**
     * @param string $email
     *
     * @return string
     */
	public function delete(string $email) : string
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            return self::ERROR_FIND;
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}