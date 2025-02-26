<template>
  <div class="col-span-6 sm:col-span-4">
    <tec-label :for="id" :value="label" />
    <textarea
      :id="id"
      ref="input"
      v-bind="$attrs"
      :value="modelValue"
      @input="autoResizeAndEmit"
      :class="{ 'border-red-500': error }"
      class="
        w-full
        mt-1
        border-gray-300
        focus:border-indigo-300
        focus:ring focus:ring-indigo-200 focus:ring-opacity-50
        rounded-md
        shadow-sm
      "
    />
    <tec-input-error v-if="error" :message="error" class="mt-0" />
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';
import TecLabel from '@/Jetstream/Label.vue';
import TecInputError from '@/Jetstream/InputError.vue';

export default {
  emits: ['update:modelValue'],

  components: { TecLabel, TecInputError },

  props: {
    id: {
      type: String,
      default() {
        return uuidv4();
      },
    },
    modelValue: String,
    label: String,
    error: String,
  },

  mounted() {
    this.$nextTick(() => {
      this.$refs.input.setAttribute('style', `height: ${this.$refs.input.scrollHeight + 2}px`);
    });
  },

  methods: {
    focus() {
      this.$refs.input.focus();
    },
    select() {
      this.$refs.input.select();
    },
    autoResizeAndEmit(event) {
      event.target.style.height = 'auto';
      event.target.style.height = `${event.target.scrollHeight + 2}px`;
      this.$emit('update:modelValue', event.target.value);
    },
  },
};
</script>
