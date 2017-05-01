/**
* Function that sends user entered data
* to server for validation before continuing
* on with the next page in the account creation
* process.
* @author Eva Kuntz
**/
function createAccount(){
	var info = {request_type:1,name:document.getElementById("name").value, age:document.getElementById("age").value,
		email:document.getElementById("email").value, password:document.getElementById("password").value,
		passwordConfirm:document.getElementById("passwordConfrim").value};

		document.getElementById("nameError").innerHTML = "";
		document.getElementById("emailError").innerHTML = "";
		document.getElementById("passError").innerHTML = "";
		document.getElementById("passError2").innerHTML = "";
		
		//AJAX call to server; validate user sign up information
		$.ajax({
			type: 'POST',
			url: 'createUserAccount.php',
			dataType: 'json',
			data: info,
			success: function(data){
				console.log(data);
				if(!data['success']){
					//display errors
					document.getElementById("nameError").innerHTML = data['nameError'];
					document.getElementById("emailError").innerHTML = data['emailError'];
					document.getElementById("passError").innerHTML = data['passError'];
					document.getElementById("passError2").innerHTML = data['passError'];
				}else{
					//redirect to new page
					window.location.href = "createUserProfile.html";
				}
			},
			error: function(data){
				console.log(data);
			}
		});
}


/**
* Function that validates the information
* and notifies user of any errors
* so the information can be fixed
* BEFORE it goes to the server and is
* entered into the database.
* @author Eva Kuntz
**/
function validateInformation(){
	document.getElementById("musicError").innerHTML = "";
	document.getElementById("foodError").innerHTML = "";
	document.getElementById("cafeError").innerHTML = "";
	document.getElementById("restaurantError").innerHTML = "";
	document.getElementById("arcadeError").innerHTML = "";
	document.getElementById("concertsError").innerHTML = "";
	var errorCount = 0;
	var cafe = aracade = restaurant = concerts = "";

	var music_results = get_select_values(document.getElementById("music"));
	if(music_results == 1){
		document.getElementById("musicError").innerHTML = "You may only select up to three values.";
		errorCount++;
	}
	else if(music_results == 2){
		document.getElementById("musicError").innerHTML = "You must select at least one value.";
		errorCount++;
	}

	var food_results = get_select_values(document.getElementById("food"));
	if(food_results == 1){
		document.getElementById("foodError").innerHTML = "You may only select up to three values.";
		errorCount++;
	}
	else if(food_results == 2){
		document.getElementById("foodError").innerHTML = "You must select at least one value.";
		errorCount++;
	}

	//cafe validation
	if(document.getElementById("cafeYES").checked){
		cafe = document.getElementById("cafeYES").value;
	}
	else if(document.getElementById("cafeNO").checked){
		cafe = document.getElementById("cafeNO").value;
	}
	else{
		errorCount++;
		document.getElementById("cafeError").innerHTML = "Please select 'yes' or 'no'.";	
	}

	//arcade validation
	if(document.getElementById("arcadeYES").checked){
		arcade = document.getElementById("arcadeYES").value;
	}
	else if(document.getElementById("arcadeNO").checked){
		arcade = document.getElementById("arcadeNO").value;
	}
	else{
		errorCount++;
		document.getElementById("arcadeError").innerHTML = "Please select 'yes' or 'no'.";	
	}

	//restaurant validation
	if(document.getElementById("sitdown").checked){
		restaurant = document.getElementById("sitdown").value;
	}
	else if(document.getElementById("fastfood").checked){
		restaurant = document.getElementById("fastfood").value;
	}
	else{
		errorCount++;
		document.getElementById("restaurantError").innerHTML = "Please select one.";	
	}

	//concerts validation
	if(document.getElementById("concertsYES").checked){
		concerts = document.getElementById("concertsYES").value;
	}
	else if(document.getElementById("concertsNO").checked){
		concerts = document.getElementById("concertsNO").value;
	}
	else{
		errorCount++;
		document.getElementById("concertsError").innerHTML = "Please select 'yes' or 'no'.";	
	}



	if(errorCount == 0){
		//call other function here to make AJAX call
		var info = {request_type:2,music:music_results,food:food_results,cafe:cafe,arcade:arcade,restaurant:restaurant,concert:concerts};
		return info;
	}

	return 1;


}

/**
* Function that iterates through the options
* of a given multiple select element and
* puts all of the 'selected' options into an
* array.
* Either returns an array of the selected values
* or an integer that denotes what kind of error
* occured.
* @author Eva Kuntz
**/
function get_select_values(selectElement){
	var select_results = []; //result array
	var options = selectElement.selectedOptions;

	for(var i = 0; i < options.length; i++){
		if(options[i].selected){
			select_results[i] = options[i].value;		
		}
	}
	
	//more than 3 selected, return 1 as notification of error
	if(select_results.length > 3){
		return 1;
	}
	else if(select_results.length == 0){ //if no options selected, return 2 as notification of error
		return 2;
	}
	return select_results; //else return the array of options selected
}


function createUserAccount(){
	var info = validateInformation();
	if(info != 1){
		//AJAX CALL HERE
		$.ajax({
			type: 'POST',
			url: 'createUserAccount.php',
			dataType: 'json',
			data: info,
			success: function(data){
				console.log(data);
				if(data["success"]){
					alert("Your account was created successfully. You will now be redirected to the login page.");
					window.location.href = "login.html";
				}
			},
			error: function(data){
				console.log(data);
			}
		});//end of AJAX call
	}

	//else, do nothing and wait for user to correct their errors
}