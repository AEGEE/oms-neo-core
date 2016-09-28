<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveFeeRequest;

use App\Models\Fee;

use Excel;
use Input;

class FeeController extends Controller
{
    public function getFees(Fee $fee, Request $req) {
        $max_permission = $req->get('max_permission');
    	$search = array(
            'name'          		=>  Input::get('name'),
            'availability_from'     =>  Input::get('availability_from'),
            'availability_to'  		=>  Input::get('availability_to'),
            'availability_unit' 	=>  Input::get('availability_unit'),
            'price_from'       		=>  Input::get('price_from'),
            'price_to'        		=>  Input::get('price_to'),
            'currency'         		=>  Input::get('currency'),
            'mandatory'        		=>  Input::get('mandatory'),
    		'sidx'      			=>  Input::get('sidx'),
    		'sord'					=>	Input::get('sord'),
    		'limit'     			=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      			=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $export = Input::get('export', false);
        if($export) {
            $search['noLimit'] = true;
        }

        $fees = $fee->getFiltered($search);

        if($export) {
            Excel::create('fees', function($excel) use ($fees) {
                $excel->sheet('fees', function($sheet) use ($fees) {
                    $sheet->loadView('excel_templates.fees')->with("fees", $fees);
                });
            })->export('xlsx');
            return;
        }

    	$feesCount = $fee->getFiltered($search, true);
    	if($feesCount == 0) {
            $numPages = 0;
        } else {
            if($feesCount % $search['limit'] > 0) {
                $numPages = ($feesCount - ($feesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $feesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $feesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($fees as $feeX) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeFee' title='Edit' ng-click='vm.editFee(".$feeX->id.")'><i class='fa fa-pencil'></i></button>";
                }
            } else {
                $actions = $feeX->id;
            }


            $availabilityUnitStr = "";
            switch ($feeX->availability_unit) {
            	case '1':
            		$availabilityUnitStr = "Month";
            		break;
            	case '2':
            		$availabilityUnitStr = "Year";
            		break;
            }

        	$toReturn['rows'][] = array(
        		'id'	=>	$feeX->id,
        		'cell'	=> 	array(
        			$actions,
        			$feeX->name,
        			$feeX->availability,
        			$availabilityUnitStr,
        			$feeX->price." (".$feeX->currency.")",
        			empty($feeX->is_mandatory) ? "No" : "Yes"
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveFee(Fee $fee, SaveFeeRequest $req) {
    	$id = Input::get('id');
        if(!empty($id)) {
            $fee = $fee->findOrFail($id);
        }

        $fee->name = Input::get('name');
        $fee->availability = Input::get('availability');
        $fee->availability_unit = Input::get('availability_unit');
        $fee->price = Input::get('price');
        $fee->currency = Input::get('currency');
        $fee->is_mandatory = Input::get('is_mandatory');
        $fee->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getFee(Fee $fee) {
        $id = Input::get('id');
        $fee = $fee->findOrFail($id);

        $toReturn['success'] = 1;
        $toReturn['fee'] = $fee;
        return response(json_encode($toReturn), 200);
    }
}
