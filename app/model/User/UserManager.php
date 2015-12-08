<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Tracy\Debugger;



/**
 * Users management.
 */
class UserManager extends Nette\Object
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'email',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role',
		COLUMN_LANGUAGE = 'language_id';


	/** @var Nette\Database\Context */
	private $database;

	protected $user;

	public function __construct(Nette\Database\Context $database, Nette\Security\User $user)
	{
		$this->database = $database;
		$this->user = $user;

	}



	public function authenticate(array $credentials)
	{
		$authenticate = new Authenticate($this->database);
		return $authenticate->authenticate($credentials);

	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($username, $password)
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert(array(
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			));
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

	/**
	 * @return Nette/Security/User
	 */
	public function getUser(){

		$user = $this->user;
		return $user;
	}


}



class DuplicateNameException extends \Exception
{}
