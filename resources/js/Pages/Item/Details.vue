<template>
  <div v-if="item" class="bg-gray-100 -mx-6 px-4 md:px-6 md:rounded-md print:h-full print:block print:m-0 print:pt-0">
    <div class="bg-white -mx-4 md:mx-0 md:rounded-md shadow overflow-x-auto print:m-0 print:block">
      <table class="w-full my-4">
        <tbody>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">
              <button type="button" @click="() => (show = true)">
                <img v-if="item.photo" class="block w-24 h-24 rounded-sm mr-2 -my-2" :src="item.photo" />
              </button>
            </td>
            <td class="px-6 py-2">
              <svg
                class="barcode"
                jsbarcode-width="2"
                jsbarcode-height="70"
                jsbarcode-fontSize="12"
                :jsbarcode-value="item.code"
                :jsbarcode-format="item.symbology"
              ></svg>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Name') }}</td>
            <td class="px-6 py-2">
              <span class="font-bold">{{ item.name }}</span>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('SKU') }}</td>
            <td class="px-6 py-2">
              {{ item.sku }}
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Category') }}</td>
            <td class="px-6 py-2">
              {{ item.categories[0].name }}
            </td>
          </tr>
          <tr v-if="item.categories.length > 2">
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Child Category') }}</td>
            <td class="px-6 py-2">
              {{ item.categories[1].name }}
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Rack') }}</td>
            <td class="px-6 py-2">
              <div v-if="item.rack_location">{{ item.rack_location }}</div>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Unit') }}</td>
            <td class="px-6 py-2">
              <div v-if="item.unit">{{ item.unit.name }}</div>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Track Serials') }}</td>
            <td class="px-6 py-2">
              <icons v-if="item.track_serials" name="tick" class="text-green-600" />
              <icons v-else name="cross" class="text-red-600" />
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Track Weight') }}</td>
            <td class="px-6 py-2">
              <icons v-if="item.track_weight" name="tick" class="text-green-600" />
              <icons v-else name="cross" class="text-red-600" />
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Track Quantity') }}</td>
            <td class="px-6 py-2">
              <icons v-if="item.track_quantity" name="tick" class="text-green-600" />
              <icons v-else name="cross" class="text-red-600" />
            </td>
          </tr>
          <!-- <tr>
          <td class="px-6 py-2 whitespace-nowrap">{{ $t('Track Variants e.g. Size and/or Color') }}</td>
          <td class="px-6 py-2">
            <icons v-if="item.has_variants" name="tick" class="text-green-600" />
            <icons v-else name="cross" class="text-red-600" />
          </td>
        </tr> -->
          <tr v-if="item.has_variants">
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Variants') }}</td>
            <td class="px-6 py-2">
              <div v-for="v in item.variants" :key="v.name">
                <strong>{{ v.name }}:</strong>
                {{
                  v.option
                    .filter(o => o)
                    // .map(o => $t(o))
                    .join(', ')
                }}
              </div>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-2 whitespace-nowrap">{{ $t('Details') }}</td>
            <td class="px-6 py-2">
              <span v-if="item.details">{{ item.details }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="-mx-4 md:mx-0 print:m-0 print:block">
      <div class="mt-6 avoid print:mt-0">
        <div class="grid gap-6" :class="modal ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1 lg:grid-cols-2 xl:grid-cols-3'">
          <div :key="'w_' + w.id" v-for="w in item.stock" class="w-full print:m-3 print:border print:rounded-md print:w-5/12">
            <div class="bg-white pt-3 pb-2 md:rounded-md shadow overflow-x-auto print:mb-2">
              <div class="flex items-center justify-between px-4 -my-3 py-3">
                <h4 class="text-lg font-bold">{{ w[0].warehouse.name }} ({{ w[0].warehouse.code }})</h4>
                <p>{{ w[0].rack_location || '' }}</p>
              </div>
              <table class="w-full mt-3">
                <tbody>
                  <tr :key="stock.id" v-for="stock in w" :class="{ 'font-bold': !stock.variation }">
                    <td class="border-t pl-4 pr-2 py-2">
                      <span v-if="stock.variation" v-html="$meta(stock.variation.meta)" />
                      <span v-else>{{ $t('Quantity') }}</span>
                    </td>
                    <td class="border-t pr-4 pl-2 py-2 text-right">
                      {{ $number(stock.quantity) }} {{ item.unit ? item.unit.code : '' }}
                      <span v-if="item.track_weight">({{ $number(stock.weight) }} {{ $settings.weight_unit }})</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <template v-if="item.has_variants && variations.length">
      <div class="mt-6 -mx-4 md:mx-0 flex flex-col gap-6 avoid print:mb-2 print:block">
        <loading v-if="loading" />
        <div v-for="variation in variations" :key="variation.sku" class="bg-white p-4 md:rounded-md shadow overflow-x-auto">
          <div class="flex items-center">
            <span class="text-gray-500 mr-2">{{ $t('Variation') }}:</span>
            <h4 class="text-lg font-bold" v-html="$meta(variation.meta)"></h4>
          </div>
          <div>
            <span class="text-gray-500 mr-2">{{ $t('SKU') }}:</span>
            {{ variation.sku }}
          </div>
          <div class="-mx-4 pb-4 border-b print:hidden"></div>
          <div class="flex flex-wrap mt-4 -mb-4 -mr-4 print:m-0">
            <div
              :key="stock.id"
              v-for="stock in variation.stock"
              class="w-full lg:w-1/2 xl:w-1/3 avoid print:m-2 print:rounded-md print:w-5/12"
            >
              <div class="px-4 mb-4 mr-4 border py-2 md:rounded-md print:mb-2 print:rounded">
                <h4 class="text-lg font-bold">{{ stock.warehouse.name }} ({{ stock.warehouse.code }})</h4>
                <table class="w-full">
                  <tbody>
                    <tr v-if="item.track_quantity">
                      <td class="pr-3 py-2">{{ $t('Quantity') }}</td>
                      <td class="pl-3 py-2">{{ $number(stock.quantity) }} {{ item.unit ? item.unit.code : '' }}</td>
                    </tr>
                    <tr v-if="item.track_weight">
                      <td class="pr-3 py-2">{{ $t('Weight') }}</td>
                      <td class="pl-3 py-2">{{ $number(stock.weight) }} {{ $settings.weight_unit }}</td>
                    </tr>
                    <tr v-if="stock.rack_location">
                      <td class="pr-3 py-2">{{ $t('Rack Location') }}</td>
                      <td class="pl-3 py-2">{{ stock.rack_location }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <modal :show="show" max-width="2xl" :closeable="true" :transparent="true" @close="() => (show = false)">
      <div class="print:block print:h-full">
        <img class="block max-w-full mx-auto rounded-md" :src="item.photo" />
      </div>
    </modal>
  </div>
</template>

<script>
import JsBarcode from 'jsbarcode';
import Loading from '@/Shared/Loading.vue';
import Modal from '@/Jetstream/Modal.vue';
export default {
  components: { Loading, Modal },

  props: { item: Object, modal: { default: false } },

  data() {
    return {
      show: false,
      loading: false,
      variations: [],
    };
  },

  mounted() {
    JsBarcode('.barcode').init();
    if (!this.item.stock) {
      this.item.stock = this.item.all_stock;
    }
    // if (this.item.has_variants == 1) {
    //   this.loading = true;
    //   axios
    //     .get(route('items.show', this.item.id) + '?json=yes&variation_stock=yes')
    //     .then(res => (this.variations = res.data.variations))
    //     .finally(() => (this.loading = false));
    // }
  },

  updated() {
    JsBarcode('.barcode').init();
  },
};
</script>
