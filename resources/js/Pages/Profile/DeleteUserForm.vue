<template>
  <tec-action-section>
    <template #title>{{ $t('delete_x', { x: $t('Account') }) }}</template>

    <template #description>{{ $t('Permanently delete your account.') }}</template>

    <template #content>
      <div class="text-gray-600">
        {{
          $t(
            'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'
          )
        }}
      </div>

      <div class="mt-5">
        <tec-danger-button @click="confirmUserDeletion">{{ $t('delete_x', { x: $t('Account') }) }}</tec-danger-button>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <tec-dialog-modal :show="confirmingUserDeletion" @close="closeModal">
        <template #title>{{ $t('delete_x', { x: $t('Account') }) }}</template>

        <template #content>
          {{
            $t(
              'Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.'
            )
          }}

          <div class="mt-4">
            <tec-input
              ref="password"
              type="password"
              class="mt-1 block w-3/4"
              placeholder="Password"
              v-model="form.password"
              @keyup.enter="deleteUser"
            />

            <tec-input-error :message="form.errors.password" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <tec-secondary-button @click="closeModal">{{ $t('Cancel') }}</tec-secondary-button>

          <tec-danger-button class="ml-2" @click="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ $t('delete_x', { x: $t('Account') }) }}
          </tec-danger-button>
        </template>
      </tec-dialog-modal>
    </template>
  </tec-action-section>
</template>

<script>
import TecInput from '@/Jetstream/Input.vue';
import TecInputError from '@/Jetstream/InputError.vue';
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TecDangerButton from '@/Jetstream/DangerButton.vue';
import TecActionSection from '@/Jetstream/ActionSection.vue';
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  components: {
    TecInput,
    TecInputError,
    TecDialogModal,
    TecDangerButton,
    TecActionSection,
    TecSecondaryButton,
  },

  data() {
    return {
      confirmingUserDeletion: false,

      form: this.$inertia.form({
        password: '',
      }),
    };
  },

  methods: {
    confirmUserDeletion() {
      this.confirmingUserDeletion = true;

      setTimeout(() => this.$refs.password.focus(), 250);
    },

    deleteUser() {
      this.form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => this.closeModal(),
        onError: () => this.$refs.password.focus(),
        onFinish: () => this.form.reset(),
      });
    },

    closeModal() {
      this.confirmingUserDeletion = false;

      this.form.reset();
    },
  },
};
</script>
