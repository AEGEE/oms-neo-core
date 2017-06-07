<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveBodyRequest;

use App\Models\Body;
use App\Models\Country;

use Excel;
use Input;

class BodyController extends Controller
{
    public function getBodies(Body $body, Request $req) {
        $max_permission = $req->get('max_permission');
  	   $search = array(
            'name'          =>  Input::get('name'),
            'city'          =>  Input::get('city'),
            'country_id'    =>  Input::get('country_id'),
    		'sidx'      	=>  Input::get('sidx'),
    		'sord'			=>	Input::get('sord'),
    		'limit'     	=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      	=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $export = Input::get('export', false);
        if($export) {
            $search['noLimit'] = true;
        }

        $bodies = $body->getFiltered($search);

        if($export) {
            Excel::create('antennae', function($excel) use ($bodies) {
                $excel->sheet('antennae', function($sheet) use ($bodies) {
                    $sheet->loadView('excel_templates.antennae')->with("antennae", $bodies);
                });
            })->export('xlsx');
            return;
        }

    	$bodiesCount = $body->getFiltered($search, true);
    	if($bodiesCount == 0) {
            $numPages = 0;
        } else {
            if($bodiesCount % $search['limit'] > 0) {
                $numPages = ($bodiesCount - ($bodiesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $bodiesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $bodiesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($bodies as $body) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeAnt' title='Edit' ng-click='vm.editBody(".$body->id.")'><i class='fa fa-pencil'></i></button>";
                }
            } else {
                $actions = $body->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$body->id,
        		'cell'	=> 	array(
        			$actions,
        			$body->name,
              $body->email,
              $body->address,
              $body->phone,
        			$body->city
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveBody(Body $body, Country $country, SaveBodyRequest $req) {
        $id = Input::get('id');
        if(!empty($id)) {
            $body = $body->findOrFail($id);
        }

        $body->name = Input::get('name');
        $body->city = Input::get('city');
        $body->email = Input::get('email');
        $body->address = Input::get('address');
        $body->phone = Input::get('phone');

        $country_id = Input::get('country_id');
        $countryCheck = $country->findOrFail($country_id);

        $body->country_id = $country_id;
        $body->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getBody(Request $req, Body $body) {
        $body->syncRoles($req->get('user'));
        $toReturn['success'] = 1;
        $toReturn['body'] = $body;
        return response()->json($body);
        return response(json_encode($toReturn), 200);
    }
}
