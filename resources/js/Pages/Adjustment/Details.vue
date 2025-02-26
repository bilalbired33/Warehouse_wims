<template>
  <div
    v-if="adjustment"
    class="mt-auto bg-gray-100 -m-6 py-6 px-4 md:px-6 rounded-md print:bg-white print:mt-0 print:pt-0 print:h-screen print:overflow-visible"
  >
    <div class="bg-white p-4 rounded-md shadow overflow-x-auto print:shadow-none print:pt-0">
      <div class="block sm:flex justify-between print:flex">
        <tec-application-logo class="mb-4 sm:mb-0" />
        <div class="text-left sm:text-right leading-snug max-w-md print:text-right">
          <div class="font-bold">{{ adjustment.warehouse.name }} ({{ adjustment.warehouse.code }})</div>
          <div v-if="adjustment.warehouse.address">{{ adjustment.warehouse.address }}</div>
          <div v-if="adjustment.warehouse.phone">{{ adjustment.warehouse.phone }}</div>
          <div v-if="adjustment.warehouse.email">{{ adjustment.warehouse.email }}</div>
        </div>
      </div>
      <div class="border-b my-4 -mx-4"></div>

      <trashed-message v-if="adjustment.draft == 1" class="mb-4 h-12 print:hidden" :action="false" icon="info">
        {{ $t('This record is still a draft.') }}
      </trashed-message>
      <trashed-message v-if="adjustment.deleted_at" class="mb-4 h-12 print:hidden" :action="false">
        {{ $t('This record has been deleted.') }}
      </trashed-message>

      <div class="py-4 w-full">
        <h1 class="text-xl text-center uppercase font-extrabold">{{ $t('Adjustment') }}</h1>
        <svg id="barcode" class="mt-2 mx-auto"></svg>
      </div>

      <div class="block sm:flex justify-between print:flex">
        <div class="w-full sm:w-1/2 leading-snug mb-6 sm:mb-0">
          <div>{{ $t('Type') }}: {{ adjustment.type }}</div>
          <div>{{ $t('Date') }}: {{ $date(adjustment.date) }}</div>
          <div>{{ $t('Reference') }}: {{ adjustment.reference }}</div>
          <div>{{ $t('Created at') }}: {{ $datetime(adjustment.created_at) }}</div>
        </div>
      </div>

      <div class="-mx-4 overflow-x-auto">
        <table class="w-full mt-8 mb-4" style="min-width: 500px">
          <thead>
            <tr>
              <th class="px-6 py-2 text-left">{{ $t('Item') }}</th>
              <th class="px-6 py-2 text-right" :class="$settings.track_weight ? 'w-32' : 'w-px'">
                <span v-if="$settings.track_weight">{{ $t('Weight') }}</span>
              </th>
              <th class="px-6 py-2 w-32 text-right">{{ $t('Quantity') }}</th>
            </tr>
          </thead>
          <template v-for="(item, ii) in adjustment.items" :key="'i_' + item.id">
            <tbody
              class="group avoid"
              v-if="item.variations.length"
              :class="{ 'bg-gray-50': ii % 2 != 0, 'border-b': adjustment.items.length == ii + 1 }"
            >
              <tr>
                <td class="group-hover:bg-gray-100 border-t px-6 pt-2 font-bold">{{ $t(item.item.name) }} ({{ item.item.code }})</td>
                <td
                  class="group-hover:bg-gray-100 border-t px-6 py-2 text-right"
                  :class="$settings.track_weight && item.item.track_weight ? 'w-32' : 'w-px'"
                ></td>
                <td class="group-hover:bg-gray-100 border-t px-6 pt-2 w-32 text-right"></td>
              </tr>
              <tr v-for="(variation, vi) in item.variations" :key="'v_' + variation.id">
                <td :class="{ 'pb-2': vi + 1 == item.variations.length }" class="group-hover:bg-gray-100 px-6 pt-2">
                  <span v-html="$meta(variation.meta)"></span>
                </td>
                <td
                  :class="{ 'pb-2': vi + 1 == item.variations.length, 'w-32': $settings.track_weight && item.item.track_weight }"
                  class="group-hover:bg-gray-100 px-6 pt-2 text-right"
                >
                  <span v-if="$settings.track_weight && item.item.track_weight"
                    >{{ $number(variation.pivot.weight) }} {{ $settings.weight_unit }}</span
                  >
                </td>
                <td :class="{ 'pb-2': vi + 1 == item.variations.length }" class="group-hover:bg-gray-100 px-6 pt-2 w-32 text-right">
                  {{ $number(variation.pivot.quantity) }} {{ item.unit ? item.unit.code : '' }}
                </td>
              </tr>
            </tbody>
            <tbody v-else class="group avoid" :class="{ 'bg-gray-50': ii % 2 != 0, 'border-b': adjustment.items.length == ii + 1 }">
              <tr>
                <td class="group-hover:bg-gray-100 border-t px-6 py-2">{{ $t(item.item.name) }} ({{ item.item.code }})</td>
                <td
                  class="group-hover:bg-gray-100 border-t px-6 py-2 text-right"
                  :class="$settings.track_weight && item.item.track_weight ? 'w-32' : 'w-px'"
                >
                  <span v-if="$settings.track_weight && item.item.track_weight"
                    >{{ $number(item.weight) }} {{ $settings.weight_unit }}</span
                  >
                </td>
                <td class="group-hover:bg-gray-100 border-t px-6 py-2 w-32 text-right">
                  {{ $number(item.quantity) }} {{ item.unit ? item.unit.code : '' }}
                </td>
              </tr>
            </tbody>
          </template>
        </table>
      </div>

      <div v-if="adjustment.attachments && adjustment.attachments.length" class="print:hidden py-4 w-full">
        <dt class="font-medium">{{ $t('Attachments') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">
          <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
            <li
              v-for="attachment in adjustment.attachments"
              :key="attachment.id"
              class="pl-3 pr-4 py-3 flex items-center justify-between text-sm"
            >
              <div class="w-0 flex-1 flex items-center">
                <icons name="clip" class="flex-shrink-0 h-5 w-5 text-gray-400" />
                <span class="ml-2 flex-1 w-0 truncate"> {{ attachment.title }} </span>
              </div>
              <div class="ml-4 flex-shrink-0">
                <a class="font-medium text-indigo-600 hover:text-indigo-500" :href="route('media.download', attachment.id)">
                  {{ $t('Download') }}
                </a>
              </div>
            </li>
          </ul>
        </dd>
      </div>

      <div v-if="adjustment.details" class="py-4 w-full">
        {{ $t(adjustment.details) }}
      </div>
    </div>
    <div class="mt-auto pt-4 w-full text-center text-sm text-gray-500 hidden print:block">
      {{ $t('This is computer generated document, no signature required.') }}
    </div>
  </div>
</template>

<script>
import JsBarcode from 'jsbarcode';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

export default {
  props: { adjustment: Object },

  components: { TrashedMessage, TecApplicationLogo },

  data() {
    return {
      loading: false,
    };
  },

  mounted() {
    // JsBarcode('.barcode').init();
    JsBarcode('#barcode', this.adjustment.reference, {
      width: 1,
      margin: 0,
      fontSize: 0,
      height: '20',
      format: 'CODE128',
    });
  },

  updated() {
    JsBarcode('#barcode', this.adjustment.reference, {
      width: 1,
      margin: 0,
      fontSize: 0,
      height: '20',
      format: 'CODE128',
    });
  },

  methods: {},
};
</script>
