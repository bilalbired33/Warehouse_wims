<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <tec-validation-errors class="mb-4" />

    <form @submit.prevent="submit">
      <div>
        <tec-label for="name" value="Name" />
        <tec-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
      </div>

      <div class="mt-4">
        <tec-label for="email" value="Email" />
        <tec-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
      </div>

      <div class="mt-4">
        <tec-label for="password" value="Password" />
        <tec-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
      </div>

      <div class="mt-4">
        <tec-label for="password_confirmation" value="Confirm Password" />
        <tec-input
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />
      </div>

      <div class="mt-4" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
        <tec-label for="terms">
          <div class="flex items-center">
            <tec-checkbox name="terms" id="terms" v-model:checked="form.terms" />

            <div class="ml-2">
              I agree to the
              <a target="_blank" :href="route('terms.show')" class="underline text-gray-600 hover:text-gray-900">Terms of Service</a>
              and
              <a target="_blank" :href="route('policy.show')" class="underline text-gray-600 hover:text-gray-900">Privacy Policy</a>
            </div>
          </div>
        </tec-label>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link :href="route('login')" class="underline text-gray-600 hover:text-gray-900"> Already registered? </Link>

        <tec-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Register </tec-button>
      </div>
    </form>
  </tec-authentication-card>
</template>

<script>
import TecInput from '@/Jetstream/Input.vue';
import TecLabel from '@/Jetstream/Label.vue';
import TecButton from '@/Jetstream/Button.vue';
import TecCheckbox from '@/Jetstream/Checkbox.vue';
import TecValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TecAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import TecAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

export default {
  components: {
    TecInput,
    TecLabel,
    TecButton,
    TecCheckbox,
    TecValidationErrors,
    TecAuthenticationCard,
    TecAuthenticationCardLogo,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      });
    },
  },
};
</script>
