<template>
  <form @submit.prevent="submit" @keyup.enter="submit">
    <div class="mb-6">
      <div class="flex flex-wrap -mr-4 mb-2">
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <text-input type="date" v-model="form.start_date" :label="$t('Start Date')" class="mr-4" />
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <text-input type="date" v-model="form.end_date" :label="$t('End Date')" class="mr-4" />
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <text-input type="datetime-local" v-model="form.start_created_at" :label="$t('Start Created At')" class="mr-4" />
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <text-input type="datetime-local" v-model="form.end_created_at" :label="$t('End Created At')" class="mr-4" />
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <text-input v-model="form.reference" :label="$t('Reference')" class="mr-4" />
        </div>
        <div v-if="type == 'checkin' || type == 'checkout'" class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <!-- <auto-complete json :label="$t('Contact')" v-model="form.contact_id" :suggestions="route('contacts.search')" class="mr-4" /> -->
          <auto-complete clearable :label="$t('Contact')" v-model="form.contact_id" :suggestions="contacts" class="mr-4" />
        </div>
        <div v-if="type != 'transfer'" class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <auto-complete clearable :label="$t('Warehouse')" v-model="form.warehouse_id" :suggestions="warehouses" class="mr-4" />
        </div>
        <template v-if="type == 'transfer'">
          <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
            <auto-complete
              clearable
              class="mr-4"
              :suggestions="warehouses"
              :label="$t('From Warehouse')"
              v-model="form.from_warehouse_id"
            />
          </div>
          <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
            <auto-complete clearable :label="$t('To Warehouse')" v-model="form.to_warehouse_id" :suggestions="warehouses" class="mr-4" />
          </div>
        </template>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <auto-complete clearable :label="$t('User')" v-model="form.user_id" :suggestions="users" class="mr-4" />
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/4 mb-4">
          <auto-complete clearable :label="$t('Category')" v-model="form.category_id" :suggestions="categories" class="mr-4" />
        </div>
      </div>
      <div class="flex items-center justify-between mb-6">
        <button
          type="button"
          @click="reset"
          class="
            px-4
            py-3
            bg-gray-200
            border border-transparent
            rounded-md
            text-sm
            uppercase
            hover:bg-gray-300
            active:bg-gray-300
            focus:outline-none
            focus:ring focus:ring-gray-300
          "
        >
          {{ $t('Reset') }}
        </button>
        <div class="flex items-center justify-end">
          <check-box id="trashed" v-model:checked="form.trashed" class="mr-6" :label="$t('Include trashed records')" />
          <check-box id="draft" v-model:checked="form.draft" class="mr-6" :label="$t('Only draft records')" />
          <loading-button type="submit" :loading="form.processing" :disabled="form.processing">{{ $t('Submit') }}</loading-button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import CheckBox from '@/Shared/CheckBox.vue';
import TextInput from '@/Shared/TextInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';

export default {
  props: ['type', 'warehouses', 'users', 'categories', 'contacts', 'action'],

  components: { CheckBox, TextInput, SelectInput, AutoComplete, LoadingButton },

  data() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
      form: this.$inertia.form({
        start_date: urlParams.get('start_date'),
        end_date: urlParams.get('end_date'),
        start_created_at: urlParams.get('start_created_at'),
        end_created_at: urlParams.get('end_created_at'),
        reference: urlParams.get('reference'),
        contact_id: urlParams.get('contact_id'),
        user_id: urlParams.get('user_id'),
        category_id: urlParams.get('category_id'),
        warehouse_id: urlParams.get('warehouse_id'),
        to_warehouse_id: urlParams.get('to_warehouse_id'),
        from_warehouse_id: urlParams.get('from_warehouse_id'),
        draft: urlParams.get('draft'),
        trashed: urlParams.get('trashed'),
      }),
    };
  },

  methods: {
    submit() {
      if (this.action) {
        this.form
          .transform(data => {
            let d = {};
            Object.keys(data).map(k => {
              if (data[k] !== null) {
                d[k] = data[k];
              }
            });
            return d;
          })
          .get(this.action, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
          });
      }
    },
    reset() {
      this.form = this.$inertia.form({
        start_date: null,
        end_date: null,
        start_created_at: null,
        end_created_at: null,
        reference: null,
        contact_id: null,
        user_id: null,
        category_id: null,
        warehouse_id: null,
        to_warehouse_id: null,
        from_warehouse_id: null,
        draft: null,
        trashed: null,
      });
      this.$inertia.visit(this.action, { replace: true, preserveState: true });
    },
  },
};
</script>
