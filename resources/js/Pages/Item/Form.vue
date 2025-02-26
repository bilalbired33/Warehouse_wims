<template>
  <admin-layout :title="$t(edit ? 'edit_x' : 'create_x', { x: $t('Item') })">
    <tec-form-section @submitted="update">
      <template #title>
        <template v-if="edit">
          <div class="flex items-center">
            <Link class="text-blue-600 hover:text-blue-700" :href="route('items.index')">{{ $t('Items') }}</Link>
            <span class="text-blue-600 font-medium mx-2">/</span>
            <img v-if="edit?.photo" class="block w-6 h-6 rounded-full mr-2" :src="edit.photo" />
            {{ edit.name }}
          </div>
        </template>
        <template v-else>
          {{ $t('create_x', { x: $t('Item') }) }}
        </template>
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
              <text-input v-model="form.name" :error="$page.props.errors.name" :label="$t('Name')" />
              <text-input v-model="form.code" :error="$page.props.errors.code" :label="$t('Code')" />
              <auto-complete
                id="symbology"
                v-model="form.symbology"
                :suggestions="symbologies"
                :label="$t('Barcode Symbology')"
                :error="$page.props.errors.symbology"
              />
              <text-input v-model="form.sku" :error="$page.props.errors.sku" :label="$t('SKU')" />
            </div>
            <div class="flex flex-col gap-6 w-full lg:w-1/2">
              <auto-complete
                id="category"
                :suggestions="parents"
                :label="$t('Category')"
                v-model="form.category_id"
                :error="$page.props.errors.category_id"
              />
              <transition name="slidedown">
                <auto-complete
                  id="subcategory"
                  :suggestions="subcategories"
                  :label="$t('Child Category')"
                  v-model="form.child_category_id"
                  v-if="form.category_id && subcategories.length > 0"
                />
              </transition>
              <auto-complete id="unit" :label="$t('Unit')" :suggestions="units" v-model="form.unit_id" />
              <text-input v-model="form.rack_location" :error="$page.props.errors.rack_location" :label="$t('Rack Location')" />
              <div class="flex items-end">
                <div v-if="edit?.photo" class="w-16 h-16 mr-4 relative group">
                  <button
                    type="button"
                    @click="() => (photo = true)"
                    class="hidden group-hover:flex items-center rounded justify-center absolute inset-1 bg-gray-900/80"
                  >
                    <icons name="trash" class="shrink-0 w-6 h-6 text-red-500" />
                  </button>
                  <img :src="edit.photo" alt="" class="rounded w-16 h-16" />
                </div>
                <div class="flex-1">
                  <file-input v-model="form.photo" :error="$page.props.errors.photo" :label="$t('Photo')" />
                </div>
              </div>
            </div>
          </div>
          <textarea-input v-model="form.details" :error="$page.props.errors.details" :label="$t('Details')" />
          <div>
            <!-- <check-box
              class="mb-2"
              id="has_serials"
              :label="$t('Track Serials')"
              v-model:checked="form.has_serials"
              :error="$page.props.errors.has_serials"
            /> -->
            <check-box
              class="mb-2"
              id="track_weight"
              :label="$t('Track Weight')"
              v-model:checked="form.track_weight"
              :error="$page.props.errors.track_weight"
            />
            <check-box
              id="track_quantity"
              :label="$t('Track Quantity')"
              v-model:checked="form.track_quantity"
              :error="$page.props.errors.track_quantity"
            />
          </div>
          <transition name="slidedown">
            <template v-if="form.track_quantity">
              <text-input
                class="mb-6"
                type="number"
                v-model="form.alert_quantity"
                :label="$t('Alert on low stock of')"
                :error="$page.props.errors.alert_quantity"
              />
            </template>
          </transition>
          <div class="-mt-4">
            <check-box
              id="has_variants"
              v-model:checked="form.has_variants"
              :error="$page.props.errors.has_variants"
              :label="$t('Track Variants e.g. Size and/or Color')"
            />
            <p class="ml-6 text-sm text-yellow-600">{{ $t('Modifying variants after you have stock could result in wrong stock.') }}</p>
            <div v-if="form.has_variants == 1" class="my-6">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">{{ $t('Variants') }}</h3>
                <div>
                  <button
                    type="button"
                    @click="form.variants.push({ name: '', option: ['', ''] })"
                    class="text-gray-100 px-4 py-3 bg-blue-600 border border-blue-600 rounded-l-md ml-4 focus:outline-none hover:bg-blue-700"
                  >
                    <span class="flex items-center justify-center text-lg w-4 h-4">+</span>
                  </button>
                  <button
                    type="button"
                    @click="form.variants.pop()"
                    class="text-gray-100 px-4 py-3 bg-blue-600 border border-blue-600 rounded-r-md focus:outline-none hover:bg-blue-700"
                  >
                    <span class="flex items-center justify-center text-lg w-4 h-4">-</span>
                  </button>
                </div>
              </div>
              <div class="flex flex-wrap items-start -mx-3">
                <div v-for="(variant, vi) in form.variants" :key="vi" class="mt-6 px-3 w-full lg:w-1/2">
                  <div class="p-4 bg-gray-100 shadow rounded-md">
                    <div class="flex items-start">
                      <text-input
                        class="flex-1"
                        v-model="variant.name"
                        :error="form.errors['variants.' + vi + '.name']"
                        :label="$t('Variant') + ' ' + (vi + 1) + ' ' + $t('Name')"
                      />
                      <span class="mt-6">
                        <button
                          type="button"
                          @click="variant.option.push('')"
                          class="mt-1 text-gray-100 px-4 py-3 bg-gray-600 border border-gray-600 rounded-l-md ml-4 focus:outline-none hover:bg-gray-500"
                        >
                          <span class="flex items-center justify-center text-lg w-4 h-4">+</span>
                        </button>
                        <button
                          type="button"
                          @click="variant.option.pop()"
                          class="mt-1 text-gray-100 px-4 py-3 bg-gray-600 border border-gray-600 rounded-r-md focus:outline-none hover:bg-gray-500"
                        >
                          <span class="flex items-center justify-center text-lg w-4 h-4">-</span>
                        </button>
                      </span>
                    </div>
                    <div class="flex flex-wrap items-start -mx-2">
                      <div class="mt-4 px-2 w-1/2" v-for="(opt, oi) in variant.option" :key="oi">
                        <text-input
                          v-model="variant.option[oi]"
                          :label="$t('Option') + ' ' + (oi + 1)"
                          :error="form.errors['variants.' + vi + '.option.' + oi]"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-6">
              <div class="font-bold">{{ $t('Warehouse Rack Locations') }}</div>
              <div class="mt-3 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div v-for="(wh, wi) in form.warehouses">
                  <text-input
                    class="flex-1"
                    v-model="form.warehouses[wi].rack"
                    :error="form.errors['warehouses.' + wi + '.rack']"
                    :label="$t('Rack Location for {x}', { x: wh.name })"
                  />
                </div>
              </div>
            </div>
          </div>
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
              {{ $t('delete_x', { x: $t('Item') }) }}
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
            <!-- <Link
              :href="route('items.index')"
              class="hidden md:inline px-4 py-2 rounded border-2 border-transparent hover:border-gray-300 focus:outline-none focus:border-gray-300"
            >
              {{ $t('Go to Listing') }}
            </Link> -->
            <tec-action-message :on="form.recentlySuccessful" class="mx-3">{{ $t('Saved.') }}</tec-action-message>
            <loading-button type="submit" :loading="form.processing" :disabled="form.processing">{{ $t('Save') }}</loading-button>
            <!-- <loading-button type="button" @click="update(true)" class="ml-4" :loading="form.processing" :disabled="form.processing">{{
              $t('Save & Go to Listing')
            }}</loading-button> -->
          </div>
        </div>
      </template>
    </tec-form-section>

    <!-- Delete User Confirmation Modal -->
    <Dialog
      max-width="md"
      :show="photo"
      action-type="delete"
      :action="deletePhoto"
      title="Delete Item Photo?"
      :close="() => (photo = false)"
      action-text="Delete Item Photo?"
      :content="$t('Are you sure you want to delete the photo?')"
    />

    <Dialog
      max-width="md"
      :show="permanent"
      action-type="delete"
      title="Delete Item?"
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
      :action="deleteItem"
      action-type="delete"
      title="Delete Item?"
      action-text="Delete Item"
      :content="$t('Are you sure you want to delete the record?')"
    />

    <!-- Restore Account Confirmation Modal -->
    <Dialog
      :show="restoreConf"
      :action="restoreItem"
      title="Restore Item!"
      :close="closeRestoreModal"
      action-text="Restore Item"
      :content="$t('Are you sure you want to restore the record?')"
    />
  </admin-layout>
