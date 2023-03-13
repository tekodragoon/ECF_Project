const expanders = document.querySelectorAll('.expand');

expanders.forEach(expander => {
	expander.addEventListener('click', () => {
		// find google font icon and switch it
		const span = expander.firstElementChild;
		span.innerText = toggleExpand(span.innerText);
		// toggle setting visibility
		const id = expander.id.slice(-1);
		const toSwitchExpand = document.querySelector('#setting-' + id);
		toSwitchExpand.classList.toggle('hidden');
	})
})

// find open/close choices
for (let i = 0; i < 7; i++) {
	// find open/close
	const openCloseToggle = document.querySelector(`#restaurant_openDays_${i}_open`);
	const openCloseElement = document.querySelector(`#open-close-${i}`);
	if (openCloseToggle.value === '0') {
		openCloseElement.classList.add('hidden');
	}
	openCloseToggle.addEventListener('change', () => {
		openCloseElement.classList.toggle('hidden');
	})
	// find noon service
	const noonToggle = document.querySelector(`#restaurant_openDays_${i}_noonService`);
	const noonElement = document.querySelector(`#noon-${i}`);
	if (noonToggle.value === '0') {
		noonElement.classList.add('hidden');
	}
	noonToggle.addEventListener('change', () => {
		noonElement.classList.toggle('hidden');
	})
	// find evening service
	const eveningToggle = document.querySelector(`#restaurant_openDays_${i}_eveningService`);
	const eveningElement = document.querySelector(`#evening-${i}`);
	if (eveningToggle.value === '0') {
		eveningElement.classList.add('hidden');
	}
	eveningToggle.addEventListener('change', () => {
		eveningElement.classList.toggle('hidden');
	})
}

const toggleExpand = (s) => {
	if (s === 'expand_more') return 'expand_less'
	return 'expand_more'
}