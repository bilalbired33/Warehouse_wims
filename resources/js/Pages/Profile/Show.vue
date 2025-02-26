<template>
  <admin-layout :title="$t('Profile')">
    <!-- <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template> -->

    <div>
      <div>
        <template v-if="$page.props.jetstream.canUpdateProfileInformation">
          <update-profile-information-form :user="$page.props.auth.user" />
          <tec-section-border />
        </template>

        <template v-if="$page.props.jetstream.canUpdatePassword">
          <update-password-form class="mt-10 sm:mt-0" />
          <tec-section-border />
        </template>

        <template v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <two-factor-authentication-form class="mt-10 sm:mt-0" />
          <tec-section-border />
        </template>

        <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <tec-section-border />
          <delete-user-form class="mt-10 sm:mt-0" />
        </template>
      </div>
    </div>
  </admin-layout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TecSectionBorder from '@/Jetstream/SectionBorder.vue';
import DeleteUserForm from '@/Pages/Profile/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/UpdatePasswordForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/TwoFactorAuthenticationForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/UpdateProfileInformationForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/LogoutOtherBrowserSessionsForm.vue';

export default {
  props: ['sessions'],

  components: {
    AdminLayout,
    DeleteUserForm,
    TecSectionBorder,
    UpdatePasswordForm,
    TwoFactorAuthenticationForm,
    UpdateProfileInformationForm,
    LogoutOtherBrowserSessionsForm,
  },
};
</script>
