window.addEventListener("load", (event) => {
	let list = document.querySelector(".list");
	cat_selector.addEventListener("change", (event) => {
		for(let i=0; i< list.querySelectorAll(".item").length; ++i){
			let item = list.querySelectorAll(".item")[i];
			let categories = item.dataset.categories.split(" ");
			for(let j =0; j<categories.length; ++j){
				if(categories[j]==cat_selector.value){
					item.removeAttribute("hidden");
					break;
				}else{
					item.setAttribute("hidden", "true");
				}
			}
		}
	})
})