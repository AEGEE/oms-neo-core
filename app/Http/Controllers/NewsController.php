<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddNewsRequest;

use App\Models\News;
use App\Models\Notification;
use App\Models\Member;

use Input;

class NewsController extends Controller
{
    public function getNews(News $news) {
        $search = array(
            'sidx'          =>  Input::get('sidx'),
            'sord'          =>  Input::get('sord'),
            'limit'         =>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'          =>  empty(Input::get('page')) ? 1 : Input::get('page')
        );

        $newsX = $news->getFiltered($search);
        $newsCount = $news->getFiltered($search, true);

        if($newsCount == 0) {
            $numPages = 0;
        } else {
            if($newsCount % $search['limit'] > 0) {
                $numPages = ($newsCount - ($newsCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $newsCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $newsCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );


        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..
        foreach($newsX as $new) {
            $actions = "";
            if($isGrid) {
                
            } else {
                $actions = $new->id;
            }
            $toReturn['rows'][] = array(
                'id'    =>  $new->id,
                'cell'  =>  array(
                    $actions,
                    $new->title,
                    $new->content,
                    $new->created_at->format('d/m/Y'),
                    $new->user->first_name." ".$new->user->last_name
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveNews(AddNewsRequest $req, News $news) {
    	$userData = $req->get('userData');
    	$id = Input::get('id');

        $toSendNotification = true;
        if(!empty($id)) {
            $toSendNotification = false;
    		$news = $news->findOrFail($id);
    	}

    	$news->title = Input::get('title');
    	$news->content = Input::get('content');
    	$news->user_id = $userData['id'];
    	$news->save();

        if($toSendNotification) {
            $users = User::where('id', '!=', $userData['id'])->get();
            foreach($users as $user) {
                $not = new Notification();
                $not->title = "New news item";
                $not->text = "A new news item was posted!";
                $not->user_id = $user->id;
                $not->link = "/news/".$news->id;
                $not->save();
            }
        }

    	$toReturn['success'] = 1;
    	return response(json_encode($toReturn), 200);
    }

    public function deleteNews(News $news) {
    	$id = Input::get('id');
    	$news = $news->findOrFail($id);
    	$news->delete();

    	$toReturn['success'] = 1;
    	return response(json_encode($toReturn), 200);
    }

    public function getNewsById(News $news) {
    	$id = Input::get('id');
    	$news = $news->with('user')->findOrFail($id);
    	$toReturn['success'] = 1;
    	$toReturn['news'] = $news;
    	return response(json_encode($toReturn), 200);
    }
}
