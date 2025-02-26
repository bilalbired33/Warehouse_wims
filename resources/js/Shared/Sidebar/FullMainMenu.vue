<template>
  <div class="bg-gray-800 text-gray-300 w-full md:w-64 grid grid-cols-1 place-content-between overflow-x-hidden overflow-y-auto">
    <div>
      <div class="md:py-2">
        <template v-if="$page.props.settings.sidebar_style == 'dropdown'">
          <template v-for="menu in menus" :key="menu.name">
            <div v-if="menu.heading" class="hidden md:block mt-1 font-bold text-xs text-gray-600 px-4 py-2">
              {{ $t(menu.heading) }}
            </div>

            <menu-item
              :href="route(menu.route)"
              v-if="$can(menu.permissions) && !menu.sub_menu && !menu.hidden"
              :class="{ 'bg-blue-600': isActiveUrl(route(menu.route)) }"
            >
              <template #icon v-if="menu.icon">
                <Icons :name="menu.icon" class="mr-3" />
              </template>
              {{ trans(menu.lang) }}
            </menu-item>

            <dropdown-menu v-else-if="$can(menu.permissions) && menu.sub_menu.length" :active="isActiveUrl(menu.children, true)">
              <template #icon v-if="menu.icon">
                <Icons :name="menu.icon" class="mr-3" />
              </template>
              {{ trans(menu.lang) }}
              <template #dropdown>
                <template v-for="(sm, smi) in menu.sub_menu" :key="sm.name">
                  <menu-item
                    space="less"
                    :href="route(sm.route)"
                    v-if="$can(sm.permissions) && !sm.hidden"
                    :class="{ 'bg-blue-600': isActiveUrl(route(sm.route)), 'border-transparent': smi + 1 == menu.sub_menu.length }"
                  >
                    {{ trans(sm.lang) }}
                  </menu-item>
                </template>
              </template>
            </dropdown-menu>
          </template>
        </template>

        <template v-else>
          <template v-for="menu in menus" :key="menu.name">
            <div v-if="menu.heading" class="block mt-1 font-bold text-xs text-gray-600 px-4 py-2">
              {{ $t(menu.heading) }}
            </div>

            <menu-item
              :href="route(menu.route)"
              :class="{ 'bg-blue-600': isActiveUrl(route(menu.route)) }"
              v-if="$can(menu.permissions) && !menu.sub_menu && !menu.hidden"
            >
              <template #icon v-if="menu.icon">
                <Icons :name="menu.icon" class="mr-3" />
              </template>
              {{ trans(menu.lang) }}
            </menu-item>

            <template v-else-if="menu.sub_menu.length">
              <div v-if="$can(menu.permissions)" class="block mt-1 font-bold text-xs text-gray-600 px-4 py-2">
                {{ trans(menu.lang) }}
              </div>

              <template v-for="sm in menu.sub_menu" :key="sm.name">
                <menu-item
                  :href="route(sm.route)"
                  v-if="$can(sm.permissions) && !sm.hidden"
                  :class="{ 'bg-blue-600': isActiveUrl(route(sm.route)) }"
                >
                  <template #icon v-if="sm.icon">
                    <Icons :name="sm.icon" class="mr-3" />
                  </template>
                  {{ trans(sm.lang) }}
                </menu-item>
              </template>
            </template>
          </template>
        </template>
      </div>
    </div>

    <!-- Other Links -->
    <div class="py-3 my-4 sm:my-0">
      <icon-menus class="mx-3 md:mx-0 gap-x-6 md:gap-x-3 flex items-center justify-between sm:hidden" />
    </div>
  </div>
</template>

<script>
import Menus from '@/Core/menu';
import IconMenus from '@/Shared/Top/IconMenus.vue';
import MenuItem from '@/Shared/Sidebar/MenuItem.vue';
import DropdownMenu from '@/Shared/Sidebar/DropdownMenu.vue';

export default {
  components: { MenuItem, DropdownMenu, IconMenus },
  data() {
    return { menus: Menus, current: '' };
  },
  methods: {
    trans(key) {
      if (key.helper) {
        return this.$t(key.helper, { x: this.$t(key.main) });
      }
      return this.$t(key.main);
    },
    isActiveUrl(url, dropdown = false) {
      let currentUrl = this.$page.props.settings.baseUrl + (this.$page.url != '/' ? this.$page.url : '');
      if (dropdown) {
        let open = false;
        url.map(q => {
          if (currentUrl.includes(q)) {
            open = true;
          }
        });
        return open;
      }
      return url.split('?')[0] == currentUrl.split('?')[0];
    },
  },
};
</script>
