/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#1B3A5C',
          light: '#2A5A8C',
          dark: '#0F2440',
          50: '#EEF2F7',
          100: '#D5DEE9',
          200: '#ADBDD3',
          300: '#859CBD',
          400: '#5D7BA7',
          500: '#1B3A5C',
          600: '#163050',
          700: '#112744',
          800: '#0F2440',
          900: '#0A1930',
        },
        accent: {
          DEFAULT: '#3B82F6',
          light: '#60A5FA',
        },
        'accent-gold': {
          DEFAULT: '#F5B942',
          light: '#FBD98C',
          dark: '#D99A1F',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
      boxShadow: {
        'card': '0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06)',
        'card-hover': '0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)',
      },
      width: {
        'sidebar': '260px',
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.5s ease',
        'slide-down': 'slideDown 0.3s ease',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(16px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideDown: {
          '0%': { opacity: '0', transform: 'translateY(-8px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [],
}
