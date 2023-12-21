
<!DOCTYPE html>
<html>
	<meta charset='utf-8'>
	<head>
		<link rel="stylesheet" type="text/css" href="https://yui.yahooapis.com/3.5.1/build/cssreset/cssreset-min.css">

		<style>
			/* Styles can go here, in the 'my-css' module, or both! */
		</style>

		<script src="http://yui.yahooapis.com/3.5.1/build/yui/yui-min.js"></script>
	</head>
	<body>
		<p id="message"></p>
		<img src="http://reid.github.com/decks/2011/jsconf/logo.png" height="300">
		
		<script>
		YUI({
			/* Some additional configs useful for development */
			//base: "http://localhost:3000/",
			//combine: false,
			//debug: false,
			//filter:"raw",
			modules: {
				'my-module': {
					fullpath: 'my-module.js',
					requires: ['node', 'my-css']
				},
				'my-css': {
					fullpath: 'my-css.css',
					type: 'css'
				}
			}
		}).use('my-module', function (Y) {
			Y.one('#message').setContent(Y.message);
		})
		</script>
	</body>
</html>