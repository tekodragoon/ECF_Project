/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./assets/**/*.js",
		"./templates/**/*.html.twig",
	],
	theme: {
		extend: {
			colors: {
				"primary": "#647295",
				"secondary": "#9F496E",
				"darkBg": "#2B262D",
				"lightBg": "#F2EBE5",
				"textColor": "#110D12",
				"lightText": "#F7F7F7",
				"black": "#0E0B0F",
				"white": "#FAFAFA",
				"link": "#6E81FF",
				"valid": "#63B56B",
				"error": "#FF3426",
				"warning": "#E13426",
			},
		},
	},
	plugins: [],
}
