<?php
// Set username
$username = 'taduuda';

// Extend maximum execution time
ini_set('max_execution_time', 500);

// Load PHP Simple HTML DOM Parser Class
include_once('lib/simple_html_dom.php');

// Initialize PHP Simple HTML DOM Parser
$unsplash = new simple_html_dom();
$unsplash->load_file('https://unsplash.com/' . $username . '/likes/');

// Check number of pages
$page = $unsplash->find('.pagination a[!rel]', -1);


// Loop all pages and save photos
for($i=1; $i <= $page->plaintext; $i++) {

	savePhotos($i,$username);
	
	echo 'Page n' . $i . ' OK</br>';
	
}

function savePhotos($pageNbr,$username) {

	// If img folder doesn't exist we create it
	if (!file_exists('img'))
		mkdir('img');
	
	$photos = new simple_html_dom();
	$photos->load_file('https://unsplash.com/' . $username . '/likes/?page='.$pageNbr);
	
	foreach($photos->find('.photo__image-container img') as $photo) {
	
		// Remove attributes in URL
		$img = explode('?', $photo->src);
        
        // Set name of current img as filename
        $filename = 'img/' . basename($img[0]) .'.jpg';

		// If image does not exist we download it
        if(!file_exists($filename)) {
            // Save images into img folder
		    file_put_contents($filename, file_get_contents($img[0]));
        }
	}
	
	
}

?>
