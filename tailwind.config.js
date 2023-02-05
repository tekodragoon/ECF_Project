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
				"secondary": "#BF9969",
				"textColor": "#402A22",
				"lightText": "#F7F7F7",
				"darkBg": "#494738",
				"lightBg": "#FCFAEA",
				"black": "#060316",
				"white": "#FAFAFA",
				"link": "#0DAAB4",
				"valid": "#63B56B",
				"error": "#DA3939",
				"warning": "#EB9B22",
			},
		},
	},
	plugins: [],
}
