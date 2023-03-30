const menuToogle = document.querySelector('#menu-toggle');
const navlinks = document.querySelectorAll('.navlink');

menuToogle.addEventListener('click', () => {
	navlinks.forEach((navlink) => {
		navlink.classList.toggle('navlink')
		navlink.classList.toggle('navlink-show')
	})
});