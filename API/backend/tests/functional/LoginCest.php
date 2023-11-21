<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * @param FunctionalTester $I
     */

    public function loginWithBlank(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', '');
        $I->fillField('#loginform-password', '');
        $I->click('#kt_login_signin_submit');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginUser(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->see('System Configuration');
    }
}
