<template>
  <div class="min-h-screen flex flex-col justify-center text-gray-800 items-center py-8 bg-gray-100">
    <div>
      <slot name="logo" />
    </div>

    <div class="w-full sm:max-w-sm mt-6 px-6 py-4 bg-white shadow overflow-hidden sm:rounded-lg">
      <slot />
    </div>
  </div>
</template>
