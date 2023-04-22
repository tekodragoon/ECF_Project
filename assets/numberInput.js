const plusButtons = document.querySelectorAll('.number-plus');
const minusButtons = document.querySelectorAll('.number-minus');
const maxvalue = document.querySelector('.number-maxvalue');

plusButtons.forEach(button => {
	button.addEventListener('click', () => {
		const input = button.parentElement.querySelector('input[type=number]');
		if (maxvalue) {
			if (isNaN(input.valueAsNumber)) {
				input.value = 1;
			} else if (input.valueAsNumber < maxvalue.innerText) {
				input.valueAsNumber += 1;
			}
		} else {
			if (isNaN(input.valueAsNumber)) {
				input.value = 1;
			} else {
				input.valueAsNumber += 1;
			}
		}
		input.addEventListener('change', () => {
			if (maxvalue) {
				if (input.valueAsNumber > maxvalue.innerText) {
					input.value = maxvalue.innerText;
				}
			}
		})
	})
})

minusButtons.forEach(button => {
	button.addEventListener('click', () => {
		const input = button.parentElement.querySelector('input[type=number]');
		if (isNaN(input.valueAsNumber)) {
			input.value = 1;
		} else if (input.valueAsNumber > input.min) {
			input.valueAsNumber -= 1;
		}
	})
})