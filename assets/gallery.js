const zoom = document.querySelector('#gallery-zoom');
const prevButton = document.querySelector('#prev-button');
const nextButton = document.querySelector('#next-button');
const closeButton = document.querySelector('#zoom-close');
const zoomImage = document.querySelector('#zoom-img');
const zoomInfo = document.querySelector('#zoom-info');
const images = document.querySelectorAll('.gallery');
let currentIndex;
Array.from(images).map((image, index) => {
	image.addEventListener('click', () => {
		if (zoom.classList.contains('hidden')) {
			zoom.classList.remove('hidden');
			currentIndex = index;
			changeImage();
		}
	})
})

prevButton.addEventListener('click', () => {
	currentIndex--;
	if (currentIndex < 0) {
		currentIndex = images.length - 1;
	}
	changeImage();
})

nextButton.addEventListener('click', () => {
	currentIndex++;
	if (currentIndex === images.length) {
		currentIndex = 0;
	}
	changeImage();
})

closeButton.addEventListener('click', () => {
	zoom.classList.add('hidden');
})

const changeImage = () => {
	let image = images[currentIndex];
	zoomImage.src = image.children[0].src;
	zoomInfo.innerText = image.children[1].innerText.trim();
}