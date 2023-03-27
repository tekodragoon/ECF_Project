const openInput = document.querySelector('#open-input');
const filename = document.querySelector('#filename');
const imagePreview = document.querySelector('#image-preview');

openInput.addEventListener('click', () => {
	const sibling = openInput.nextElementSibling;
	const input = sibling.querySelector('input');
	//remove event listener in case user change file
	input.removeEventListener('change', inputChange);
	//listen to file change
	input.addEventListener('change', inputChange);
	//click the input
	input.click();
});

function inputChange(e) {
	//show file name
	const file = e.target.files[0];
	filename.innerHTML = file.name;
	//show file preview
	const reader = new FileReader();
	reader.readAsDataURL(file);
	const file_path = (window.URL || window.webkitURL).createObjectURL(file);
	const tmpElem = document.createElement('img');
	tmpElem.classList.add('preview');
	tmpElem.src = file_path;
	imagePreview.innerHTML = '';
	imagePreview.appendChild(tmpElem);
}