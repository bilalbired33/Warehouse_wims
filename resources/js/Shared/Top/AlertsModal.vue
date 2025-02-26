<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <div class="relative px-6 py-4">
      <h3 class="text-lg font-bold">
        {{ $t('Application Alerts') }}
      </h3>
      <button
        @click="close()"
        class="fixed top-2 right-2 flex items-center justify-center h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-300 focus:outline-none"
      >
        <icons name="cross" class="h-5 w-5" />
      </button>

      <div class="mt-4">
        <section class="py-2 mx-auto max-w-7xl">
          <loading v-if="loading" />
          <div v-if="!got_alerts && !loading">
            {{ $t('There is no data to display.') }}
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <Link
              :key="warehouse.id"
              v-for="warehouse in alerts.warehouses"
              :href="route('alerts.list', warehouse.id)"
              class="p-4 border rounded-md hover:bg-gray-100"
            >
              <div class="flex items-start justify-between">
                <h2 class="mb-2 text-lg font-semibold leading-none text-gray-900 truncate">
                  {{ $t(warehouse.name) }} ({{ warehouse.code }})
                </h2>
                <span
                  class="flex items-center space-x-1 text-sm font-medium leading-none"
                  :class="(warehouse.alert / warehouse.stock_count) * 100 > 20 ? 'text-red-500' : 'text-yellow-500'"
                >
                  <icons name="down" />
                  <span>{{ $number((warehouse.alert / warehouse.stock_count) * 100) }}%</span>
                </span>
              </div>
              <p class="text-sm leading-none text-gray-600">{{ $t('x items are low in stock.', { x: warehouse.alert }) }}</p>
            </Link>
            <template v-if="alerts.draft">
              <template :key="draft" v-for="draft in Object.keys(alerts.draft)">
                <template v-if="draft != 'warehouses'">
                  <Link :href="route(draft + '.index', { draft: 'yes' })" class="p-4 border rounded-md hover:bg-gray-100">
                    <div class="flex items-start justify-between">
                      <h2 class="mb-2 text-lg font-semibold leading-none text-gray-900 truncate">
                        {{ $t($capitalize(draft)) }}
                      </h2>
                    </div>
                    <p class="text-sm leading-none text-gray-600">{{ $t('x records are still draft.', { x: alerts.draft[draft] }) }}</p>
                  </Link>
                </template>
              </template>
            </template>
          </div>
        </section>
      </div>
    </div>
  </modal>
</template>

<script>
import Modal from '@/Jetstream/Modal.vue';
import Loading from '@/Shared/Loading.vue';

export default {
  emits: ['close'],

  components: { Loading, Modal },

  props: {
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
    return {
      alerts: [],
      loading: true,
    };
  },

  watch: {
    show: {
      immediate: true,
      handler(show) {
        if (show) {
          this.loading = true;
          axios
            .get('/alerts')
            .then(res => (this.alerts = res.data))
            .finally(() => (this.loading = false));
        }
      },
    },
  },

  computed: {
    got_alerts() {
      return this.alerts.warehouses ? this.alerts.warehouses.filter(w => w.alerts > 0) : this.alerts.draft ? true : false;
    },
  },

  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>
