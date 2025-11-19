module.exports = {
  prefix: "tw-",
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.{js,ts,jsx,tsx}"
  ],
  theme: {
    extend: {
      animation: {
        "fade-in": "fade-in 0.5s ease"
      },
      keyframes: {
        "fade-in": {
          "0%": { opacity: "0", transform: "translateY(10px)" },
          "100%": { opacity: "1", transform: "translateY(0)" }
        }
      }
    }
  },
  plugins: [require("tailwindcss-animate")]
};
