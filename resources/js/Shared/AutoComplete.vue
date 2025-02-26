<template>
  <div class="col-span-6 sm:col-span-4">
    <tec-label :for="id" :value="label" v-if="label" />
    <div class="relative mt-1 mb-2">
      <label :for="id" name="chevron-down" class="cursor-pointer absolute top-0 right-0 border border-transparent p-2 text-gray-500">
        <icons name="chevron-down" class-name="w-5 h-6" />
      </label>
      <button
        type="button"
        @click="reset()"
        v-if="clearable && modelValue"
        class="cursor-pointer focus:outline-none absolute top-0 right-8 border border-transparent p-2 text-gray-500 hover:text-red-500"
      >
        <icons name="cross" class-name="w-4 h-6" />
      </button>
      <input
        :id="id"
        ref="input"
        @blur="hide"
        @focus="show"
        @click="show"
        @keyup.up="up"
        v-model="search"
        autocomplete="off"
        @keyup.down="down"
        @keyup.enter="enter"
        :readonly="!searchable"
        :placeholder="placeholder || label"
        :class="{ 'border-red-500': error, 'pr-8': !clearable, 'pr-16': clearable }"
        class="
          w-full
          block
          text-base
          py-2
          pl-4
          placeholder-gray-400
          border border-gray-300
          focus:outline-none
          focus:border-indigo-300
          focus:ring focus:ring-indigo-200 focus:ring-opacity-50
          rounded-md
          shadow-sm
        "
      />
      <div v-if="open" :class="position" class="absolute mt-2 w-full rounded-md z-20">
        <ul class="max-h-56 rounded-md bg-white shadow-lg text-base overflow-auto focus:outline-none sm:text-sm">
          <template v-if="result && result.length">
            <template :key="ri" v-for="(r, ri) in result">
              <!-- v-if="disable && !disabledOptions.includes(cValue(r))" -->
              <template v-if="!(disable && disable == cValue())">
                <li>
                  <button
                    type="button"
                    @click="suggestionClick(ri)"
                    :class="isActive(ri) ? 'bg-blue-600 text-white' : 'bg-white text-gray-900'"
                    class="w-full text-left flex items-center select-none relative py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white"
                  >
                    <span class="ml-3 block font-normal truncate">
                      {{ cLabel(r) }}
                    </span>
                  </button>
                </li>
              </template>
            </template>
          </template>
          <li v-else class="bg-blue-600 text-white cursor-default select-none relative py-2 pl-3 pr-9">
            <div class="flex items-center">
              <span class="ml-3 block font-normal truncate">
                <span v-if="loading">{{ $t('Searching for results') }}...</span>
                <span v-else-if="result == null">{{ $t('Scan barcode or search items for next') }}</span>
                <span v-else-if="json">{{ defaultText || $t('Please type to search') }}</span>
                <span v-else>{{ $t('No result suggestions to list.') }}</span>
              </span>
            </div>
          </li>
        </ul>
      </div>
      <tec-input-error v-if="error" :message="error" class="absolute mt-0" />
    </div>
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';
import throttle from 'lodash/throttle';
import TecLabel from '@/Jetstream/Label.vue';
import TecInputError from '@/Jetstream/InputError.vue';

