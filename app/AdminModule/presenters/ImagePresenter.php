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

class ImagePresenter extends BasePresenter
{




    /**
     * @var \App\Model\ImageManager
     */
    private $imageManager;

    public $database;

    private $name = NULL;

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


    public function actionUpload($postId){


        $file = new Nette\Http\FileUpload($_FILES['file']);



        if($file->isOk() ){
            try{
                $up = $this->imageManager->save($_FILES['file'])->saveAsPostImage($postId);
            }
            catch(Exception $e){

            }

            if($up){
                return true;
            }
        }



    }

    public function actionDelete(){

        $name = $this->request->post['name'];
        $postId = $this->request->post['id'];
        $row = $this->database->table(Model\ImageManager::TABLE_NAME)->where(Model\ImageManager::COLUMN_NAME, $name)
            ->where(Model\ImageManager::COLUMN_POSTID,$postId);
        $info = $row->fetchAll();

        foreach($info as $one){
            $name = $one[Model\ImageManager::COLUMN_ID] . "." . $one[Model\ImageManager::COLUMN_EXTENSION];
            $mini = $one[Model\ImageManager::COLUMN_ID] . Model\ImageManager::MINI_EXTENSION ."." . $one[Model\ImageManager::COLUMN_EXTENSION];
            $this->imageManager->deleteImage($name);
            $this->imageManager->deleteImage($mini);
        }

        $row->delete();

        return true;
    }


}