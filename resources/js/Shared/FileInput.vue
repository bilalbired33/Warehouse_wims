<template>
  <div>
    <tec-label v-if="label" :for="id" :value="label" />
    <div class="mt-1 relative rounded-md">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <icons name="link" class="text-gray-500" />
      </div>
      <tec-input
        type="text"
        @click="browse"
        id="file-input"
        readonly="true"
        :value="selected"
        :placeholder="$t('Select File')"
        class="block w-full pl-10 pr-12"
      />
      <div class="absolute inset-y-0 right-0 flex items-center">
        <button
          type="button"
          @click="remove"
          v-if="modelValue"
          class="px-4 py-2 bg-red-500 hover:bg-red-700 focus:outline-none rounded-r-md font-medium text-white flex items-center"
        >
          <icons name="cross" class="w-6 h-6 sm:mr-2" />&nbsp;
          <span class="hidden sm:inline-block">{{ $t('Remove') }}</span>
        </button>
        <button
          v-else
          type="button"
          @click="browse"
          class="px-4 py-2 bg-gray-600 hover:bg-gray-800 focus:outline-none rounded-r-md font-medium text-white flex items-center"
        >
          <icons name="folder" class="w-6 h-6 sm:mr-2" />&nbsp;
          <span class="hidden sm:inline-block">{{ $t('Browse') }}</span>
        </button>
      </div>
      <input :id="id" ref="file" type="file" :accept="accept" class="hidden" @change="change" />
    </div>

    <!-- <div class="form-input p-0" :class="{ error: errors.length }">
      <div v-if="!value" class="p-2">
        <button type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="browse">
          Browse
        </button>
      </div>
      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1">
          {{ value.name }} <span class="text-gray-500 text-xs">({{ filesize(value.size) }})</span>
        </div>
        <button type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="remove">
          Remove
        </button>
      </div>
    </div> -->
    <tec-input-error v-if="error" :message="error" class="absolute mt-0" />
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';
import TecLabel from '@/Jetstream/Label.vue';
import TecInput from '@/Jetstream/Input.vue';
import TecInputError from '@/Jetstream/InputError.vue';
export default {
  emits: ['update:modelValue'],

  components: { TecLabel, TecInput, TecInputError },

  props: {
    label: String,
    error: String,
    accept: String,
    modelValue: File,
    id: {
      type: String,
      default() {
        return uuidv4();
      },
    },
  },

  watch: {
    modelValue(value) {
      if (!value) {
        this.$refs.file.value = '';
      }
    },
  },

  computed: {
    selected() {
      return this.modelValue ? this.modelValue.name + ' (' + this.filesize(this.modelValue.size) + ')' : '';
    },
  },

  methods: {
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024));
      return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    },
    browse() {
      this.$refs.file.click();
    },
    change(e) {
      this.$emit('update:modelValue', e.target.files[0]);
    },
    remove() {
      this.$emit('update:modelValue', null);
    },
  },
};
</script>
