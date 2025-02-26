<template>
  <admin-layout :title="$t('Alerts')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Item quantity alerts for x', { x: warehouse.name }) }}</template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="mb-6 flex justify-between items-center print:hidden">
        <search-filter v-model="form.search" :dropdown="false" class="w-full max-w-md mr-4" @reset="reset" />
      </div>
      <div id="dd-table" class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-y-visible overflow-x-auto">
        <table class="w-full overflow-y-visible">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Name') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Code') }}</th>
              <th class="px-6 pt-6 pb-4 text-right">{{ $t('Alert on') }}</th>
              <th class="px-6 pt-6 pb-4 text-right">{{ $t('Stock') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr :key="item.id" v-for="item in items.data" class="hover:bg-gray-100 focus-within:bg-gray-100">
              <td class="border-t">
                <div class="px-6 py-4">{{ item.name }}</div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4">{{ item.code }}</div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 text-right">{{ $number(item.alert_quantity) }}</div>
              </td>
              <td class="border-t">
                <div class="px-6 py-4 text-right">
                  <div v-for="stock in item.stock" :key="stock.id">
                    {{ $number(stock.quantity) }}
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
  </admin-layout>
</template>

<script>
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle';
import mapValues from 'lodash/mapValues';
import Pagination from '@/Shared/Pagination.vue';
import TecDropdown from '@/Jetstream/Dropdown.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import SearchFilter from '@/Shared/SearchFilter.vue';
import TecDropdownLink from '@/Jetstream/DropdownLink.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  components: {
    Pagination,
    AdminLayout,
    TecDropdown,
    AutoComplete,
    SearchFilter,
    TecDropdownLink,
    TecSectionTitle,
  },

  props: {
    items: Object,
    filters: Object,
    warehouse: Object,
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
        this.$inertia.get(
          this.route(
            'alerts.list',
            Object.keys(query).length ? { ...query, warehouse: this.warehouse.id } : { warehouse: this.warehouse.id, remember: 'forget' }
          ),
          {},
          { replace: true, preserveState: true }
        );
      }, 150),
      deep: true,
    },
  },

  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
