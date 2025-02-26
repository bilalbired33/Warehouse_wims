<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <div class="relative px-6 py-4">
      <h3 class="text-lg font-bold">
        {{ $t('Select Variants') }}
      </h3>
      <button
        @click="close()"
        class="fixed top-2 right-2 flex items-center justify-center h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
      >
        <icons name="cross" class="h-5 w-5" />
      </button>

      <div class="mt-4">
        <section class="py-2 mx-auto max-w-7xl">
          <div v-if="error || nf" class="text-red-600 px-4 py-3 mb-6 rounded-md bg-red-100">{{ error || nf }}</div>
          <text-input class="w-full block mb-6" id="variation-sku" v-model="sku" :placeholder="$t('Scan barcode')" />
          <div class="flex items-start gap-6">
            <template v-for="variant in variants" :key="variant.sku">
              <auto-complete
                class="w-1/2"
                :id="variant.sku"
                :label="$t(variant.name)"
                v-model="variant.selected"
                position="bottom-12 sm:top-12"
                @update:model-value="checkSelected"
                :suggestions="variant.option.filter(o => o).map(o => $t(o))"
              />
            </template>
          </div>
        </section>
      </div>
    </div>
    <!-- <div class="flex justify-end w-full bg-gray-100 px-6 py-3 sm:rounded-b-lg">
      <loading-button @click="add">{{ $t('Add') }}</loading-button>
    </div> -->
  </modal>
</template>

<script>
import Modal from '@/Jetstream/Modal.vue';
import TextInput from '@/Shared/TextInput.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';

export default {
  emits: ['close', 'selected'],

  components: { AutoComplete, LoadingButton, Modal, TextInput },

  props: {
    variants: {
      required: true,
    },
    nf: String,
    show: {
      default: false,
    },
    maxWidth: {
      default: '2xl',
    },
    closeable: {
      default: true,
    },
  },

  data() {
    return { sku: null, error: null };
  },

  watch: {
    sku: {
      immediate: true,
      handler(sku) {
        if (sku && sku.length == 36) {
          this.error = null;
          this.$emit('selected', sku);
        }
      },
    },
    // show: {
    //   immediate: true,
    //   handler() {
    //     this.error = null;
    //   },
    // },
  },

  mounted() {
    this.error = null;
    document.getElementById('variation-sku').focus();
  },

  methods: {
    checkSelected() {
      if (!this.variants.find(v => !v.selected)) {
        this.add();
      }
    },
    add() {
      if (this.variants.find(v => !v.selected)) {
        this.error = this.$t('Please select all the variants');
      } else {
        let meta = {};
        this.variants.map(v => (v.selected ? (meta[v.name] = v.selected) : ''));
        this.error = null;
        this.$emit('selected', meta);
      }
    },
    close() {
      this.$emit('close');
    },
  },
};
</script>
