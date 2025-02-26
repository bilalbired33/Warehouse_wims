<template>
  <Head :title="title" />
  <div class="bg-gray-100 min-h-screen print:bg-white">
    <div class="md:flex md:flex-col">
      <div class="md:h-screen md:flex md:flex-col">
        <top-bar
          class="print:hidden"
          @on-mobile-menu-change="v => (show = v)"
          :class="{ 'md:fixed md:top-0 w-full z-20': $page.props.settings.sidebar == 'mini' }"
        />
        <div class="md:flex md:grow md:items-stretch" :class="$page.props.settings.sidebar != 'mini' ? 'overflow-hidden' : 'pt'">
          <mini-main-menu v-if="$page.props.settings.sidebar == 'mini'" class="hidden md:grid print:hidden ondark" />
          <full-main-menu v-else class="hidden md:grid print:hidden ondark" />
          <transition name="slide-fade">
            <full-main-menu v-if="show" class="block md:hidden print:hidden" />
          </transition>
          <div
            class="md:flex-1 md:px-4 py-8 md:p-8 overflow-x-hidden md:overflow-y-auto print:m-0 print:p-0 print:overflow-visible"
            scroll-region
          >
            <flash-messages class="print:hidden"></flash-messages>
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TopBar from '@/Shared/Top/Navbar.vue';
import FlashMessages from '@/Shared/FlashMessages.vue';
import FullMainMenu from '@/Shared/Sidebar/FullMainMenu.vue';
import MiniMainMenu from '@/Shared/Sidebar/MiniMainMenu.vue';

export default {
  props: ['title'],

  components: {
    TopBar,
    FullMainMenu,
    MiniMainMenu,
    FlashMessages,
  },

  // watch: {
  //   title: {
  //     immediate: true,
  //     handler(title) {
  //       document.title = (title ? title + ' • ' : '') + this.$settings.name;
  //     },
  //   },
  // },

  // created() {
  //   this.$root.$i18n.locale(this.$page.props.language);
  // },

  data() {
    return { show: false };
  },
};
</script>
