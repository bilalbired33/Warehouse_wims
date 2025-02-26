<template>
  <template v-if="href">
    <Link
      :href="href"
      class="inline-flex items-center focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
    >
      <slot></slot>
    </Link>
  </template>
  <template v-else>
    <div
      :href="href"
      class="inline-flex items-center focus:outline-none focus:ring focus:ring-gray-300 transition ease-in-out duration-150"
    >
      <slot></slot>
    </div>
  </template>
</template>

<script>
export default {
  props: {
    href: {
      type: String,
      default: '',
    },
  },
};
</script>
