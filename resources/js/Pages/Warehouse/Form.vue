<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Warehouse') })">
    <tec-form-section @submitted="update">
      <template #title>
        <div class="flex items-center">
          <template v-if="edit">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('warehouses.index')">{{ $t('Warehouses') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            {{ edit.name }}
          </template>
          <template v-else>
            {{ $t('create_x', { x: $t('Warehouse') }) }}
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

        <div class="flex flex-col gap-6">
          <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
              <text-input v-model="form.code" :error="$page.props.errors.code" :label="$t('Code')" />
              <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
            </div>
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
              <text-input v-model="form.phone" :error="$page.props.errors.phone" :label="$t('Phone')" />
              <text-input type="email" v-model="form.email" :error="$page.props.errors.email" :label="$t('Email')" />
            </div>
          </div>
          <div class="flex items-center justify-between">
            <file-input class="grow mr-4" v-model="form.logo" :error="$page.props.errors.logo" :label="$t('Logo')" />
            <div v-if="edit && edit.logo">
              <p class="text-sm">{{ $t('Current Logo') }}</p>
              <img class="block h-12 rounded" :src="edit.logo" />
            </div>
          </div>
          <textarea-input v-model="form.address" :error="$page.props.errors.address" :label="$t('Address')" />
          <check-box id="active" :label="$t('Active')" v-model:checked="form.active" :error="$page.props.errors.active" />
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
              {{ $t('delete_x', { x: $t('Warehouse') }) }}
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
      title="Delete Role?"
      :close="closePermanentModal"
      :action="deleteRolePermanently"
      action-text="Delete Permanently"
      :content="`<p class='mb-2'>${$t('Are you sure you want to delete the record permanently?')}</p>
        <p>${$t('Once deleted, all of its resources and data will be permanently deleted.')}</p>`"
    />

    <!-- Delete Account Confirmation Modal -->
    <Dialog
      :show="confirm"
      :close="closeModal"
      :action="deleteWarehouse"
      action-type="delete"
      title="Delete Warehouse?"
      action-text="Delete Warehouse"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreWarehouse"
      title="Restore Warehouse!"
      :close="closeRestoreModal"
      action-text="Restore Warehouse"
      :content="$t('Are you sure you want to restore the record?')"
    />
  </admin-layout>
</template>

<script>
import Dialog from '@/Shared/Dialog.vue';
import CheckBox from '@/Shared/CheckBox.vue';
import FileInput from '@/Shared/FileInput.vue';
import TextInput from '@/Shared/TextInput.vue';
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
  props: ['edit'],

  components: {
    Dialog,
    CheckBox,
    FileInput,
    TextInput,
    AdminLayout,
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
        logo: null,
        code: this.edit ? this.edit.code : null,
        name: this.edit ? this.edit.name : null,
        email: this.edit ? this.edit.email : null,
        phone: this.edit ? this.edit.phone : null,
        address: this.edit ? this.edit.address : null,
        active: this.edit ? this.edit.active == 1 : true,
      }),
    };
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('warehouses.update', this.edit.id) : this.route('warehouses.store'), {
        preserveScroll: true,
      });
    },
    destroy() {
      this.confirm = true;
    },
    deleteWarehouse() {
      this.form.delete(route('warehouses.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreWarehouse() {
      this.$inertia.put(this.route('warehouses.restore', this.edit.id), {
        onSuccess: () => (this.restoreConf = false),
      });
    },
    closeRestoreModal() {
      this.restoreConf = false;
    },
    deletePermanently() {
      this.permanent = true;
    },
    deleteRolePermanently() {
      this.form.delete(route('warehouses.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
  },
};
</script>
