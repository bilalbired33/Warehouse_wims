<template>
  <button
    :disabled="loading"
    :class="{ 'pr-12 opacity-75 cursor-wait': loading }"
    class="relative flex items-center justify-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring focus:ring-gray-300 focus:shadow-outline-gray transition-all ease-in-out duration-150"
  >
    <svg
      v-if="loading"
      viewBox="0 0 38 38"
      :class="loadingClass"
      xmlns="http://www.w3.org/2000/svg"
      class="absolute right-4 stroke-current inline h-5 w-5 my-0"
    >
      <g fill="none" fill-rule="evenodd">
        <g transform="translate(1 1)" stroke-width="2">
          <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
          <path d="M36 18c0-9.94-8.06-18-18-18">
            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite" />
          </path>
        </g>
      </g>
    </svg>
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
    loadingClass: String,
  },
};
</script>
