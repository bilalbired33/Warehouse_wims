<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('User') })">
    <tec-form-section @submitted="update">
      <template #title>
        <div class="flex items-center">
          <template v-if="edit">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('users.index')">{{ $t('Users') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            <img v-if="edit.photo" class="block w-6 h-6 rounded-full mr-2" :src="edit.photo" />
            {{ edit.name }}
          </template>
          <template v-else>
            {{ $t('create_x', { x: $t('User') }) }}
          </template>
        </div>
      </template>

      <template #description>{{
        edit ? $t('Update the record by modifying the details in the form below') : $t('Please fill the form below to add new record.')
      }}</template>

      <template #form>
        <trashed-message v-if="edit && edit.deleted_at" class="mb-6" @restore="restore">
          {{ $t('This user has been deleted.') }}
        </trashed-message>

        <div class="flex flex-col gap-6">
          <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
              <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
              <text-input v-model="form.username" :error="$page.props.errors.username" :label="$t('Username')" />
              <text-input type="email" v-model="form.email" :error="$page.props.errors.email" :label="$t('Email')" />
              <text-input v-model="form.phone" :error="$page.props.errors.phone" :label="$t('Phone')" />
            </div>
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
              <text-input
                id="password"
                type="password"
                :label="$t('Password')"
                v-model="form.password"
                :error="$page.props.errors.password"
              />
              <text-input
                type="password"
                id="password_confirmation"
                :label="$t('Confirm Password')"
                v-model="form.password_confirmation"
                :error="$page.props.errors.password_confirmation"
              />
              <auto-complete
                id="warehouse"
                :label="$t('Warehouse')"
                :suggestions="warehouses"
                v-model="form.warehouse_id"
                :error="$page.props.errors.warehouse_id"
              />
            </div>
          </div>
        </div>
        <!-- <select-input v-model="form.owner" :error="$page.props.errors.owner" class="pr-6 pb-8 w-full lg:w-1/2" label="Owner">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input> -->
        <div v-if="roles && roles.length" class="flex flex-wrap items-center gap-x-6 gap-y-3">
          <label class="block w-full font-medium text-gray-700">{{ $t('Roles') }}</label>
          <template v-for="role in roles" :key="role.name">
            <check-box
              :id="role.name"
              :value="role.name"
              :label="$t(role.name)"
              v-model:checked="form.roles"
              :error="$page.props.errors.roles"
            />
          </template>
          <tec-input-error v-if="$page.props.errors.roles" :message="$page.props.errors.roles" class="w-full mt-1" />
        </div>

        <h1 class="font-bold">{{ $t('Permissions') }}</h1>
        <div class="flex flex-col gap-6">
          <div class="flex flex-col lg:flex-row gap-6">
            <check-box id="view_all" v-model:checked="form.view_all" :label="$t('Can view all record')" />
            <check-box id="edit_all" v-model:checked="form.edit_all" :label="$t('Can edit all record')" />
          </div>
        </div>
      </template>

      <template #actions>
        <div class="w-full flex items-center justify-between">
          <template v-if="edit">
            <span>
              <button
                type="button"
                @click="destroy"
                v-if="!edit.deleted_at"
                class="text-red-600 px-4 py-2 rounded border-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-gray-300"
              >
                {{ $t('delete_x', { x: $t('User') }) }}
              </button>
              <button
                v-else
                type="button"
                @click="deletePermanently"
                class="text-red-600 px-4 py-2 rounded border-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-gray-300"
              >
                {{ $t('delete_x', { x: $t('Permanently') }) }}
              </button>
              <tec-danger-button
                class="ml-2"
                :disabled="disabling"
                v-if="edit.two_factor_enabled"
                :class="{ 'opacity-25': disabling }"
                @click="disableTwoFactorAuthentication"
                >{{ $t('Disable 2FA') }}</tec-danger-button
              >
            </span>
          </template>
          <div v-else></div>
          <div class="flex items-center">
            <tec-action-message :on="form.recentlySuccessful" class="mx-3">{{ $t('Saved.') }}</tec-action-message>
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
      title="Delete User?"
      :close="closePermanentModal"
      :action="deleteUserPermanently"
      action-text="Delete Permanently"
      :content="`<p class='mb-2'>${$t('Are you sure you want to delete the record permanently?')}</p>
        <p>${$t('Once deleted, all of its resources and data will be permanently deleted.')}</p>`"
    />

    <!-- Delete User Confirmation Modal -->
    <Dialog
      :show="confirm"
      :close="closeModal"
      :action="deleteUser"
      action-type="delete"
      title="Delete User?"
      action-text="Delete User"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore User Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreUser"
      title="Restore User!"
      :close="closeRestoreModal"
      action-text="Restore User"
      :content="$t('Are you sure you want to restore the record?')"
    />
  </admin-layout>
</template>

<script>
import Dialog from '@/Shared/Dialog.vue';
import CheckBox from '@/Shared/CheckBox.vue';
import TextInput from '@/Shared/TextInput.vue';
import FileInput from '@/Shared/FileInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecInputError from '@/Jetstream/InputError.vue';
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecDangerButton from '@/Jetstream/DangerButton.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  props: ['edit', 'roles', 'warehouses'],

  components: {
    Dialog,
    CheckBox,
    FileInput,
    TextInput,
    AdminLayout,
    AutoComplete,
    LoadingButton,
    TecInputError,
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
      disabling: false,
      permanent: false,
      restoreConf: false,
      form: this.$inertia.form({
        _method: this.edit ? 'put' : 'post',
        photo: null,
        password: null,
        password_confirmation: null,
        name: this.edit ? this.edit.name : null,
        roles: this.edit ? this.edit.roles : [],
        email: this.edit ? this.edit.email : null,
        username: this.edit ? this.edit.username : null,
        view_all: this.edit ? this.edit.view_all == 1 : false,
        edit_all: this.edit ? this.edit.edit_all == 1 : false,
        warehouse_id: this.edit ? this.edit.warehouse_id : null,
      }),
    };
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('users.update', this.edit.id) : this.route('users.store'), {
        preserveScroll: true,
        onSuccess: () => this.form.reset('password', 'password_confirmation', 'photo'),
      });
    },
    destroy() {
      this.confirm = true;
    },
    restore() {
      this.restoreConf = true;
    },
    deletePermanently() {
      this.permanent = true;
    },

    restoreUser() {
      this.$inertia.put(this.route('users.restore', this.edit.id), {
        onSuccess: () => (this.restoreConf = false),
      });
    },
    deleteUser() {
      this.form.delete(route('users.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    deleteUserPermanently() {
      this.form.delete(route('users.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeRestoreModal() {
      this.restoreConf = false;
    },
    closePermanentModal() {
      this.permanent = false;
    },
    closeModal() {
      this.confirm = false;
    },
    disableTwoFactorAuthentication() {
      this.disabling = true;
      this.$inertia.delete(route('users.disable.2fa', this.edit.id), {
        preserveScroll: true,
        onSuccess: () => (this.disabling = false),
      });
    },
  },
};
</script>
