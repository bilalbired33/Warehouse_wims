<template>
  <admin-layout :title="$t('Items')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Items') }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex flex-col-reverse lg:flex-row px-4 lg:px-0 gap-4 justify-between items-center print:hidden">
        <search-filter v-model="form.search" class="w-full max-w-md" :close="close" @reset="reset">
          <!-- <label class="mt-4 block text-gray-700">{{ $t('Trashed') }}:</label> -->
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
        </search-filter>
        <div class="flex items-center justify-center">
          <div class="mr-4 flex items-center -mb-1 w-44">
            <auto-complete id="warehouse" label="" :placeholder="$t('Warehouse')" :suggestions="warehouses" v-model="warehouse_id" />
          </div>
          <tec-dropdown align="right" width="48" v-if="$can(['create-items', 'import-items', 'export-items'])">
            <template #trigger>
              <button
                class="flex items-center px-4 py-3 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
              >
                <icons name="menu"></icons>
              </button>
            </template>

            <template #content>
              <tec-dropdown-link v-if="$can('create-items')" :href="route('items.create')">
                {{ $t('create_x', { x: $t('Item') }) }}
              </tec-dropdown-link>
              <a
                v-if="$can('export-items')"
                :href="route('items.export', { search: form.search })"
                class="block px-4 py-2 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
              >
                {{ $t('export_x', { x: $t('Items') }) }}
              </a>
              <tec-dropdown-link v-if="$can('import-items')" :href="route('items.import')">
                {{ $t('import_x', { x: $t('Items') }) }}
              </tec-dropdown-link>
            </template>
          </tec-dropdown>
        </div>
      </div>
      <div id="dd-table" class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-y-visible overflow-x-auto">
        <table class="w-full overflow-y-visible">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Options') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Variants') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Relations') }}</th>
              <th class="px-6 pt-6 pb-4" colspan="2">
                {{ $t('Stock') }} {{ warehouse_id ? ' (' + warehouses.find(w => w.id == warehouse_id).code + ')' : '' }}
              </th>
              <!-- <th class="px-6 pt-6 pb-4" colspan="2">{{ $t('Details') }}</th> -->
            </tr>
          </thead>
          <tbody>
            <tr :key="item.id" v-for="item in items.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
                <div class="px-6 py-4 max-w-md flex items-center focus:text-indigo-500">
                  <img v-if="item.photo" class="block w-16 h-16 rounded-sm mr-2 -my-2" :src="item.photo" />
                  <div>
                    <div class="font-bold">{{ item.name }}</div>
                    <div>
                      {{ $t('Code') }}: <strong>{{ item.code }}</strong>
                    </div>
                    <div v-if="item.sku" class="text-gray-600">{{ $t('SKU') }}: {{ item.sku }}</div>
                    <div class="flex items-center">
                      <span class="text-gray-600 mr-1">{{ $t('Symbology') }}:</span> <span class="uppercase">{{ item.symbology }}</span>
                    </div>
                  </div>
                  <icons v-if="item.deleted_at" name="trash" class="shrink-0 w-4 h-4 text-red-500 ml-2" />
                </div>
              </td>
              <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
                <div class="px-6 py-4 w-48">
                  <!-- <div class="flex items-center">
                  <span class="text-gray-600 mr-1">{{ $t('Track Serials') }}</span>
                  <boolean :value="item.has_serials" class="w-3 h-3 ml-1" />
                </div> -->
                  <div class="flex items-center">
                    <span class="text-gray-600 mr-1">{{ $t('Track Weight') }}</span>
                    <boolean :value="item.track_weight" class="w-3 h-3 ml-1" />
                  </div>
                  <div class="flex items-center">
                    <span class="text-gray-600 mr-1">{{ $t('Track Quantity') }}</span>
                    <boolean :value="item.track_quantity" class="w-3 h-3 ml-2" />
                  </div>
                  <div v-if="item.track_quantity == 1" class="flex items-center">
                    <span class="text-gray-600 mr-1">{{ $t('Alert on') }}:</span>
                    <span>{{ $number(item.alert_quantity || 0) }}</span>
                  </div>
                </div>
              </td>
              <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
                <div class="px-6 py-4 w-48">
                  <div v-if="item.has_variants == 1" class="flex items-center flex-wrap">
                    <span class="text-gray-600 mr-1">{{ $t('Variants') }}:</span>
                    <template v-for="v in item.variants" :key="v.name">
                      <template v-if="v.name">
                        <div class="ml-1">
                          <strong>{{ v.name }}:</strong>
                          <!-- <strong>{{ $t(v.name) }}:</strong> -->
                          {{
                            v.option
                              .filter(o => o)
                              // .map(o => $t(o))
                              .join(', ')
                          }}
                        </div>
                      </template>
                    </template>
                  </div>
                </div>
              </td>
              <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
                <div class="px-6 py-4 w-48">
                  {{ item.categories.map(c => c.name).join(', ') }}
                  <div v-if="item.unit">
                    <span class="text-gray-600">{{ $t('Unit') }}:</span> {{ item.unit.name }}
                  </div>
                </div>
              </td>
              <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
                <template v-if="warehouse_id">
                  <div class="px-6 py-4 w-56 whitespace-nowrap">
                    <div class="w-full flex flex-col items-center justify-between text-right">
                      <!-- <div class="text-gray-600 mr-1">{{ $t('Total') }}:</div> -->
                      <div class="w-full flex items-center justify-between">
                        <span class="text-gray-600">{{ $t('Quantity') }}:</span>
                        <span class="font-bold">{{ $number(item.stock.find(w => w.warehouse_id == warehouse_id)?.quantity) }}</span>
                      </div>
                      <div v-if="item.track_weight == 1" class="w-full flex items-center justify-between">
                        <span class="text-gray-600">{{ $t('Weight') }}:</span>
                        <span class="font-bold">{{ $number(item.stock.find(w => w.warehouse_id == warehouse_id)?.weight) }}</span>
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="px-6 py-4 w-56 whitespace-nowrap">
                    <!-- <div v-for="stock in item.stock" :key="stock.id" class="w-full flex items-center justify-between border-b">
                  <div class="text-gray-600 mr-1">{{ stock.warehouse ? stock.warehouse.code : '' }}:</div>
                  <div>
                    {{ $number(stock.quantity) }} <span v-if="item.track_weight == 1">({{ $number(stock.weight) }})</span>
                  </div>
                </div> -->
                    <div class="w-full flex flex-col items-center justify-between text-right">
                      <!-- <div class="text-gray-600 mr-1">{{ $t('Total') }}:</div> -->
                      <div class="w-full flex items-center justify-between">
                        <span class="text-gray-600">{{ $t('Quantity') }}:</span>
                        <span class="font-bold">{{ $number(item.stock.reduce((a, c) => a + parseFloat(c.quantity), 0)) }}</span>
                      </div>
                      <div v-if="item.track_weight == 1" class="w-full flex items-center justify-between">
                        <span class="text-gray-600">{{ $t('Weight') }}:</span>
                        <span class="font-bold">{{ $number(item.stock.reduce((a, c) => a + parseFloat(c.weight), 0)) }}</span>
                      </div>
                    </div>
                  </div>
                </template>
              </td>
              <!-- <td class="border-t" @click="goto(item)" :class="{ 'cursor-pointer': $can('read-items') }">
              <div class="px-6 py-4 flex items-center max-w-lg min-w-56 w-64">
                <div class="w-full whitespace-normal line-clamp-4">
                  {{ item.details }}
                </div>
              </div>
            </td> -->
              <td class="border-t w-16">
                <div class="px-4 flex items-center print:hidden">
                  <div class="flex items-center" v-if="$can(['create-items', 'import-items', 'export-items'])">
                    <Link
                      v-if="$can('trail-items')"
                      :href="route('items.trail', item.id)"
                      class="flex items-center p-3 md:p-2 bg-blue-600 rounded-l-md text-white hover:bg-blue-700 focus:bg-blue-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                    >
                      <icons name="list"></icons>
                    </Link>
                    <Link
                      :href="route('items.show', item.id)"
                      class="flex items-center p-3 md:p-2 bg-blue-600 text-white hover:bg-blue-700 focus:bg-blue-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                    >
                      <icons name="doc"></icons>
                    </Link>
                    <Link
                      v-if="$can('update-items')"
                      :href="route('items.edit', item.id)"
                      class="flex items-center p-3 md:p-2 bg-yellow-600 text-white hover:bg-yellow-700 focus:bg-yellow-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                    >
                      <icons name="edit"></icons>
                    </Link>
                    <template v-if="item.deleted_at">
                      <button
                        type="button"
                        @click="restore(item)"
                        class="flex items-center p-3 md:p-2 bg-blue-600 text-white hover:bg-blue-700 focus:bg-blue-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="refresh"></icons>
                      </button>
                      <button
                        type="button"
                        @click="deletePermanently(item)"
                        class="flex items-center p-3 md:p-2 bg-red-600 rounded-r-md text-white hover:bg-red-700 focus:bg-red-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="trash"></icons>
                      </button>
                    </template>
                    <template v-else>
                      <button
                        type="button"
                        @click="destroy(item)"
                        class="flex items-center p-3 md:p-2 bg-red-600 rounded-r-md text-white hover:bg-red-700 focus:bg-red-700 z-0 focus:z-10 focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
                      >
                        <icons name="trash"></icons>
                      </button>
                    </template>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="items.data.length === 0">
              <td class="border-t px-6 py-4" colspan="5">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="items.meta" :links="items.links" />
    </div>

    <!-- Item Details Modal -->
    <modal :show="details" max-width="4xl" :closeable="true" @close="hideDetails">
      <div class="rounded-md overflow-hidden">
        <div class="px-6 py-4 bg-white border-b flex items-center justify-between print:hidden">
          <div class="text-lg">
            {{ $t('Item Details') }} <span v-if="details && item">({{ item.name }})</span>
          </div>
          <button
            @click="hideDetails()"
            class="flex items-center justify-center -mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="cross" class="h-5 w-5" />
          </button>
        </div>

        <div class="p-6 print:block print:h-full bg-gray-100">
          <Item-details v-if="item" :item="item" :modal="true" />
        </div>
      </div>
    </modal>

    <!-- Delete User Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="permanent"
      action-type="delete"
      title="Delete Item?"
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
      title="Delete Item?"
      action-text="Delete Item"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreItem"
      title="Restore Item!"
      :close="closeRestoreModal"
      action-text="Restore Item"
      :content="$t('Are you sure you want to restore the record?')"
    />

    <loading v-if="loading" />
  </admin-layout>
