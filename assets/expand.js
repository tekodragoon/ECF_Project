const expanders = document.querySelectorAll('.expand');

expanders.forEach(expander => {
	expander.addEventListener('click', () => {
		// find google font icon and switch it
		const span = expander.firstElementChild;
		span.innerText = toggleExpand(span.innerText);

		// find all next sibling element to hide/show
		const recipesToToggle = findRecipeSibling(expander.parentElement, 'no-expand');
		recipesToToggle.forEach(r => {
			r.classList.toggle('hidden');
		})
	})
})

const toggleExpand = (s) => {
	if (s === 'expand_more') return 'expand_less'
	return 'expand_more'
}

const findRecipeSibling = (element, condition) => {
	let siblings = [];
	let sibling = element.nextElementSibling;
	// iterate until it find nothing or condition class stop
	while (sibling) {
		siblings.push(sibling);
		sibling = sibling.nextElementSibling;
		if (!sibling) {
			break;
		}
		if (sibling.classList.contains(condition)) {
			break;
		}
	}
	return siblings;
}