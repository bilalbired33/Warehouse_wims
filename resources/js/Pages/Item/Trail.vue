<template>
  <admin-layout :title="$t('Item Trail')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>
          <div class="flex items-center">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('items.index')">{{ $t('Items') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            {{ $t('Trail') }} <span class="font-medium mx-2">/</span> {{ $t(item.name) }}
          </div>
        </template>
        <template #description>{{ $t('Please review the data in the table below') }}</template>
      </tec-section-title>

      <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-y-visible overflow-x-auto">
        <table class="w-full whitespace-nowrap overflow-y-visible">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pt-6 pb-4">{{ $t('Created at') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Warehouse') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Variation') }}</th>
              <!-- <th class="px-6 pt-6 pb-4">{{ $t('Type') }}</th> -->
              <th class="px-6 pt-6 pb-4">{{ $t('Quantity') }}</th>
              <th class="px-6 pt-6 pb-4">{{ $t('Weight') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="trail in trails.data" :key="trail.id">
              <td class="border-t px-6 py-4">
                <div>
                  {{ $datetime(trail.created_at) }}
                </div>
                <!-- <div class="capitalize text-gray-500">{{ trail.subject_type }}: {{ trail.subject_id }}</div> -->
                <div class="capitalize text-gray-500">{{ trail.type }} ({{ trail.subject_id }})</div>
              </td>
              <td class="border-t px-6 py-4">{{ $t(trail.warehouse.name) }}</td>
              <td class="border-t px-6 py-4" v-html="trail.variation ? $meta(trail.variation.meta) : ''"></td>
              <!-- <td class="border-t  px-6 py-4">{{ $t('Type') }}</td> -->
              <td class="border-t px-6 py-4">{{ $number(trail.quantity) }} {{ trail.unit ? trail.unit.code : '' }}</td>
              <td class="border-t px-6 py-4">
                <span v-if="trail.weight">{{ $number(trail.weight) }} {{ $settings.weight_unit || '' }}</span>
              </td>
            </tr>
            <tr v-if="trails.data.length === 0">
              <td class="border-t px-6 py-4" colspan="5">{{ $t('There is no data to display.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination class="mt-6" :meta="trails.meta" :links="trails.links" />
    </div>
  </admin-layout>
</template>

<script>
import Pagination from '@/Shared/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  props: { item: Object, trails: Object },

  components: { Pagination, AdminLayout, TecSectionTitle },

  data() {
    return {
      loading: false,
    };
  },

  methods: {},
};
</script>
