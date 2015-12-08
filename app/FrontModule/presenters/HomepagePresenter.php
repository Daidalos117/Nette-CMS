<?php
/**
 * User: Roman
 * Date: 26. 6. 2015
 * Time: 16:57
 * File: HomepagePresenter.php
 */

namespace App\FrontModule\Presenters;

use Nette,
    App\Model;

class HomepagePresenter extends BasePresenter
{

    /** @var Nette\Database\Context */
    public $database;

    public function __construct(Nette\Database\Context $database){
        $this->database = $database;
    }

    public function beforeRender(){



    }
    public function renderDefault(){


    }

}