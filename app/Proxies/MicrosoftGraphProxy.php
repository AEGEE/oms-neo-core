<?php

namespace App\Proxies;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Guzzle\Stream\PhpStreamRequestFactory;
use App\Models\User;

use Log;
use OBE;
use App\Proxies\MailProxy;

class MicrosoftGraphProxy implements OBE
{

    public function sendEmail($receiver, $subject, $content) {
        $arguments = [
            'message'    => [
                'subject'       => $subject,
                'body'          => [
                    'contentType'   => 'text',
                    'content'       => $content
                ],
                'toRecipients'  => [
                    [
                        'emailAddress'  => [
                            'address'   => $receiver,
                        ],
                    ],
                ],
            ],
            'SaveToSentItems' => false,
        ];

        return $this->makePostAPICall('users/' . config('graph.systemAccountID') . '/sendMail', $arguments);
    }

    public function createAccountForUser(User $user) {
        $password = "Aa1!" . str_random(8);
        $internal_email = $user->getUsernameSlug() . '@member.aegee.eu';

        //Create account.
        $arguments = [
            'accountEnabled'    => true,
            'displayName'       => $user->getDisplayName(),
            'mailNickname'      => $user->getUsernameSlug(),
            'passwordProfile'   => [
                'password'                      => $password,
                'forceChangePasswordNextSignIn' => 'true',
            ],
            'usageLocation' => 'BE', //TODO
            'userPrincipalName' => $internal_email,
        ];
        $response = $this->makePostAPICall('users', $arguments);
        if ($response === false) {
            return false;
        }

        $id = $response->id;

        //Send account information.
        $response = MailProxy::sendOBELoginDetails($user, $internal_email, $password);
        if ($response === false) {
            return false;
        }

        //Set license.
        $arguments = [
            'addLicenses' => [
                [
                    'disabledPlans' => [],
                    'skuId'         => config('graph.licenseSkuId'),
                ],
            ],
            'removeLicenses' => [],
        ];
        return $this->makePostAPICall('users/' . $id . '/assignLicense', $arguments);
    }

    public function getUsers() {
        return $this->makeGetAPICall("users");
    }

    private function makeGetAPICall($url) {
        return $this->makeAPICall('GET', $url);
    }

    private function makePostAPICall($url, $arguments) {
        return $this->makeAPICall('POST', $url, $arguments);
    }

    private function makeAPICall($method, $url, $arguments = []) {
        $client = new GuzzleClient();

        try {
            $response = $client->request($method, 'https://graph.microsoft.com/v1.0/' . $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAppToken(),
                    'Content-Type' => 'application/json;odata.metadata=minimal;odata.streaming=true'
                ],
                'json' => $arguments,
            ]);

            $stream = Psr7\stream_for($response->getBody());
            return json_decode($stream->getContents());
        } catch (RequestException $e) {
            Log::error($e);
            Log::error($e->getResponse()->getBody()->getContents());

            return false;
        }
    }

    private function getAppToken() {
        $client = new GuzzleClient();

        try {
            $response = $client->request('POST', 'https://login.microsoftonline.com/aegee.eu/oauth2/v2.0/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'client_id'     => config('oauth.oAuthId'),
                    'scope'         => 'https://graph.microsoft.com/.default',
                    'client_secret' => config('oauth.oAuthSecret'),
                    'grant_type'    => 'client_credentials',
                ],
            ]);
        } catch (RequestException $e) {
            Log::error($e);
            Log::error($e->getResponse()->getBody()->getContents());

            return [];
        }

        $stream = Psr7\stream_for($response->getBody());
        $response = json_decode($stream->getContents());
        return $response->access_token;
    }
}
