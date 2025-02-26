<template>
  <div class="flex items-center">
    <div class="flex items-stretch w-full bg-white shadow rounded-md">
      <template v-if="dropdown">
        <tec-dropdown align="left" width="64" :close-on-click="close">
          <template #trigger>
            <button
              class="
                flex
                h-10
                px-2
                md:pl-4
                md:pr-0
                items-center
                justify-center
                border-2 border-transparent
                rounded-l-md
                focus:outline-none
                focus:ring focus:ring-gray-300
                relative
                focus:z-30
                transition
                duration-150
                ease-in-out
              "
            >
              <span class="text-gray-700 hidden md:inline">{{ $t('Filter') }}</span>
              <icons name="chevron-down" class="md:mx-2" />
            </button>
          </template>

          <template #content>
            <div class="p-4 w-screen rounded-md max-w-full">
              <slot />
            </div>
          </template>
        </tec-dropdown>
      </template>
      <input
        type="text"
        name="search"
        id="page-search"
        autocomplete="off"
        :value="modelValue"
        :placeholder="$t('Search')"
        :class="{ 'rounded-l-md': !dropdown }"
        @input="$emit('update:modelValue', $event.target.value)"
        class="
          relative
          z-0
          w-full
          px-6
          py-2
          border-0 border-l border-gray-300
          focus:border-transparent
          rounded-r-md
          focus:ring focus:ring-gray-300
        "
      />
    </div>
    <button
      class="
        ml-2
        p-2
        text-sm text-gray-500
        hover:text-gray-700
        focus:text-indigo-500
        rounded-md
        border-2 border-transparent
        focus:outline-none
        focus:ring focus:ring-gray-300
      "
      type="button"
      @click="$emit('reset')"
    >
      {{ $t('Reset') }}
    </button>
  </div>
</template>

<script>
import TecDropdown from '@/Jetstream/Dropdown.vue';
export default {
  components: { TecDropdown },
  emits: ['update:modelValue', 'reset'],
  props: { modelValue: String, close: { type: Boolean, default: false }, dropdown: { type: Boolean, default: true } },
};
</script>
