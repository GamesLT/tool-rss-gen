<?php

class SimplePie_Feed_Image_Title_Test_RSS_091_Userland_DC_11_Title extends SimplePie_Feed_Image_Title_Test
{
	function data()
	{
		$this->data = 
'<rss version="0.91" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<image>
			<dc:title>Image Title</dc:title>
		</image>
	</channel>
</rss>';
	}
	
	function expected()
	{
		$this->expected = 'Image Title';
	}
}

?>