<?php

class DepartmentCest
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
        $I->amOnPage('/admin/departments');
    }
}
