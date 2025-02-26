<template>
  <admin-layout :title="$t('Activity')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title> {{ $t('Activities') }} </template>
        <template #description> {{ $t('Please review the data in the table below') }} </template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter :dropdown="false" v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" />
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Created at') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Description') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('By') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              :key="activity.id"
              @click="showDetails(activity)"
              v-for="activity in activities.data"
              class="hover:bg-gray-100 focus-within:bg-gray-100 cursor-pointer"
            >
              <td class="border-t px-6 py-4">
                {{ $datetime(activity.created_at) }}
              </td>
              <td class="border-t px-6 py-4">
                {{ activity.log_name }}
              </td>
              <td class="border-t px-6 py-4">
                {{ activity.description }}
              </td>
              <td class="border-t px-6 py-4">
                {{ activity.causer ? activity.causer.name : '' }}
              </td>
            </tr>
            <tr v-if="activities.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="activities.meta" :links="activities.links" />
    </div>

    <!-- Activity Details Modal -->
    <modal :show="details" max-width="2xl" :closeable="true" @close="hideDetails">
      <div class="px-6 py-4 print:px-0">
        <div class="flex items-center justify-between print:hidden">
          <div class="text-lg">
            {{ $t('Activity Details') }}
          </div>
          <button
            @click="hideDetails()"
            class="flex items-center justify-center -mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="cross" class="h-5 w-5" />
          </button>
        </div>

        <div v-if="activity" class="mt-4 p-4 bg-gray-100 -mx-6 -mb-6 md:rounded-b-lg">
          <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto print:m-0">
            <table class="w-full my-4">
              <tbody>
                <tr>
                  <td class="px-6 py-2 w-32 whitespace-nowrap">{{ $t('Subject Id') }}</td>
                  <td class="px-6 py-2">{{ activity.subject_id }}</td>
                </tr>
                <tr>
                  <td class="bg-gray-50 px-6 py-2 w-32 whitespace-nowrap">{{ $t('Subject Type') }}</td>
                  <td class="bg-gray-50 px-6 py-2">{{ activity.subject_type }}</td>
                </tr>
                <tr>
                  <td class="px-6 py-2 w-32 whitespace-nowrap">{{ $t('Properties') }}</td>
                  <td class="px-6 py-2">
                    <pre class="text-sm font-mono tracking-wide">{{ activity.properties }}</pre>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </modal>
  </admin-layout>
</template>

<script>
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import Modal from '@/Jetstream/Modal.vue';
import TecButton from '@/Jetstream/Button.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import Pagination from '@/Shared/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SearchFilter from '@/Shared/SearchFilter.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    Modal,
    TecButton,
    Pagination,
    AdminLayout,
    SelectInput,
    SearchFilter,
    TecSectionTitle,
  },

  props: {
    filters: Object,
    activities: Object,
  },

  data() {
    return {
      activity: null,
      details: false,
      form: { search: this.filters.search },
    };
  },

  watch: {
    form: {
      handler: throttle(function () {
        let query = pickBy(this.form);
        this.$inertia.visit(this.route('activity', Object.keys(query).length ? query : { remember: 'forget' }), {
          onFinish: () => {
            document.getElementById('page-search').focus();
          },
        });
      }, 150),
      deep: true,
    },
  },

  methods: {
    showDetails(activity) {
      this.activity = activity;
      this.details = true;
    },
    hideDetails() {
      this.activity = null;
      this.details = false;
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
