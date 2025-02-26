<template>
  <div class="flex">
    <label :for="id" class="flex items-center">
      <input
        :id="id"
        :value="value"
        type="checkbox"
        v-model="proxyChecked"
        :class="{ 'border-red-500': error }"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      />
      <span v-if="label" v-html="label" class="ml-2 text-gray-600"></span>
    </label>
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';
import TecCheckbox from '@/Jetstream/Checkbox.vue';
import TecInputError from '@/Jetstream/InputError.vue';

export default {
  emits: ['update:checked'],
  components: { TecCheckbox, TecInputError },
  props: {
    id: {
      type: String,
      default() {
        return uuidv4();
      },
    },
    checked: {
      type: [Array, Boolean],
      default: false,
    },
    modelValue: {
      default: null,
    },
    value: String,
    label: String,
    error: String,
  },
  computed: {
    proxyChecked: {
      get() {
        return this.checked;
      },
      set(val) {
        this.$emit('update:checked', val);
      },
    },
  },
};
</script>
