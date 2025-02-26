<template>
  <div :class="className ? className : 'h-4 w-4'">
    <svg v-if="name == 'link'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
      />
    </svg>
    <svg v-else-if="name == 'in'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'out'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'zoom-in'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M5 8a1 1 0 011-1h1V6a1 1 0 012 0v1h1a1 1 0 110 2H9v1a1 1 0 11-2 0V9H6a1 1 0 01-1-1z" />
      <path
        fill-rule="evenodd"
        d="M2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8zm6-4a4 4 0 100 8 4 4 0 000-8z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'zoom-out'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
        clip-rule="evenodd"
      />
      <path fill-rule="evenodd" d="M5 8a1 1 0 011-1h4a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
    </svg>
    <svg v-else-if="name == 'home'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
      />
    </svg>
    <svg v-else-if="name == 'list'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'cog'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
      />
    </svg>
    <svg v-else-if="name == 'plus'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
    </svg>
    <svg v-else-if="name == 'cart'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
      />
    </svg>
    <svg v-else-if="name == 'bag'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
      />
    </svg>
    <svg v-else-if="name == 'user'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
    </svg>
    <svg v-else-if="name == 'users'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
      />
    </svg>
    <svg v-else-if="name == 'view'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
      <path
        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
      />
    </svg>
    <svg v-else-if="name == 'edit'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
      <path d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
    </svg>
    <svg v-else-if="name == 'trash'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
      />
    </svg>
    <svg v-else-if="name == 'cross'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
      />
    </svg>
    <svg v-else-if="name == 'search'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
    </svg>
    <svg v-else-if="name == 'printer'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'refresh'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'menu'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
      />
    </svg>
    <svg v-else-if="name == 'adjustments'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
      />
    </svg>
    <svg v-else-if="name == 'up'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'down'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'toggle'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"
      />
    </svg>
    <svg v-else-if="name == 'truck'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
      <path
        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"
      />
    </svg>
    <svg v-else-if="name == 'email'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
      <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
    </svg>
    <svg v-else-if="name == 'info'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'folder'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
    </svg>
    <svg v-else-if="name == 'open-folder'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" />
      <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
    </svg>
    <svg v-else-if="name == 'chevron-down'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
    </svg>
    <svg v-else-if="name == 'chart-bar'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"
      />
    </svg>
    <svg v-else-if="name == 'chart-pie'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
      <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
    </svg>
    <svg v-else-if="name == 'bell'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
      />
    </svg>
    <svg v-else-if="name == 'heart'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'group'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
      />
    </svg>
    <svg v-else-if="name == 'collection'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
      />
    </svg>
    <svg v-else-if="name == 'chip'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M13 7H7v6h6V7z" />
      <path
        fill-rule="evenodd"
        d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'building'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'plane'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"
      />
    </svg>
    <svg v-else-if="name == 'upload'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
      />
    </svg>
    <svg v-else-if="name == 'clip'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
      />
    </svg>
    <svg v-else-if="name == 'doc'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
    </svg>
    <svg v-else-if="name == 'doc-text'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
      />
    </svg>
    <svg v-else-if="name == 'tick'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
    </svg>
    <svg v-else-if="name == 'mobile'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" />
    </svg>
    <svg v-else-if="name == 'at'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z"
        clip-rule="evenodd"
      />
    </svg>
    <svg v-else-if="name == 'otp'" class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 448">
      <path d="m128 416c0 4.417969-3.582031 8-8 8s-8-3.582031-8-8 3.582031-8 8-8 8 3.582031 8 8zm0 0" />
      <path
        d="m240 224h-144v-128h144v-72c0-13.253906-10.746094-24-24-24h-16v8c0 13.253906-10.746094 24-24 24h-112c-13.253906 0-24-10.746094-24-24v-8h-16c-13.253906 0-24 10.746094-24 24v400c0 13.253906 10.746094 24 24 24h192c13.253906 0 24-10.746094 24-24zm-120 216c-13.253906 0-24-10.746094-24-24s10.746094-24 24-24 24 10.746094 24 24-10.746094 24-24 24zm104-56h-208v-16h208zm0 0"
      />
      <path d="m64 16h112c4.417969 0 8-3.582031 8-8v-8h-128v8c0 4.417969 3.582031 8 8 8zm0 0" />
      <path
        d="m448 112h-336v96h336zm-256.289062 57.070312-8 13.859376-15.710938-9.074219v18.144531h-16v-18.144531l-15.710938 9.074219-8-13.859376 15.710938-9.070312-15.710938-9.070312 8-13.859376 15.710938 9.074219v-18.144531h16v18.144531l15.710938-9.074219 8 13.859376-15.710938 9.070312zm80 0-8 13.859376-15.710938-9.074219v18.144531h-16v-18.144531l-15.710938 9.074219-8-13.859376 15.710938-9.070312-15.710938-9.070312 8-13.859376 15.710938 9.074219v-18.144531h16v18.144531l15.710938-9.074219 8 13.859376-15.710938 9.070312zm80 0-8 13.859376-15.710938-9.074219v18.144531h-16v-18.144531l-15.710938 9.074219-8-13.859376 15.710938-9.070312-15.710938-9.070312 8-13.859376 15.710938 9.074219v-18.144531h16v18.144531l15.710938-9.074219 8 13.859376-15.710938 9.070312zm80 0-8 13.859376-15.710938-9.074219v18.144531h-16v-18.144531l-15.710938 9.074219-8-13.859376 15.710938-9.070312-15.710938-9.070312 8-13.859376 15.710938 9.074219v-18.144531h16v18.144531l15.710938-9.074219 8 13.859376-15.710938 9.070312zm0 0"
      />
    </svg>
    <svg v-else class="w-full h-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
      <path
        d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"
      />
    </svg>
  </div>
</template>

<script>
export default {
  props: ['name', 'className'],
};
</script>
