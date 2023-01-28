export const addFormElement = (e) => {
	const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);

	const item = document.createElement('div');
	item.classList.add('mb-2');
	item.innerHTML = collectionHolder
			.dataset
			.prototype
			.replace(
					/__name__/g,
					collectionHolder.dataset.index
			);
	item.querySelector('.btn-remove').addEventListener('click', () => item.remove());
	collectionHolder.appendChild(item);
	collectionHolder.dataset.index++;
}

export const removeItem = (e) => {
	e.currentTarget.closest('.item').remove();
}