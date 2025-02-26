<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo class="text-gray-800" />
    </template>

    <tec-validation-errors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <tec-label for="username" :value="$t('Username')" />
        <tec-input id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus />
      </div>

      <div class="mt-4">
        <tec-label for="password" :value="$t('Password')" />
        <tec-input
          required
          id="password"
          type="password"
          v-model="form.password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />
      </div>

      <div class="block mt-4">
        <label class="flex items-center">
          <tec-checkbox name="remember" v-model:checked="form.remember" />
          <span class="ml-2 text-gray-600">{{ $t('Remember me') }}</span>
        </label>
      </div>

      <div class="w-full mt-4">
        <loading-button type="submit" class="block w-full" :loading="form.processing" :disabled="form.processing">
          {{ $t('Login') }}
        </loading-button>

        <!-- <tec-button class="block w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Log in </tec-button> -->
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="transition-all block w-full mt-4 pr-4 py-2 rounded-md hover:pl-4 hover:bg-gray-100 text-gray-600 hover:text-gray-900"
        >
          {{ $t('Forgot your password?') }}
        </Link>
      </div>
    </form>
  </tec-authentication-card>
</template>

<script>
import TecInput from '@/Jetstream/Input.vue';
import TecLabel from '@/Jetstream/Label.vue';
import TecButton from '@/Jetstream/Button.vue';
import TecCheckbox from '@/Jetstream/Checkbox.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TecAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import TecAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

export default {
  components: {
    TecLabel,
    TecInput,
    TecButton,
    TecCheckbox,
    LoadingButton,
    TecValidationErrors,
    TecAuthenticationCard,
    TecAuthenticationCardLogo,
  },

  props: {
    canResetPassword: Boolean,
    status: String,
  },

  created() {
    document.title = this.$t('Login') + ' • ' + this.$settings.name;
  },

  data() {
    return {
      form: this.$inertia.form({
        username: this.$page.props.demo == 1 ? 'admin' : '',
        password: this.$page.props.demo == 1 ? 'amI$tup!D?n0' : '',
        remember: false,
      }),
    };
  },

  mounted() {
    document.body.classList.add('spinner-normal');
  },
  unmounted() {
    document.body.classList.remove('spinner-normal');
  },

  methods: {
    submit() {
      this.form
        .transform(data => ({
          ...data,
          remember: this.form.remember ? 'on' : '',
        }))
        .post(this.route('login'), {
          onFinish: () => this.form.reset('password'),
        });
    },
  },
};
</script>
