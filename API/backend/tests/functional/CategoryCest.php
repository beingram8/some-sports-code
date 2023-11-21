<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\RewardCategory;

/**
 * Class LoginCest
 */
class CategoryCest
{
    public function listCategory(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->amOnPage('/admin/reward-category/index');
        $I->see('Reward Categories', 'h2');
        $I->amOnPage('/admin/reward-category/create');
    }

    public function createCategoryWithBlank(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->amOnPage('/admin/reward-category/create');
        $I->see('Create Category', 'h2');
        $I->click('Submit');
        $I->see('Name cannot be blank.');
    }

    public function createCategory(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->amOnPage('/admin/reward-category/create');
        $I->see('Create Category', 'h2');
        $I->fillField('RewardCategory[name]', 'test');
        $I->click('Submit');
        $I->see('Reward Categories', 'h2');
    }

    public function updateCategory(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');

        $model = RewardCategory::find()->one();

        $I->amOnPage('/admin/reward-category/update?id=' . $model->id);
        $I->see('Update Category', 'h2');

        $model->name = 'update_reward';
        $model->save();

        $I->click('Submit');
        $I->see('Reward Categories', 'h2');
    }

    public function deleteCategory(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');

        $model = RewardCategory::find()->one();

        $I->amOnPage('/admin/reward-category/delete?id=' . $model->id);
        $I->see('Reward Categories', 'h2');
    }
}
