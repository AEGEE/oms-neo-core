<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Guzzle\Stream\PhpStreamRequestFactory;

class MicrosoftGraphController extends Controller
{
    public function getUsers(Request $req) {

        $client = new GuzzleClient();

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

        $response = $client->request('POST', 'https://graph.microsoft.com/v1.0/me/sendmail', [
            'headers' => [
                'Authorization' => 'Bearer ' . Auth::user()->oauth_token,
                'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'
            ],
            'body' => $email
        ]);
        if($response->getStatusCode() === 201) {
            exit('Email sent, check your inbox');
        } else {
            dd($response->getStatusCode());
            exit('There was an error sending the email. Status code: ' . $response->getStatusCode());
        }

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
}
