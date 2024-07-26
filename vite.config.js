import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname,'./src')
    }
  },
  build:
    {
      cssCodeSplit: false,
      lib: {
        entry: 'src/widgets.js',
        name: 'WidgetComponents',
        formats: ['umd'],
        fileName: 'widgets-loader',
      },
      rollupOptions: {
        external: ['vue'],
        output: {
          globals: {
            vue: 'Vue'
          }
        }
      }
    }
});