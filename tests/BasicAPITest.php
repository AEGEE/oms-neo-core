<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicAPITest extends TestCase
{

    private $token;

    public function setUp() {
        parent::setUp();
        $response = $this->json('POST', '/api/login', ['username' => 'admin@aegee.org', 'password' => '1234']);
        //dd($response);
        $this->token = $response->getData(true)['data'];
    }

    public function testLogin() {
        $this->json('POST', '/api/login', ['username' => 'derk.snijders@aegee.org', 'password' => '1234'])
            ->assertJson([
                'success' => true,
             ]);
    }

    public function testGetters() {
        $this->helperGETSuccess('api/bodies');
        $this->helperGETSuccess('api/bodies/1');
        $this->helperGETSuccess('api/users');
        $this->helperGETSuccess('api/users/1');
        $this->helperGETSuccess('api/users/i3anaan');
        $this->helperGETSuccess('api/users/1/bodies');
        $this->helperGETSuccess('api/bodies/types');
        $this->helperGETSuccess('api/bodies/types/1');
        $this->helperGETSuccess('api/addresses');
        $this->helperGETSuccess('api/addresses/1');
        $this->helperGETSuccess('api/countries');
        $this->helperGETSuccess('api/countries/1');
    }

    public function helperGETSuccess($url) {
        $this->json('GET', $url, [], ['HTTP_X_AUTH_TOKEN' => $this->token])
            ->assertJson([
                'success' => true,
        ]);
    }
}
