<template>
  <div
    v-if="checkin"
    class="mt-auto bg-gray-100 -m-6 py-6 px-4 md:px-6 rounded-md print:bg-white print:mt-0 print:pt-0 print:h-screen print:overflow-visible"
  >
    <div class="bg-white p-4 rounded-md shadow overflow-x-auto print:shadow-none print:pt-0">
      <div class="block sm:flex justify-between print:flex">
        <img v-if="checkin.warehouse.logo" :src="checkin.warehouse.logo" class="mb-4 sm:mb-0" style="max-height: 90px" />
        <tec-application-logo v-else class="mb-4 sm:mb-0" />
        <div class="text-left sm:text-right leading-snug max-w-md print:text-right">
          <div class="font-bold">{{ checkin.warehouse.name }} ({{ checkin.warehouse.code }})</div>
          <div v-if="checkin.warehouse.address">{{ checkin.warehouse.address }}</div>
          <div v-if="checkin.warehouse.phone">{{ checkin.warehouse.phone }}</div>
          <div v-if="checkin.warehouse.email">{{ checkin.warehouse.email }}</div>
        </div>
      </div>
      <div class="border-b my-4 -mx-4"></div>

      <trashed-message v-if="checkin.draft == 1" class="mb-4 h-12 print:hidden" :action="false" icon="info">
        {{ $t('This record is still a draft.') }}
      </trashed-message>
      <trashed-message v-if="checkin.deleted_at" class="mb-4 h-12 print:hidden" :action="false">
        {{ $t('This record has been deleted.') }}
      </trashed-message>

      <div class="py-4 w-full">
        <h1 class="text-xl text-center uppercase font-extrabold">{{ $t('Checkin') }}</h1>
        <svg id="barcode" class="mt-2 mx-auto"></svg>
      </div>

      <div class="block sm:flex justify-between print:flex">
        <div class="w-full sm:w-1/2 leading-snug mb-6 sm:mb-0">
          <div class="text-sm font-bold">&nbsp;</div>
          <div>{{ $t('Date') }}: {{ $date(checkin.date) }}</div>
          <div>{{ $t('Reference') }}: {{ checkin.reference }}</div>
          <div>{{ $t('Created at') }}: {{ $datetime(checkin.created_at) }}</div>
        </div>
        <div class="text-left w-full sm:w-1/2 leading-snug">
          <div class="text-sm font-bold">{{ $t('For') }}:</div>
          <div>{{ checkin.contact.name }}</div>
          <div v-if="checkin.contact.phone">{{ checkin.contact.phone }}</div>
          <div v-if="checkin.contact.email">{{ checkin.contact.email }}</div>
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
          <template v-for="(item, ii) in checkin.items" :key="'i_' + item.id">
            <tbody
              class="group avoid"
              v-if="item.variations.length"
              :class="{ 'bg-gray-50': ii % 2 != 0, 'border-b': checkin.items.length == ii + 1 }"
            >
              <tr>
                <td class="group-hover:bg-gray-100 border-t px-6 pt-2 font-bold">
                  <div class="flex items-center gap-x-2 gap-y-1">
                    <img
                      v-if="item.item.photo"
                      class="block w-8 h-8 rounded-sm -my-2"
                      :src="'/assets/' + item.item.photo"
                      @click="showPhoto(item.item.photo)"
                    />
                    {{ $t(item.item.name) }} ({{ item.item.code }})
                  </div>
                </td>
                <td
                  class="group-hover:bg-gray-100 border-t px-6 py-2 text-right"
                  :class="$settings.track_weight && item.item.track_weight ? 'w-32' : 'w-px'"
                ></td>
                <td class="group-hover:bg-gray-100 border-t px-6 pt-2 w-32 text-right"></td>
              </tr>
              <tr v-for="(variation, vi) in item.variations" :key="'v_' + variation.id">
                <td :class="{ 'pb-2': vi + 1 == item.variations.length }" class="group-hover:bg-gray-100 px-6 pt-2">
                  <div class="flex items-center gap-x-2 gap-y-1">
                    <span v-if="item.item.photo" class="block w-8 h-8 rounded-sm -my-2" />
                    <span v-html="$meta(variation.meta)"></span>
                  </div>
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
            <tbody v-else class="group avoid" :class="{ 'bg-gray-50': ii % 2 != 0, 'border-b': checkin.items.length == ii + 1 }">
              <tr>
                <td class="group-hover:bg-gray-100 border-t px-6 py-2">
                  <div class="flex items-center gap-x-2 gap-y-1">
                    <img
                      v-if="item.item.photo"
                      class="block w-8 h-8 rounded-sm -my-2"
                      :src="'/assets/' + item.item.photo"
                      @click="showPhoto(item.item.photo)"
                    />
                    {{ $t(item.item.name) }} ({{ item.item.code }})
                  </div>
                </td>
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

      <div v-if="checkin.attachments && checkin.attachments.length" class="print:hidden py-4 w-full">
        <dt class="font-medium">{{ $t('Attachments') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">
          <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
            <li
              v-for="attachment in checkin.attachments"
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

      <div v-if="checkin.details" class="py-4 w-full">
        {{ $t(checkin.details) }}
      </div>
    </div>
    <div class="mt-auto pt-4 w-full text-center text-sm text-gray-500 hidden print:block">
      {{ $t('This is computer generated document, no signature required.') }}
    </div>

    <modal :show="show" max-width="2xl" :closeable="true" :transparent="true" @close="hidePhoto()">
      <div class="print:block print:h-full">
        <img class="block max-w-full mx-auto rounded-md" :src="photo" />
      </div>
    </modal>
  </div>
</template>

<script>
import JsBarcode from 'jsbarcode';
import Modal from '@/Jetstream/Modal.vue';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

export default {
  props: { checkin: Object },

  components: { Modal, TrashedMessage, TecApplicationLogo },

  data() {
    return {
      photo: null,
      show: false,
      loading: false,
    };
  },

  mounted() {
    // JsBarcode('.barcode').init();
    JsBarcode('#barcode', this.checkin.reference, {
      width: 1,
      margin: 0,
      fontSize: 0,
      height: '20',
      format: 'CODE128',
    });
  },

  updated() {
    JsBarcode('#barcode', this.checkin.reference, {
      width: 1,
      margin: 0,
      fontSize: 0,
      height: '20',
      format: 'CODE128',
    });
  },

  methods: {
    showPhoto(photo) {
      this.show = true;
      this.photo = '/assets/' + photo;
    },
    hidePhoto() {
      this.show = false;
      this.photo = null;
    },
  },
};
</script>
