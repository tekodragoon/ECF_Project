const plusButtons = document.querySelectorAll('.number-plus');
const minusButtons = document.querySelectorAll('.number-minus');

plusButtons.forEach(button => {
	button.addEventListener('click', () => {
		const input = button.parentElement.querySelector('input[type=number]');
		input.valueAsNumber += 1;
	})
})

minusButtons.forEach(button => {
	button.addEventListener('click', () => {
		const input = button.parentElement.querySelector('input[type=number]');
		if (input.valueAsNumber > input.min) {
			input.valueAsNumber -= 1;
		}
	})
})