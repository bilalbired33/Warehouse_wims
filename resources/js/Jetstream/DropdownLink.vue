<template>
  <div>
    <button
      type="submit"
      v-if="as == 'button'"
      class="block w-full px-4 py-2 leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
      <slot></slot>
    </button>

    <Link
      v-else
      :href="href"
      class="block px-4 py-2 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
      <slot></slot>
    </Link>
  </div>
</template>

<script>
export default {
  props: ['href', 'as'],
};
</script>
