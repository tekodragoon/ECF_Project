const categories = document.querySelectorAll('.cat');
const items = [];

categories.forEach((category, index) => {
	let item = {
		index: index,
		title: category.innerText,
		formId: `category_order_group_categories_${index}_listOrder`,
	}
	items.push(item);
})

const displayItems = () => {
	console.log('display items');
	const parent = document.querySelector('.drag-ul');
	parent.innerHTML = '';
	items.forEach(item => {

		const li = document.createElement('li');
		li.classList.add('drag-li');
		li.setAttribute('draggable', 'true');

		const text = document.createElement('div');
		text.innerText = item.title;

		const dragHandle = document.createElement("span");
		dragHandle.innerText = "menu";
		dragHandle.classList.add('material-symbols-outlined');

		li.addEventListener('dragstart', (ev) => {
			ev.dataTransfer.setData('index', item.index);
			li.style.opacity = '0.4';
		})

		li.addEventListener('dragenter', () => {
			li.classList.add('drag-over');
		})

		li.addEventListener('dragleave', () => {
			li.classList.remove('drag-over');
		})

		li.addEventListener('drop', (ev) => {
			const dragIndex = ev.dataTransfer.getData('index');
			const dropIndex = item.index;
			swap(dragIndex, dropIndex);
			li.setAttribute('draggable', 'false');
		})

		li.addEventListener('dragover', (ev) => {
			ev.preventDefault();
		})

		li.appendChild(text);
		li.appendChild(dragHandle);
		parent.appendChild(li);
	})
}

const swap = (dragIndex, dropIndex) => {
	const dragged = items[dragIndex];
	const drop = items[dropIndex];

	items[dragIndex] = drop;
	items[dropIndex] = dragged;

	dragged.index = dropIndex;
	drop.index = dragIndex;

	const dragForm = document.querySelector(`#${dragged.formId}`);
	const dropForm = document.querySelector(`#${drop.formId}`);

	dragForm.value = Number(dragged.index) + 1;
	dropForm.value = Number(drop.index) + 1;

	displayItems();
}

displayItems();