<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Unit') })">
    <tec-form-section @submitted="update">
      <template #title>
        <div class="flex items-center">
          <template v-if="edit">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('units.index')">{{ $t('Units') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            {{ edit.name }}
          </template>
          <template v-else>
            {{ $t('create_x', { x: $t('Unit') }) }}
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

        <div class="flex flex-col gap-6 w-full">
          <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
          <text-input v-model="form.code" :error="$page.props.errors.code" :label="$t('Code')" />
          <select-input
            :label="$t('Base Unit')"
            v-model="form.base_unit_id"
            :error="$page.props.errors.base_unit_id"
            v-if="base_units && base_units.length > 1"
          >
            <option value="">{{ $t('Select') }}</option>
            <template v-for="unit in base_units" :key="unit.id">
              <template v-if="unit.id != edit?.id">
                <option :value="unit.id">{{ unit.name }}</option>
              </template>
            </template>
          </select-input>

          <transition name="slidedown">
            <div class="flex flex-col gap-6 w-full" v-if="form.base_unit_id">
              <select-input :label="$t('Operator')" v-model="form.operator" :error="$page.props.errors.operator">
                <option value="*">{{ $t('Multiply') }}</option>
                <option value="/">{{ $t('Divide') }}</option>
                <option value="+">{{ $t('Plus') }}</option>
                <option value="-">{{ $t('Minus') }}</option>
              </select-input>
              <text-input
                type="number"
                v-model="form.operation_value"
                :label="$t('Operation Value')"
                :error="$page.props.errors.operation_value"
              />
              <div v-if="form.operator && form.operation_value" class="px-4 py-3 bg-blue-100 rounded">{{ formula }}</div>
            </div>
          </transition>
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
              {{ $t('delete_x', { x: $t('Unit') }) }}
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
      :action="deleteUnit"
      action-type="delete"
      title="Delete Unit?"
      action-text="Delete Unit"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreUnit"
      title="Restore Unit!"
      :close="closeRestoreModal"
      action-text="Restore Unit"
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
  props: ['edit', 'base_units'],

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
        code: this.edit ? this.edit.code : null,
        name: this.edit ? this.edit.name : null,
        operator: this.edit ? this.edit.operator : null,
        base_unit_id: this.edit ? this.edit.base_unit_id : null,
        operation_value: this.edit ? parseFloat(this.edit.operation_value) : null,
      }),
    };
  },

  computed: {
    formula() {
      let base_unit = this.base_units.find(u => u.id == this.form.base_unit_id);
      if (base_unit) {
        return (
          base_unit.name +
          ' (' +
          base_unit.code +
          ') ' +
          (this.form.operator || '') +
          ' ' +
          (this.form.operation_value || '') +
          ' = ' +
          (this.form.name ? this.form.name : '') +
          (this.form.code ? '(' + this.form.code + ')' : '')
        );
      }
      return null;
    },
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('units.update', this.edit.id) : this.route('units.store'), {
        preserveScroll: true,
      });
    },
    destroy() {
      this.confirm = true;
    },
    deleteUnit() {
      this.form.delete(route('units.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreUnit() {
      this.$inertia.put(this.route('units.restore', this.edit.id), {
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
      this.form.delete(route('units.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
  },
};
</script>
