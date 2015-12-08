<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2015
 * Time: 22:12
 */


namespace App\AdminModule\Presenters;


use Nette,
    App\Model,
    App\Forms\SignFormFactory,
    Nette\Application\UI\Form;
use Tracy\Debugger;


/**
 * Setting presenter.
 */
class SettingPresenter extends BasePresenter
{

    public $database;

    /**
     * @var \App\Model\SettingManager @inject
     */
    public $settingManager;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    public function renderDefault(){

    }

    /**
     * @return Form
     */
    public function createComponentSettings(){

        $form = new Form(NULL,"settings");
        $settings = $this->settingManager->getSettings()->fetchPairs("name","value");

        Debugger::barDump( $settings );


        $form->getElementPrototype()->class('form-full');

        $form->addText(Model\SettingManager::ROW_WEBSITE_NAME,$this->translator->translate("admin.settings.nameofweb").":")
            ->setAttribute('class','form-control')
            ->setAttribute('placeholder',$this->translator->translate("admin.settings.typename"));

        $form->addText(Model\SettingManager::ROW_PRIMARY_EMAIL,$this->translator->translate("admin.settings.premail").":")
            ->setAttribute('class','form-control')
            ->setAttribute('placeholder',$this->translator->translate("admin.settings.typepremail"));

        $form->setDefaults($settings);

        $form->addSubmit('submit',$this->translator->translate("admin.form.save"))->setAttribute('class','btn btn-primary pull-right');
        $form->onSuccess[] = array($this, 'settingsSucceed');
        return $form;
    }

    public function settingsSucceed($form){

        $values = $form->values;
        Debugger::barDump($values);

    }

}