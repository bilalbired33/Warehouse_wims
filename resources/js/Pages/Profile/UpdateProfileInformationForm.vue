<template>
  <tec-form-section @submitted="updateProfileInformation">
    <template #title>{{ $t('Profile Information') }}</template>

    <template #description>{{ $t("Update your account's profile information and email address.") }}</template>

    <template #form>
      <!-- Profile Photo -->
      <div class="col-span-6 sm:col-span-4" v-if="$page.props.jetstream.managesProfilePhotos">
        <!-- Profile Photo File Input -->
        <input type="file" class="hidden" ref="photo" @change="updatePhotoPreview" />
        <tec-label for="photo" :value="$t('Photo')" />

        <!-- Current Profile Photo -->
        <div class="mt-2" v-show="!photoPreview">
          <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover" />
        </div>

        <!-- New Profile Photo Preview -->
        <div class="mt-2" v-show="photoPreview">
          <span
            class="block rounded-full w-20 h-20"
            :style="
              'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' +
              photoPreview +
              '\');'
            "
          >
          </span>
        </div>

        <tec-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto"
          >{{ $t('Select A New Photo') }}
        </tec-secondary-button>

        <tec-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto" v-if="user.profile_photo_path">
          {{ $t('Remove Photo') }}
        </tec-secondary-button>

        <tec-input-error :message="form.errors.photo" class="mt-2" />
      </div>

      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <tec-label for="name" :value="$t('Name')" />
        <tec-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
        <tec-input-error :message="form.errors.name" class="mt-2" />
      </div>

      <!-- Email -->
      <div class="col-span-6 sm:col-span-4">
        <tec-label for="email" :value="$t('Email')" />
        <tec-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
        <tec-input-error :message="form.errors.email" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <tec-action-message :on="form.recentlySuccessful" class="mr-3">{{ $t('Saved.') }}</tec-action-message>
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
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  components: {
    TecInput,
    TecLabel,
    TecButton,
    TecInputError,
    TecFormSection,
    TecActionMessage,
    TecSecondaryButton,
  },

  props: ['user'],

  data() {
    return {
      form: this.$inertia.form({
        _method: 'PUT',
        name: this.user.name,
        email: this.user.email,
        photo: null,
      }),

      photoPreview: null,
    };
  },

  methods: {
    updateProfileInformation() {
      if (this.$refs.photo) {
        this.form.photo = this.$refs.photo.files[0];
      }

      this.form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
      });
    },

    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const reader = new FileReader();

      reader.onload = e => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(this.$refs.photo.files[0]);
    },

    deletePhoto() {
      this.$inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => (this.photoPreview = null),
      });
    },
  },
};
</script>
