import path from 'path';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  base: '/',
  resolve: {
    alias: {
      '@base': path.resolve(__dirname, './'),
      '@lang': path.resolve(__dirname, './lang'),
      '@r': path.resolve(__dirname, './resources'),
      '@': path.resolve(__dirname, './resources/js'),
      'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
    },
  },
  build: {
    outDir: './public',
    emptyOutDir: false,
    sourcemap: process.env.NODE_ENV == 'development',
  },
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      // ssr: 'resources/js/ssr.js',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    VitePWA({
      strategies: 'generateSW', // 'generateSW' | 'injectManifest'
      injectRegister: 'auto',
      registerType: 'prompt',
      manifest: {
        start_url: '.',
        short_name: 'WIMS',
        name: 'Inventory Manager',
        display: 'standalone',
        background_color: '#111827',
        description: 'Warehouse Inventory Management Solution by Tecdiary',
        icons: [
          {
            src: '/48.png',
            type: 'image/png',
            sizes: '48x48',
          },
          {
            src: '/76.png',
            type: 'image/png',
            sizes: '76x76',
          },
          {
            src: '/88.png',
            type: 'image/png',
            sizes: '88x88',
          },
          {
            src: '/100.png',
            type: 'image/png',
            sizes: '100x100',
          },
          {
            src: '/120.png',
            type: 'image/png',
            sizes: '120x120',
          },
          {
            src: '/144.png',
            type: 'image/png',
            sizes: '144x144',
          },
          {
            src: '/152.png',
            type: 'image/png',
            sizes: '152x152',
          },
          {
            src: '/196.png',
            type: 'image/png',
            sizes: '196x196',
          },
          {
            src: '/256.png',
            type: 'image/png',
            sizes: '256x256',
          },
          {
            src: '/512.png',
            type: 'image/png',
            sizes: '512x512',
          },
        ],
      },
    }),
  ],
});
