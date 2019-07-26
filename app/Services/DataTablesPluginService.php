<?php
declare(strict_types = 1);

namespace App\Services;

/**
 * Class DataTablesPluginService
 *
 * This class provides frontend assets located within the private part of the application.
 * It's reached via specific routes.
 *
 * @package App\Services
 */
class DataTablesPluginService
{

    private $pluginBasePath;
    private $defaultLanguage = "en";
    private $i18nRelativePath = "i18n";
    private $dateTimeRelativePath = "dataRender/datetime.js";
    private $languageFilePaths;

    public function __construct(string $pluginBasePath)
    {

        if (file_exists($pluginBasePath) === false ||
            is_dir($pluginBasePath) === false ||
            is_readable($pluginBasePath) === false
        ) {
            throw new \InvalidArgumentException("Invalid plugin base path: " . $pluginBasePath);
        }

        $this->pluginBasePath = $pluginBasePath;

        // @todo improve logic and complete list
        $this->languageFilePaths = [
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Afrikaans.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Albanian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Arabic.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Armenian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Azerbaijan.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Bangla.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Basque.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Belarusian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Bulgarian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Catalan.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Chinese.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Chinese-traditional.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Croatian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Czech.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Danish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Dutch.lang',
            "en" => $pluginBasePath . "/" . $this->i18nRelativePath . "/English.lang",
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Estonian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Filipino.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Finnish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/French.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Galician.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Georgian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/German.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Greek.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Gujarati.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Hebrew.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Hindi.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Hungarian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Icelandic.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Indonesian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Indonesian-Alternative.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Irish.lang',
            "it" => $pluginBasePath . "/" . $this->i18nRelativePath . "/Italian.lang",
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Japanese.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Kazakh.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Korean.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Kyrgyz.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Latvian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Lithuanian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Macedonian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Malay.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Mongolian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Nepali.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Norwegian-Bokmal.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Norwegian-Nynorsk.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Pashto.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Persian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Polish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Portuguese.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Portuguese-Brasil.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Romanian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Russian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Serbian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Sinhala.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Slovak.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Slovenian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Spanish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Swahili.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Swedish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Tamil.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/telugu.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Thai.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Turkish.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Ukrainian.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Urdu.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Uzbek.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Vietnamese.lang',
            // '' => $pluginBasePath . '/' . $this->i18nRelativePath . '/Welsh.lang',
        ];

    }

    /**
     * Get translation as JSON string, from country code
     *
     * @param string $countryCode
     * @return string
     *
     * @see https://datatables.net/plug-ins/i18n/
     */
    public function getLanguage(string $countryCode) : string
    {

        $countryCode = strtolower($countryCode);

        if (isset($this->languageFilePaths[$countryCode]) === false) {
            $countryCode = $this->defaultLanguage;
        }

        $languageFileContent = $this->languageFilePaths[$countryCode];

        // All comments are stripped from file, in order to make the JSON valid.
        // https://stackoverflow.com/questions/17776942/remove-fake-comments-from-json-file#17776997
        $languageFileContent = trim(preg_replace("#/\*.*?\*/#s", "", file_get_contents($languageFileContent)));

        return $languageFileContent;

    }

    /**
     * Get dateTime plugin
     *
     * @return string
     *
     * @see https://datatables.net/plug-ins/dataRender/datetime
     */
    public function getDateTime() : string
    {

        return file_get_contents($this->pluginBasePath . "/" . $this->dateTimeRelativePath);

    }

}
