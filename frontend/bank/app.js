const users = document.getElementById('users')
const form = document.querySelector('.section-one')

const request = document.getElementById('bag')
const requestform = document.querySelector('.request')

const screening = document.getElementById('requestScreening')
const formRequest = document.querySelector('.request-transfers')

const formRequesTransfer = document.querySelector('.request-screening-trans')

const editprofile = document.querySelector('.edit-profile-info')


request.addEventListener('click', toggleRequest);
users.addEventListener('click', toggleForm);
screening.addEventListener('click', screeningRequest);

function toggleRequest() {
  if(requestform.style.display == 'none') {
    requestform.style.display = 'block'
  } else {requestform.style.display = 'none'}
}
function toggleForm() {
  if(!users.classList.contains('show')) {
    form.classList.toggle('show')
  }
}

function screeningRequest() {
  if(formRequest.style.display == 'none'){
    formRequest.style.display = 'block'
  } else {formRequest.style.display = 'none'}
}

function transferBag(vado) {
	
    if(formRequesTransfer.style.display == 'none'){
	  document.getElementById("BagtransID").value = vado;
      formRequesTransfer.style.display = 'block'
    } else {formRequesTransfer.style.display = 'none'}

}


function openEditProfile() {
	
    if(editprofile.style.display == 'none'){
		
		document.getElementById("editfname").value = user.first_name;
		document.getElementById("editlname").value = user.last_name;
		document.getElementById("editphone").value =  user.Phone;
		
      editprofile.style.display = 'block'
    } else {editprofile.style.display = 'none'}

}