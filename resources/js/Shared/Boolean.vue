<template>
  <template v-if="value == 1">
    <icons name="tick" class="text-green-600" />
  </template>
  <template v-else>
    <icons name="cross" class="text-red-600" />
  </template>
</template>

<script>
export default {
  props: ['value'],
};
</script>
