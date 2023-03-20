const flagButton = document.querySelector('#cur-flag');
const enFlag = document.querySelector('#en-flag');
const frFlag = document.querySelector('#fr-flag');

flagButton.addEventListener('click', () => {
	enFlag.classList.remove('hidden');
	frFlag.classList.remove('hidden');
	flagButton.classList.add('hidden');
})