<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <tec-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <tec-label for="email" :value="$t('Email')" />
        <tec-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
      </div>

      <div class="mt-4">
        <tec-label for="password" :value="$t('Password')" />
        <tec-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
      </div>

      <div class="mt-4">
        <tec-label for="password_confirmation" :value="$t('Confirm Password')" />
        <tec-input
          required
          type="password"
          class="mt-1 block w-full"
          id="password_confirmation"
          autocomplete="new-password"
          v-model="form.password_confirmation"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <loading-button type="submit" class="block w-full" :loading="form.processing" :disabled="form.processing">
          {{ $t('Reset Password') }}
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

  props: {
    email: String,
    token: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: '',
        password_confirmation: '',
      }),
    };
  },

  created() {
    document.title = this.$t('Reset Password') + ' • ' + this.$settings.name;
  },
  mounted() {
    document.body.classList.add('spinner-normal');
  },
  unmounted() {
    document.body.classList.remove('spinner-normal');
  },

  methods: {
    submit() {
      this.form.post(this.route('password.update'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      });
    },
  },
};
</script>
