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
}
