<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CheckLanguageKeys extends Command
{
    // const LOCALES = collect(json_decode(File::get(lang_path('languages.json')))->available)->pluck('value')->all();

    const STORAGE_DISK = 'lang';

    protected $signature = 'app:check-language';

    protected $description = 'Find, translate and save missing translation keys';

    public static $locales = [];

    private GoogleTranslate $googleTranslate;

    public function __construct(GoogleTranslate $googleTranslate)
    {
        $this->googleTranslate = $googleTranslate;
        parent::__construct();

        self::$locales = collect(json_decode(File::get(base_path('lang/languages.json')))->available)->pluck('value')->all();
    }

    public function handle(): void
    {
        $alreadyTranslated = $this->loadAllSavedTranslations();
        $translationsKeys = $this->findKeysInFiles();
        $this->translateAndSaveNewKeys($translationsKeys, $alreadyTranslated);
        $this->info('All done!');
    }

    private function findKeysInFiles(): array
    {
        $path = [app_path(), resource_path('views'), resource_path('js')];
        $functions = ['\$t', 't', 'i18n.t', '@lang', '__'];

        $groupPattern =                             // See https://regex101.com/r/WEJqdL/6
            "[^\w|>]" .                             // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .  // Must start with one of the functions
            "\(" .                                  // Match opening parenthesis
            "[\'\"]" .                              // Match " or '
            '(' .                                   // Start a new group to match:
            '[\/a-zA-Z0-9_-]+' .                    // Must start with group
            "([.](?! )[^\1)]+)+" .                  // Be followed by one or more items/keys
            ')' .                                   // Close group
            "[\'\"]" .                              // Closing quote
            "[\),]";                                // Close parentheses or new parameter

        $stringPattern =
            "[^\w]" .                                       // Must not have an alphanum before real method
            '(' . implode('|', $functions) . ')' .          // Must start with one of the functions
            "\(\s*" .                                       // Match opening parenthesis
            "(?P<quote>['\"])" .                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)" . // Match any string that can be {quote} escaped
            "\k{quote}" .                                   // Match " or ' previously matched
            "\s*[\),]";                                     // Close parentheses or new parameter

        $finder = new Finder();
        $finder->in($path)->exclude(['vendor', 'storage'])->files()->name(['*.php', '*.vue']);
        // $finder->path('admin/settings');
        $this->line('> finding missing language key in ' . $finder->count() . ' files.');
        $keys = [];
        foreach ($finder as $file) {
            if (preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                if (count($matches) < 2) {
                    continue;
                }

                foreach ($matches[2] as $key) {
                    if (strlen($key) < 2) {
                        continue;
                    }

                    if (! (str($key)->contains('::') && str($key)->contains('.'))) { // only for json strings
                        $keys[$key] = '';
                    }
                }
            }
            if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[\/a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here do nothing, it has to be treated as a group
                        continue;
                    }

                    // skip keys which contain namespacing characters, unless they also contain a space, which makes it JSON.
                    if (! (str($key)->contains('::') && str($key)->contains('.')) || str($key)->contains(' ')) {
                        $keys[$key] = '';
                    }
                }
            }
        }
        uksort($keys, 'strnatcasecmp');

        return $keys;
    }

    private function loadAllSavedTranslations(): array
    {
        // $path = Storage::disk(self::STORAGE_DISK)->path('');
        $finder = new Finder();
        $finder->in(lang_path())->name(['*.json'])->files();
        $translations = [];
        foreach ($finder as $file) {
            $locale = $file->getFilenameWithoutExtension();
            if (! in_array($locale, self::$locales)) {
                continue;
            }
            $this->line('loading: ' . $locale);
            $jsonString = $file->getContents();
            $translations[$locale] = json_decode($jsonString, true);
        }

        return $translations;
    }

    private function saveToFile(string $locale, array $newKeysWithValues, array $alreadyTranslated)
    {
        $localeTranslations = array_merge($newKeysWithValues, $alreadyTranslated);
        uksort($localeTranslations, 'strnatcasecmp');
        Storage::disk(self::STORAGE_DISK)
            ->put($locale . '.json', json_encode($localeTranslations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT, 2));
    }

    private function translateAndSaveNewKeys(array $translationsKeys, array $alreadyTranslated)
    {
        foreach (self::$locales as $locale) {
            $newKeysFound = array_diff_key($translationsKeys, $alreadyTranslated[$locale]);
            if (count($newKeysFound) < 1) {
                continue;
            }
            $this->info(count($newKeysFound) . ' new keys found for "' . $locale . '"');
            $newKeysWithValues = $this->translateKeys($locale, $newKeysFound);
            $this->saveToFile($locale, $newKeysWithValues, $alreadyTranslated[$locale]);
        }
    }

    private function translateKey(string $locale, string $key): string
    {
        if ($locale === 'en') {
            return $key;
        }
        try {
            $this->googleTranslate->setTarget($locale);
            $translated = $this->googleTranslate->translate($key);
        } catch (Exception $exception) {
            logger()->warning('Google translate issue with ' . $key . ': ' . $exception->getMessage());
            $translated = $key;
        }

        return $translated;
    }

    private function translateKeys(string $locale, array $keys): array
    {
        foreach ($keys as $keyIndex => $keyValue) {
            $keys[$keyIndex] = $this->translateKey($locale, $keyIndex);
        }

        return $keys;
    }
}
