<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <div class="mb-4 text-gray-600">
      <template v-if="!recovery">
        {{ $t('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
      </template>

      <template v-else>{{ $t('Please confirm access to your account by entering one of your emergency recovery codes.') }}</template>
    </div>

    <tec-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div v-if="!recovery">
        <tec-label for="code" :value="$t('Code')" />
        <tec-input
          autofocus
          id="code"
          ref="code"
          type="text"
          inputmode="numeric"
          v-model="form.code"
          class="mt-1 block w-full"
          autocomplete="one-time-code"
        />
      </div>

      <div v-else>
        <tec-label for="recovery_code" :value="$t('Recovery Code')" />
        <tec-input
          type="text"
          id="recovery_code"
          ref="recovery_code"
          class="mt-1 block w-full"
          v-model="form.recovery_code"
          autocomplete="one-time-code"
        />
      </div>

      <div class="flex items-center justify-end mt-4">
        <button
          type="button"
          @click.prevent="toggleRecovery"
          class="text-gray-600 py-3 px-4 rounded-md hover:text-gray-900 hover:bg-gray-200 cursor-pointer transition-all"
        >
          <template v-if="!recovery">{{ $t('Use a recovery code') }} </template>
          <template v-else>{{ $t('Use an authentication code') }} </template>
        </button>

        <loading-button type="submit" class="ml-2" :loading="form.processing" :disabled="form.processing"
          >{{ $t('Login') }}
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
      recovery: false,
      form: this.$inertia.form({
        code: '',
        recovery_code: '',
      }),
    };
  },

  created() {
    document.title = this.$t('OTP') + ' • ' + this.$settings.name;
  },
  mounted() {
    document.body.classList.add('spinner-normal');
  },
  unmounted() {
    document.body.classList.remove('spinner-normal');
  },

  methods: {
    toggleRecovery() {
      this.recovery ^= true;

      this.$nextTick(() => {
        if (this.recovery) {
          this.$refs.recovery_code.focus();
          this.form.code = '';
        } else {
          this.$refs.code.focus();
          this.form.recovery_code = '';
        }
      });
    },

    submit() {
      this.form.post(this.route('two-factor.login'));
    },
  },
};
</script>
