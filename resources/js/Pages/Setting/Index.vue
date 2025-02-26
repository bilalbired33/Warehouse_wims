<template>
  <admin-layout :title="$t('Settings')">
    <tec-form-section @submitted="submit">
      <template #title>{{ $t('Settings') }}</template>
      <template #description>{{ $t('Please fill the form below to update settings.') }}</template>

      <template #form>
        <div class="flex items-start flex-wrap -mr-4 md:-mr-6">
          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Site Name')" />
            </div>
          </div>
          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="language"
                :searchable="false"
                v-model="form.language"
                :label="$t('Language')"
                :error="$page.props.errors.language"
                :suggestions="$page.props.languages"
              />
            </div>
          </div>
          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="reference"
                :searchable="false"
                v-model="form.reference"
                :label="$t('Reference')"
                :error="$page.props.errors.reference"
                :suggestions="[
                  { value: 'ulid', label: 'ULID - Universally Unique Lexicographically Sortable Identifier' },
                  { value: 'ai', label: 'Auto Increment (MYSQL only)' },
                  { value: 'uniqid', label: 'Uniqid - PHP Generate a Unique ID' },
                  { value: 'uuid', label: 'UUID - Universally Unique Identifier' },
                ]"
              />
            </div>
          </div>
          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <text-input v-model="form.currency_code" :error="$page.props.errors.currency_code" :label="$t('Currency Code')" />
            </div>
          </div>

          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <text-input v-model="form.default_locale" :error="$page.props.errors.default_locale" :label="$t('Date & Number Locale')" />
            </div>
          </div>

          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="date_format"
                :searchable="false"
                v-model="form.date_format"
                :label="$t('Date Format')"
                :error="$page.props.errors.date_format"
                :suggestions="[
                  { value: 'php', label: $t('On Server Side (PHP)') },
                  { value: 'js', label: $t('In Browser (Javascript)') },
                ]"
              />
            </div>
          </div>

          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="fraction"
                :searchable="false"
                v-model="form.fraction"
                :label="$t('Decimal Fractions')"
                :error="$page.props.errors.fraction"
                :suggestions="[
                  { value: 0, label: 0 },
                  { value: 1, label: 1 },
                  { value: 2, label: 2 },
                  { value: 3, label: 3 },
                  { value: 4, label: 4 },
                ]"
              />
            </div>
          </div>

          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="per_page"
                :searchable="false"
                v-model="form.per_page"
                :label="$t('Rows per page')"
                :error="$page.props.errors.per_page"
                :suggestions="[
                  { value: 10, label: 10 },
                  { value: 15, label: 15 },
                  { value: 25, label: 25 },
                  { value: 50, label: 50 },
                  { value: 100, label: 100 },
                ]"
              />
            </div>
          </div>

          <div class="w-full sm:w-1/2">
            <div class="mr-6 mb-6">
              <auto-complete
                id="sidebar"
                :searchable="false"
                :label="$t('Sidebar')"
                v-model="form.sidebar"
                :error="$page.props.errors.sidebar"
                :suggestions="[
                  { value: 'full', label: $t('Default') },
                  { value: 'mini', label: $t('Collapsed') },
                ]"
              />
            </div>
          </div>
          <transition name="slidedown">
            <div class="w-full sm:w-1/2" v-if="form.sidebar == 'full'">
              <div class="mr-6 mb-6">
                <auto-complete
                  id="sidebar_style"
                  :searchable="false"
                  :label="$t('Sidebar Style')"
                  v-model="form.sidebar_style"
                  :error="$page.props.errors.sidebar_style"
                  :suggestions="[
                    { value: 'full', label: $t('Full') },
                    { value: 'dropdown', label: $t('Dropdown') },
                  ]"
                />
              </div>
            </div>
          </transition>

          <div class="w-full mb-4">
            <check-box
              id="over_selling"
              :label="$t('Enable over selling')"
              v-model:checked="form.over_selling"
              :error="$page.props.errors.over_selling"
            />
          </div>

          <div class="w-full">
            <check-box
              id="track_weight"
              v-model:checked="form.track_weight"
              :error="$page.props.errors.track_weight"
              :label="$t('Track weight of item')"
            />
            <transition name="slidedown">
              <div v-if="form.track_weight" class="mt-6 w-full sm:w-1/2">
                <div class="mr-6 mb-6">
                  <text-input v-model="form.weight_unit" :error="$page.props.errors.weight_unit" :label="$t('Weight Unit')" />
                </div>
              </div>
            </transition>
          </div>
        </div>
      </template>

      <template #actions>
        <tec-action-message :on="form.recentlySuccessful" class="mr-3"> Saved. </tec-action-message>
        <loading-button type="submit" :loading="form.processing" :disabled="form.processing"> Save </loading-button>
      </template>
    </tec-form-section>

    <div class="mt-8">
      <tec-section-title>
        <template #title>{{ $t('Preview') }}</template>
        <template #description>{{ $t('Number and Date Format') }}</template>
      </tec-section-title>
      <div class="mt-6 bg-white shadow overflow-hidden md:rounded-md">
        <!-- <div class="px-4 py-5 sm:px-6 border-b">
          <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $t('Preview') }}</h3>
          <p class="mt-1 max-w-2xl text-gray-500">{{ $t('Number, Currency and Date Format') }}</p>
        </div> -->
        <div class="border-gray-200">
          <dl>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="font-medium text-gray-500">{{ $t('Number') }}</dt>
              <dd class="mt-1 text-gray-900 sm:mt-0 sm:col-span-2">
                {{
                  $number(20000000.5, form.default_locale, {
                    minimumFractionDigits: form.fraction,
                  })
                }}
              </dd>
            </div>
            <!-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="font-medium text-gray-500">{{ $t('Currency') }}</dt>
              <dd class="mt-1 text-gray-900 sm:mt-0 sm:col-span-2">
                {{
                  $currency(20000000.5, form.default_locale, {
                    style: 'currency',
                    currencyDisplay: 'symbol',
                    currency: form.currency_code,
                    minimumFractionDigits: form.fraction,
                  })
                }}
              </dd>
            </div> -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="font-medium text-gray-500">{{ $t('Date') }}</dt>
              <dd class="mt-1 text-gray-900 sm:mt-0 sm:col-span-2">
                {{ $date(new Date().toString(), form.default_locale) }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="font-medium text-gray-500">{{ $t('Date Time') }}</dt>
              <dd class="mt-1 text-gray-900 sm:mt-0 sm:col-span-2">
                {{ $datetime(new Date().toString(), form.default_locale) }}
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <div v-if="app_version" class="mt-8 flex items-center justify-center gap-6 text-sm">
      <div>
        App: v<span class="font-bold">{{ app_version }}</span>
      </div>
      <div>
        Laravel: v<span class="font-bold">{{ laravel }}</span>
      </div>
      <div>
        Vue: v<span class="font-bold">{{ version }}</span>
      </div>
      <div>
        <a href="/documentation.pdf" class="font-bold ml-4">Docs</a>
      </div>
    </div>
  </admin-layout>
</template>

<script setup>
import { onMounted, ref, version } from 'vue';

const app_version = ref(null);

onMounted(async () => {
  let composer = await import('@r/../composer.json');
  app_version.value = composer.version;
});
</script>

<script>
import CheckBox from '@/Shared/CheckBox.vue';
import TextInput from '@/Shared/TextInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';

export default {
  props: ['current', 'laravel'],

  components: {
    CheckBox,
    TextInput,
    SelectInput,
    AdminLayout,
    AutoComplete,
    LoadingButton,
    TecFormSection,
    TecSectionTitle,
    TecActionMessage,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: 'POST',
        name: this.current.name,
        sidebar: this.current.sidebar,
        language: this.current.language,
        fraction: +this.current.fraction,
        per_page: +this.current.per_page,
        weight_unit: this.current.weight_unit,
        date_format: this.current.date_format,
        currency_code: this.current.currency_code,
        sidebar_style: this.current.sidebar_style,
        default_locale: this.current.default_locale,
        reference: this.current.reference || 'ulid',
        over_selling: this.current.over_selling == 1,
        track_weight: this.current.track_weight == 1,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(route('settings.store'), { preserveScroll: true });
    },
  },
};
</script>
