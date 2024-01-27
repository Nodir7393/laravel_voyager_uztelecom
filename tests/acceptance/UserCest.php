<?php

use App\Models\User;
use Tests\Unit\UserServiceTest;

class UserCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function getData(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email','admin@admin.com');
        $I->fillField('password','teamprodev12346');
        $I->click('Войти');
        $I->amOnPage('/admin/users');
    }
    public function changeLeader(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email','admin@admin.com');
        $I->fillField('password','teamprodev12346');
        $I->click('Войти');
        $I->amOnPage('/admin/users');
        $I->amOnPage('/user/9');
    }
}
