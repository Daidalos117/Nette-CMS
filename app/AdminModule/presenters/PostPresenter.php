<?php
/**
 * User: Roman
 * Date: 29. 6. 2015
 * Time: 15:15
 * File: PagePresenter.php
 */
namespace App\AdminModule\Presenters;

use Nette, Nette\Application\UI\Form, Nette\Database\Conventions, App\Model;
use Tracy\Debugger;

class PostPresenter extends BasePresenter
{


    /**
     * @var \App\Model\PostManager @inject
     */
    public $postManager;

    /**
     * @var \App\Model\ImageManager
     */
    private $imageManager;

    public $database;

    private $name = NULL;

    private $id = NULL;

    public function __construct(Nette\Database\context $database,\App\Model\ImageManager $imageManager)
    {
        $this->database = $database;
        $this->imageManager = $imageManager;
    }

    public function startup()
    {
        parent::startup();
        $this->isLogged();

    }

    public function renderDefault()
    {

        $data = $this->database->table("posts")->select("posts.*, language_id.name AS language_name");
        $this->template->posts = $data;

        $this->template->languages = $this->database->table("languages");

    }



    public function actionDelete($id)
    {

        $this->postManager->removePost($id);
        $this->flashMessage("Článek byl úspěšně smazán", "success");
        $this->redirect("Post:");
    }


    protected function createComponentNewShortForm()
    {
        $form = new Form();
        $form->addText('title')
            ->setAttribute('class','form-control')
            ->setAttribute('placeholder','Zadejte jméno');
        $form->addSubmit('submit','Odeslat')->setAttribute('class','form-control');
        $form->onSuccess[] = array($this, 'newShortFormSucceeded');
        return $form;
    }


    public function newShortFormSucceeded($form){
        $values = $form->getValues();


        $save = $this->postManager->savePost($values);

        $this->redirect("Post:new",[$save]);
    }

    public function renderNew($id){
        $this->template->id = $id;
        $this->id = $id;
    }

    public function renderEdit($id){
        $this->template->id = $id;
        $this->id = $id;
        $pictures = $this->database
            ->table(Model\ImageManager::TABLE_NAME)
            ->select("*")
            ->where(Model\ImageManager::COLUMN_POSTID,$id)
            ->fetchAssoc(Model\ImageManager::COLUMN_ID);


        $newPictures =[];
        foreach($pictures as $picture){

            $pictureA = "/".Model\ImageManager::WWW_DIR."/".$picture[Model\ImageManager::COLUMN_ID].Model\ImageManager::MINI_EXTENSION.".".$picture["extension"];
            $picture['picture'] = $pictureA;
            $newPictures[] = $picture;
        }

        $this->template->pictures = $newPictures;

    }

    protected function createComponentNewForm($name)
    {

        $data = $this->database->table(Model\PostManager::TABLE_NAME)->get($this->id);

        $form = new Form(NULL, "new-post");
        $form->getElementPrototype()->class('form-group');

        $form->addText(Model\PostManager::COLUMN_NAME, "Název")
            ->setRequired()
            ->setDefaultValue( $data[Model\PostManager::COLUMN_NAME])
            ->setAttribute("class","form-control");

        $form->addTextArea(Model\PostManager::COLUMN_SHORT, "Krátký popis")
            ->setRequired()
            ->setDefaultValue($data[Model\PostManager::COLUMN_SHORT])
            ->setAttribute("class","form-control");


        $form->addTextArea(Model\PostManager::COLUMN_CONTENT, "Obsah", 0, 25)
            ->setRequired()
            ->setDefaultValue($data[Model\PostManager::COLUMN_CONTENT])
            ->setAttribute("id", "summernote");

        $jazyky = $this->database->table("languages")->fetchPairs("id", "name");

        #$form->addSelect("language_id", "Jazyk:", $jazyky)->setAttribute("class","form-control");

        #$stranky = $this->database->table("tbpages")->fetchPairs("id", "title");

        #$form->addSelect("id_page", "Stránka:", $stranky)->setAttribute("class","stranky");

        $form->addCheckbox('active', 'Aktivní')->setAttribute("class","form-control");


        $form->addSubmit("submit")->setAttribute("class","btn btn-default");
        $form->onSuccess[] = array($this, 'newFormSucceeded');
        return $form;


    }



    public function newFormSucceeded(form $form)
    {

        $postId = $this->getParameter('id');

        $values['user_id'] = $this->getUser()->id;
        $values = $form->getValues();

        $text = $this->translator->translate('admin.messages.postAdded');
        $values['id'] = $postId;

        try{
            if(!$postId){
                $save = $this->postManager->savePost($values);

                $this->flashMessage($save,"success");

            }else{
                $edit = $this->postManager->editPost($values);
                $this->flashMessage($edit,"success");

            }
        }catch (\Exception $e){
            $this->flashMessage($e,"error");
            Debugger::barDump($e);
        }

        #$this->redirect("Post:");


    }




    public function actionEdit($id)
    {
        $page = $this->database->table('posts')->get($id);
        if (!$page) {
            $this->error('Příspěvek nebyl nalezen');
        }
        $this['newForm']->setDefaults($page->toArray());
    }


}