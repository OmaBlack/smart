//form ids 
const addUserform = document.querySelector('.section-one');
const requestBagform = document.querySelector('.request');
const requestScreeningform = document.querySelector('.request-transfers');
const requestTransferform = document.querySelector('.request-screening-trans');
const editprofilet = document.querySelector('.edit-profile-info')




if (sessionStorage.length == 0) {
	window.location.replace(`../Login`);
}else
{
	user = JSON.parse(sessionStorage.getItem('user'));
	
	document.getElementById("name").innerHTML = user.first_name+' '+user.last_name;
	
	switch(user.Category) {
	 
	  case 'Screener':
	    // code block
		window.location.replace(`../screen`);
	    break;
	  case 'Regulator':
	    // code block
		window.location.replace(`../regulator`);
	    break;
	
	}
	
}

const url ='../../v1/dashboard/bank/'+user.organizationsid;

fetch(url)
  .then(response => response.json())
  .then(function(data) {
	  document.getElementById("Unused").innerHTML = data[0][0];
	  document.getElementById("Awaiting").innerHTML = data[0][1];
	  document.getElementById("Destroyed").innerHTML = data[0][2];
	  document.getElementById("Expired").innerHTML = data[0][3];
	  document.getElementById("Safe").innerHTML = data[0][4];
	  document.getElementById("Unsafe").innerHTML = data[0][5];
});


// For request bags

 async function requestBag(){
	var bagrequest = document.getElementById('bagrequest').value;
	var id = user.id;
	var orgid = user.organizationsid;
	var orgprivilege = user.privilege;
	
	let data = {
	  number: bagrequest,
	  user: id,
	  org: orgid
	};
	
	if(orgprivilege == 'Supervisor'){
		let response = await fetch('../../v1/bag/request', {
		  method: 'POST',
		  headers: {
		    'Content-Type': 'application/json;charset=utf-8'
		  },
		  body: JSON.stringify(data)
		});
		
		let result = await response.json();
		
		if(result.ok == true){
			
			requestBagform.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}
		
	}else{
		alert('You don\'t have the privilege to request bags, please talk to your supervisor');
	}
	
	
}

 async function RequestScreening(){
	var BagID = document.getElementById('BagID').value;
	var DonorID = document.getElementById('DonorID').value;
	var screening = document.getElementById('screening').value;
	var id = user.id;
	var orgid = user.organizationsid;
	
	let data = {
	  bagid: BagID ,
	  donorid: DonorID,
	  screener: screening
	};
	

		let response = await fetch('../../v1/bagaction/request/screening', {
		  method: 'POST',
		  headers: {
		    'Content-Type': 'application/json;charset=utf-8'
		  },
		  body: JSON.stringify(data)
		});
		
		let result = await response.json();
		
		if(result.ok == true){
			requestScreeningform.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}

}


async function addUser(){
	
	var firstname = document.getElementById('fname').value;
	var lastname = document.getElementById('lname').value;
	var phone = document.getElementById('phone').value;
	var email = document.getElementById('email').value;
	var privilege = document.getElementById('privilege').value;
	var orgid = user.organizationsid;
	var createid = user.id;
	
	
	let data = {
	  firstname: firstname,
	  lastname: lastname,
	  phone: phone,
	  email: email,
	  privilege: privilege,
	  org: orgid,
	  createby: createid
	};
	
	if(user.privilege == 'Supervisor'){
		let response = await fetch('../../v1/login/add/user', {
		  method: 'POST',
		  headers: {
		    'Content-Type': 'application/json;charset=utf-8'
		  },
		  body: JSON.stringify(data)
		});
		
		let result = await response.json();
		
		if(result.ok == true){
			
			addUserform.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}
	}else{
		alert('You don\'t have the privilege add a user, please talk to your supervisor');
	}
}


async function RequestTransfer(){
	var BagtransID = document.getElementById('BagtransID').value;
	var bbid = document.getElementById('bbid').value;
	var orgid = user.organizationsid;
	var createid = user.id;
	
	let data = {
	  BagtransID: BagtransID,
	  bbid: bbid,
	  orgid: orgid,
	  createid: createid
	};
	
	if(user.privilege == 'Supervisor'){
		let response = await fetch('../../v1/bag/transfer', {
		  method: 'POST',
		  headers: {
		    'Content-Type': 'application/json;charset=utf-8'
		  },
		  body: JSON.stringify(data)
		});
		
		let result = await response.json();
		
		if(result.ok == true){
			requestTransferform.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}
	}else{
		alert('You don\'t have the privilege add a user, please talk to your supervisor');
	}
}

async function editUser(){
	var phone = document.getElementById('editphone').value;
	var lname = document.getElementById('editlname').value;
	var fname = document.getElementById('editfname').value;
	
	var orgid = user.organizationsid;
	var createid = user.id;
	
	let data = {
	  fname: fname,
	  lname: lname,
	  phone: phone,
	  createid: createid
	};
		let response = await fetch('../../v1/login/edit/user', {
		  method: 'POST',
		  headers: {
		    'Content-Type': 'application/json;charset=utf-8'
		  },
		  body: JSON.stringify(data)
		});
		
		let result = await response.json();
		
		if(result.ok == true){
			editprofilet.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}
	
}

function valuer(c) {
	if(c == '0'){
		return 'Negative';
	}else{
		return 'Positive';
	}
}

function rhesus(c) {
	if(c == '-'){
		return '-Ve';
	}else{
		return '+Ve';
	}
}

