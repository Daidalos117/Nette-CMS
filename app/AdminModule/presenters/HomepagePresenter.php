<?php
/**
 * User: Roman
 * Date: 26. 6. 2015
 * Time: 16:49
 * File: HomepagePresenter.php
 */

namespace App\AdminModule\Presenters;


use Nette,
    App\Model,
    App\Forms\SignFormFactory,
    Nette\Application\UI\Form;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

    /**
     * @var Nette\Database\Context
     */
    public $database;


    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * SignIn form
     * @return Form
     */
    protected function createComponentSignInForm()
    {

        $form = new Form;
        $form->getElementPrototype()->addAttributes(['class' => 'login']);
        $form->addText('email', $this->translator->translate("admin.loginPage.username"))
            ->setAttribute("placeholder","E-mail")
            ->setRequired('Prosím zadejte uživatelské jméno.');

        $form->addPassword('password', $this->translator->translate("admin.loginPage.pass"))
            ->setAttribute("placeholder",$this->translator->translate("admin.loginPage.pass"))
            ->setRequired($this->translator->translate("admin.loginPage.passNot"));

        $form->addSubmit('send', $this->translator->translate("admin.loginPage.login"))
            ->setAttribute("class","btn btn-success btn-sm");

        $form->onSuccess[] = array($this, 'signInFormSucceeded');
        return $form;

    }

    protected function createComponentNewUserForm()
    {
        $form = new Form;
        $form->addText('email', 'Email:');
        $form->addText('password', 'Password:');
        $form->addSubmit('send', 'Přidat usera');

        $form->onSuccess[] = array($this, 'newUserFormSucceeded');
        return $form;
    }

    public function newUserFormSucceeded($form)
    {

        $values = $form->values;
        $this->userManager->add($values->email, $values->password);
        $this->flashMessage('uživatel byl přidán');
    }




    public function signInFormSucceeded($form)
    {

        $values = $form->values;

        try {
            $this->getUser()->login($values->email, $values->password);
            #$this->user->setExpiration('30 minutes', TRUE);
            $this->redirect('Homepage:admin');

        } catch (Nette\Security\AuthenticationException $e) {

            $this->flashMessage('Chyba v přihlášení. Zkontrolujte si jméno a heslo.');

        }
    }

    public function renderAdmin(){
       // parent::isLogged();

    }

    public function renderDefault(){
        if($this->getUser()->isLoggedIn()){
            $this->redirect('Homepage:admin');
        }

    }




}