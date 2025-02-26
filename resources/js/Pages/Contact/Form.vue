<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Contact') })">
    <tec-form-section @submitted="update">
      <template #title>
        <div class="flex items-center">
          <template v-if="edit">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('contacts.index')">{{ $t('Contacts') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            <img v-if="edit.photo" class="block w-6 h-6 rounded-full mr-2" :src="edit.photo" />
            {{ edit.name }}
          </template>
          <template v-else>
            {{ $t('create_x', { x: $t('Contact') }) }}
          </template>
        </div>
      </template>

      <template #description>{{
        edit ? $t('Update the record by modifying the details in the form below') : $t('Please fill the form below to add new record.')
      }}</template>

      <template #form>
        <trashed-message v-if="edit && edit.deleted_at" class="mb-6" @restore="restore">
          {{ $t('This record has been deleted.') }}
        </trashed-message>

        <div class="flex flex-col gap-y-6">
          <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
          <text-input type="email" v-model="form.email" :error="$page.props.errors.email" :label="$t('Email')" />
          <text-input v-model="form.phone" :error="$page.props.errors.phone" :label="$t('Phone')" />
          <textarea-input v-model="form.details" :error="$page.props.errors.details" :label="$t('Details')" />
        </div>
      </template>

      <template #actions>
        <div class="w-full flex items-center justify-between">
          <template v-if="edit">
            <button
              type="button"
              @click="destroy"
              v-if="!edit.deleted_at"
              class="text-red-600 px-4 py-2 rounded border-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-gray-300"
            >
              {{ $t('delete_x', { x: $t('Contact') }) }}
            </button>
            <button
              v-else
              type="button"
              @click="deletePermanently"
              class="text-red-600 px-4 py-2 rounded border-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-gray-300"
            >
              {{ $t('delete_x', { x: $t('Permanently') }) }}
            </button>
          </template>
          <div v-else></div>
          <div class="flex items-center">
            <tec-action-message :on="form.recentlySuccessful" class="mr-3">{{ $t('Saved.') }}</tec-action-message>
            <loading-button type="submit" :loading="form.processing" :disabled="form.processing">{{ $t('Save') }}</loading-button>
          </div>
        </div>
      </template>
    </tec-form-section>

    <!-- Delete User Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="permanent"
      action-type="delete"
      title="Delete Contact?"
      :close="closePermanentModal"
      :action="deleteContactPermanently"
      action-text="Delete Permanently"
      :content="`<p class='mb-2'>${$t('Are you sure you want to delete the record permanently?')}</p>
        <p>${$t('Once deleted, all of its resources and data will be permanently deleted.')}</p>`"
    />

    <!-- Delete Account Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="confirm"
      :close="closeModal"
      :action="deleteContact"
      action-type="delete"
      title="Delete Contact?"
      action-text="Delete Contact"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="restoreConf"
      :action="restoreContact"
      title="Restore Contact!"
      :close="closeRestoreModal"
      action-text="Restore Contact"
      :content="$t('Are you sure you want to restore the record?')"
    />
  </admin-layout>
</template>

<script>
import Dialog from '@/Shared/Dialog.vue';
import TextInput from '@/Shared/TextInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecDangerButton from '@/Jetstream/DangerButton.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  remember: 'form',

  props: ['edit'],

  components: {
    Dialog,
    TextInput,
    AdminLayout,
    SelectInput,
    LoadingButton,
    TextareaInput,
    TecDialogModal,
    TecFormSection,
    TecDangerButton,
    TrashedMessage,
    TecSectionTitle,
    TecActionMessage,
    TecSecondaryButton,
  },

  data() {
    return {
      confirm: false,
      permanent: false,
      restoreConf: false,
      form: this.$inertia.form({
        _method: this.edit ? 'put' : 'post',
        name: this.edit ? this.edit.name : null,
        email: this.edit ? this.edit.email : null,
        phone: this.edit ? this.edit.phone : null,
        details: this.edit ? this.edit.details : null,
      }),
    };
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('contacts.update', this.edit.id) : this.route('contacts.store'), {
        preserveScroll: true,
        onSuccess: () => this.form.reset('password', 'photo'),
      });
    },
    destroy() {
      this.confirm = true;
    },
    deleteContact() {
      this.form.delete(route('contacts.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreContact() {
      this.$inertia.put(this.route('contacts.restore', this.edit.id), {
        onSuccess: () => (this.restoreConf = false),
      });
    },
    closeRestoreModal() {
      this.restoreConf = false;
    },
    deletePermanently() {
      this.permanent = true;
    },
    deleteContactPermanently() {
      this.form.delete(route('contacts.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
  },
};
</script>
