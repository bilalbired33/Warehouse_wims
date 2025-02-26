<template>
  <admin-layout :title="$t('Transfer Details')">
    <div class="px-4 md:px-0">
      <div class="flex items-start justify-between print:hidden">
        <tec-section-title class="-mx-4 md:mx-0 mb-6">
          <template #title>
            <div class="flex items-center">
              <Link class="text-blue-600 hover:text-blue-700" :href="route('transfers.index')">{{ $t('Transfers') }}</Link>
              <span class="text-blue-600 font-medium mx-2">/</span>
              {{ transfer.reference }}
            </div>
          </template>
          <template #description>{{ $t('Please review the transfer details below') }}</template>
        </tec-section-title>
        <div class="flex">
          <button
            @click="print()"
            class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="printer" class="h-5 w-5" />
          </button>
          <Link
            v-if="$can('update-transfers')"
            :href="route('transfers.edit', transfer.id)"
            class="flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"
          >
            <icons name="edit" class="h-5 w-5" />
          </Link>
        </div>
      </div>

      <transfer-details v-if="transfer" :transfer="transfer" class="mt-0 pt-0" />
    </div>
  </admin-layout>
</template>

<script>
import TransferDetails from './Details.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';

export default {
  props: { transfer: Object },
  components: { AdminLayout, TransferDetails, TecSectionTitle },
  methods: {
    print() {
      window.print();
    },
  },
};
</script>
