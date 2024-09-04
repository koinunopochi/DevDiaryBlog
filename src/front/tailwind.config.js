/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    extend: {
      keyframes: {
        ripple: {
          '0%': { transform: 'scale(0)', opacity: 0.5 },
          '100%': { transform: 'scale(4)', opacity: 0 },
        },
      },
      animation: {
        ripple: 'ripple 1s ease-out',
      },
    },
  },
  plugins: [],
};
