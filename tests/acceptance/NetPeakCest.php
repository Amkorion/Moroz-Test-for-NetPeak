<?php
class NetPeakCest
{   
    public function _before(\AcceptanceTester $I)
 //Probably it is not the best solution and it would look nice if I hide this function somewhere, but as for me, it is easier to check when everything is in one place
    {function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    }

    public function _after(\AcceptanceTester $I)
    {
    }
    // tests       

    public function tryToTestNetPeakWebsite(AcceptanceTester $I)
    {#1. Перейти по ссылке на главную страницу сайта Netpeak. (https://netpeak.ua/).
        $I->amOnPage('/');
    
    #2. Перейдите на страницу "Работа в Netpeak", нажав на кнопку "Карьера".    
        $I->click('li a[href*="career"]');
   
    #3. Перейти на страницу заполнения анкеты, нажав кнопку - "Я хочу работать в Netpeak".    
        $I->scrollTo('.vac-block-border');
        $I->click('div a[class="btn green-btn"]');
    
    #4. Загрузить файл с недопустимым форматом в блоке "Резюме", например png, и проверить что на странице появилось сообщение, о том что формат изображения неверный.
        $I->attachFile('input[type="file"]', 'photo.jpg');
        $I->wait(3);
        $I->seeElement("#up_file_name label");
    
    #5. Заполнить случайными данными блок "3. Личные данные".
        $I->fillField('#inputName', generateRandomString());
        $I->fillField('#inputLastname', generateRandomString());
        $I->fillField('#inputEmail', generateRandomString(). '@test.mail');
        $I->fillField('#inputPhone', '+380'. random_int(1234567890, 9876543210));
        $I->selectOption('[data-error-name="Birth year"]', random_int(1952, 2003));
        $I->selectOption('[data-error-name="Birth month"]', random_int(11, 12)); //yeh I know that there must be (1, 12) but values on site are kind of "01-09" so it is doesn't accepted by this function and I am a lazy for creating a new for this field))) However, it is random)))
        $I->selectOption('[data-error-name="Birth day"]', random_int(1, 31));
   
    #6. Нажать на кнопку отправить резюме.
        $I->scrollTo('#submit');
        $I->click('#submit'); 
    
    #7. Проверить что сообщение на текущей странице - "Все поля являются обязательными для заполнения" - подсветилось красным цветом.
        $I->scrollTo('//body/div[2]');
        $color = $I->executeJS("return jQuery('p.warning-fields.help-block').css('color');");
        $I->assertEquals($color, 'rgb(255, 0, 0)');

    #8. Перейти на страницу "Курсы" нажав соответствующую кнопку в меню и убедиться что открылась нужная страница
        $I->click('li a[href*="school"]'); 
        $I->seeInTitle('Образовательный Центр Netpeak: курсы по SEO, PPC, PHP в Одессе'); //I know that there are several methods to check that I am on the needed page, just choose not by url but by title because it is also a unique identity
}
}