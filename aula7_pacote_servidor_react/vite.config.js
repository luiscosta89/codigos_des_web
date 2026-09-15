import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  base: './',
  plugins: [react()],
  build: {
    rollupOptions: {
      input: {
        principal: 'index.html',
        exemplos: 'exemplos-react.html',
      },
    },
  },
  test: {
    environment: 'jsdom',
    setupFiles: './tests/setup.js',
  },
});
