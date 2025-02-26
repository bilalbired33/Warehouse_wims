<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <div class="mb-4 text-gray-600">
      {{ $t('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <tec-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <tec-label for="password" :value="$t('Password')" />
        <tec-input
          required
          autofocus
          id="password"
          type="password"
          v-model="form.password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />
      </div>

      <div class="flex justify-end mt-4">
        <loading-button type="submit" class="ml-4" :loading="form.processing" :disabled="form.processing">
          {{ $t('Confirm') }}
        </loading-button>
      </div>
    </form>
  </tec-authentication-card>
</template>

<script>
import TecInput from '@/Jetstream/Input.vue';
import TecLabel from '@/Jetstream/Label.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TecAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import TecAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

export default {
  components: {
    TecInput,
    TecLabel,
    LoadingButton,
    TecValidationErrors,
    TecAuthenticationCard,
    TecAuthenticationCardLogo,
  },

  data() {
    return {
      form: this.$inertia.form({
        password: '',
      }),
    };
  },

  created() {
    document.title = this.$t('Confirm Password') + ' • ' + this.$settings.name;
  },

  methods: {
    submit() {
      this.form.post(this.route('password.confirm'), {
        onFinish: () => this.form.reset(),
      });
    },
  },
};
</script>
