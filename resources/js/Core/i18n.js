import en from '@lang/en.json';
import { createI18n } from 'vue-i18n';
import languages from '@lang/languages.json';

const messages = { en };
export const LANGUAGES = languages.available;
export const SUPPORT_LOCALES = languages.available.map(l => l.value).filter(l => l != 'en');

const i18n = createI18n({
  messages,
  legacy: false,
  missingWarn: true,
  mode: 'composition',
  fallbackWarn: false,
  fallbackLocale: 'en',
  warnHtmlMessage: false,
  locale: typeof window === 'undefined' ? 'en' : window.Locale || 'en',
  missing: async (locale, key) => {
    console.log('"' + key + '" missing for ' + locale + ' locale.');
    console.log('"' + key + '": "' + key + '",');
  },
});

export default i18n;
