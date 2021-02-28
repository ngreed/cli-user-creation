<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    const INDEX_SUCCESS = 'success';
    const INDEX_ERROR = 'error';
    const INDEX_DUPLICATE = 'duplicate';
    const INDEX_INVALID_EMAIL = 'invalidEmail';
    const INDEX_NOT_FOUND = '404';

	private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
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
     * @return array
     */
	public function create(
		?string $firstName,
		?string $lastName,
		string $email,
		?string $phoneNumber1,
		?string $phoneNumber2,
		?string $comment
	) : array {
        if (!$this->validateEmail($email)) {
            return [self::INDEX_INVALID_EMAIL => $email];
        }

        $existingUser = $this->find($email);
        if ($existingUser instanceof User) {
            return [self::INDEX_DUPLICATE => $email];
        }

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

            return [self::INDEX_ERROR => $email];
        }

        return [self::INDEX_SUCCESS => $email];
	}

    /**
     * @param string $email
     *
     * @return array
     */
	public function delete(string $email) : array
    {
        $user = $this->find($email);
        if (!$user instanceof User) {
            return [self::INDEX_NOT_FOUND => $email];
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

            return [self::INDEX_ERROR => $email];
        }

        return [self::INDEX_SUCCESS => $email];
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
     * @param string $filepath
     *
     * @return array|null
     */
    public function import(string $filepath) : ?array
    {
        if (($handle = fopen($filepath, "r")) !== false) {
            $userData = [];
            while (($data = fgetcsv($handle, 255)) !== false) {
                $userData = array_merge_recursive(
                    $userData,
                    $this->create(
                        $data[0],
                        $data[1],
                        $data[2],
                        $data[3],
                        $data[4],
                        $data[5]
                    )
                );
            }
            fclose($handle);

            return $userData;
        }

        return null;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function validateEmail(string $email) : bool
    {
        return preg_match('|^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.com$|', $email);
    }
}