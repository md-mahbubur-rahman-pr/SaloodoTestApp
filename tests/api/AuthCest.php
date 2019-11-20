<?php namespace App\Tests;
use App\Tests\ApiTester;

class AuthCest
{
    protected $token;

    public function _before(ApiTester $I)
    {

    }

    public function createUser(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'userlogin';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test.com',
            'password' => 'userlogin@123'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"message":"User '.$username.' successfully created"}');
    }

    public function tryToLogin(ApiTester $I)
    {

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');

        $I->sendPOST('/login', [
            'username' => 'userlogin',
            'password' => 'userlogin@123'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 400
        $I->seeResponseIsJson();

        $this->token = $I->grabDataFromResponseByJsonPath('$.token');
    }


}
