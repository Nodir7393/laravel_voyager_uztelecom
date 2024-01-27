<?php

use App\Models\Application;

class ApplicationCest
{
    public string $id;

    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function create(AcceptanceTester $I)
    {
        $I->am('admin');
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'aziz@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage('/ru/site/applications');
        $I->click('Создать');
        $I->click('Компания');
        $I->amOnPage($I->grabFromCurrentUrl());
        $I->submitForm("//form[@data-aire-component='form']", [
            'initiator' => 'TEST!!! Тошкент омборидан Республика омборхоналаригача (ёки обьектларгачам)',
            'basis' => 'TEST!!!  “Ўзбектелеком” АК томонидан имзоланган шартномаларга асосан келтириладиган телекоммуникация қурилмаларини ҳамда',
            'name' => 'JAVATEST!!!  Бизнес режа асосида.',
            'specification' => 'TEST!!!  Бизнес режа асосида.',
            'separate_requirements' => 'TEST!!! Тент, контейнеровоз.',
            'other_requirements' => 'TEST!!! Тент, контейнеровоз.',
            'planned_price' => '550000000',
            'incoterms' => 'TEST!!!  Тошкент омборидан Республика омборхоналаригача (ёки обьектларгачам)',
            'info_business_plan' => 'TEST!!! САРЕХ 2022',
            'info_purchase_plan' => 'TEST!!! Планом закупок предусмотрено',
            'comment' => 'java adsada',
            'resource_id' => 22,
            'currency' => 'USD',
            'draft'=> 0,
        ], "//form[@data-aire-component='form']//button[@value=1]");
        $this->id = (int)$this->grabFromUrlIdApplication($I->grabFromCurrentUrl());
    }

    public function edit(AcceptanceTester $I)
    {
        $I->am('admin');
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'aziz@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage("/ru/site/applications/$this->id/edit");
        $I->submitForm("//form[@data-aire-id='0']", [
            'initiator' => 'TEST!!! Тошкент омборидан Республика омборхоналаригача (ёки обьектларгачам)',
            'basis' => 'TEST!!!  “Ўзбектелеком” АК томонидан имзоланган шартномаларга асосан келтириладиган телекоммуникация қурилмаларини ҳамда',
            'name' => 'JAVATEST перестест!!!  Бизнес режа асосида.',
            'specification' => 'TEST!!!  Бизнес режа асосида.',
            'separate_requirements' => 'TEST!!! Тент, контейнеровоз.',
            'other_requirements' => 'TEST!!! Тент, контейнеровоз.',
            'planned_price' => '550000000',
            'incoterms' => 'TEST!!!  Тошкент омборидан Республика омборхоналаригача (ёки обьектларгачам)',
            'info_business_plan' => 'TEST!!! САРЕХ 2022',
            'info_purchase_plan' => 'TEST!!! Планом закупок предусмотрено',
            'comment' => 'java adsada',
            'resource_id' => 32,
            'currency' => 'USD',
            'branch_id' => 9,
            'show_leader' => 1,
            'draft'=>0,
        ], "//form[@data-aire-component='form']//button[@value=0]");
    }

    public function draft(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'aziz@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage('/ru/site/applications/drafts');
    }
    public function leader_test(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'testleader@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage("/ru/site/applications/$this->id/show");
        $I->selectOption('performer_role_id','94');
        $I->click('Отправить');
    }
    public function performer_test(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'testleader@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage("/ru/site/applications/$this->id/edit");
        $I->submitForm("//form[@data-aire-component]", [
            'branch_customer_id' => '26',
            'lot_number' => '123456',
            'contract_number' => '123456',
            'protocol_number' => '123456',
            'contract_price' => '12344457',
            'performer_status' => '42',
        ], "//form//button[@class='btn btn-success']");
    }
    public function warehouse_test(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'testwarehouse@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage("/ru/site/applications/$this->id/edit");
        $I->click('товар доставлен');

    }
    public function destroy(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('email', 'aziz@gmail.com');
        $I->fillField('password', 'password');
        $I->click('Войти');
        $I->amOnPage("/ru/site/applications");
        $I->amOnPage("/ru/site/applications/$this->id/destroy");
    }

    private function grabFromUrlIdApplication($url)
    {
        return trim($url, 'ru/site/applications/edit/show/clone/destroy');
    }
}
