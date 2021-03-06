<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicAPITest extends TestCase
{

    private $token;

    public function setUp() {
        parent::setUp();
        $response = $this->json('POST', '/api/login', ['username' => 'admin', 'password' => 'admin']);
        $this->token = $response->getData(true)['data'];
    }

    public function testLogin() {
        $this->json('POST', '/api/login', ['username' => 'guest', 'password' => 'password'])
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

        $this->helperGETSuccess('api/circles');
        $this->helperGETSuccess('api/circles/1');
        $this->helperGETSuccess('api/circles/1/members');
        $this->helperGETSuccess('api/circles/1/members?recursive=true');
        $this->helperGETSuccess('api/circles/1/members?recursive=false');
        $this->helperGETSuccess('api/circles/3/parent');
        $this->helperGETSuccess('api/circles/3/parent?recursive=true');
        $this->helperGETSuccess('api/circles/1/parent?recursive=false');
        $this->helperGETSuccess('api/circles/1/children');
        $this->helperGETSuccess('api/circles/1/children?recursive=true');
        $this->helperGETSuccess('api/circles/3/children?recursive=false');
        $this->helperGETSuccess('api/bodies/1/circles');
        $this->helperGETSuccess('api/users/2/circles');
    }

    public function helperGETSuccess($url) {
        $this->json('GET', $url, [], ['HTTP_X_AUTH_TOKEN' => $this->token])
            ->assertJson([
                'success' => true,
        ]);
    }
}
