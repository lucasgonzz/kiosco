$('.delete-sale').hide();
$('.table tr').hover(function(){
	alert("asdasd");
	let deleteSale = this.children('.delete-sale');
	if (deleteSale.style.display === "none") {
		deleteSale.style.display = "block";
	} else {
		deleteSale.style.display = "none";
	}
});