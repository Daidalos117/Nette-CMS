<?php


namespace App\AdminModule\Presenters;

use Nette\Application\UI\Form,

    Tracy\Debugger,
    Nette,
    Nette\Database\Conventions;

/**
 * Class BasePresenter
 * @package App\AdminModule\Presenters
 */
class BasEPresenter extends \App\Presenters\BasePresenter {


    /**
     * @var \Nette\Database\Context
     * @inject
     */
    public $database;


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

    const LOGIN_PAGE = "Admin:Homepage:default";


    public function __construct()
    {

    }


    public function startup(){
        parent::startup();

        /** @var  $presenterName */
        $presenterName = $this->getName();
        $parameters = $this->getParameters();
        $fullUrl = $presenterName.":".$parameters['action'];

        /** @var languages to template*/
        $this->template->languages = $this->languageManager->getLanguages();

        /** Only on secured section of admin */
        if( $this->isProtected($fullUrl) ){

            /** Runs stuff on protected section */
            $this->protectedMethod($fullUrl);

        }

    }

    /**
     * Is this section protected?
     * @param $fullUrl
     * @return bool
     */
    private function isProtected($fullUrl){
        if($fullUrl == self::LOGIN_PAGE){
            return false;
        }else{
            return true;
        }
    }


    /**
     * Is visitor logged? If not redirects him
     * @return void
     */
    public function isLogged(){
        $user = $this->getUser();

        if(!$user->isLoggedIn() ){
            $this->flashMessage($this->translator->translate("admin.messages.notLogged"));
            $this->redirect(":".self::LOGIN_PAGE);
        }
    }

    /**
     * Runs stuff on protected section of admin
     * @param $fullUrl
     */
    protected function protectedMethod($fullUrl){

        /** USER MUST BE LOGGED */
        $this->isLogged();

        /** SETTING LANGUAGE */
        $this->languageManager->setLocale();

        /** @var $breadcumberManager, Breadcrumbs to template*/
        $breadcumberManager = new \App\Model\BreadcrumbManager($fullUrl);
        $this->template->breadcrumbs = $breadcumberManager->getBreadcrumbs();

    }


    /**
     * Logout
     * @return void
     */
    public function actionOut()
    {
        $this->getUser()->logout(TRUE);
        $this->flashMessage($this->translator->translate("admin.messages.logout"));
        $this->redirect(":".self::LOGIN_PAGE);
    }

}