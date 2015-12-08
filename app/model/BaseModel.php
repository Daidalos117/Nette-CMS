<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.09.2015
 * Time: 15:15
*/

namespace App\Model;

use Nette\Database\Context;
use Nette\Object;
use Kdyby\Translation;

/**
 * Základní třída modelu pro všechny modely aplikace.
 * Předává přístup k práci s databází.
 * @package App\Model
 */
abstract class BaseModel extends Object
{
    /** @var Context Instance třídy pro práci s databází. */
    protected $database;

    /** @var \Kdyby\Translation\Translator */
    protected $translator;

    /**
     * Konstruktor s injektovanou třídou pro práci s databází.
     * @param Context $database automaticky injektovaná třída pro práci s databází
     */
    public function __construct(Context $database,Translation\Translator $translator)
    {
        $this->database = $database;
        $this->translator = $translator;
    }
}