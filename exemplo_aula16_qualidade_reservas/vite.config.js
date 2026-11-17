import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: './',
  plugins: [react()],
  test: {
    environment: 'jsdom',
    setupFiles: './src/test/setup.js',
    coverage: { reporter: ['text', 'html'], include: ['src/**/*.{js,jsx}'], exclude: ['src/main.jsx'] }
  }
})
