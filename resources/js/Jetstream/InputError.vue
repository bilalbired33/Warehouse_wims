<template>
  <div v-show="message" class="max-w-full">
    <p class="text-sm text-red-600 truncate">
      {{ message ? message.replace(' id', '') : '' }}
    </p>
  </div>
</template>

<script>
export default {
  props: ['message'],
};
</script>
