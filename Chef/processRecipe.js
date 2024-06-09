$(document).ready(function(){
	$('#addbutton').on("click", function(){
		
		let ingredient= $('#ingredientsHidden').children(":first").clone(true).removeAttr('hidden');
		$('#ingredientlist').append(ingredient);
	});

	$('.remove').on("click", function(){
		console.log($(this).parent());
		$(this).parent().remove(); 
	});
});