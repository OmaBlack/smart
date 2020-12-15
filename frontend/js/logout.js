async function logout(){
	localStorage.removeItem('user');
	sessionStorage.removeItem('user');
	window.location.replace(`../Login`);
}
