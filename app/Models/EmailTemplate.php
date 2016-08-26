<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\GlobalOption;

class EmailTemplate extends Model
{
    protected $table = "email_templates";

    // Relationships..

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $emailTemplates = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $emailTemplates = $emailTemplates->where('name', 'LIKE', "%".$search['name']."%");
        }
        // END filters..

        if($onlyTotal) {
            return $emailTemplates->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $emailTemplates = $emailTemplates->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $emailTemplates = $emailTemplates->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $emailTemplates = $emailTemplates->take($limit)->skip($from);
        }

        return $emailTemplates->get();
    }

    public function prepareContentForView($replaceArr = array()) {
        $globalObj = new GlobalOption();
        
        $toReturn = array(
            'title'     =>  $this->title,
            'content'   =>  $this->content,
            'globals'   =>  $globalObj->getOptionsArray()
        );

        $replaceArr['{app_name}'] = $toReturn['globals']['app_name'];
        $replaceArr['{time_available}'] = $toReturn['globals']['password_reset_time'];

        if(isset($replaceArr['{link}'])) {

            $linkTpl = '</td>
                    </tr>
                    <tr>
                        <td class="panel">
                            <a href="'.$replaceArr['{link}'].'">'.$replaceArr['{link}'].'</a>
                        </td>
                    </tr>
                    <tr>
                        <td>';

            $replaceArr['{link}'] = $linkTpl;
        }

        $toReturn['title'] = strtr($toReturn['title'], $replaceArr);
        $toReturn['content'] = strtr($toReturn['content'], $replaceArr);

        return $toReturn;
    }
}
