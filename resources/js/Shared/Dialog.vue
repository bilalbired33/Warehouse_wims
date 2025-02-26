<template>
  <tec-dialog-modal :show="show" @close="close()" :max-width="maxWidth || 'sm'">
    <template #title> {{ title }} </template>
    <template #content> <span v-html="content" /> </template>
    <template #footer>
      <div class="flex items-center justify-between">
        <tec-secondary-button @click="close()"> Cancel </tec-secondary-button>
        <loading-button
          class="ml-2"
          @click="doAction"
          :loading="loading"
          :disabled="loading"
          :class="{ 'bg-red-500 hover:bg-red-600': actionType == 'delete' }"
        >
          {{ actionText }}
        </loading-button>
      </div>
    </template>
  </tec-dialog-modal>
</template>
<script>
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TecDangerButton from '@/Jetstream/DangerButton.vue';
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  props: ['show', 'title', 'content', 'close', 'action', 'actionType', 'actionText', 'maxWidth'],
  components: {
    LoadingButton,
    TecDialogModal,
    TecDangerButton,
    TecSecondaryButton,
  },
  data() {
    return { loading: false };
  },
  methods: {
    doAction() {
      this.loading = true;
      setTimeout(() => {
        this.loading = false;
        this.close();
      }, 250);
      this.action();
    },
  },
};
</script>
