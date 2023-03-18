const addButtons = document.querySelectorAll('.btn-new');
const removeButtons = document.querySelectorAll('.btn-remove');

addButtons.forEach(button => {
	button.addEventListener('click', () => {
		const collectionHolder = document.querySelector(button.dataset.collection);

		const item = document.createElement('div');
		if (button.dataset.collection !== '#tables') {
			item.classList.add('mb-2');
		}
		item.innerHTML = collectionHolder
				.dataset
				.prototype
				.replace(
						/__name__/g,
						collectionHolder.dataset.index
				);
		item.querySelector('.btn-remove').addEventListener('click', () => item.remove());

		// ajout des events +- sur l'input number pour la gestion des tables
		if (button.dataset.collection === '#tables') {
			const plusBtn = item.querySelector('.number-plus');
			plusBtn.addEventListener('click', () => {
				const input = plusBtn.parentElement.querySelector('input[type=number]');
				input.valueAsNumber += 1;
			})
			const minBtn = item.querySelector('.number-minus');
			minBtn.addEventListener('click', () => {
				const input = minBtn.parentElement.querySelector('input[type=number]');
				if (input.valueAsNumber > input.min) {
					input.valueAsNumber -= 1;
				}
			})
			item.querySelector('input[type=number]').value = 1;
		}

		if (button.dataset.collection === '#tables') {
			document.querySelector('#table-holder').appendChild(item);
		} else {
			collectionHolder.appendChild(item);
		}
		collectionHolder.dataset.index++;
	});
})

removeButtons.forEach(button => {
	button.addEventListener('click', () => {
		const b = button.closest('.form-item');
		b.remove();
	})
})