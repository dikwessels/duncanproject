/*
filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
(function() {

	var isAdvancedUpload = false;
	var draggedFile = null;

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			ParseFile(f);
		}

	}


	// output file information
	function ParseFile(file) {

		// Output(
		// 	"<p>File information: <strong>" + file.name +
		// 	"</strong> type: <strong>" + file.type +
		// 	"</strong> size: <strong>" + file.size +
		// 	"</strong> bytes</p>"
		// );

		$id("image").value = file.name;
		var prevImg = $id("prevImg");
		prevImg.src = URL.createObjectURL(file);

		draggedFile = file;
		isAdvancedUpload = true;
	}


	// initialize
	function Init() {

		var fileselect = $id("userfile"),
			filedrag = $id("filedrag");

		// file select
		// fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


	$('#productForm').submit(function(ev){
		
		
		if (isAdvancedUpload) {
			ev.preventDefault();

			var $form = $('#productForm');
			var $input = $form.find('input[id="userfile"]');

			console.log($input);

			var ajaxData = new FormData(document.getElementById('productForm'));
			ajaxData.set( $input.attr('name'), draggedFile );

			$.ajax({
			    url: $form.attr('action'),
			    type: $form.attr('method'),
			    data: ajaxData,
			    // dataType: 'text',
			    cache: false,
			    contentType: false,
			    processData: false,
			    complete: function() {
			      
			    },
			    success: function(data) {
			      document.write(data);
			    },
			    error: function() {
			      // Log the error, show an alert, whatever works for you
			    }
			  });
		}

	});


})();