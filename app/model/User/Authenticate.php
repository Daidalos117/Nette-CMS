<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2015
 * Time: 21:38
 */

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Class Authenticate
 * @package App\Model
 */
class Authenticate implements Nette\Security\IAuthenticator{


    /** @var Nette\Database\Context */
    private $database;
    

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;


    }

    /**
     * Performs an authentication.
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;

        $row = $this->database->table(UserManager::TABLE_NAME)->where(UserManager::COLUMN_NAME, $username)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', UserManager::IDENTITY_NOT_FOUND);

        } elseif (!Passwords::verify($password, $row[UserManager::COLUMN_PASSWORD_HASH])) {
            throw new Nette\Security\AuthenticationException('The password is incorrect.', UserManager::INVALID_CREDENTIAL);

        } elseif (Passwords::needsRehash($row[UserManager::COLUMN_PASSWORD_HASH])) {
            $row->update(array(
                UserManager::COLUMN_PASSWORD_HASH => Passwords::hash($password),
            ));
        }

        $arr = $row->toArray();
        unset($arr[UserManager::COLUMN_PASSWORD_HASH]);
        return new Nette\Security\Identity($row[UserManager::COLUMN_ID], $row[UserManager::COLUMN_ROLE], $arr);
    }



}