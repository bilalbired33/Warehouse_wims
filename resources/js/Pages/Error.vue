<template>
  <div class="h-screen w-full flex items-center justify-center bg-gray-200">
    <div class="shadow rounded-md max-w-md w-full">
      <div class="p-4 text-red-600 rounded-t-md bg-gray-50">
        <h1 class="text-2xl font-bold mb-2">{{ title }}</h1>
        <div>{{ description }}</div>
      </div>
      <div class="p-4 border-t flex items-center justify-between bg-gray-100 rounded-b-md">
        <button class="bg-gray-50 py-2 px-3 rounded-md shadow text-gray-700 hover:bg-white">Back</button>
        <button class="bg-gray-50 py-2 px-3 rounded-md shadow text-gray-700 hover:bg-white">Home</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    status: Number,
  },
  computed: {
    title() {
      return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
      }[this.status];
    },
    description() {
      return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
      }[this.status];
    },
  },
};
</script>
