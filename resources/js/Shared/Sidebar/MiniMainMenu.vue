<template>
  <div class="bg-gray-800 text-gray-300 w-full h-full md:w-16 grid grid-cols-1 place-content-between">
    <div>
      <div class="lg:py-2">
        <template v-for="menu in menus" :key="menu.name">
          <div v-if="$can(menu.permissions) && menu.heading" class="hidden lg:block mt-1 font-bold text-xs text-gray-600 px-4 py-2">
            {{ $t(menu.heading) }}
          </div>

          <menu-item-icon
            :href="route(menu.route)"
            :class="{ 'bg-blue-600': isActiveUrl(route(menu.route)) }"
            v-if="$can(menu.permissions) && !menu.sub_menu && !menu.hidden"
          >
            <template #icon v-if="menu.icon">
              <Icons :name="menu.icon" />
            </template>
            {{ trans(menu.lang) }}
          </menu-item-icon>

          <icon-sub-menu v-else-if="$can(menu.permissions) && menu.sub_menu.length" :active="isActiveUrl(menu.children, true)">
            <template #icon v-if="menu.icon">
              <Icons :name="menu.icon" />
            </template>
            {{ trans(menu.lang) }}
            <template #dropdown>
              <div class="py-2">
                <template v-for="sm in menu.sub_menu" :key="sm.name">
                  <menu-item
                    space="less"
                    :hide-icon="true"
                    :href="route(sm.route)"
                    v-if="$can(sm.permissions) && !sm.hidden"
                    :class="{ 'bg-blue-600': isActiveUrl(route(sm.route)) }"
                  >
                    {{ trans(sm.lang) }}
                  </menu-item>
                </template>
              </div>
            </template>
          </icon-sub-menu>
        </template>
      </div>
    </div>

    <div class="py-4">
      <icon-menus class="mx-3 md:mx-0 gap-y-6 md:gap-y-4 flex flex-col items-center justify-center" />
    </div>
  </div>
</template>

<script>
import Menus from '@/Core/menu';
import IconMenus from '@/Shared/Top/IconMenus.vue';
import MenuItem from '@/Shared/Sidebar/MenuItem.vue';
import IconSubMenu from '@/Shared/Sidebar/IconSubMenu.vue';
import MenuItemIcon from '@/Shared/Sidebar/MenuItemIcon.vue';

export default {
  components: { MenuItem, MenuItemIcon, IconSubMenu, IconMenus },
  data() {
    return { menus: Menus };
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
      return url == currentUrl;
    },
  },
};
</script>
