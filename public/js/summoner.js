document.getElementById('buscar').addEventListener("click", function(e) {
	e.preventDefault();
	let server = document.getElementById('server').value;
	let summoner = document.getElementById('summoner').value;
	window.location.href = server + "/" + summoner;
});