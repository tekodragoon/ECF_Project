const addButtons = document.querySelectorAll('.btn-new');
const removeButtons = document.querySelectorAll('.btn-remove');

addButtons.forEach(button => {
	button.addEventListener('click', () => {
		const collectionHolder = document.querySelector(button.dataset.collection);

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
	});
})

removeButtons.forEach(button => {
	button.addEventListener('click', () => {
		const b = button.closest('.form-item');
		b.remove();
	})
})