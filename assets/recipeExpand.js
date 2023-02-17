const expanders = document.querySelectorAll('.expand');

console.log('found ' + expanders.length + ' expanders');

expanders.forEach(expander => {
	expander.addEventListener('click', () => {
		const span = expander.firstElementChild;
		span.innerText = toggleExpand(span.innerText);
		const recipesToToggle = findRecipeSibling(expander.parentElement, 'category-container');
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