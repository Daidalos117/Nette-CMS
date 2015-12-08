<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.09.2015
 * Time: 15:17
 */
namespace App\Model;

use Nette,
    Nette\Object,
    Exception,
    App\Presenters\BasePresenter;
use Kdyby\Translation;
use Tracy\Debugger;
use Nette\Security\User;



/**
 * Zpracovává vykreslování článků.

 */

class BlockManager extends BaseModel{

    /** Konstanty pro manipulaci s modelem. */
    const
        TABLE_NAME = 'blocks',
        COLUMN_ID = 'id';


    /** @var Nette\Database\Context */
    protected $database;

    /** @var Nette\Security\User  */
    protected $user;

    public function __construct(Nette\Database\Context $database, Nette\Security\User $user)
    {

        $this->database = $database;
        $this->user = $user;

    }
        /**
     * Vrátí seznam článků v databázi.
     * @return Selection seznam článků
     */
    public function getPosts()
    {
        return $this->database->table(self::TABLE_NAME)->order(self::COLUMN_ID . ' DESC');
    }

    /**
     * Vrátí článek z databáze podle jeho URL.
     * @param string $url URl článku
     * @return bool|mixed|IRow první článek, který odpovídá URL nebo false při neúspěchu
     */
    public function getPost($url)
    {
        return $this->database->table(self::TABLE_NAME)->where(self::COLUMN_URL, $url)->fetch();
    }

    /**
     * Uloží článek do systému. Pokud není nastaveno ID, vloží nový, jinak provede editaci.
     * @param array|ArrayHash $article článek
     */
    public function savePost($article)
    {
        $article['user_id'] = $this->user->id;
        $article['url'] = Nette\Utils\Strings::webalize($article->title);
        $row = $this->database->table(self::TABLE_NAME)->insert($article);


        return $row->id;


    }

    public function editPost($post, $returnId = FALSE){

        Debugger::barDump($post);
        $update = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $post[self::COLUMN_ID])->update($post);
        if(!$update){
            throw new Exception($this->translator->translate('admin.messages.nonExistPost'));
        }else{
            if(!$returnId){
                return $this->translator->translate('admin.messages.postEdited');
            }else{
                return $this->database->getInsertId();
            }

        }

    }

    /**
     * Odstraní článek.
     * @param string $url URL článku
     */
    public function removePost($id)
    {
        $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->delete();
    }


}