export default {
  emits: ['update:modelValue'],

  components: { TecLabel, TecInputError },

  data() {
    return {
      search: '',
      result: [],
      open: false,
      current: null,
      loading: false,
      selected: null,
    };
  },
  props: {
    id: {
      type: String,
      default() {
        return uuidv4();
      },
    },
    json: Boolean,
    label: String,
    error: String,
    position: String,
    valueKey: String,
    labelKey: String,
    defaultText: String,
    placeholder: String,
    disable: [String, Number],
    modelValue: [String, Number],
    suggestions: [String, Array],
    clearable: { type: Boolean, default: false },
    searchable: { type: Boolean, default: true },
    keepFocus: { type: Boolean, default: false },
    resetSearch: { type: Boolean, default: false },
  },

  watch: {
    modelValue: function (v) {
      if (!v) {
        this.search = '';
        this.$nextTick(() => (this.open = false));
      }
    },
    search: function (search) {
      if (this.searchable) {
        this.open = true;
        if (Array.isArray(this.suggestions)) {
          this.result = search ? this.suggestions.filter(r => this.cLabel(r, search)) : this.suggestions;
        } else {
          this.loading = true;
          this.getSuggestions(search);
        }
      }
    },
  },

  computed: {
    currentLabel() {
      return this.cLabel(this.selected);
    },
    currentValue() {
      return this.cValue(this.selected);
    },
    // disabledOptions() {
    //   if (this.disable) {
    //     if (Array.isArray(this.disable)) {
    //       return this.disable;
    //     }
    //     return [this.disable];
    //   }
    //   return undefined;
    // },
  },

  created() {
    if (Array.isArray(this.suggestions)) {
      this.result = this.suggestions;
    }
  },

  mounted() {
    if (this.modelValue) {
      if (Array.isArray(this.suggestions)) {
        this.selected = this.suggestions.find(
          s => s[this.valueKey] == this.modelValue || s.value == this.modelValue || s.id == this.modelValue
        );
        this.search = this.currentLabel;
        // this.search = this.searchable ? this.currentLabel : '';
        this.$nextTick(() => (this.open = false));
      } else {
        axios
          .post(this.suggestions, { id: this.modelValue })
          .then(res => {
            this.result = res.data;
            this.selected = res.data[0];
            this.search = this.cLabel(this.selected);
          })
          .finally(() => {
            this.open = false;
            this.loading = false;
          });
      }
    }
  },

  methods: {
    getSuggestions: throttle(function (search) {
      if ((search && !this.result) || (this.result && (!this.result.length || this.modelValue != this.result[0].id))) {
        axios
          .post(this.suggestions, { search })
          .then(res => {
            this.result = res.data;
            if (this.result.length == 1) {
              this.suggestionClick(0, true);
            }
          })
          .finally(() => (this.loading = false));
      }
    }, 250),
    cLabel(r, search) {
      if (r === null) {
        return null;
      } else if (typeof r !== 'object') {
        return r;
      }
      let key = '';
      if (this.labelKey) {
        key = this.labelKey;
      } else if (r.label !== undefined) {
        key = 'label';
      } else if (r.name !== undefined) {
        key = 'name';
      }
      if (search) {
        return key ? r[key].toLowerCase().includes(search.toLowerCase()) : r.toLowerCase().includes(search.toLowerCase());
      }
      return key ? r[key] : r;
    },
    cValue(r) {
      if (r === null) {
        return null;
      } else if (typeof r !== 'object') {
        return r;
      }
      let key = '';
      if (this.valueKey) {
        key = this.valueKey;
      } else if (r.value !== undefined) {
        key = 'value';
      } else if (r.id !== undefined) {
        key = 'id';
      }
      return key ? r[key] : r;
    },
    show() {
      this.open = true;
      this.search = '';
      this.$refs.input.select();
      if (!this.selected) {
        this.current = 0;
      }
    },
    hide() {
      setTimeout(() => {
        if (this.selected) {
          this.search = this.currentLabel;
        }
        setTimeout(() => (this.open = false), 100);
      }, 200);
    },
    enter() {
      this.suggestionClick(this.current);
      this.hide();
      return false;
    },
    up() {
      if (this.current > 0) this.current--;
    },
    down() {
      if (this.current < this.result.length - 1) this.current++;
    },
    isActive(index) {
      return index == this.current;
    },
    suggestionClick(index, reset = false) {
      if (index !== undefined) {
        this.current = index;
        this.selected = this.result[index];
        this.$emit('update:modelValue', this.json ? this.selected : this.currentValue);
        this.search = this.resetSearch ? '' : this.currentLabel;
        this.open = false;
      }
      if (reset) {
        this.result = null;
      }
      if (this.resetSearch) {
        this.selected = null;
      }
      if (this.keepFocus) {
        this.$refs.input.focus();
      }
    },
    reset() {
      this.search = '';
      this.$emit('update:modelValue', '');
      this.$nextTick(() => (this.open = false));
    },
  },
};
</script>
