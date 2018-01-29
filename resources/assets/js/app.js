/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

window.datatablesLocalizations = {

    // https://cdn.datatables.net/plug-ins/1.10.16/i18n/
    // 2017-08-31
    // @todo find all locale codes and associate to links
    // @todo make all these links local files

    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Afrikaans.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Albanian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Amharic.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Arabic.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Armenian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Azerbaijan.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Bangla.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Basque.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Belarusian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Bulgarian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Catalan.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Chinese-traditional.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Chinese.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Croatian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Czech.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Danish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Dutch.json
    en: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/English.json',
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Estonian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Filipino.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Finnish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/French.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Galician.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Georgian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/German.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Greek.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Gujarati.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Hebrew.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Hindi.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Hungarian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Icelandic.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Indonesian-Alternative.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Indonesian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Irish.json
    it: '//cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Italian.json'
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Japanese.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Kazakh.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Korean.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Kyrgyz.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Latvian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Lithuanian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Macedonian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Malay.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Mongolian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Nepali.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Norwegian-Bokmal.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Norwegian-Nynorsk.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Pashto.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Persian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Polish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Portuguese-Brasil.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Portuguese.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Romanian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Russian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Serbian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Sinhala.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Slovak.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Slovenian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Spanish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Swahili.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Swedish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Tamil.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/telugu.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Thai.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Turkish.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Ukrainian.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Urdu.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Uzbek.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Vietnamese.json
    // //cdn.datatables.net/plug-ins/' + $.fn.dataTable.version + '/i18n/Welsh.json

};
