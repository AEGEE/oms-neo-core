<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class MicrosoftGraphController extends Controller
{
    public function getUsers(Request $req) {


        try {
            $client = new GuzzleClient();
            $res = $client->get('https://graph.microsoft.com/v1.0/me', ['headers' =>  ['Authorization' => "Bearer " . Auth::user()->oauth_token], 'debug' => true]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
            die();
        }

        return response()->success($res->getBody());
    }
}
