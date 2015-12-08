<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.09.2015
 * Time: 17:09
 */

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form,
    Nette\Database\Conventions,
    App\Model,
    Tracy\Debugger;

class LanguagePresenter extends BasePresenter
{

    /**
     * @var \App\Model\UserManager
     * @inject
     */
    public $userManager;

    /**
     * @var \App\Model\LanguageManager
     * @inject
     */
    public $languageManager;





    public function __construct(\App\Model\UserManager $userManager)
    {
        $this->userManager = $userManager;

    }



    public function actionSet($language){

        $this->languageManager->setUserLanguage($language,$this->user->id);
        $this->presenter->redirect("Homepage:");

    }





}