</template>

<script>
import Dialog from '@/Shared/Dialog.vue';
import CheckBox from '@/Shared/CheckBox.vue';
import FileInput from '@/Shared/FileInput.vue';
import TextInput from '@/Shared/TextInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AutoComplete from '@/Shared/AutoComplete.vue';
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
  props: ['edit', 'categories', 'units', 'warehouses'],

  components: {
    Dialog,
    CheckBox,
    FileInput,
    TextInput,
    AdminLayout,
    SelectInput,
    AutoComplete,
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
      photo: false,
      confirm: false,
      permanent: false,
      restoreConf: false,
      symbologies: [
        { value: 'CODE128', label: this.$t('CODE128') },
        { value: 'CODE39', label: this.$t('CODE39') },
        { value: 'EAN5', label: this.$t('EAN-5') },
        { value: 'EAN8', label: this.$t('EAN-8') },
        { value: 'EAN13', label: this.$t('EAN-13') },
        { value: 'UPC', label: this.$t('UPC-A') },
      ],
      form: this.$inertia.form({
        photo: null,
        _method: this.edit ? 'put' : 'post',
        sku: this.edit ? this.edit.sku : null,
        name: this.edit ? this.edit.name : null,
        code: this.edit ? this.edit.code : null,
        stock: this.edit ? this.edit.stock : null,
        details: this.edit ? this.edit.details : null,
        unit_id: this.edit ? this.edit.unit_id : null,
        set_stock: this.edit ? this.edit.set_stock : null,
        symbology: this.edit ? this.edit.symbology : 'code128',
        rack_location: this.edit ? this.edit.rack_location : null,
        has_serials: this.edit ? this.edit.has_serials == 1 : null,
        category_id: this.edit ? this.edit.categories[0]?.id : null,
        has_variants: this.edit ? this.edit.has_variants == 1 : null,
        track_weight: this.edit ? this.edit.track_weight == 1 : null,
        track_quantity: this.edit ? this.edit.track_quantity == 1 : true,
        child_category_id: this.edit ? this.edit.categories[1]?.id : null,
        alert_quantity: this.edit && this.edit.alert_quantity ? Number(this.edit.alert_quantity) : null,
        variants: this.edit && this.edit.variants ? this.edit.variants : [{ name: '', option: ['', ''] }],
        warehouses: this.warehouses.map(w => ({ rack: '', warehouse_id: w.id, name: w.name })) || [],
      }),
    };
  },

  created() {
    if (this.edit) {
      this.form.warehouses = this.edit.stock.map(w => ({ rack: w.rack_location, warehouse_id: w.warehouse_id, name: w.warehouse?.name }));
    }
    this.warehouses.map(w => {
      if (!this.form.warehouses.find(wh => wh.warehouse_id == w.id)) {
        this.form.warehouses.push({ rack: '', warehouse_id: w.id, name: w.name });
      }
    });
  },

  computed: {
    parents() {
      return this.categories.filter(c => !c.parent_id);
    },
    subcategories() {
      return this.categories.filter(c => c.parent_id == this.form.category_id);
    },
  },

  methods: {
    update(list) {
      // this.form.post(this.route('items.update', this.edit.id) + (list ? '?listing=yes' : ''), {
      this.form.post(this.edit ? this.route('items.update', this.edit.id) : this.route('items.store'), {
        preserveScroll: true,
        // onFinish: () => {
        //   if (list) {
        //     this.$inertia.visit(route('items.index'), { preserveState: true, preserveScroll: true });
        //   }
        // },
      });
    },
    destroy() {
      this.confirm = true;
    },
    deleteItem() {
      this.$inertia.delete(route('items.destroy', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closeModal() {
      this.confirm = false;
    },
    restore() {
      this.restoreConf = true;
    },
    restoreItem() {
      this.$inertia.put(this.route('items.restore', this.edit.id), {
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
      this.$inertia.delete(route('items.destroy.permanently', this.edit.id), {
        onSuccess: () => this.closeModal(),
      });
    },
    closePermanentModal() {
      this.permanent = false;
    },
    deletePhoto() {
      this.$inertia.delete(route('items.deletePhoto', this.edit.id), {
        onSuccess: () => {
          this.edit.photo = null;
          this.closeModal();
        },
      });
    },
  },
};
</script>
