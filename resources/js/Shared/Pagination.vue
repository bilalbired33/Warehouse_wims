<template>
  <div v-if="meta.total > 0">
    <div class="hidden xl:flex items-center justify-between">
      <div class="mr-4">{{ $t('pagination_info_text', { from: meta.from, to: meta.to, total: meta.total }) }}</div>

      <div v-if="meta.last_page > 1" class="flex flex-wrap -mb-1">
        <template v-for="(link, i) in meta.links" :key="'link_' + i">
          <template v-if="link.url === null">
            <div
              class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 shadow opacity-50 bg-white rounded-md cursor-default"
              v-html="
                link.label.replace('Next', '').replace('Previous', '').replace('&amp;laquo;', '&lang;').replace('&amp;raquo;', '&rang;')
              "
            />
          </template>
          <template v-else>
            <tec-button
              :href="link.url"
              v-html="
                link.label.replace('Next', '').replace('Previous', '').replace('&amp;laquo;', '&lang;').replace('&amp;raquo;', '&rang;')
              "
              :class="link.active ? 'bg-blue-600 border-blue-700 text-white' : 'text-gray-700 bg-white'"
              class="inline-block whitespace-nowrap mr-1 mb-1 px-4 py-3 text-sm leading-4 shadow rounded-md hover:bg-blue-700 hover:text-white hover:border-blue-700 focus:border-blue-600 focus:text-white"
            ></tec-button>
          </template>
        </template>
      </div>
    </div>
    <div class="flex items-center justify-between xl:hidden">
      <tec-button
        :href="links.prev"
        v-if="meta.last_page > 1"
        class="inline-block whitespace-nowrap mr-1 mb-1 px-4 py-3 text-sm leading-4 shadow rounded-md print:hidden"
        :class="
          links.prev
            ? 'text-gray-700 bg-white hover:bg-blue-700 hover:text-white hover:border-blue-700 focus:border-blue-600 focus:text-white'
            : 'text-gray-600 bg-gray-100 cursor-default'
        "
      >
        &Lang; <span class="hidden sm:inline-block">{{ $t('Previous') }}</span>
      </tec-button>
      <div class="mx-3">{{ $t('pagination_info_text', { from: meta.from, to: meta.to, total: meta.total }) }}</div>
      <tec-button
        :href="links.next"
        v-if="meta.last_page > 1"
        class="inline-block whitespace-nowrap mr-1 mb-1 px-4 py-3 text-sm leading-4 shadow rounded-md print:hidden"
        :class="
          links.next
            ? 'text-gray-700 bg-white hover:bg-blue-700 hover:text-white hover:border-blue-700 focus:border-blue-600 focus:text-white'
            : 'text-gray-600 bg-gray-100 cursor-default'
        "
      >
        <span class="hidden sm:inline-block">{{ $t('Next') }}</span> &Rang;
      </tec-button>
    </div>
  </div>
</template>

<script>
import TecButton from '@/Shared/Button.vue';

export default {
  props: ['meta', 'links'],
  components: { TecButton },
  computed: {
    pages() {
      if (this.meta.last_page > 9) {
        const previous = this.meta.links.shift();
        const next = this.meta.links.pop();
        let links = this.meta.links.slice(
          this.meta.current_page > 4 ? this.meta.current_page - 4 : 0,
          this.meta.last_page > this.meta.current_page + 3 ? this.meta.current_page + 3 : this.meta.last_page
        );
        links.unshift(previous);
        links.push(next);
        if (this.meta.current_page > 4) {
          // console.log(this.meta.links[0]);
          links.unshift({ ...this.meta.links[0], label: '&Lang;' });
        }
        if (this.meta.last_page > this.meta.current_page + 3) {
          // console.log(this.meta.links[this.meta.links.length - 1]);
          links.push({ ...this.meta.links[this.meta.links.length - 1], label: '&Rang;' });
        }
        return links;
      }
      return this.meta.links;
    },
  },
};
</script>
