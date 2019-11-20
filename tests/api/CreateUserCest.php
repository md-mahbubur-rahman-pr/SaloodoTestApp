<?php
namespace App\Tests;

class CreateUserCest
{
    public function _before(ApiTester $I)
    {

    }


    public function createUserWithSmallUserName(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'test';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test.com',
            'password' => 'testuser@123'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"propertyPath":"username","message":"This value is too short. It should have 5 characters or more."}]');
    }


    public function createUserWithSmallPassword(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'testuser';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test.com',
            'password' => 'tes'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"propertyPath":"password","message":"This value is too short. It should have 8 characters or more."}]');
    }

    public function createUserWithInvalidEmail(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'testuser';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test',
            'password' => 'test@1234567'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"propertyPath":"email","message":"The email is not a valid email."}]');
    }

    public function createUserWithAllInvalidInput(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'te';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'te',
            'password' => 'tes'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"propertyPath":"username","message":"This value is too short. It should have 5 characters or more."},{"propertyPath":"email","message":"The email is not a valid email."},{"propertyPath":"password","message":"This value is too short. It should have 8 characters or more."}]');
    }

    public function createUser(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'testuser';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test.com',
            'password' => 'testuser@123'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"message":"User '.$username.' successfully created"}');
    }


    public function createUserWithSameUserName(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $username = 'testuser';
        $I->sendPOST('/register', [
            'username' => $username,
            'email' => 'test@test.com',
            'password' => 'testuser@123'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"propertyPath":"username","message":"The username is already taken"}]}');
    }
}
