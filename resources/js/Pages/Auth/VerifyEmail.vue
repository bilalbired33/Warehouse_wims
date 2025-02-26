<template>
  <tec-authentication-card>
    <template #logo>
      <tec-authentication-card-logo />
    </template>

    <div class="mb-4 text-gray-600">
      Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If
      you didn't receive the email, we will gladly send you another.
    </div>

    <div class="mb-4 font-medium text-green-600" v-if="verificationLinkSent">
      A new verification link has been sent to the email address you provided during registration.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <loading-button type="submit" class="block w-full" :loading="form.processing" :disabled="form.processing">
          Resend Verification Email
        </loading-button>
        <Link :href="route('logout')" method="post" as="button" class="underline text-gray-600 hover:text-gray-900">Log Out</Link>
      </div>
    </form>
  </tec-authentication-card>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import TecAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

export default {
  components: {
    LoadingButton,
    TecAuthenticationCard,
    TecAuthenticationCardLogo,
  },

  props: {
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form(),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route('verification.send'));
    },
  },

  computed: {
    verificationLinkSent() {
      return this.status === 'verification-link-sent';
    },
  },
};
</script>