</template>

<script>
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import ItemDetails from './Details.vue';
import Dialog from '@/Shared/Dialog.vue';
import Modal from '@/Jetstream/Modal.vue';
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
    ItemDetails,
    AdminLayout,
    TecDropdown,
    SelectInput,
    AutoComplete,
    SearchFilter,
    TecDropdownLink,
    TecSectionTitle,
  },

  props: {
    items: Object,
    filters: Object,
    warehouses: Array,
  },

  data() {
    return {
      edit: null,
      item: null,
      close: false,
      confirm: false,
      details: false,
      loading: false,
      permanent: false,
      restoreConf: false,
      warehouse_id: false,
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
        this.$inertia.visit(this.route('items.index', Object.keys(query).length ? query : { remember: 'forget' }), {
          onFinish: () => {
            document.getElementById('page-search').focus();
          },
        });
      }, 150),
      deep: true,
    },
  },

  methods: {
    goto(item) {
      // this.item = item;
      // this.details = true;
      if (this.item && this.item.id == item.id) {
        this.details = true;
      } else {
        this.loading = true;
        axios.get(route('items.show', item.id) + '?json=yes').then(res => {
          this.item = res.data;
          this.details = true;
          this.loading = false;
        });
      }
      // if (this.$can('read-items')) {
      //   this.$inertia.visit(route('items.show', id));
      // }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    // destroy(id, deleted_at) {
    //   this.$inertia.delete(route(deleted_at ? 'items.destroy.permanently' : 'items.destroy', id));
    // },
    destroy(edit) {
      this.edit = edit;
      this.confirm = true;
    },
    deleteItem() {
      this.$inertia.delete(route('items.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    showDetails() {
      this.details = false;
    },
    hideDetails() {
      this.details = false;
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
      this.$inertia.put(this.route('items.restore', this.edit.id), {
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
      this.$inertia.delete(route('items.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.edit = null;
      this.permanent = false;
    },
  },
};
</script>
