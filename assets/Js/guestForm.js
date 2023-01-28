import {addFormElement, removeItem} from "./addFormElement";

document.querySelectorAll('.btn-new').forEach(
		btn => btn.addEventListener('click', addFormElement)
);
document.querySelectorAll('.btn-remove').forEach(
		btn => btn.addEventListener('click', removeItem)
);