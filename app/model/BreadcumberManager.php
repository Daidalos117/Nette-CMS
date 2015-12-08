<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.09.2015
 * Time: 11:50
 */

namespace App\Model;

use Nette,
    Nette\Object,
    Exception,
    Tracy\Debugger;

/**
 * Class BreadcumberManager
 * @package App\Model
 */

class BreadcrumbManager extends BaseModel{




    private $fullUrl;

    public function __construct($fullUrl)
    {
        $this->fullUrl = $fullUrl;
    }

    /**
     * Gets breadcumbs from url
     * @return array
     */
    public function getBreadcrumbs(){
        $breadcrumbs = explode(":",$this->fullUrl);
        $breadcrumbs = $this->getLinks($breadcrumbs);
        return $breadcrumbs;
    }

    /**
     * Makes links for each breadcumb
     * @param $breadcrumbs
     * @return array
     */
    private function getLinks($breadcrumbs){

        $count = count($breadcrumbs);
        $newBreadcrumbs = array();
        foreach($breadcrumbs as $key => $breadcrumb){
            $link = ":".implode(":",array_slice($breadcrumbs,0,$key+1) ).":";
            if($key == 0) $link .= "Homepage:";
            $newBreadcrumbs[] = ["name"=> $breadcrumb,"link"=> $link];
        }

        return $newBreadcrumbs;
    }

}