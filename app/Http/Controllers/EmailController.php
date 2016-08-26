<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\EmailTemplate;
use App\Models\GlobalOption;

use Input;
use Session;

class EmailController extends Controller
{
    public function getEmailTemplates(EmailTemplate $tpl) {
    	$search = array(
            'name'          =>  Input::get('name'),
    		'sidx'      	=>  Input::get('sidx'),
    		'sord'			=>	Input::get('sord'),
    		'limit'     	=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      	=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $emailTemplates = $tpl->getFiltered($search);
    	$emailTemplatesCount = $tpl->getFiltered($search, true);

    	if($emailTemplatesCount == 0) {
            $numPages = 0;
        } else {
            if($emailTemplatesCount % $search['limit'] > 0) {
                $numPages = ($emailTemplatesCount - ($emailTemplatesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $emailTemplatesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $emailTemplatesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($emailTemplates as $template) {
            $actions = "";
            if($isGrid) {
                $actions .= "<button class='btn btn-default btn-xs clickMeTpl' title='Edit' ng-click='vm.editEmail(".$template->id.")'><i class='fa fa-pencil'></i></button>";
                $actions .= "<button class='btn btn-default btn-xs m-l-5 clickMeTpl' title='Edit' ng-click='vm.viewEmail(\"".$template->code."\")'><i class='fa fa-search'></i></button>";
            } else {
                $actions = $template->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$template->id,
        		'cell'	=> 	array(
        			$actions,
        			$template->name,
        			$template->title,
        			$template->description
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function getEmailTemplate(EmailTemplate $tpl) {
    	$id = Input::get('id');
    	$tpl = $tpl->findOrFail($id);

    	$toReturn['success'] = 1;
        $toReturn['email'] = $tpl;
        return response(json_encode($toReturn), 200);
    }

    public function saveEmailTemplate(EmailTemplate $tpl) {
    	$id = Input::get('id');
    	$tpl = $tpl->findOrFail($id);

    	$tpl->title = Input::get('title');
    	$tpl->content = Input::get('content');
    	$tpl->save();

    	$toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function previewEmail(EmailTemplate $tpl, GlobalOption $opt, $templateName = '') {
    	$userData = Session::get('userData');
    	if(!$userData['logged_in']) {
    		return response('Forbidden', 403);
    	}

		$access = Session::get('moduleAccess');
    	if(!$userData['is_superadmin'] && !isset($access['settings'])) {
			return response('Forbidden', 403);
    	}

    	$toReplace = array(
    		'{fullname}'		=>	$userData['fullname'],
    		'{username}'		=>	$userData['username'],
    		'{password}'		=>	substr(str_shuffle('asdfghjklzxcvbnm1234567890qwertyuiop'), 0, 8),
    		'{link}'			=>	url('/'),
    		'{reason}'			=>	'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium ipsa repudiandae illum iure perferendis, voluptatum veniam magnam, nesciunt enim praesentium eveniet assumenda in quos quas veritatis at quae doloremque officia!',
    		'{time_generated}' 	=>	date('Y-m-d H:i:s')
    	);

    	$tpl = $tpl->where('code', $templateName)->firstOrFail();

    	$addToView = $tpl->prepareContentForView($toReplace);

    	return view('emails.email', $addToView);
    }
}
