<?php

namespace App\Proxies;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Guzzle\Stream\PhpStreamRequestFactory;
use App\Models\User;

use App\Contracts\OnlineBusinessEnvironment as EBO;

class MicrosoftGraphProxy implements EBO
{

    public function createAccountForUser(User $user) {
        $arguments = [
            'accountEnabled'    => true,
            'displayName'       => $user->getDisplayName(),
            'mailNickname'      => $user->getUsernameSlug(),
            'passwordProfile'   => [
                'password'                      => config('graph.defaultPassword'),
                'forceChangePasswordNextSignIn' => 'true',
            ],
            'usageLocation' => 'BE',
            'userPrincipalName' => $user->getUsernameSlug() . '@member.aegee.eu',
        ];
        $this->makePostAPICall('users', $arguments);
        $arguments = [
            'addLicenses' => [
                [
                    'disabledPlans' => [],
                    'skuId'         => config('graph.licenseSkuId'),
                ],
            ],
            'removeLicenses' => [],
        ];
        return $this->makePostAPICall('users/' . $user->getUsernameSlug() . '@member.aegee.eu' . '/assignLicense', $arguments);
    }

    public function getUsers() {
        return $this->makeGetAPICall("users");
    }

    private function makeGetAPICall($url) {
        return makeAPICall('GET', $url);
    }

    private function makePostAPICall($url, $arguments) {
        return $this->makeAPICall('POST', $url, $arguments);
    }

    private function makeAPICall($method, $url, $arguments = []) {
        $client = new GuzzleClient();

        try {
            $response = $client->request($method, 'https://graph.microsoft.com/v1.0/' . $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . Auth::user()->oauth_token,
                    'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'
                ],
                'json' => $arguments,
            ]);
        } catch (RequestException $e) {
            dd($e->getResponse()->getBody()->getContents());
        }

        $stream = Psr7\stream_for($response->getBody());
        return json_decode($stream->getContents());
    }
}
