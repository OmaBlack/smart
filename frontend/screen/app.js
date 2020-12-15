const users = document.getElementById('users');
const form = document.querySelector('.section-one');
const sectiontwoform = document.querySelector('.section-two');
const sectionthreeform = document.querySelector('.section-three');
const sectionfourform = document.querySelector('.section-four');
const sectionviewbag = document.querySelector('.section-view-bag');
const editprofile = document.querySelector('.edit-profile-info')

users.addEventListener('click', toggleForm);

function toggleForm() {
  if (!users.classList.contains('show')) {
    form.classList.toggle('show');
  } 
}

function viewReject(vara) {
	document.getElementById("rejectID").value = vara;
    sectiontwoform.classList.toggle('show');
	
}

function viewDestroy(vara) {
	document.getElementById("destroytID").value = vara;
    sectionthreeform.classList.toggle('show');
}

function viewResult(vara) {
	document.getElementById("TestID").value = vara;
    sectionfourform.classList.toggle('show');
}

function viewBag(vara) {
	
	const viewBagurls ='../../v1/bagaction/moreinfo/'+vara;
	console.log(viewBagurls);
	fetch(viewBagurls)
	  .then(response => response.json())
	  .then(function(data) {
	  	var hbv = valuer(data[0].HBV);
		var hcv = valuer(data[0].HCV);
		var hiv = valuer(data[0].HIV);
		var Syphilis = valuer(data[0].Syphilis);
		var Malaria = valuer(data[0].Malaria);
		var Rhesus = rhesus(data[0].Rhesus);
		
		  var div = "<br/>";
		  	  div += "<p><strong>Bag ID:</strong>"+data[0].Bag_id+" </p>";
			  div += "<p><strong>Date of Collection:</strong>"+data[0].Donated_date+" </p>";
			  div += "<p><strong>Date of Test:</strong>"+data[0].Test_date+" </p>";
			  div += "<p><strong>BloodBank ID:</strong>"+data[0].Bag_id+" </p>";
			   div += "<p><strong>Donor ID:</strong>"+data[0].Donor_id+" </p>";
			  div += "<p><strong>BloodType:</strong>"+data[0].BloodType+" </p>";
			  div += "<p><strong>Rhesus:</strong>"+Rhesus+" </p>";
			  div += "<p><strong>HBV:</strong>"+hbv+" </p>";
			  div += "<p><strong>HCV:</strong>"+hcv+" </p>";
			  div += "<p><strong>HIV:</strong>"+hiv+" </p>";
			  div += "<p><strong>Syphilis:</strong>"+Syphilis+" </p>";
			  div += "<p><strong>Malaria:</strong>"+Malaria+" </p>";
			  div += "<br/>";
			  div += "<button type='button' onclick='closeViewBag()'>Close </button>";
			 ;
			  
			  document.getElementById("details").innerHTML = div;
	});
    sectionviewbag.classList.toggle('show');
}


function closeViewBag() {
	//sectionviewbag.style.display = 'none';
	sectionviewbag.classList.toggle('show');
}

function openEditProfile() {
	
    if(editprofile.style.display == 'none'){
		
		document.getElementById("editfname").value = user.first_name;
		document.getElementById("editlname").value = user.last_name;
		document.getElementById("editphone").value =  user.Phone;
		
      editprofile.style.display = 'block'
    } else {editprofile.style.display = 'none'}

}
