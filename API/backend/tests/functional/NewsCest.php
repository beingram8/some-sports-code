<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\News;

/**
 * Class LoginCest
 */
class NewsCest
{
    /**
     * @param FunctionalTester $I
     */

    public function indexNews(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->see('System Configuration');
        $I->amOnPage('/admin/news/index');
        $I->see('News', 'h2');
        $I->amOnPage('/admin/news/create');
    }

    public function createNewsWithBlankInput(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->amOnPage('/admin/news/create');
        $I->see('Create News', 'h2');
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->attachFile('input[id="news-thumb_img"]', 'test.png');
        $I->attachFile('input[id="news-main_img"]', 'test.png');
        $I->click('Submit');
        $I->see('Title cannot be blank.', 'div');
        $I->see('Is Active cannot be blank.', 'div');
        $I->see('Body cannot be blank.', 'div');
    }

    public function createNews(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $I->amOnPage('/admin/news/create');
        $I->see('Create News', 'h2');
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->attachFile('input[id="news-thumb_img"]', 'test.png');
        $I->attachFile('input[id="news-main_img"]', 'test.png');
        $I->fillField('input[id="news-title"]', 'test');
        $I->selectOption('select[name="News[is_active]"]', 1);

        $I->click('Submit');
        $I->dontSee('Title cannot be blank.', 'div');
        $I->dontSee('Is Active cannot be blank.', 'div');
        $I->see('Body cannot be blank.', 'div');
    }

    public function updateNews(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');
        $model = News::find()->one();

        $I->amOnPage('/admin/news/update?id=' . $model->id);
        $I->see('Update News', 'h2');
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->attachFile('input[id="news-thumb_img"]', 'test.png');
        $I->attachFile('input[id="news-main_img"]', 'test.png');
        $I->fillField('input[id="news-title"]', 'test');
        $I->selectOption('select[name="News[is_active]"]', 1);

        $I->click('Submit');
        $I->dontSee('Title cannot be blank.', 'div');
        $I->dontSee('Is Active cannot be blank.', 'div');
        $I->dontSee('Body cannot be blank.', 'div');
    }

    public function deleteNews(FunctionalTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('#loginform-username', 'admin@admin.com');
        $I->fillField('#loginform-password', '12345678');
        $I->click('#kt_login_signin_submit');

        $model = News::find()->one();

        $I->amOnPage('/admin/news/delete?id=' . $model->id);
        $I->see('News', 'h2');
    }
}
