import './bootstrap';
import '../css/app.css';

import i18n from '@/Core/i18n';
import mixin from '@/Core/mixin';
import Icons from '@/Shared/Icons.vue';
import Boolean from '@/Shared/Boolean.vue';
import Loading from '@/Shared/Loading.vue';
import { LANGUAGES, SUPPORT_LOCALES } from '@/Core/i18n';

import { useI18n } from 'vue-i18n';
import { createApp, h } from 'vue';
import { route, ZiggyVue } from 'ziggy-js';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'WIMS';

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  async setup({ el, App, props, plugin }) {
    for await (const lang of SUPPORT_LOCALES) {
      let messages = await import(`../../lang/${lang}.json`);
      messages = JSON.parse(JSON.stringify(messages));
      messages = { ...messages, ...messages?.default, default: 'default' };
      i18n.global.setLocaleMessage(lang, messages);
    }

    let app = createApp({
      setup() {
        const { t } = useI18n();
        return { t };
      },
      render: () => h(App, props),
      mounted: () => {
        document.getElementById('app-loading').style.display = 'none';
      },
    })
      .use(plugin)
      .use(i18n)
      .mixin(mixin)
      .use(ZiggyVue, Ziggy)
      .component('Head', Head)
      .component('Link', Link)
      .component('Icons', Icons)
      .component('Boolean', Boolean)
      .component('Loading', Loading);

    app.config.globalProperties.$route = route;
    return app.mount(el);
  },
  progress: { color: '#2563EB', showSpinner: true },
});

// InertiaProgress.init({ color: '#2563EB', showSpinner: true });
