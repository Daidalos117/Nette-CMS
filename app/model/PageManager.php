<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.09.2015
 * Time: 16:30
 */
namespace App\Model;

use Nette\Object,
    Exception,
    App\Presenters\BasePresenter;


/**
 * Zpracov�v� vykreslov�n� str�nek.
 */

class PageManager extends BaseModel{

    /** Konstanty pro manipulaci s modelem. */
    const
        TABLE_NAME = 'pages',
        COLUMN_ID = 'id',
        COLUMN_URL = 'url';





}