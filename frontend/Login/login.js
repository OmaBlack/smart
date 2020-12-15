// For Login

 async function validate(){
	var org = document.getElementById('org').value;
	var loginEmail = document.getElementById('email').value;
	var loginPassword = document.getElementById('pwd').value;
	
	if ( org  == "" || loginEmail == "" || loginPassword == ""){
	alert ("all field are requried");
	return false;
	}
	
	let user = {
	  org: org,
	  email: loginEmail,
	  pwd: loginPassword
	};
	
	let response = await fetch('../../v1/login/', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json;charset=utf-8'
	  },
	  body: JSON.stringify(user)
	});
	
	let result = await response.json();
	
	if(result.ok == true){
		let space = result.description.Category;
		
		sessionStorage.setItem('user', JSON.stringify(result.description));
		
		switch(space) {
		  case 'bloodbank':
		    // code block
			window.location.replace(`../bank`);
		    break;
		  case 'Screener':
		    // code block
			window.location.replace(`../screen`);
		    break;
		  case 'Regulator':
  		    // code block
			window.location.replace(`../regulator`);
  		    break;
		}
		
	}else{
		alert(result.description);
	}
	
}

	
