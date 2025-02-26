<template>
  <div class="flex items-start max-h-8 max-w-xs overflow-hidden">
    <div class="flex">
      <tec-application-mark class="h-8 w-8 mr-3" />
      <h1 class="text-2xl font-bold">{{ $page.props.settings.name || 'Jet Start' }}</h1>
    </div>
  </div>
</template>

<script>
import TecApplicationMark from '@/Jetstream/ApplicationMark.vue';

export default {
  components: { TecApplicationMark },
};
</script>
