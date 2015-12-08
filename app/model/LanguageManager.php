<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.09.2015
 * Time: 17:17
 */

namespace App\Model;

use Nette,
    Nette\Object,
    Exception,
    Tracy\Debugger;

/**
 * Class LanguageManager
 * @package App\Model
 */

class LanguageManager extends BaseModel{

    /** Konstanty pro manipulaci s modelem. */
    const
        TABLE_NAME = 'languages',
        COLUMN_ID = 'id',
        COLUMN_NAME = 'name',
        COLUMN_SHORT = 'short';

    private $userManager;

    /** @var Nette\Database\Context */
    protected $database;

    /** @var \Kdyby\Translation\Translator  */
    public $translator;

    public function __construct(\App\Model\UserManager $userManager,Nette\Database\Context $database,\Kdyby\Translation\Translator $translator)
    {
        $this->userManager = $userManager;
        $this->database = $database;
        $this->translator = $translator;
    }

    /**
     * Accept language as short and name and translate it to id
     * @param $language string
     * @return int
     */
    public function languageToId($language){
        $language = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_SHORT,$language)->fetchField(self::COLUMN_ID);
        if(!$language){
            $language = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME,$language)->fetchField(self::COLUMN_ID);
        }
        return $language;
    }

    /**
     * ID to language (short,name)
     * @param $id int
     * @param string $type
     * @return FALSE|mixed
     */
    public function idToLanguage($id,$type = 'SHORT'){

        $language = $this->database->table(self::TABLE_NAME)->wherePrimary($id)->fetchField(self::COLUMN_SHORT);

        return $language;
    }

    /**
     * @param string $language if not set, user's language is set
     */
    public function setLocale($language = ""){
        if($language == ""){
            $user = $this->userManager->getUser();
            $language = $this->getLanguage($user->id);
        }
        $this->translator->setLocale($language);
    }

    /**
     * Sets language for user
     * @param $language string
     * @param null $userId int
     * @return void
     */
    public function setUserLanguage($language,$userId = NULL){

        $languageId = $this->languageToId($language);

        $this->database->table(UserManager::TABLE_NAME)
            ->wherePrimary($userId)
            ->update([UserManager::COLUMN_LANGUAGE => $languageId]);


    }


    /**
     * Gets language for specific user
     * @param $userId int
     * @return FALSE|mixed
     */
    public function getLanguage($userId){

        $language = $this->database->table(UserManager::TABLE_NAME)
            ->select(UserManager::COLUMN_LANGUAGE.".".self::COLUMN_SHORT." AS short")
            ->where(UserManager::TABLE_NAME.".".UserManager::COLUMN_ID,$userId)
            ->fetchField(self::COLUMN_SHORT);
        return $language;
    }


    /**
     * Returns languages as objects
     * @return array|Nette\Database\Table\IRow[]
     */
    public function getLanguages(){

        return $this->database->table(self::TABLE_NAME)->fetchAll();

    }
}