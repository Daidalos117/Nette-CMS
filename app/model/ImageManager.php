<?php
namespace App\Model;

use Nette,
Nette\Object,
Exception,
Nette\Utils\Image;

use Tracy\Debugger;
use Nette\Security\User;



/**
* Zpracovává Obrázky

*/

class ImageManager extends BaseModel
{

    const
        TABLE_NAME = 'files',
        COLUMN_ID = 'id',
        COLUMN_NAME = 'file',
        COLUMN_POSTID = 'post_id',
        COLUMN_EXTENSION = 'extension',
        MINI_EXTENSION = "_mini",
        WWW_DIR = "files";

    /**
     * @var
     */
    private $dir;

    /**
     * @var Nette\Utils\Image
     */
    private $image;

    /**
     * @var string
     */
    private $newName;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @var Integer
     */
    private $originalType;


    /** @var Nette\Database\Context */
    protected $database;


    public $allowedTypes = array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG);

    public function __construct($dir, Nette\Database\Context $database)
    {
        $this->dir = $dir;
        $this->database = $database;
    }

    /**
     * @param $file
     * @param int $maxWidth
     * @return $this
     * @throws Nette\Utils\UnknownImageFileException
     */
    public function save($file)
    {

        $this->originalName = $file["name"];
        $fileLocation = $this->dir . '/' . $file["name"];
        move_uploaded_file($file["tmp_name"], $fileLocation);
        $this->isImage($fileLocation);
        $this->getExtension($fileLocation);

        $this->image = Image::fromFile($fileLocation);
        return $this;
    }

    /**
     * Gets type and if not allowed..
     * @throws Exception
     */
    public function isImage($file){

    }

    public function getExtension($file){
        $path_parts = pathinfo($file);
        $this->originalType = $path_parts["extension"];

    }


    /**
     * @param $postId
     * @param int $maxWidth
     * @throws Exception
     */
    public function saveAsPostImage($postId,$maxWidth = 2048){

        $exists = $this->database->table(self::TABLE_NAME)
            ->where(self::COLUMN_NAME,$this->originalName)
            ->where(self::COLUMN_POSTID,$postId)
            ->fetch();
        if($exists){
            throw new Exception("Tento soubor již u tohoto příspěvku existuje, zvolte prosím jiné jméno");
        }

        $picture = $this->database->table(self::TABLE_NAME)->insert(
            [self::COLUMN_NAME => $this->originalName,
                self::COLUMN_POSTID => $postId,
                self::COLUMN_EXTENSION => $this->originalType]);

        $this->newName = $picture->id;

        $postImage = clone $this->image;
        if($postImage->width > $maxWidth)
        {
            $postImage->resize($maxWidth, NULL);
        }

        $postImage->save($this->dir . "/" . $this->newName.".".$this->originalType);
        $this->saveMini(150,150);
        unset($postImage);
        $this->deleteOriginal();
    }



    /**
     * @param $width
     * @param null $height
     * @return bool
     * @throws Exception
     */
   public function saveMini($width, $height = NULL){

       $image = clone $this->image;
       $image->resize($width,$height);
       $image->sharpen();
       $im = $image->save($this->dir . "/" . $this->newName.self::MINI_EXTENSION.".".$this->originalType );
       unset($image);
       if($im){
            return true;
        }else{
            throw new Exception("Mini");
        }

   }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleteOriginal(){

        $vymazat = unlink( $this->dir . "/" . $this->originalName );
        if(!$vymazat) throw new Exception("Nelze vymazat soubor");
        return true;
    }

    /**
     * @param $name
     * @return bool
     * @throws Exception
     */
    public function deleteImage($name){
        $vymazat = unlink( $this->dir . "/" . $name );
        if(!$vymazat) throw new Exception("Nelze vymazat soubor");
        return true;
    }

    public function getDir(){
        return $this->dir;
    }

}