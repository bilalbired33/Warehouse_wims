<template>
  <div class="px-4">
    <!-- <a
      target="_blank"
      v-if="$page.props.demo"
      href="https://tecdiary.net/products/warehouse-inventory-management-solution-wims"
      class="
        flex
        items-center
        justify-center
        p-2
        bg-blue-600
        text-white
        hover:text-white
        hover:bg-blue-700
        rounded
        font-bold
        focus:outline-none
      "
    >
      <icons name="cart" class="lg:mr-2"></icons>
      <span class="hidden lg:inline"> Buy Now</span>
    </a> -->
    <Link
      :href="route('settings')"
      v-if="$page.props.auth.user && $page.props.auth.user.roles.find(r => r.name == 'Super Admin')"
      class="inline-flex items-center justify-center p-2 bg-white text-gray-700 hover:text-white hover:bg-blue-700 rounded font-bold"
    >
      <icons name="cog"></icons>
    </Link>
    <button
      @click="alerts = true"
      class="inline-flex items-center justify-center p-2 bg-white text-gray-700 hover:text-white hover:bg-blue-700 rounded font-bold focus:outline-none"
    >
      <icons name="bell"></icons>
    </button>
    <alerts-modal :show="alerts" @close="alerts = false" />
    <!-- <a
      href="#"
      class="inline-flex items-center justify-center p-2 bg-white text-gray-700 hover:text-white hover:bg-blue-700 rounded font-bold"
    >
      <icons name="menu"></icons>
    </a> -->
  </div>
</template>

<script>
import AlertsModal from './AlertsModal.vue';

export default {
  components: { AlertsModal },

  data() {
    return { alerts: false, scaled: false };
  },

  // methods: {
  //   zoom() {
  //     if (this.scaled) {
  //       this.scaled = false;
  //       document.body.removeAttribute('style');
  //     } else {
  //       this.scaled = true;
  //       document.body.style.zoom = 1.1;
  //     }
  //     // document.body.classList.add('transform');
  //     // document.body.classList.add('origin-top-left');
  //     // if (document.body.classList.contains('lg:scale-110')) {
  //     //   document.body.classList.add('lg:scale-110');
  //     // } else {
  //     //   document.body.classList.add('lg:scale-110');
  //     // }
  //   },
  // },
};
</script>
