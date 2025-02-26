<template>
  <admin-layout :title="$t('Adjustment Details')">
    <div class="px-4 md:px-0">
      <div class="flex items-start justify-between print:hidden">
        <tec-section-title class="-mx-4 md:mx-0 mb-6">
          <template #title>
            <div class="flex items-center">
              <Link class="text-blue-600 hover:text-blue-700" :href="route('adjustments.index')">{{ $t('Adjustments') }}</Link>
              <span class="text-blue-600 font-medium mx-2">/</span>
              {{ adjustment.reference }}
            </div>
          </template>
          <template #description>{{ $t('Please review the adjustment details below') }}</template>
        </tec-section-title>
        <div class="flex">
          <button
            @click="print()"
            class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="printer" class="h-5 w-5" />
          </button>
          <Link
            v-if="$can('update-adjustments')"
            :href="route('adjustments.edit', adjustment.id)"
            class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="edit" class="h-5 w-5" />
          </Link>
        </div>
      </div>

      <adjustment-details v-if="adjustment" :adjustment="adjustment" class="mt-0 pt-0" />
    </div>
  </admin-layout>
</template>

<script>
import AdjustmentDetails from './Details.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  props: { adjustment: Object },
  components: { AdminLayout, AdjustmentDetails, TecSectionTitle },
  methods: {
    print() {
      window.print();
    },
  },
};
</script>
