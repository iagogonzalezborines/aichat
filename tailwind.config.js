/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#77bfa3", // jade verde claro
          dark: "#4f8d73",   // variante oscura
        },
        secondary: {
          DEFAULT: "#264653", // verde azulado oscuro
          dark: "#1b313a",    // variante más oscura
        },
        background: {
          DEFAULT: "#1a1a1a", // gris oscuro para fondos
          light: "#2a2a2a",   // variante más clara
        },
        text: {
          DEFAULT: "#ffffff", // siempre blanco
        },
      },
    },
  },
  plugins: [],
}
