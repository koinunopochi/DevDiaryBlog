/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'night-sky': '#080808',
        moonlight: '#CCCCCC',
        starlight: '#E0E0E0',
        'cosmic-blue': '#1E3A8A',
      },
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
  safelist: [
    {
      pattern: /bg-\[#([0-9a-fA-F]{6})\]/,
      variants: ['hover'],
    },
    {
      pattern: /text-\[#([0-9a-fA-F]{6})\]/,
      variants: ['hover'],
    },
    {
      pattern: /border-\[#([0-9a-fA-F]{6})\]/,
      variants: ['hover'],
    },
  ],
};
