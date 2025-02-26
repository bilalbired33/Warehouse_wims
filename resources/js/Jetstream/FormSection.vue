<template>
  <div>
    <jet-section-title>
      <template #title><slot name="title"></slot></template>
      <template #description><slot name="description"></slot></template>
    </jet-section-title>

    <div class="mt-6">
      <form @submit.prevent="submit" @keyup.enter="submit">
        <div class="px-4 py-5 bg-white md:p-6 shadow" :class="hasActions ? 'md:rounded-tl-md md:rounded-tr-md' : 'md:rounded-md'">
          <div class="grid gap-6">
            <slot name="form"></slot>
          </div>
        </div>

        <div
          v-if="hasActions"
          class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right md:px-6 shadow md:rounded-bl-md md:rounded-br-md"
        >
          <slot name="actions"></slot>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import JetSectionTitle from './SectionTitle.vue';

export default {
  emits: ['submitted', 'handleEnter'],

  components: { JetSectionTitle },

  data() {
    return { stop: false };
  },

  computed: {
    hasActions() {
      return !!this.$slots.actions;
    },
  },

  methods: {
    submit(e) {
      if (e.type == 'keyup' && e.code == 'Enter') {
        this.stop = true;
        this.$emit('handleEnter', e);
        setTimeout(() => (this.stop = false), 250);
      }
      if (e.type == 'submit') {
        setTimeout(() => {
          if (!this.stop) {
            this.$emit('submitted', e);
          }
        }, 100);
      }
    },
  },
};
</script>
