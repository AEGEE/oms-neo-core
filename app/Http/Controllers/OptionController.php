<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\GlobalOption;

use Input;

class OptionController extends Controller
{
    public function getOptions(GlobalOption $opt, Request $req) {
        $max_permission = $req->get('max_permission');
    	$search = array(
            'name'          =>  Input::get('name'),
            'not_editable'  =>  Input::get('not_editable'),
    		'sidx'      	=>  Input::get('sidx'),
    		'sord'			=>	Input::get('sord'),
    		'limit'     	=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      	=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $options = $opt->getFiltered($search);
    	$optionsCount = $opt->getFiltered($search, true);

    	if($optionsCount == 0) {
            $numPages = 0;
        } else {
            if($optionsCount % $search['limit'] > 0) {
                $numPages = ($optionsCount - ($optionsCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $optionsCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $optionsCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($options as $option) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {    
                    $actions .= "<button class='btn btn-default btn-xs clickMeSett' title='Edit' ng-click='vm.editOption(".$option->id.")'><i class='fa fa-pencil'></i></button>";
                }
            } else {
                $actions = $option->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$option->id,
        		'cell'	=> 	array(
        			$actions,
        			$option->name,
        			$option->value,
        			$option->description
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function getOption(GlobalOption $opt) {
    	$id = Input::get('id');
    	$opt = $opt->findOrFail($id);

    	$toReturn['success'] = 1;
        $toReturn['option'] = $opt;
        return response(json_encode($toReturn), 200);
    }

    public function saveOption(GlobalOption $opt) {
    	$id = Input::get('id');
    	$opt = $opt->findOrFail($id);

    	$opt->value = Input::get('value');
    	$opt->save();

    	$toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }
}
