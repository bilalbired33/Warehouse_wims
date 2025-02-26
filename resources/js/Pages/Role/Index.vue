<template>
  <admin-layout :title="$t('Roles')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Roles') }}</template>
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
        <tec-button :href="route('roles.create')">
          <span>
            <icons name="plus" class="w-5 h-5 lg:mr-2" />
          </span>
          <span class="hidden lg:inline">{{ $t('create_x', { x: $t('Role') }) }}</span>
        </tec-button>
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              :key="role.id"
              v-for="role in roles.data"
              @click="goto(route('roles.edit', role.id))"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
              :class="{ 'cursor-pointer': role.name != 'Super Admin' && $can('update-roles') }"
            >
              <td class="border-t">
                <div class="focus:outline-none px-6 py-4 flex items-center focus:text-indigo-500">
                  {{ $t(role.name) }}
                  <icons v-if="role.deleted_at" name="trash" class="shrink-0 w-4 h-4 text-red-500 ml-2" />
                </div>
              </td>
              <td class="border-t w-px">
                <div v-if="role.name != 'Super Admin' && $can('update-roles')" class="focus:outline-none px-4 flex items-center">
                  <icons name="chevron-down" class="transform -rotate-90 block w-4 h-4" />
                </div>
                <div v-else></div>
              </td>
            </tr>
            <tr v-if="roles.data.length === 0">
              <td class="border-t px-6 py-4" colspan="2">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="roles.meta" :links="roles.links" />
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
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SearchFilter from '@/Shared/SearchFilter.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    TecButton,
    Pagination,
    AdminLayout,
    SelectInput,
    SearchFilter,
    TecSectionTitle,
  },

  props: {
    roles: Object,
    filters: Object,
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
        this.$inertia.visit(this.route('roles.index', Object.keys(query).length ? query : { remember: 'forget' }), {
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
      if (this.$can('update-roles')) {
        this.$inertia.visit(link);
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
