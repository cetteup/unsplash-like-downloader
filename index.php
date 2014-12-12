<?php

// Extend maximum execution time
ini_set('max_execution_time', 300);

// Load PHP Simple HTML DOM Parser Class
include_once('lib/simple_html_dom.php');

// Initialize PHP Simple HTML DOM Parser
$unsplash = new simple_html_dom();
$unsplash->load_file('https://unsplash.com/');

// Check number of pages
$page = $unsplash->find('.pagination a[!rel]', -1);


// Loop all pages and save photos
for($i=1; $i <= $page->plaintext; $i++) {

	savePhotos($i);
	
	echo 'Page n' . $i . ' OK</br>';
	
}

// Archive all the files inside img folder into a ZIP file
zipFiles();


function savePhotos($pageNbr) {

	// If img folder doesn't exist we create it
	if (!file_exists('img'))
		mkdir('img');
	
	$photos = new simple_html_dom();
	$photos->load_file('https://unsplash.com/?page='.$pageNbr);
	
	foreach($photos->find('.photo-container img') as $photo) {
	
		// Remove attributes in URL
		$img = explode('?', $photo->src);

		// Save images into img folder
		file_put_contents('img/' . basename($img[0]) .'.jpg', file_get_contents($img[0]));
	}
	
	
}

function zipFiles() {

	$zip = new ZipArchive();
	
	if($zip->open('unsplash.zip', ZipArchive::CREATE) === true)
	{
		
		// List all the files inside img folder
		$files = scandir('img');
		
		foreach($files as $file) {
		
			// Add file to the archive
			$zip->addFile('img/' . $file);
		}
		
	$zip->close();
	}
	
}

?>