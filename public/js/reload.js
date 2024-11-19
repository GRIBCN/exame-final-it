function reloadPage(url) {
	url = "index.php?r=" + url;
	console.log('Estoy aquí', url);
	setTimeout(function () {
		window.location.href = url;
	}, 3000);
}
