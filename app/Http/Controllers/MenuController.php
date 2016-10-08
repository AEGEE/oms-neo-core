<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\MenuItem;

use DB;
use Input;

class MenuController extends Controller
{
    public function saveMenu(MenuItem $mItem) {
    	$menuItems = Input::get('items');

    	DB::transaction(function() use ($mItem, $menuItems){
    		DB::statement("TRUNCATE TABLE menu_items");
    		$mItem->saveMenuNested($menuItems);
    	});
    	
    	$toReturn['success'] = 1;
    	return json_encode($toReturn);
    }

    public function getMenu(MenuItem $mItem) {
    	$toReturn = json_encode($mItem->getNestedList());
    	return $toReturn;
    }
}
