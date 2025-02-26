<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <div class="mb-4 text-gray-600">
      {{
        $t(
          'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.'
        )
      }}
    </div>

    <div v-if="status" class="mb-4 font-medium text-green-600">
      {{ status }}
    </div>

    <tec-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <tec-label for="email" :value="$t('Email')" />
        <tec-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
      </div>

      <div class="w-full mt-4">
        <loading-button type="submit" class="block w-full" :loading="form.processing" :disabled="form.processing">
          {{ $t('Email Password Reset Link') }}
        </loading-button>
        <Link
          :href="route('login')"
          class="transition-all block w-full mt-4 pr-4 py-2 rounded-md hover:pl-4 hover:bg-gray-100 text-gray-600 hover:text-gray-900"
        >
          {{ $t('Back to Login') }}
        </Link>
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
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: '',
      }),
    };
  },

  created() {
    document.title = this.$t('Forgot Password') + ' • ' + this.$settings.name;
  },
  mounted() {
    document.body.classList.add('spinner-normal');
  },
  unmounted() {
    document.body.classList.remove('spinner-normal');
  },

  methods: {
    submit() {
      this.form.post(this.route('password.email'));
    },
  },
};
</script>
