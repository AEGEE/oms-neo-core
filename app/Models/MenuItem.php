<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public function saveMenuNested($menuItems, $parent = 0) {
    	$weight = 1;
    	foreach($menuItems as $item) {
    		$tmpObj = new MenuItem();
    		$tmpObj->name = $item['name'];
    		$tmpObj->type = $item['type'];
    		$tmpObj->weight = $weight;
    		if(!empty($parent)) {
    			$tmpObj->parent_id = $parent;
    		}

    		if(!empty($item['page'])) {
    			$tmpObj->module_page_id = $item['page'];
    		}

    		$tmpObj->url = $item['link'];
    		$tmpObj->icon = $item['icon'];
    		$tmpObj->save();

    		if(isset($item['children']) && !empty($item['children'])) {
    			$this->saveMenuNested($item['children'], $tmpObj->id);
    		}
    		$weight++;
    	}
    }

    public function getNestedList() {
    	$all = $this->orderBy('parent_id', 'ASC')->orderBy('weight', 'ASC')->get();

    	$parentsGrouped = array();
    	foreach($all as $item) {
    		if(empty($item->parent_id)) {
    			$item->parent_id = -1;
    		}
    		if(!isset($parentsGrouped[$item->parent_id])) {
    			$parentsGrouped[$item->parent_id] = array();
    		}

    		$parentsGrouped[$item->parent_id][] = array(
    			'id'			=>	$item->id,
    			'name'			=>	$item->name,
    			'type'			=>	$item->type,
    			'parent_id' 	=>	$item->parent_id,
    			'page' 			=>	$item->module_page_id,
    			'link'			=> 	$item->url,
    			'icon'			=>	$item->icon
    		);
    	}
        $toReturn = array();
        if(count($parentsGrouped) > 0) {
    	   $toReturn = $this->createChildren($parentsGrouped, -1);
        }
    	return $toReturn;
    }

    private function createChildren($parents, $parentId = -1) {
    	$toReturn = array();
    	foreach($parents[$parentId] as $item) {
    		$tmp = $item;
    		if(in_array($item['id'], array_keys($parents))) {
    			$tmp['children'] = $this->createChildren($parents, $item['id']);
    		}
    		$toReturn[] = $tmp;
    	}

    	return $toReturn;
    }

    public function getMenuMarkup($moduleAccess) {
        $all = $this->select('module_pages.code', 'module_pages.module_link', 'menu_items.*')
                    ->leftJoin('module_pages', 'module_pages.id', '=', 'menu_items.module_page_id')
                    ->orderBy('parent_id', 'ASC')
                    ->orderBy('weight', 'ASC')->get();

        $parentsGrouped = array();
        foreach($all as $item) {
            if(!empty($item->code) && !in_array($item->code, array_keys($moduleAccess))) {
                continue;
            }

            if(empty($item->parent_id)) {
                $item->parent_id = -1;
            }

            if(!isset($parentsGrouped[$item->parent_id])) {
                $parentsGrouped[$item->parent_id] = array();
            }

            $parentsGrouped[$item->parent_id][] = array(
                'id'            =>  $item->id,
                'name'          =>  $item->name,
                'type'          =>  $item->type,
                'parent_id'     =>  $item->parent_id,
                'page'          =>  $item->module_page_id,
                'link'          =>  $item->url,
                'icon'          =>  $item->icon,
                'code'          =>  $item->code,
                'module_link'   =>  $item->module_link
            );
        }

        $toReturn = "";
        if(count($parentsGrouped) > 0) {
            $toReturn = $this->createMenuMarkupFromChildren($parentsGrouped, -1);
        }
        return $toReturn;
    }

    private function createMenuMarkupFromChildren($parents, $parentId = -1) {
        $toReturn = "";
        foreach($parents[$parentId] as $item) {
            $hasChildren = in_array($item['id'], array_keys($parents));
            $hasSub = !$hasChildren ? "" : "class='has-sub'";
            
            $tmp = '<li '.$hasSub.' ui-sref-active="active">';

            if($hasChildren) {
                $tmp .= '<a href="javascript:;">
                            <b class="caret pull-right"></b>';
            } else {
                switch ($item['type']) {
                    case '1': // External link..
                        $tmp .= '<a href="javascript:;" ng-click="vm.goToLink(\''.$item['link'].'\')">';
                        break;
                    case '2': // Module link..
                        $tmp .= '<a ui-sref="app.'.$item['code'].'">';
                        break;
                    case '0': // No link..
                    default:
                        $tmp .= '<a href="javascript:;">';
                        break;
                }
            }

            $tmp .= '
                            <i class="fa '.$item['icon'].'"></i> 
                            <span>'.$item['name'].'</span>
                        </a>';

            if($hasChildren) {
                $tmp .= "<ul class='sub-menu'>";
                $tmp .= $this->createMenuMarkupFromChildren($parents, $item['id']);
                $tmp .= "</ul>";
            }

            $tmp .= '</li>';
            $toReturn .= $tmp;
        }

        return $toReturn;
    }
}
