<?php
	//script to build video sitemap according to google specifications
	//accepts array of arrays.
	//second array keys:url, (url of page video is on)
	//title,
	//thumbnail, (img location for thumbnail)
	//description, (video description)
	//content_loc (actual location of video file)
	//
	//more can be added relatively easily
	//
	//some things about this script are wordpress specific, such as where the sitemap is being written.
	

	function addURLs($sitemaps){
	//accepts array of sitemaps
		if(empty($sitemaps)) return;
		$funkdoc = new DOMDocument();
		//like funcdoc, but redman
		if(!file_exists(ABSPATH . 'video-sitemap.xml')){
			$newurlset = $funkdoc ->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9','urlset','');
			$funkdoc -> appendChild($newurlset);
			$newurlset->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
			$funkdoc->save(ABSPATH . 'video-sitemap.xml');
		} else $funkdoc->load(ABSPATH . 'video-sitemap.xml');	
		
			if($funkdoc){
				//get previous titles to prevent sitemap entry duplication
				$titles=array();
				$videos = $funkdoc->getElementsByTagName('url');
				foreach ($videos as $video) {
					foreach($video->childNodes as $child){
						 if($child->nodeName=="video:video"){
							foreach($child->childNodes as $children){
								if($children->nodeName=="video:title"){
									$titles[]=$children->nodeValue;
								}
							}
						}
					}
				}
				
				$urlset = $funkdoc->getElementsByTagName('urlset');
				foreach ($urlset as $urltag) {
				//should only be one
					foreach($sitemaps as $video){
					//do all sitemap additions at once for only one file write.
						
						//dupe check
						$dupe=false;
						foreach($titles as $video_title){
							if($video_title==$video['title'])
							$dupe=true;
						}
						if($dupe) continue;
						
						//url
						$newurl = $funkdoc ->createElement('url');
						//$txtNode = $funkdoc ->createTextNode ("test");
						//$newurl -> appendChild($txtNode);
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newurl -> appendChild($linebreak);
						
						//loc tag
						$newloc = $funkdoc ->createElement('loc');
						$txtNode = $funkdoc ->createTextNode ($video['url']);
						$newloc -> appendChild($txtNode);
						$newurl -> appendChild($newloc);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newurl -> appendChild($linebreak);
						
						//video tag
						$newvideo = $funkdoc ->createElement('video:video');
						$newurl -> appendChild($newvideo);
						
						//title tag
						$newtitle = $funkdoc ->createElement('video:title');
						$txtNode = $funkdoc ->createTextNode ($video['title']);
						$newtitle -> appendChild($txtNode);
						$newvideo -> appendChild($newtitle);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newvideo -> appendChild($linebreak);
						
						//thumbnail tag
						$newthumb = $funkdoc ->createElement('video:thumbnail_loc');
						$txtNode = $funkdoc ->createTextNode ($video['thumbnail']);
						$newthumb -> appendChild($txtNode);
						$newvideo -> appendChild($newthumb);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newvideo -> appendChild($linebreak);
						
						
						//description tag
						$newdesc = $funkdoc ->createElement('video:description');
						$txtNode = $funkdoc ->createTextNode ($video['description']);
						$newdesc -> appendChild($txtNode);
						$newvideo -> appendChild($newdesc);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newvideo -> appendChild($linebreak);
						

						//content location tag
						$newcontentloc = $funkdoc ->createElement('video:content_loc');
						$txtNode = $funkdoc ->createTextNode ($video['location']);
						$newcontentloc -> appendChild($txtNode);
						$newvideo -> appendChild($newcontentloc);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newvideo -> appendChild($linebreak);
						
						//publication date tag
						$newpubdate = $funkdoc ->createElement('video:publication_date');
						$txtNode = $funkdoc ->createTextNode ($video['publication']);
						$newpubdate -> appendChild($txtNode);
						$newvideo -> appendChild($newpubdate);

						$linebreak = $funkdoc ->createTextNode ("\n");
						$newvideo -> appendChild($linebreak);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$newurl -> appendChild($linebreak);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$urltag -> appendChild($newurl);
						
						$linebreak = $funkdoc ->createTextNode ("\n");
						$urltag -> appendChild($linebreak);
				}	
					$test = $funkdoc->save(ABSPATH . 'video-sitemap.xml');
			}
		}
	}
	
?>
