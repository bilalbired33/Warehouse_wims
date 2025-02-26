<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Role') })">
    <div>
      <div class="">
        <tec-form-section @submitted="update">
          <template #title>
            <div class="flex items-center">
              <template v-if="edit">
                <Link class="text-blue-600 hover:text-blue-700" :href="route('roles.index')">{{ $t('Roles') }}</Link>
                <span class="text-blue-600 font-bold mx-2">/</span>
                {{ edit.name }}
              </template>
              <template v-else>
                {{ $t('create_x', { x: $t('Role') }) }}
              </template>
            </div>
          </template>

          <template #description>{{
            edit ? $t('Update the record by modifying the details in the form below') : $t('Please fill the form below to add new record.')
          }}</template>

          <template #form>
            <div>
              <trashed-message v-if="edit && edit.deleted_at" class="mb-6" @restore="restore">
                {{ $t('This record has been deleted.') }}
              </trashed-message>
              <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
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
                  {{ $t('delete_x', { x: $t('Role') }) }}
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
                <tec-action-message :on="form.recentlySuccessful" class="mr-3">{{ $t('Saved.') }} </tec-action-message>
                <loading-button type="submit" :loading="form.processing" :disabled="form.processing">{{ $t('Save') }} </loading-button>
              </div>
            </div>
          </template>
        </tec-form-section>
      </div>

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
        :action="deleteRole"
        action-type="delete"
        title="Delete Role?"
        action-text="Delete Role"
        :content="$t('Are you sure you want to delete the record?')"
      />

      <!-- Restore Account Confirmation Modal -->
      <Dialog
        :show="restoreConf"
        :action="restoreRole"
        title="Restore Role!"
        :close="closeRestoreModal"
        action-text="Restore Role"
        :content="$t('Are you sure you want to restore the record?')"
      />

      <!-- Role Permissions Form -->
      <div class="mt-8" v-if="edit">
        <tec-form-section @submitted="submit">
          <template #title>{{ $t('Role Permissions') }}</template>
          <template #description>{{ $t('Update the role permissions in the form below') }}</template>

          <template #form>
            <div>
              <table>
                <tbody>
                  <tr>
                    <td colspan="5" class="pb-3">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Checkins') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-checkins"
                        value="read-checkins"
                        @input="updatePermissions('read-checkins')"
                        :label="$t('view_x', { x: $t('Checkins') })"
                        :checked="permissions.includes('read-checkins')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-checkins"
                        value="create-checkins"
                        @input="updatePermissions('create-checkins')"
                        :label="$t('create_x', { x: $t('Checkins') })"
                        :checked="permissions.includes('create-checkins')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-checkins"
                        value="update-checkins"
                        @input="updatePermissions('update-checkins')"
                        :label="$t('update_x', { x: $t('Checkins') })"
                        :checked="permissions.includes('update-checkins')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-checkins"
                        value="delete-checkins"
                        @input="updatePermissions('delete-checkins')"
                        :label="$t('delete_x', { x: $t('Checkins') })"
                        :checked="permissions.includes('delete-checkins')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Checkouts') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-checkouts"
                        value="read-checkouts"
                        @input="updatePermissions('read-checkouts')"
                        :label="$t('view_x', { x: $t('Checkouts') })"
                        :checked="permissions.includes('read-checkouts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-checkouts"
                        value="create-checkouts"
                        @input="updatePermissions('create-checkouts')"
                        :label="$t('create_x', { x: $t('Checkouts') })"
                        :checked="permissions.includes('create-checkouts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-checkouts"
                        value="update-checkouts"
                        @input="updatePermissions('update-checkouts')"
                        :label="$t('update_x', { x: $t('Checkouts') })"
                        :checked="permissions.includes('update-checkouts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-checkouts"
                        value="delete-checkouts"
                        @input="updatePermissions('delete-checkouts')"
                        :label="$t('delete_x', { x: $t('Checkouts') })"
                        :checked="permissions.includes('delete-checkouts')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Adjustments') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-adjustments"
                        value="read-adjustments"
                        @input="updatePermissions('read-adjustments')"
                        :label="$t('view_x', { x: $t('Adjustments') })"
                        :checked="permissions.includes('read-adjustments')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-adjustments"
                        value="create-adjustments"
                        @input="updatePermissions('create-adjustments')"
                        :label="$t('create_x', { x: $t('Adjustments') })"
                        :checked="permissions.includes('create-adjustments')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-adjustments"
                        value="update-adjustments"
                        @input="updatePermissions('update-adjustments')"
                        :label="$t('update_x', { x: $t('Adjustments') })"
                        :checked="permissions.includes('update-adjustments')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-adjustments"
                        value="delete-adjustments"
                        @input="updatePermissions('delete-adjustments')"
                        :label="$t('delete_x', { x: $t('Adjustments') })"
                        :checked="permissions.includes('delete-adjustments')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Transfers') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-transfers"
                        value="read-transfers"
                        @input="updatePermissions('read-transfers')"
                        :label="$t('view_x', { x: $t('Transfers') })"
                        :checked="permissions.includes('read-transfers')"
                      />
                    </td>

                    <td class="border-y pr-6">
                      <check-box
                        id="create-transfers"
                        value="create-transfers"
                        @input="updatePermissions('create-transfers')"
                        :label="$t('create_x', { x: $t('Transfers') })"
                        :checked="permissions.includes('create-transfers')"
                      />
                    </td>

                    <td class="border-y pr-6">
                      <check-box
                        id="update-transfers"
                        value="update-transfers"
                        @input="updatePermissions('update-transfers')"
                        :label="$t('update_x', { x: $t('Transfers') })"
                        :checked="permissions.includes('update-transfers')"
                      />
                    </td>

                    <td class="border-y pr-6">
                      <check-box
                        id="delete-transfers"
                        value="delete-transfers"
                        @input="updatePermissions('delete-transfers')"
                        :label="$t('delete_x', { x: $t('Transfers') })"
                        :checked="permissions.includes('delete-transfers')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Items') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-items"
                        value="read-items"
                        @input="updatePermissions('read-items')"
                        :label="$t('view_x', { x: $t('Items') })"
                        :checked="permissions.includes('read-items')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-items"
                        value="create-items"
                        @input="updatePermissions('create-items')"
                        :label="$t('create_x', { x: $t('Items') })"
                        :checked="permissions.includes('create-items')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-items"
                        value="update-items"
                        @input="updatePermissions('update-items')"
                        :label="$t('update_x', { x: $t('Items') })"
                        :checked="permissions.includes('update-items')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-items"
                        value="delete-items"
                        @input="updatePermissions('delete-items')"
                        :label="$t('delete_x', { x: $t('Items') })"
                        :checked="permissions.includes('delete-items')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Contacts') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-contacts"
                        value="read-contacts"
                        @input="updatePermissions('read-contacts')"
                        :label="$t('view_x', { x: $t('Contacts') })"
                        :checked="permissions.includes('read-contacts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-contacts"
                        value="create-contacts"
                        @input="updatePermissions('create-contacts')"
                        :label="$t('create_x', { x: $t('Contacts') })"
                        :checked="permissions.includes('create-contacts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-contacts"
                        value="update-contacts"
                        @input="updatePermissions('update-contacts')"
                        :label="$t('update_x', { x: $t('Contacts') })"
                        :checked="permissions.includes('update-contacts')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-contacts"
                        value="delete-contacts"
                        @input="updatePermissions('delete-contacts')"
                        :label="$t('delete_x', { x: $t('Contacts') })"
                        :checked="permissions.includes('delete-contacts')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Categories') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-categories"
                        value="read-categories"
                        @input="updatePermissions('read-categories')"
                        :label="$t('view_x', { x: $t('Categories') })"
                        :checked="permissions.includes('read-categories')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-categories"
                        value="create-categories"
                        @input="updatePermissions('create-categories')"
                        :label="$t('create_x', { x: $t('Categories') })"
                        :checked="permissions.includes('create-categories')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-categories"
                        value="update-categories"
                        @input="updatePermissions('update-categories')"
                        :label="$t('update_x', { x: $t('Categories') })"
                        :checked="permissions.includes('update-categories')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-categories"
                        value="delete-categories"
                        @input="updatePermissions('delete-categories')"
                        :label="$t('delete_x', { x: $t('Categories') })"
                        :checked="permissions.includes('delete-categories')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Units') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-units"
                        value="read-units"
                        @input="updatePermissions('read-units')"
                        :label="$t('view_x', { x: $t('Units') })"
                        :checked="permissions.includes('read-units')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-units"
                        value="create-units"
                        @input="updatePermissions('create-units')"
                        :label="$t('create_x', { x: $t('Units') })"
                        :checked="permissions.includes('create-units')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-units"
                        value="update-units"
                        @input="updatePermissions('update-units')"
                        :label="$t('update_x', { x: $t('Units') })"
                        :checked="permissions.includes('update-units')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-units"
                        value="delete-units"
                        @input="updatePermissions('delete-units')"
                        :label="$t('delete_x', { x: $t('Units') })"
                        :checked="permissions.includes('delete-units')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Warehouses') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-warehouses"
                        value="read-warehouses"
                        @input="updatePermissions('read-warehouses')"
                        :label="$t('view_x', { x: $t('Warehouses') })"
                        :checked="permissions.includes('read-warehouses')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-warehouses"
                        value="create-warehouses"
                        @input="updatePermissions('create-warehouses')"
                        :label="$t('create_x', { x: $t('Warehouses') })"
                        :checked="permissions.includes('create-warehouses')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-warehouses"
                        value="update-warehouses"
                        @input="updatePermissions('update-warehouses')"
                        :label="$t('update_x', { x: $t('Warehouses') })"
                        :checked="permissions.includes('update-warehouses')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-warehouses"
                        value="delete-warehouses"
                        @input="updatePermissions('delete-warehouses')"
                        :label="$t('delete_x', { x: $t('Warehouses') })"
                        :checked="permissions.includes('delete-warehouses')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Users') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-users"
                        value="read-users"
                        @input="updatePermissions('read-users')"
                        :label="$t('view_x', { x: $t('Users') })"
                        :checked="permissions.includes('read-users')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-users"
                        value="create-users"
                        @input="updatePermissions('create-users')"
                        :label="$t('create_x', { x: $t('Users') })"
                        :checked="permissions.includes('create-users')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-users"
                        value="update-users"
                        @input="updatePermissions('update-users')"
                        :label="$t('update_x', { x: $t('Users') })"
                        :checked="permissions.includes('update-users')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-users"
                        value="delete-users"
                        @input="updatePermissions('delete-users')"
                        :label="$t('delete_x', { x: $t('Users') })"
                        :checked="permissions.includes('delete-users')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>

                  <tr>
                    <td colspan="5" class="pb-3 pt-8">
                      <label class="block w-full font-bold text-gray-700">{{ $t('Roles') }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td class="border-y pr-6">
                      <check-box
                        id="read-roles"
                        value="read-roles"
                        @input="updatePermissions('read-roles')"
                        :label="$t('view_x', { x: $t('Roles') })"
                        :checked="permissions.includes('read-roles')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="create-roles"
                        value="create-roles"
                        @input="updatePermissions('create-roles')"
                        :label="$t('create_x', { x: $t('Roles') })"
                        :checked="permissions.includes('create-roles')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="update-roles"
                        value="update-roles"
                        @input="updatePermissions('update-roles')"
                        :label="$t('update_x', { x: $t('Roles') })"
                        :checked="permissions.includes('update-roles')"
                      />
                    </td>
                    <td class="border-y pr-6">
                      <check-box
                        id="delete-roles"
                        value="delete-roles"
                        @input="updatePermissions('delete-roles')"
                        :label="$t('delete_x', { x: $t('Roles') })"
                        :checked="permissions.includes('delete-roles')"
                      />
                    </td>
                    <td class="border-y pr-6"></td>
                  </tr>
                </tbody>
              </table>

              <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mt-8">
                <label class="block w-full font-bold text-gray-700">{{ $t('Reports') }}</label>
                <check-box
                  id="read-reports"
                  value="read-reports"
                  @input="updatePermissions('read-reports')"
                  :label="$t('view_x', { x: $t('Reports') })"
                  :checked="permissions.includes('read-reports')"
                />
              </div>

              <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mt-8">
                <label class="block w-full font-bold text-gray-700">{{ $t('Activity Logs') }}</label>
                <check-box
                  id="read-activity"
                  value="read-activity"
                  @input="updatePermissions('read-activity')"
                  :label="$t('view_x', { x: $t('Activity Logs') })"
                  :checked="permissions.includes('read-activity')"
                />
              </div>
            </div>
          </template>

          <template #actions>
            <div class="w-full flex items-center justify-end">
              <div class="flex items-center">
                <tec-action-message :on="pform.recentlySuccessful" class="mr-3">{{ $t('Saved.') }}</tec-action-message>
                <loading-button type="submit" :loading="pform.processing" :disabled="pform.processing">{{ $t('Save') }}</loading-button>
              </div>
            </div>
          </template>
        </tec-form-section>
      </div>
    </div>
  </admin-layout>
</template>

<script>
import Dialog from '@/Shared/Dialog.vue';
import CheckBox from '@/Shared/CheckBox.vue';
import TextInput from '@/Shared/TextInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import LoadingButton from '@/Shared/LoadingButton.vue';
import TecDialogModal from '@/Jetstream/DialogModal.vue';
import TrashedMessage from '@/Shared/TrashedMessage.vue';
import TecFormSection from '@/Jetstream/FormSection.vue';
import TecSectionTitle from '@/Jetstream/SectionTitle.vue';
import TecActionMessage from '@/Jetstream/ActionMessage.vue';

export default {
  props: ['edit'],

  components: {
    Dialog,
    CheckBox,
    TextInput,
    AdminLayout,
    LoadingButton,
    TecDialogModal,
    TecFormSection,
    TrashedMessage,
    TecSectionTitle,
    TecActionMessage,
  },

  data() {
    return {
      confirm: false,
      permissions: [],
      permanent: false,
      restoreConf: false,
      form: this.$inertia.form({
        _method: this.edit ? 'put' : 'post',
        name: this.edit ? this.edit.name : null,
      }),
      pform: this.$inertia.form({
        _method: 'post',
        permissions: this.edit ? this.edit.permissions : [],
      }),
    };
  },

  mounted() {
    if (this.edit) {
      this.permissions = this.edit.permissions;
      delete this.edit.permissions;
    }
  },

  methods: {
    update() {
      this.form.post(this.edit ? this.route('roles.update', this.edit.id) : this.route('roles.store'), {
        preserveScroll: true,
      });
    },
    updatePermissions(v) {
      if (this.permissions.includes(v)) {
        this.permissions = this.permissions.filter(r => r != v);
      } else {
        this.permissions = [...this.permissions, v];
      }
    },
    submit() {
      this.pform
        .transform(data => ({ ...data, permissions: this.permissions }))
        .post(this.route('roles.permissions', this.edit.id), { preserveScroll: true });
    },
    destroy() {
      this.confirm = true;
    },
    deleteRole() {
      this.form.delete(route('roles.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreRole() {
      this.$inertia.put(this.route('roles.restore', this.edit.id), {
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
      this.form.delete(route('roles.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
  },
};
</script>
