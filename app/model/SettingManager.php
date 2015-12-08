<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2015
 * Time: 20:53
 */

namespace App\Model;

use Nette,
    Nette\Object,
    Exception,
    Tracy\Debugger;


/**
 * Class SettingManager, manages settings
 * @package App\Model
 */
class SettingManager extends BaseModel
{
    /** @var Nette\Database\Context */
    protected $database;


    const   TABLE_NAME = 'settings',
            COLUMN_NAME = 'name',
            COLUMN_VALUE = 'value',
            ROW_WEBSITE_NAME = 'name_web',
            ROW_PRIMARY_EMAIL = 'primary_email';



    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    public function getSettings(){
        return $this->database->table(self::TABLE_NAME)->select("*");
    }
}