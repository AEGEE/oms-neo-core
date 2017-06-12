<?php

namespace App\Proxies;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Guzzle\Stream\PhpStreamRequestFactory;

use App\Contracts\OnlineBusinessEnvironment as EBO;

class MicrosoftGraphProxy implements EBO
{

    public function createAccount() {
        return 'here you go: [account]';
    }

    public function getUsers() {
        $client = new GuzzleClient();

        $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/users', [
            'headers' => [
                'Authorization' => 'Bearer ' . Auth::user()->oauth_token,
                'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'
            ],
        ]);

        $stream = Psr7\stream_for($response->getBody());
        return json_decode($stream->getContents());
    }

    /*
    public function getUsers(Request $req) {



        $email = "{
            Message: {
            Subject: 'Sent using the Microsoft Graph REST API',
            Body: {
                ContentType: 'text',
                Content: 'This is the email body'
            },
            ToRecipients: [
                {
                    EmailAddress: {
                    Address: 'i3anaan@gmail.com'
                    }
                }
            ]
            }}";



        //dd($response->getBody()->read(1024));

        /*
        $res = null;
        try {
            $client = new GuzzleClient();
            $res = $client->request('GET', 'https://graph.microsoft.com/v1.0/organization', ['stream' => true, 'headers' =>  ['Authorization' => "Bearer  " . Auth::user()->oauth_token, 'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'], 'debug' => false]);

            //dd($res);

            //dd($res);
            return response()->success($res->getBody());
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
            return response()->failure();
        }
        */
}
