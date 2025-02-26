<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Category') })">
    <tec-form-section @submitted="update">
      <template #title>
        <div class="flex items-center">
          <template v-if="edit">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('categories.index')">{{ $t('Categories') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            {{ edit.name }}
          </template>
          <template v-else>
            {{ $t('create_x', { x: $t('Category') }) }}
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
          <text-input v-model="form.code" :error="$page.props.errors.code" :label="$t('Code')" />
          <select-input
            v-model="form.parent_id"
            :label="$t('Parent Category')"
            v-if="parents && parents.length > 1"
            :error="$page.props.errors.parent_id"
          >
            <option value="">{{ $t('Select') }}</option>
            <template v-for="category in parents" :key="category.id">
              <template v-if="category.id != edit?.id">
                <option :value="category.id">{{ category.name }}</option>
              </template>
            </template>
          </select-input>
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
              {{ $t('delete_x', { x: $t('Category') }) }}
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
      title="Delete Category?"
      :close="closePermanentModal"
      action-text="Delete Permanently"
      :action="deleteCategoryPermanently"
      :content="`<p class='mb-2'>${$t('Are you sure you want to delete the record permanently?')}</p>
        <p>${$t('Once deleted, all of its resources and data will be permanently deleted.')}</p>`"
    />

    <!-- Delete Account Confirmation Modal -->
    <Dialog
      :show="confirm"
      :close="closeModal"
      action-type="delete"
      :action="deleteCategory"
      title="Delete Category?"
      action-text="Delete Category"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreCategory"
      title="Restore Category!"
      :close="closeRestoreModal"
      action-text="Restore Category"
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
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecDangerButton from '@/Jetstream/DangerButton.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';
import TecSecondaryButton from '@/Jetstream/SecondaryButton.vue';

export default {
  props: ['edit', 'parents'],

  components: {
    Dialog,
    TextInput,
    AdminLayout,
    SelectInput,
    LoadingButton,
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
        code: this.edit ? this.edit.code : null,
        parent_id: this.edit ? this.edit.parent_id : null,
      }),
    };
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('categories.update', this.edit.id) : this.route('categories.store'), {
        preserveScroll: true,
      });
    },
    destroy() {
      this.confirm = true;
    },
    deleteCategory() {
      this.form.delete(route('categories.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreCategory() {
      this.$inertia.put(this.route('categories.restore', this.edit.id), {
        onSuccess: () => (this.restoreConf = false),
      });
    },
    closeRestoreModal() {
      this.restoreConf = false;
    },
    deletePermanently() {
      this.permanent = true;
    },
    deleteCategoryPermanently() {
      this.form.delete(route('categories.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
  },
};
</script>
