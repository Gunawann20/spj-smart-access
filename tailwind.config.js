/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'primary-blue': '#1e3a8a',
        'light-blue': '#3b82f6',
        'gold': '#f59e0b',
        'dark-blue': '#0f172a',
      }
    },
  },
  plugins: [],
}
