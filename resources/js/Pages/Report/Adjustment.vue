<template>
  <admin-layout :title="$t('x_report', { x: $t('Adjustment') })">
    <div class="px-4 md:px-0">
      <div class="flex items-start justify-between">
        <tec-section-title class="-mx-4 md:mx-0 mb-6">
          <template #title>{{ $t('x_report', { x: $t('Adjustment') }) }}</template>
          <template #description>{{ $t('Please review the report below') }}</template>
        </tec-section-title>
        <tec-button type="button" @click="toggleForm()">
          <span>
            <icons name="toggle" class="w-5 h-5 lg:mr-2" />
          </span>
          <span class="hidden lg:inline">{{ $t('toggle_x', { x: $t('Form') }) }}</span>
        </tec-button>
      </div>

      <transition name="slidedown">
        <div v-show="showForm" class="w-full print:hidden">
          <report-form
            :users="users"
            type="adjustment"
            :categories="categories"
            :warehouses="warehouses"
            :action="route('reports.adjustment')"
          />
        </div>
      </transition>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Adjustment') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Relations') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Details') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr :key="adjustment.id" v-for="(adjustment, ci) in adjustments.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t" @click="goto(adjustment.id)" :class="{ 'cursor-pointer': $can('read-adjustments') }">
                <div class="px-6 py-4 flex items-center focus:text-indigo-500">
                  <div>
                    <div>{{ adjustment.reference }}</div>
                    <div>{{ $date(adjustment.date) }}</div>
                    <div class="flex items-center">
                      {{ $t('Draft') }}:
                      <icons v-if="adjustment.draft == 1" name="tick" class="text-green-600 mx-auto" />
                      <icons v-else name="cross" class="text-red-600 mx-auto" />
                    </div>
                  </div>

                  <icons v-if="adjustment.deleted_at" name="trash" class="shrink-0 w-4 h-4 text-red-500 ml-2" />
                </div>
              </td>
              <td class="border-t" @click="goto(adjustment.id)" :class="{ 'cursor-pointer': $can('read-adjustments') }">
                <div class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="text-gray-500 mr-1">{{ $t('Warehouse') }}:</div>
                    {{ adjustment.warehouse.name }}
                  </div>
                  <div class="flex items-center">
                    <div class="text-gray-500 mr-1">{{ $t('User') }}:</div>
                    {{ adjustment.user.name }}
                  </div>
                </div>
              </td>
              <td class="border-t max-w-lg min-w-56" @click="goto(adjustment.id)" :class="{ 'cursor-pointer': $can('read-adjustments') }">
                <div class="px-6 py-4 flex items-center">
                  <div class="w-full whitespace-normal line-clamp-3">
                    {{ adjustment.details }}
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="adjustments.data.length === 0">
              <td class="border-t px-6 py-4" colspan="3">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="adjustments.meta" :links="adjustments.links" />
    </div>

    <!-- Item Details Modal -->
    <modal :show="details" max-width="4xl" :closeable="true" @close="hideDetails">
      <div class="px-6 py-4 print:px-0">
        <div v-if="details && adjustment" class="flex items-center justify-between print:hidden">
          <div class="text-lg">
            {{ $t('Adjustment Details') }} <span class="hidden sm:inline">({{ adjustment.reference }})</span>
          </div>
          <div class="-mr-2 flex items-center">
            <button
              @click="print()"
              class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
            >
              <icons name="printer" class="h-5 w-5" />
            </button>
            <Link
              v-if="$can('update-adjustments')"
              :href="route('adjustments.edit', adjustment.id)"
              class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
            >
              <icons name="edit" class="h-5 w-5" />
            </Link>
            <button
              @click="hideDetails()"
              class="flex items-center justify-center h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
            >
              <icons name="cross" class="h-5 w-5" />
            </button>
          </div>
        </div>

        <div class="mt-4 print-mt-0">
          <adjustment-details v-if="adjustment" :adjustment="adjustment" />
        </div>
      </div>
    </modal>

    <loading v-if="loading" />
  </admin-layout>
</template>

<script>
import Modal from '@/Jetstream/Modal.vue';
import TecButton from '@/Jetstream/Button.vue';
import ReportForm from '@/Pages/Report/Form.vue';
import Pagination from '@/Shared/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdjustmentDetails from '@/Pages/Adjustment/Details.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    Modal,
    TecButton,
    ReportForm,
    Pagination,
    AdminLayout,
    AdjustmentDetails,
    TecSectionTitle,
  },

  props: {
    filters: Object,
    adjustments: Object,
    users: Array,
    categories: Array,
    warehouses: Array,
  },

  data() {
    return {
      adjustment: null,
      details: false,
      showForm: false,
      loading: false,
    };
  },

  methods: {
    toggleForm() {
      this.showForm = !this.showForm;
    },
    goto(id) {
      if (this.adjustment && this.adjustment.id == id) {
        this.details = true;
      } else {
        this.loading = true;
        axios.get(route('adjustments.show', id) + '?json=yes').then(res => {
          this.adjustment = res.data;
          this.details = true;
          this.loading = false;
        });
      }
    },
    showDetails() {
      this.details = false;
    },
    hideDetails() {
      this.details = false;
    },
    print() {
      window.print();
    },
  },
};
</script>
