/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./assets/**/*.js",
		"./templates/**/*.html.twig",
	],
	theme: {
		extend: {
			colors: {
				"primary": "#A66D58",
				"secondary": "#81A668",
				"textColor": "#261514",
				"lightText": "#F7F7F7",
				"darkBg": "#15202B",
				"lightBg": "#FCFAEA",
				"black": "#060316",
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
