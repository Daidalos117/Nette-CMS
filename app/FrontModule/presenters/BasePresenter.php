<?php
/**
 * User: Roman
 * Date: 26. 6. 2015
 * Time: 16:58
 * File: BasePresenter.php
 */

namespace App\FrontModule\Presenters;

use Nette, Nette\Application\UI\Form;


class BasePresenter extends \App\Presenters\BasePresenter {

    /**
     * @var \App\Model\UserManager
     * @inject
     */
    public $userManager;

    public $database;


    public function __construct(Nette\Database\Context $database){
        $this->database = $database;
    }

    protected function createComponentSignInForm(){

        $form = new Form;

        $form->addText('email', 'Email:')
            ->setAttribute('class','pole');

        $form->addPassword('password', 'Heslo:')
            ->setAttribute('class','pole');

        $form->addSubmit('send', 'Přihlásit')
            ->setAttribute('class','potvrd');

        $form->onSuccess[] = array($this, 'signInFormSucceeded');

        return $form;
    }

    public function signInFormSucceeded($form, $values){


    }


}