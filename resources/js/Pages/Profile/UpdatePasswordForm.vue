<template>
  <tec-form-section @submitted="updatePassword">
    <template #title>{{ $t('Update Password') }}</template>
    <template #description>{{ $t('Ensure your account is using a long, random password to stay secure.') }}</template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <tec-label for="current_password" :value="$t('Current Password')" />
        <tec-input
          type="password"
          id="current_password"
          ref="current_password"
          class="mt-1 block w-full"
          v-model="form.current_password"
          autocomplete="current-password"
        />
        <tec-input-error :message="form.errors.current_password" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <tec-label for="password" :value="$t('New Password')" />
        <tec-input
          id="password"
          ref="password"
          type="password"
          v-model="form.password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />
        <tec-input-error :message="form.errors.password" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <tec-label for="password_confirmation" :value="$t('Confirm Password')" />
        <tec-input
          type="password"
          class="mt-1 block w-full"
          id="password_confirmation"
          autocomplete="new-password"
          v-model="form.password_confirmation"
        />
        <tec-input-error :message="form.errors.password_confirmation" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <tec-action-message :on="form.recentlySuccessful && !$page.props.flash.error" class="mr-3">{{ $t('Saved.') }}</tec-action-message>
      <tec-button :loading="form.processing" :disabled="form.processing">{{ $t('Save') }}</tec-button>
    </template>
  </tec-form-section>
</template>

<script>
import TecInput from '@/Jetstream/Input.vue';
import TecLabel from '@/Jetstream/Label.vue';
import TecButton from '@/Shared/LoadingButton.vue';
import TecInputError from '@/Jetstream/InputError.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';

export default {
  components: {
    TecLabel,
    TecInput,
    TecButton,
    TecInputError,
    TecFormSection,
    TecActionMessage,
  },

  data() {
    return {
      form: this.$inertia.form({
        current_password: '',
        password: '',
        password_confirmation: '',
      }),
    };
  },

  methods: {
    updatePassword() {
      this.form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
        onError: () => {
          if (this.form.errors.password) {
            this.form.reset('password', 'password_confirmation');
            this.$refs.password.focus();
          }

          if (this.form.errors.current_password) {
            this.form.reset('current_password');
            this.$refs.current_password.focus();
          }
        },
      });
    },
  },
};
</script>
