<template>
  <admin-layout :title="$t('Adjustments')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Adjustments') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
          <auto-complete
            id="trashed"
            position="left"
            :label="$t('Trashed')"
            v-model="form.trashed"
            class="mt-1 w-full"
            :suggestions="[
              { label: $t('Not Trashed'), value: null },
              { label: $t('With Trashed'), value: 'with' },
              { label: $t('Only Trashed'), value: 'only' },
            ]"
          >
          </auto-complete>
          <auto-complete
            id="draft"
            position="left"
            :label="$t('Draft')"
            v-model="form.draft"
            class="mt-1 w-full"
            :suggestions="[
              { label: $t('All'), value: null },
              { label: $t('Yes'), value: 'yes' },
              { label: $t('No'), value: 'no' },
            ]"
          >
          </auto-complete>
        </search-filter>
        <tec-button :href="route('adjustments.create')">
          <span>
            <icons name="plus" class="w-5 h-5 lg:mr-2" />
          </span>
          <span class="hidden lg:inline">{{ $t('create_x', { x: $t('Adjustment') }) }}</span>
        </tec-button>
        <!-- <tec-dropdown align="right" width="48" v-if="$can(['create-adjustments', 'import-adjustments', 'export-adjustments'])">
          <template #trigger>
            <button
              class="flex items-center px-4 py-3 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
            >
              <icons name="menu"></icons>
            </button>
          </template>

          <template #content>
            <tec-dropdown-link v-if="$can('create-adjustments')" :href="route('adjustments.create')">
              {{ $t('create_x', { x: $t('Adjustment') }) }}
            </tec-dropdown-link>
            <a
              v-if="$can('export-adjustments')"
              :href="route('adjustments.export')"
              class="block px-4 py-2 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
            >
              {{ $t('export_x', { x: $t('Adjustments') }) }}
            </a>
            <tec-dropdown-link v-if="$can('import-adjustments')" :href="route('adjustments.import')">
              {{ $t('import_x', { x: $t('Adjustments') }) }}
            </tec-dropdown-link>
          </template>
        </tec-dropdown> -->
      </div>
      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Adjustment') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Relations') }}</th>
              <th class="px-6 pt-6 pb-4" colspan="2">{{ $t('Details') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr :key="adjustment.id" v-for="(adjustment, ci) in adjustments.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t" @click="goto(adjustment.id)" :class="{ 'cursor-pointer': $can('read-adjustments') }">
                <div class="px-6 py-4 flex items-center focus:text-indigo-500">
                  <div>
                    <div>
                      <span class="text-gray-500">{{ $t('Ref') }}:</span> {{ adjustment.reference }}
                    </div>
                    <div>
                      <span class="text-gray-500">{{ $t('Date') }}:</span> {{ $date(adjustment.date) }}
                    </div>
                    <div>
                      <span class="text-gray-500">{{ $t('Type') }}:</span> {{ adjustment.type }}
                    </div>
                    <div v-if="adjustment.draft == 1" class="flex items-center">
                      <span class="text-gray-500">{{ $t('Draft') }}:</span>
                      <icons name="tick" class="text-green-600 ml-2" />
                      <!-- <icons v-else name="cross" class="text-red-600 mx-auto" /> -->
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
              <td class="border-t w-16">
                <div class="px-4 flex items-center print:hidden">
                  <div class="flex items-center" v-if="$can(['create-adjustments', 'import-adjustments', 'export-adjustments'])">
                    <Link
                      :href="route('adjustments.show', adjustment.id)"
                      class="flex items-center p-3 md:p-2 bg-blue-600 rounded-l-md text-white hover:bg-blue-700 focus:bg-blue-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                    >
                      <icons name="doc"></icons>
                    </Link>
                    <!-- <button
                    @click="sendEmail(adjustment.id)"
                    class="flex items-center p-3 md:p-2 bg-indigo-600 text-white hover:bg-indigo-700 focus:bg-indigo-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                  >
                    <icons name="email"></icons>
                  </button> -->
                    <Link
                      v-if="$can('update-adjustments')"
                      :href="route('adjustments.edit', adjustment.id)"
                      class="flex items-center p-3 md:p-2 bg-yellow-600 text-white hover:bg-yellow-700 focus:bg-yellow-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                    >
                      <icons name="edit"></icons>
                    </Link>
                    <template v-if="adjustment.deleted_at">
                      <button
                        type="button"
                        @click="restore(adjustment)"
                        class="flex items-center p-3 md:p-2 bg-blue-600 text-white hover:bg-blue-700 focus:bg-blue-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="refresh"></icons>
                      </button>
                      <button
                        type="button"
                        @click="deletePermanently(adjustment)"
                        class="flex items-center p-3 md:p-2 bg-red-600 rounded-r-md text-white hover:bg-red-700 focus:bg-red-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="trash"></icons>
                      </button>
                    </template>
                    <template v-else>
                      <button
                        type="button"
                        @click="destroy(adjustment)"
                        class="flex items-center p-3 md:p-2 bg-red-600 rounded-r-md text-white hover:bg-red-700 focus:bg-red-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="trash"></icons>
                      </button>
                    </template>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="adjustments.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ $t('There is no data to display.') }}</td>
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
            <!-- <button
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
            </Link> -->
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

    <!-- Delete User Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="permanent"
      action-type="delete"
      title="Delete Adjustment!"
      :close="closePermanentModal"
      action-text="Delete Permanently"
      :action="deleteCategoryPermanently"
      :content="`<p class='mb-2'>${$t('Are you sure you want to delete the record permanently?')}</p>
        <p>${$t('Once deleted, all of its resources and data will be permanently deleted.')}</p>`"
    />

    <!-- Delete Account Confirmation Modal -->
    <Dialog
      :show="confirm"
      :close="closeModal"
      :action="deleteItem"
      action-type="delete"
      title="Delete Adjustment!"
      action-text="Delete Adjustment"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreItem"
      title="Restore Adjustment!"
      :close="closeRestoreModal"
      action-text="Restore Adjustment"
      :content="$t('Are you sure you want to restore the record?')"
    />

    <loading v-if="loading" />
  </admin-layout>
</template>

<script>
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import Dialog from '@/Shared/Dialog.vue';
import Modal from '@/Jetstream/Modal.vue';
import AdjustmentDetails from './Details.vue';
import TecButton from '@/Jetstream/Button.vue';
import Pagination from '@/Shared/Pagination.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import TecDropdown from '@/Jetstream/Dropdown.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import SearchFilter from '@/Shared/SearchFilter.vue';
import TecDropdownLink from '@/Jetstream/DropdownLink.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    Modal,
    Dialog,
    TecButton,
    Pagination,
    AdminLayout,
    TecDropdown,
    SelectInput,
    AutoComplete,
    SearchFilter,
    AdjustmentDetails,
    TecDropdownLink,
    TecSectionTitle,
  },

  props: {
    filters: Object,
    adjustments: Object,
  },

  data() {
    return {
      edit: null,
      close: false,
      adjustment: null,
      details: false,
      confirm: false,
      loading: false,
      permanent: false,
      restoreConf: false,
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
        this.$inertia.get(
          this.route('adjustments.index', Object.keys(query).length ? query : { remember: 'forget' }),
          {},
          {
            onFinish: () => {
              document.getElementById('page-search').focus();
            },
          }
        );
      }, 150),
      deep: true,
    },
  },

  methods: {
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
      // if (this.$can('read-adjustments')) {
      //   this.$inertia.visit(route('adjustments.show', id));
      // }
    },
    showDetails() {
      this.details = false;
    },
    hideDetails() {
      this.details = false;
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    sendEmail(id) {
      this.$inertia.get(route('notifications.adjustment', id));
    },
    destroy(edit) {
      this.edit = edit;
      this.confirm = true;
    },
    deleteItem() {
      this.$inertia.delete(route('adjustments.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.edit = null;
      this.confirm = false;
    },
    restore(edit) {
      this.edit = edit;
      this.restoreConf = true;
    },
    restoreItem() {
      this.$inertia.put(this.route('adjustments.restore', this.edit.id), {
        onSuccess: () => (this.restoreConf = false),
      });
    },
    closeRestoreModal() {
      this.edit = null;
      this.restoreConf = false;
    },
    deletePermanently(edit) {
      this.edit = edit;
      this.permanent = true;
    },
    deleteCategoryPermanently() {
      this.$inertia.delete(route('adjustments.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.edit = null;
      this.permanent = false;
    },
    print() {
      window.print();
    },
  },
};
</script>
