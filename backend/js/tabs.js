jQuery(function () {
    var secondmenu = jQuery('div.second-menu > div'); // получаем массив контейнеров
    secondmenu.hide().filter(':first').show(); // прячем все, кроме первого
    // далее обрабатывается клик по вкладке
    jQuery('div.main-menu a').click(function () {
        secondmenu.hide(); // прячем все табы
        secondmenu.filter(this.hash).show() // показываем содержимое текущего
        jQuery('div.main-menu a').removeClass('active'); // у всех убираем класс 'selected'
        jQuery(this).addClass('active'); // текушей вкладке добавляем класс 'selected'
        //return false;
    }).filter(':first').click();
});

jQuery(function () {
    var producttab = jQuery('div.tab-content > div'); // получаем массив контейнеров
    producttab.hide().filter(':first').show(); // прячем все, кроме первого
    // далее обрабатывается клик по вкладке
    jQuery('div.tab-links a').click(function () {
        producttab.hide(); // прячем все табы
        producttab.filter(this.hash).show() // показываем содержимое текущего
        jQuery('div.tab-links a').removeClass('active'); // у всех убираем класс 'selected'
        jQuery(this).addClass('active'); // текушей вкладке добавляем класс 'selected'
        return false;
    }).filter(':first').click();
});

jQuery(function () {
    var language = jQuery('div.language > div'); // получаем массив контейнеров
    language.hide().filter(':first').show(); // прячем все, кроме первого
    // далее обрабатывается клик по вкладке
    jQuery('a#language').click(function () {
        language.hide(); // прячем все табы
        language.filter(this.hash).show() // показываем содержимое текущего
        jQuery('a#language').removeClass('active'); // у всех убираем класс 'selected'
        jQuery(this).addClass('active'); // текушей вкладке добавляем класс 'selected'
        return false;
    }).filter(':first').click();
});