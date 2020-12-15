const userForm = document.querySelector('.section-one');
const rejectForm = document.querySelector('.section-two');
const destoryForm = document.querySelector('.section-three');
const ApproveForm = document.querySelector('.section-four');
//const sectionviewbag = document.querySelector('.section-view-bag');

const editprofilet = document.querySelector('.edit-profile-info')


if (sessionStorage.length == 0) {
	window.location.replace(`../Login`);
}else
{
	let user = JSON.parse(sessionStorage.getItem('user'));
	
	document.getElementById("name").innerHTML = user.first_name+' '+user.last_name;
	
	switch(user.Category) {
	 
	  case 'bloodbank':
	    // code block
		window.location.replace(`../bank`);
	    break;
	  case 'Screener':
	    // code block
		window.location.replace(`../screen`);
	    break;
	
	}
	
}

const url ='../../v1/dashboard/regulator/'+user.organizationsid;

fetch(url)
  .then(response => response.json())
  .then(function(data) {
	  document.getElementById("bag").innerHTML = data[0][0];
	  document.getElementById("awaiting").innerHTML = data[0][1];
	  document.getElementById("destroyed").innerHTML = data[0][2];

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
	

		let response = await fetch('../../v1/bag/request/screening', {
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
			userForm.style.display = 'none';
			alert(result.description);
		}else{
		
			alert('There was an issues creating the request. please try again later');
		}
	}else{
		alert('You don\'t have the privilege add a user, please talk to your supervisor');
	}
}

async function rejectBag(){
	var rejectID = document.getElementById('rejectID').value;
	var Reason = document.getElementById('rejectReason').value;
	var createid = user.id;
	
	let data = {
	  rejectid: rejectID,
	  Reason: Reason,
	  createby: createid
	};
	
	let response = await fetch('../../v1/bagaction/reject', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json;charset=utf-8'
	  },
	  body: JSON.stringify(data)
	});
	
	let result = await response.json();
	
	if(result.ok == true){
		 //rejectForm.classList.toggle('hide');
		 rejectForm.style.display = 'none';
		alert(result.description);
	}else{
	
		alert('There was an issues creating the request. please try again later');
	}
}

async function DestroyBag(){
	var rejectID = document.getElementById('destroytID').value;
	var Reason = document.getElementById('destroyReason').value;
	var proof = document.getElementById('proof');
	var createid = user.id;
	
	let data = {
	  rejectid: rejectID,
	  Reason: Reason,
	  createby: createid
	};
	
	let response = await fetch('../../v1/bagaction/destroy', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json;charset=utf-8'
	  },
	  body: JSON.stringify(data)
	});
	
	let result = await response.json();
	
	if(result.ok == true){
		 destoryForm.style.display = 'none';
		alert(result.description);
	}else{
	
		alert('There was an issues creating the request. please try again later');
	}
}

async function addAppoveral(){
	var bagID = document.getElementById('TestID').value;
	
	var createid = user.id;
	
	let data = {
	  bagid: bagID,
	  createby: createid
	};
	
	let response = await fetch('../../v1/bagaction/approved', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json;charset=utf-8'
	  },
	  body: JSON.stringify(data)
	});
	
	let result = await response.json();
	
	if(result.ok == true){
		ApproveForm.style.display = 'none';
		alert(result.description);
	}else{
	
		alert('There was an issues creating the request. please try again later');
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
		 return '<p style="color:red;">Positive</p>';
		//return 'Positive';
	}
}

function rhesus(c) {
	if(c == '-'){
		return '-Ve';
	}else{
		return '+Ve';
	}
}