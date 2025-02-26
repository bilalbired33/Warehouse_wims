<template>
  <admin-layout :title="$t('Users')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Users') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
          <label class="block text-gray-700">{{ $t('Role') }}:</label>
          <select-input v-model="form.role" class="mt-1 w-full">
            <option :value="null">{{ $t('All') }}</option>
            <template v-for="role in roles" :key="role">
              <option :value="role.name">{{ $t(role.name) }}</option>
            </template>
          </select-input>
          <label class="mt-4 block text-gray-700">{{ $t('Trashed') }}:</label>
          <select-input v-model="form.trashed" class="mt-1 w-full">
            <option :value="null">{{ $t('Not Trashed') }}</option>
            <option value="with">{{ $t('With Trashed') }}</option>
            <option value="only">{{ $t('Only Trashed') }}</option>
          </select-input>
        </search-filter>
        <tec-button :href="route('users.create')">
          <span>
            <icons name="plus" class="w-5 h-5 lg:mr-2" />
          </span>
          <span class="hidden lg:inline">{{ $t('create_x', { x: $t('User') }) }}</span>
        </tec-button>
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Email') }}</th>
              <th class="px-6 pt-6 pb-4" colspan="2">{{ $t('Role') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              :key="user.id"
              v-for="user in users.data"
              @click="goto(route('users.edit', user.id))"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
              :class="{ 'cursor-pointer': $can('update-users') }"
            >
              <td class="border-t">
                <div class="focus:outline-none px-6 py-4 flex items-center focus:text-indigo-500">
                  <img v-if="user.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="user.photo" />
                  {{ user.name }}
                  <icons v-if="user.two_factor_enabled" name="otp" class="shrink-0 text-blue-600 ml-2" />
                  <icons v-if="user.deleted_at" name="trash" class="shrink-0 text-red-500 ml-2" />
                </div>
              </td>
              <td class="border-t">
                <div class="focus:outline-none px-6 py-4 flex items-center">
                  {{ user.email }}
                </div>
              </td>
              <td class="border-t">
                <div class="focus:outline-none px-6 py-4 flex items-center">
                  {{ user.roles ? user.roles.map(r => $t(r)).join(', ') : '' }}
                </div>
              </td>
              <td class="border-t w-px">
                <div class="focus:outline-none px-4 flex items-center" v-if="$can('update-users')">
                  <icons name="chevron-down" class="transform -rotate-90 block w-4 h-4" />
                </div>
              </td>
            </tr>
            <tr v-if="users.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="users.meta" :links="users.links" />
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
    users: Object,
    filters: Object,
  },

  data() {
    return {
      form: {
        role: this.filters.role,
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },

  watch: {
    form: {
      handler: throttle(function () {
        let query = pickBy(this.form);
        this.$inertia.visit(this.route('users.index', Object.keys(query).length ? query : { remember: 'forget' }), {
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
      if (this.$can('update-users')) {
        this.$inertia.visit(link);
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
