<template>
  <admin-layout :title="$t('Contacts')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Contacts') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
          <label class="mt-4 block text-gray-700">{{ $t('Trashed') }}:</label>
          <select-input v-model="form.trashed" class="mt-1 w-full">
            <option :value="null">{{ $t('Not Trashed') }}</option>
            <option value="with">{{ $t('With Trashed') }}</option>
            <option value="only">{{ $t('Only Trashed') }}</option>
          </select-input>
        </search-filter>
        <tec-dropdown align="right" width="48" v-if="$can(['create-contacts', 'import-contacts', 'export-contacts'])">
          <template #trigger>
            <button
              class="flex items-center px-4 py-3 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
            >
              <icons name="menu"></icons>
            </button>
          </template>

          <template #content>
            <tec-dropdown-link v-if="$can('create-contacts')" :href="route('contacts.create')">
              {{ $t('create_x', { x: $t('Contact') }) }}
            </tec-dropdown-link>
            <a
              v-if="$can('export-contacts')"
              :href="route('contacts.export')"
              class="block px-4 py-2 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
            >
              {{ $t('export_x', { x: $t('Contacts') }) }}
            </a>
            <tec-dropdown-link v-if="$can('import-contacts')" :href="route('contacts.import')">
              {{ $t('import_x', { x: $t('Contacts') }) }}
            </tec-dropdown-link>
          </template>
        </tec-dropdown>
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Email') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Phone') }}</th>
              <th class="px-6 pt-6 pb-4" colspan="2">{{ $t('Details') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              :key="contact.id"
              v-for="contact in contacts.data"
              @click="goto(route('contacts.edit', contact.id))"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
              :class="{ 'cursor-pointer': $can('update-contacts') }"
            >
              <td class="border-t">
                <div class="px-6 py-4 flex items-center focus:text-indigo-500">
                  {{ contact.name }}
                  <icons v-if="contact.deleted_at" name="trash" class="shrink-0 w-4 h-4 text-red-500 ml-2" />
                </div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 flex items-center">
                  {{ contact.email }}
                </div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 flex items-center">
                  {{ contact.phone }}
                </div>
              </td>
              <td class="border-t max-w-sm">
                <div class="px-6 py-4 flex items-center truncate">
                  {{ contact.details }}
                </div>
              </td>
              <td class="border-t w-px">
                <div v-if="$can('update-contacts')" class="px-4 flex items-center">
                  <icons name="chevron-down" class="transform -rotate-90 block w-4 h-4" />
                </div>
              </td>
            </tr>
            <tr v-if="contacts.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="contacts.meta" :links="contacts.links" />
    </div>
  </admin-layout>
</template>

<script>
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import TecButton from '@/Jetstream/Button.vue';
import Pagination from '@/Shared/Pagination.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import TecDropdown from '@/Jetstream/Dropdown.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SearchFilter from '@/Shared/SearchFilter.vue';
import TecDropdownLink from '@/Jetstream/DropdownLink.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    TecButton,
    Pagination,
    AdminLayout,
    TecDropdown,
    SelectInput,
    SearchFilter,
    TecDropdownLink,
    TecSectionTitle,
  },

  props: {
    filters: Object,
    contacts: Object,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },

  watch: {
    form: {
      handler: throttle(function () {
        let query = pickBy(this.form);
        this.$inertia.visit(this.route('contacts.index', Object.keys(query).length ? query : { remember: 'forget' }), {
          onFinish: () => {
            document.getElementById('page-search').focus();
          },
        });
      }, 150),
      deep: true,
    },
  },

  methods: {
    goto(link) {
      if (this.$can('update-contacts')) {
        this.$inertia.visit(link);
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
