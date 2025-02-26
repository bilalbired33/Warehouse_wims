<template>
  <template v-if="href">
    <Link
      :href="href"
      class="inline-flex items-center px-4 py-3 bg-gray-800 shadow border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring focus:ring-gray-300 focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
    >
      <slot></slot>
    </Link>
  </template>
  <template v-else>
    <button
      :type="type"
      class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring focus:ring-gray-300 focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
    >
      <slot></slot>
    </button>
  </template>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'submit',
    },
    href: {
      type: String,
      default: '',
    },
  },
};
</script>
