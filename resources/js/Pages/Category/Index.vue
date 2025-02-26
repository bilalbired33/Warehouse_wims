<template>
  <admin-layout :title="$t('Categories')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Categories') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
          <label class="mt-4 block text-gray-700">{{ $t('Child of') }}:</label>
          <select-input v-model="form.parent" class="mt-1 w-full">
            <option :value="null">{{ $t('All') }}</option>
            <template v-for="c in parents" :key="c.id">
              <option :value="c.id">{{ c.name }}</option>
            </template>
          </select-input>
          <label class="mt-4 block text-gray-700">{{ $t('Trashed') }}:</label>
          <select-input v-model="form.trashed" class="mt-1 w-full">
            <option :value="null">{{ $t('Not Trashed') }}</option>
            <option value="with">{{ $t('With Trashed') }}</option>
            <option value="only">{{ $t('Only Trashed') }}</option>
          </select-input>
        </search-filter>
        <tec-dropdown align="right" width="48" v-if="$can(['create-categories', 'import-categories', 'export-categories'])">
          <template #trigger>
            <button
              class="flex items-center px-4 py-3 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
            >
              <icons name="menu"></icons>
            </button>
          </template>

          <template #content>
            <tec-dropdown-link v-if="$can('create-categories')" :href="route('categories.create')">
              {{ $t('create_x', { x: $t('Category') }) }}
            </tec-dropdown-link>
            <a
              v-if="$can('export-categories')"
              :href="route('categories.export')"
              class="block px-4 py-2 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
            >
              {{ $t('export_x', { x: $t('Categories') }) }}
            </a>
            <tec-dropdown-link v-if="$can('import-categories')" :href="route('categories.import')">
              {{ $t('import_x', { x: $t('Categories') }) }}
            </tec-dropdown-link>
          </template>
        </tec-dropdown>
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Code') }}</th>
              <th class="px-6 pt-6 pb-4" colspan="2">{{ $t('Child of') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              :key="category.id"
              v-for="category in categories.data"
              @click="goto(route('categories.edit', category.id))"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
              :class="{ 'cursor-pointer': $can('update-categories') }"
            >
              <td class="border-t">
                <div class="px-6 py-4">
                  <div class="flex items-center">
                    {{ category.name }}
                    <icons v-if="category.deleted_at" name="trash" class="shrink-0 w-4 h-4 text-red-500 ml-2" />
                  </div>
                </div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 flex items-center">
                  {{ category.code }}
                </div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 flex items-center">
                  {{ category.parent_id ? category.parent.name : '' }}
                  <span class="text-gray-500 ml-1">{{ category.parent_id ? '(' + category.parent.code + ')' : '' }}</span>
                </div>
              </td>
              <td class="border-t w-px">
                <div v-if="$can('update-categories')" class="px-4 flex items-center">
                  <icons name="chevron-down" class="transform -rotate-90 block w-4 h-4" />
                </div>
              </td>
            </tr>
            <tr v-if="categories.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="categories.meta" :links="categories.links" />
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
    parents: Array,
    filters: Object,
    categories: Object,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
        parent: this.filters.parent,
        trashed: this.filters.trashed,
      },
    };
  },

  watch: {
    form: {
      handler: throttle(function () {
        let query = pickBy(this.form);
        this.$inertia.visit(this.route('categories.index', Object.keys(query).length ? query : { remember: 'forget' }), {
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
      if (this.$can('update-categories')) {
        this.$inertia.visit(link);
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
