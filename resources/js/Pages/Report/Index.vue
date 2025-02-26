<template>
  <admin-layout :title="$t('Total Records')">
    <div class="px-4 md:px-0">
      <tec-section-title class="-mx-4 md:mx-0 mb-6">
        <template #title>{{ $t('Total Records') }}</template>
        <template #description>{{ $t('Please review the total records below') }}</template>
      </tec-section-title>

      <section class="mb-4 mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="p-4 rounded-md shadow bg-white" v-for="key in Object.keys(data)" :key="key">
            <div class="flex items-start justify-between">
              <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 truncate">{{ data[key] }}</h2>
            </div>
            <p class="leading-none text-gray-600">{{ $t(key.charAt(0).toUpperCase() + key.slice(1)) }}</p>
          </div>
        </div>
      </section>

      <tec-section-title class="-mx-4 md:mx-0 pt-6 mb-6">
        <template #title>{{ $t('Report Links') }}</template>
        <template #description>{{ $t('Please click to view the report') }}</template>
      </tec-section-title>

      <section class="mb-4 mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <Link :href="route('reports.checkin')" class="p-4 rounded-md shadow bg-gray-700">
            <p class="leading-none text-gray-100">{{ $t('x_report', { x: $t('Checkin') }) }}</p>
          </Link>
          <Link :href="route('reports.checkout')" class="p-4 rounded-md shadow bg-gray-700">
            <p class="leading-none text-gray-100">{{ $t('x_report', { x: $t('Checkout') }) }}</p>
          </Link>
          <Link :href="route('reports.transfer')" class="p-4 rounded-md shadow bg-gray-700">
            <p class="leading-none text-gray-100">{{ $t('x_report', { x: $t('Transfer') }) }}</p>
          </Link>
          <Link :href="route('reports.adjustment')" class="p-4 rounded-md shadow bg-gray-700">
            <p class="leading-none text-gray-100">{{ $t('x_report', { x: $t('Adjustment') }) }}</p>
          </Link>
        </div>
      </section>
    </div>
  </admin-layout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  props: ['data'],
  components: {
    AdminLayout,
    TecSectionTitle,
  },
};
</script>
