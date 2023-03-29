const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
	if (input.type === 'hidden') return;
	const length = input.value.length;
	input.setSelectionRange(length, length);
})