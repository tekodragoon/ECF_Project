const hourButtons = document.querySelectorAll('.btn-hour');
const nextButton = document.querySelector('.btn-next');
const hourInputSelect = document.querySelector('#basic_booking_hour_hour');
const minInputSelect = document.querySelector('#basic_booking_hour_minute');
let selectedHour = null;

hourButtons.forEach((button) => {
	button.addEventListener('click', () => {
		button.classList.add('btn-select');
		if (selectedHour) {
			selectedHour.classList.remove('btn-select');
		}
		selectedHour = button;
		nextButton.disabled = false;
		const [hour, min] = button.innerText.split(':');
		hourInputSelect.value = hour;
		minInputSelect.value = min === '00' ? '0' : min;
	});
});