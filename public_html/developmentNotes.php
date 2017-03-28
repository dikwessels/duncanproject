<?php
/* TO DO 
	- SYNTAX CHECK 8/20/12 √
	- TEST RUN WITH CALLING FUNCTION 
		- will not run correctly unless all other functions are included directly in script 
		- fixed 8/25/12
	- need to include keywords function
	- include this as a function and not an include script
	- test incorporating this as an include file in another calling script
		- 8/25/12 √
	- clean up display to include featured items and not include two sets of search and sort fields √
	- consolidate all other supporting variables and functions to individual includes
		- all done except meta keyword function √	
	- separate search result sorting and display options header from actual search results √
		- make sure sorting functions work properly √
		- have H2 text be derived from the various item names √

8/27/12	
	- consolidate flatware search to this script
		- general search done need to:
			- add place setting searches √
			- make sure pattern handle images are rendered √
			- add separation between categories √
				- fix category links
			- combine featured items to one query instead of calling twice √

9/1/12
	- separate sorting and searching functions to use AJAX updating instead of calling entire page again √
	- modify template to include searchResultsSub, have that content updated via AJAX √
		- add id to searchResultsSub container √
		- add id to search results page nav container √
		- create new javascript function solely to change page numbers using ajax  √
		- store total results in form data √
		- streamline sorting to make results more meaningful
		- fix item etc sorting to preserve the display limit information √

9/2/12
	- modify script to show results summary even if one result is shown √
	- modify page navigation to highlight javascript √
	- have "Now showing update" after results are returned √
	- add overlay div during AJAX updates √
	- add loading graphic during AJAX updates √
	- test static Search creation script √
	- modify CSS position of search result option fields √
	- increase font size of page navigation links √
	
	- create new main category pages
		- create page template √
		- create script that will load page template and replace content with category specific content
			- have script import category.inc file and replace that in template script √
			- replace deprecated "language" attribute with type attribute for script tags √
		- fix productSearch for items to filter for search terms √
		
		- fix generateSearchResults to show proper no results found √
		- create sub search field for featured items
		- replicate current featured items information using div architecture √
		- create new sidemenus referencing new product search script √
		
		- implement hollowware.php method of showing all images for items with multiple images
9/3/12
	- implement new product search page into dropdown menus	  √
	- have dropdown search terms replace H2 headers in searches √
	- correct bug with display N items select not returning proper item results
	- search terms are being split up incorrectly when sent to updateSearchResults script, resulting in too many search terms
	- need to refine the search to do a regexp on both terms using AND instead of OR √
	
9/4/12
	- modify category side menus to include propery H2 headers √

9/5/12
	- modify staticHTML search creation script to hanel category and other link menu creation without variable overwrite √

9/6/12
		- complete implementation of side menu static page creations
		- add link section at bottom of the page with same category linking as dropdown menus
		- fix naming convention for static Item pages
		- fix broken image links for static search script √
		- make sure static search is returning same results as product search √
		- test static file naming script according to FSC suggestions
		
		- reconcile file name path creation script with link creating line in √
			- works fine in generateSearchResults. verify functionality in createsaticSearchesSEO √
		- make sure file name creation and checking is consistent across all scripts
		- modify includes to include_once √
		- add H3 header to item listing links √
		- add descriptive title to item listing links √
		-create static HTML script now working 
			- clearstatcache() and login_ftp improved some √
			- all and baby silver are being incorrectly created

9/7/12
		- fix and streamline all folder naming and checking for static creation scripts √
		- have staticSearch script modify title with H2 header as well √
		- add hollowware side menu items to database with appropriate search categories √
		- modify side menu script for hollowware page to only get searchcategory items √
		- modify category links menu script to not show searchCategory items √
		- tweak CSS for category pages to show side menu and main content appropriately with page resizing √
		- make sure side menu items modify H2 header as well
		- tweak image CSS for better MOBILE BROWSING
		-category links look good
		
9/9/12
		- fix static searches not executing properly, returning no results √
		- put finishing touches on category page template, apply across all categories
		- add lightbox image to 
		- fix sorting for searchCategory pages √
		
		
9/10/12 
		- make sure position resets when sorting search results √
		- restoring backup √ lost 4 hours of work
		- converting H1 header to encapsulate link back to main category page √

9/17/12
		- investigating new search algorithm
9/18/12
		- generic search algorithm
			- works well with single word patterns and makers √
			- can filter out category and monogram keywords √
			- need to improve query for items and multiple word brand and patterns
				- investigate match against, with soundex filter
					- pretty robust now with brand and pattern checking 
				- correctly remove mispellings or create arrays to catch the most common ones √
				
				-figure out why brand is not being successfully returned by getBrandfromPatternfunction √
					- was retrieving field in uppercase, duh. 
				- figure out why beaucoup designs won't match to beaucoup √
					- levenshtein was equal, accounted for that

9/21/12
		- implement search bar √
		- streamline CSS for search bar √
		- correct problems with francis i search, and gorham matching to wrong gorham patterns
		- correct odd CSS problem with flatware, stylesheet was removed?!?!?!

TO DO:
		- refine search results  √ 
		
		
			

		- add contact information to all pages
			

			
		- make new gifts and storage products page √
			- need to refine H1 header text to be more meaningful
			
		- add place settings, complete sets, and serving pieces to side menu generation script √
			- added to static HTML search script √
			- need to modify side menu function within flatware main page to add extra links √
			
		- change "All" category to something more meaningful and relevant
		- update meta keywords!!!
		
9/25/12

		- improve placement of cart icon
			- new icon ?
			- remove images for cart count
			
		- complete item page template
			
		- add contact information to footer √
		- add 1800 phone number to all pages √
		- complete search page generation
		- place pattern guide menu to header not footer	√
			- complete new pattern guide page	
		- create a manufacturer listing page (this will help the crawl bots)
			- have a sortable/filterable list of manufacturers, each link taking to a homepage showing all of the 
			- /silver-manufacturers/gorham-silver/
			- /silver-manufacturers/whiting-silver/  with a list of all the patterns in stock
			- /silver-manufacturers/tiffany-silver/
				
		- finish category page templates
			- add box shadow to side menu and category link containers √
			
			- flatware
				- featured items not appearing v
			
			- hollowware
				- fix link to old page √

			- jewelry √
			- baby √
			- collectibles 
		
		- refine keyword generation for search results
		- refine page headers for search results
			- have search insert any matching patterns or brands if there is one specific match
			otherwise have search terms
		- make sure category links get proper folder connection √
		- refine search field interface
			- improve text size √
			- have field automatically clear and then reset during searches √

		- modify and refine createStaticHTMLSEO script to incorporate new folder naming scheme √
		
		
		
9/26/12
		- mobile design:
		- further work on responsive CSS,
			- proper resizing and display of other links
			- proper resizing and display of search field
			- category pages shouldn't have the category links, just the home page, category pages can have search form and sub category links
			

9/27/12 

	- testing and refining mobile CSS
			 - improve cart placement √
			 - improve header graphic √
			 - double size of images?
	 
	 - decide on folder naming convention
	 - /category/brand/pattern/item?
	 
	 file name should include pattern by brand item name (monogrammed) id
	 - /category/brand/
	 - /category/brand/pattern.html			

	 -create generic file naming script that all search scripts will use to create and test for static pages √
	 
	 

	9/28/12
	2 hrs
	 - making new index page with responsive CSS for better mobile display √
	 - CSS mod for better MOBILE display √
	 	- mobile header design √
	 9/30/12
	 - 2 hrs
	 	- mobile header design, redesign of horizontal links for better display sizing response √
	 	
	 	
	 	TO DO: create all links
	 		- update all search links with new linking structures
	 		- fix placement of cart in search pages
	 			- baby silver template has problem √ fixed 10/1/12
	 		- tweak maincontent height on pattern listing page √
	 		- search function returns "place settings etc" for things like "lace"
	 		- create SEO version of additem script √


10/1/12
	- TO DO: 
		- create SEO version of ShowItem script √ 30 min
		- fix image generation √
		
		- add lightbox to product pages?
		- add breadcrumbs link after H1 on page √
			- brand breadcrumb added 45 min
				- fix durgin/gorham 30 min √
			- fix display of mobile 30 min
	 
	 - modified page headers and category headers for better mobile display √ 30 min
	 - need to figure out why side menu links aren't highlighting as they should be
	 
			
	 - creating new cart image that resizes with mobile scaling 1 hr √ 
	 - adding cart graphic to search script 20 min √
	 
	 - to do, finish pattern bread crumb, send to duncan
	 	- ask if he wants complete makeover of site to mobile css, do flat rate for $1000
	 		- remaining pages that need mobile redesign
	 			- contact
	 			- other services
	 			- useful links
	 			- sitemap
	 			- repairs
	 			- gift registry
10/2/12
	- consolidate breadcrumb link generation to staticHTML to improve user experience and search engine crawling 30 min √
	- fixing header display with new breadcrumb links √
		- making H1 and H2 headers pad and position correctly
	- tweaking item page layout for improved design √
	- modifying new show item script for better layout and proper H1 
	to do: 
		- complete view more links for item page
		- improve resizing of search form field

10/3/12

10/5/12
	- .5 hrs, updating HTML doctypes to HTML5 and charset UTF-8
		
		index 				√
		search template 	√
		category template 	√
		silver patterns 	√
		item page template	√
	
	
10/6/12
	- flatware side menu link can't be made using the text	
		- add override for flatware page to ignore the text

10/8/12
		- modify code to correctly apply baby silver category to category page urls	√

		- correct footer on showItemSEO page √ 15 min
		- correct footer on createStaticHTMLSEO script √
		- correct position of suggested items div √	5 min	

		- add breadcrumbs to createStaticHTMLSearches √ 15 min
		- have handle images appear for items without inventory √ 10 min
		- have H2 text appropriately appear for category pages for specific patterns√ 15 min
		
10/9/12
		- determine why breadcrumb links are not properly highlighting
		- test category links code to make sure it's properly looking for category main pages 
		- modify header to never say "Silver All"
		- add facebook and useful links to index page √
		- have input box resize better in mobile mode
		- remove cart buttons from suggested items √		
		- have H1 SilverCare be Replaced with "Care Products" √
		- fixed search form bug on JewelryBeta √		
		- add pattern links to category links as well
		- reposition useful links and site map links √
		- replace #, "no" with "number" in file names and pattern names
			-# √

		- have page title go in order of pattern, brand, category
			


Pages to convert to mobile platform
	- index
	- flatwareBeta
	- hollowwareBeta
	- babySilverBeta
	- jewelryBeta
	- CollectiblesBeta
	- contact
	- silver-patterns
	- addItemSEO
	- showItemSEO
	- productSearchNew
	- searchTest
	- orderDetails
	- gift registry?
	- repairs
	- category template
	- item page template
	- search template
	- privacy policy
	- guarantee page
	- engraving page
	- hallmark guide
	- comprehensive hallmark guide
	- order tracking
	- selling page
	- FAQ
	- knife blades
	- soup spoons
	- directions
	
	
	10/16/12
		- have showItemSEO include breadcrumb to item searches if followwed from an item search?

	10/17/12
		- modified side menu script to handle flatware pattern names to correctly link to static pages √ 15 min
		- fixed "Silver Baby Silver" h1 on showItemSEO.php √
		- fix view more items link
	
	
10/19/12 - moved to 10/9/12 for  invoicing 
	- add thawte seal to new index page √
		- modify css for index page √	15 min
	- add google analytics to showItemSEO √	already done
	- fixed footer placement on item page template √ 5 min
	- empty suggested items container shouldn't appear √ 15 min
	- have all item info be in page h3 header?
		- modified h2 header to hold item info 30 min
	
10/22/12
	- modify mobile css to have category menus look like main page menus
	- fixe view more items link √ 10 min
	- have category searches simply return a listing of patterns within that category ?
	- have brand pages have a list of patterns? 
	
10/23/12
	- have suggested items not appear if not listed √ 5 min
	- collectibles side menu not appearing 
	- standardize format of category page headers √ 10 min
	- correct H1 header on gifts page, currently says "Silver" should say "Silver Gift Ideas"
	- make sure all links go to https://  30 min
		- showItemSEO category links √
		- breadcrumb links √
		- generate search results √
		- update search results √
		- category side menus √
		
		
	- search sorting is timing out on static pages and productSearchNew?
		- calls have to be going from https: to https for it to work
		
	- remove breadcrumb from main item search pages √ 5 min
	- jewelry search sorting causing error due to missing overlay? 
	- modify css for buttons to have hand pointer √ 5 min
	- modify "Add" to "Add to Cart" for item Pages √ 
	- add back to search results to item page template √ 15 min
	- redo item page to have image under the pattern header? 10 min √
	- add pinterest button to item page √ 30 min
	- add item name to pattern header on item page 10 min
		- modify CSS of pattern header to have proper height as needed	
	- replace share with new Facebook like image
	- add open graph to page template √ 15 min
		- need to have special case for items without images

10/24/12
	- tweaking new CSS for item page suggested items and item details 15 min
	- dont have breadcrumbs if no pattern or brand exists √ 5 min
	- fix image sizing on new item page template 5 min √
	- modifying item page template to streamline and line up item details and suggested items 15min
	- add html 5 footer tag instead of div id footer tag √ 15 min


10/25/12
	- add christmas items to menu options √ 10 min
	
10/26/12
	- correct bug with new index page 15 min√
	- replace index page brand images with text √20
	- need to have proper page title for brand static search pages 
	- correct alignment issues on Safari and Chrome with index page main image 15 min √

10/28/12
	- add FB like to item page template 10 min
	- need to correct showall on category main pages 
		- hollowware √
		- flatware √
		- jewelry √
		- baby silver √
		- collectibles √
	- fix collectibles side menu √ 5 min
	- add Google+ button√ 5min
	- update collectibles featured items msg √
	- addItemSEO not updating cart 5min √

10/29/12
	- add box shadow and padding to featured items on category page
	- ad padding to main content to space footer √ 
	- consolidate code on category pages to external script 30 min √
		- hollowware √
		- flatware √
		- baby silver √
		- jewelry √		
	- go live with new site  1hr 
	
	RENAME LINKS: 
		- convert all categoryBeta links on IndexSEO2 to category links √
		
		- convert all showItemSEO links to showItem
			- generate search results √
			- update search results √
			
		- convert all addItemSEO to addItem
			- generate search results √
			- update search results √
			- category pages
				- hollowware √
				- flatware √
				- baby silver √
				- collectibles √
				- jewelry √

		- convert category arrays homepage array√

		- rename anylinks to productSearchNew with productSearch
			- jewelryBeta √
			- flatwareBeta √
			- hollowwareBeta √
			- babySilver √
			- collectibles √
			- staticHTMLFunctions √	
	
	
	RENAME FILES
	- rename files on server
		- addItem		-> 	addItemOLD 			√
		- addItemSEO	-> 	addItem 			√
		- showItem		-> 	showItemOLD 		√
		- showItemSEO	-> 	showItem 			√
		- flatware		->	flatwareOLD 		√
		- flatwareBeta	->	flatware			√
		- hollowware	-> 	hollowwareOLD 		√
		- hollowareBeta	-> 	hollowware 			√
		- productSearchNew-> 	productSearch	√
		- babysilver	->		babysilverOLD 	√
		- babySilverBeta->	 	babysilver 		√
		- collectibles	->	collectiblesOLD 	√
		- collectiblesBeta -> collectibles 		√
		
		-	search test not working?!?!?  √
		
		
	


10/31/12  - ADDED TO 10/29 
	- create static searches not doing "/silver/" folder for all categories
	- convert useful info, contact us, hallmark guide etc to new template	
		- 	otherservices			√	8 min
		- 	ourguarantee			√	8 min
		-	engraving				√	5 min
		-	repairs					√	
		-	sell					√	15 min
		-	usefulInfo				√	5 min
		-	hallmark, hallmarkcomp 	√ 	1hr
		-	tracking				√	15 min
		-	privacy					√	5 min
			
		total 8:15 -  10:45 
		
  - add page head image title to seach creation scripts


11/3/12
implementing mobile site redirect 15 min
	- adding redirect code to index page for mobile site
	- modifying CSS for mobile site popup message
	- fixing bug with new static search page creation script	

11/4/12
	- researching linking from wikipedia 5 min
	- converting SEO paragraph links to new product Search page 11 min NIX
	- generating new sitemap
		- still referencing old links? 
			- jewelry page wasn't updated
	
	- createStaticSearches isn't properly generating √	5 min
	- new contact page 5 min √

11/5/12 1 hr INCLUDE WITH 11/4/12

	- fix crawling errors
		- fixed wrong contact link
		- converted jewelry page link
		- fixed incorrect productSearchNew link from silver-patterns.php
		- added nofollow attributes to sitemap, useful links, facebook, and giftregistry
		- code refinement on search page for faster performance and better legacy programming
		

11/6/12 8-9am
	
	- fixing recent aqcuisitions links on all category pages √
	- finalizing new repair page template √
		- standardize style to match rest of new website style

9:10 am 1pm - 			
	- refine css cell column widths for more consistent display 
		- repairs
		- search page template
		- generate search results		
		- category page template
		- item page template
			- place pattern header inside item information
		- replace any "~" with "
		- fix jewelry sorting bug and collectibles sorting bug
		- update search results wasn't properly ignoring brand="all", pattern="all" from old scripts
		- add nofollow attribute to manufacturer sort on patterns to improve crawling √
		- add nofollow to category showall links √
		- crawling site and looking/correcting any broken links

11/7/12
	- fixing sorting error with & in brand and pattern names √
	- fixing sorting error with https and http handling √
	
TO DO: 
	- remove category suffix from silver care and silver cleaning static search pages
	- fix all crawling errors, submit sitemap to google
	- convert directions.php to new template 	
	- add brand and pattern direct links to category pages?
	
	
11/8/12
	- converting gift registry page to new format 2 hr √  - INCLUDED WITH  NEW FORMAT BILLING
	
11/9/12
	- converting gift registry editing page to new format
		1.25 hr not finished
	- fix order details shipping bug, is placing decimal point two places too far to left 
			- calculating ridiculously huge balances for things over 800 √  5min
	- corrected incorrect link to patterns on otherservices page 5 min
	- fixed back link error on new gift registry page

11/12/12 1.5 hrs
	- converting wishlist page to new format
		-wishlist.php √
		-viewwishlist.php
	- improving no search results feedback  


11/13/12
	- improve category pages by adding AJAX functionality for featured items 1.5hrs - INCLUDED AS A BONUS
		- develop stand alone script to return featured items √ 30 min
		- create javascript function to call and update featured items content
	- file cleanup 10 min
	
	- creating index files for flatware, hollowware, jewelry, collectibles and baby silver for better SEO crawling 1 hr
		
	
	
	11/14/12
	
	- implementing redirects on old category main pages √
	- running site crawl
	- uploading new gift registry pages with new template

	- converting pages to new template
		- directions.php to new format √ 5 min
		- settingsizes.php  √ 5 min
		- knifeblades.php 5 min √
		- soupspoons.php 5 min √
		- cleaning.php 5 min √
		
	- correcting usefulinfo links to old pages 5 min √
	- removing old SEO paragraph links (not helping SEO), 15 min √
	
	- adding brand list for hollowware page for better SEO crawling 20 min √
	- modified robots.txt to allow showItem.php to be crawled
TO DO: 
	tweak new gift registry page for better layout and design


11/15/12
	- pinging google with new sitemap and running fetch on google to verify correction of website access problems 15 min √
	
	- improving message for no result searches, and linking to wishlist page √
	- determing methods to link "silver" pages to the rest of the website  30 min

11/20/12
	- checking into installation of mobile site script, script is not executing √ 15 min
	
11/25/12 15 min
	- converting search.php to new template
		- is being accessed by the mobile site
	
11/30/12
	- creating virtual tour page, modifying CSS for improved display 30 min

	- adding enhanced linking for google analytics tracking on all pages 30 min
	- adding webmaster tool association to site for improved reporting
		- index						√
		- search template 			√
		- category page template	√
		- item page template 		√
		- contact page 				√
			- adding redirect to old contact page url so users fon't get 404 error
		- directions 				√
		- engraving 				√
		- gift registry 			√
		- gift registry editing 	√
		- hallmarks 				√
		- hallmarks complete 		√
		- knife blades 				√
		- links 					√
		- otherservices 			√
		- ourguarantee 				√
		- privacy 					√
		- soupspoons 				√
		- tour 						√
		- tracking 					√


12/1/12
	- corresponding on mobile site linking, installation code is not functioning as expected 15 min 
	
12/3/12
	- emailing Greg at ServiceReps regarding Likes Local installation code 15 min	
	- triple checking installation code
	
12/7/12
	- adding redirect to gifts.html, deprecated site being linked to from mobile site
	- fixing category links on engraving page
12/9/12
	- 15 min investigating likeslocal redirect issues
	- contacting AYLISS inquiring about other possible installation code that they may have received from LikesLocal
	
12/17/12
	- 12/17/12	30 min
		- responding to inquriry from AYLISS regarding likeslocal 
		- 15 min, looking into likeslocal's website
		- testing implementing code that is being used on likeslocal for possible implementation on AYLISS
		- modifying code directly from likeslocal website to use for AYLISS
		- testing redirect on mobile browser
		- corresponding with AYLISS regarding script installation

	- 12/17/12  30 min
		- modifying processInfo.php and shippingInfo.html to add $10 to 2nd-day air shipping option
		- looking into blank order information for order 1785
		- placing contact information from confirmation email into website database for order 1785
		

12/18/12 - 15 min
		- updating all shipping prices on website to reflect Sales program prices
			- processInfo.php
			- shippingInfo.html
		- updating 2nd day shipping price on sales program 
		
		
12/20/12
		- 30 min
			- reviewing website sales, contacting customer on possible transaction problem
			- adding tour links to contact page, modifying contact page
			- modifying email contacts on contact page 
			- adding improved google tracking code to shipping, billing and order summary pages 

12/23/12
		-30 min
			- modifying tracking page to use FedEx tracking instead of UPS tracking

12/26/12
		-15 min
			- reviewing and retrieving information for sale 128963
						
1/7/13
	- reviewing and correcting duplicate inventory entries for cape cod polish 15 min
		
		- removing items 79443 and 78569, replacing with 74612
		1/10/13
		- 30 min
		 removing duplicate inventory entries for cape cod polish tins 


1/19/13 15 min brainstorming, looking into Bing SEO

1/21/13 15 min 
- verifying and adding site to BING search engine and Yahoo! search engines (completed with verification by Bing)

1/21/13 1.25 hr 
 researching meta keyword and description value to SEO improvement, modifying meta page description for showItem and search generation scripts to use more keyword search terms in page title and description
 
 analyzing and modifying keyword generation in showItem.php
 
 
 
 TO DO for SEO:
	- add following keyword patterns to showItem.php
		
		brand pattern item
	 	silver brand pattern item
	 	sterling silver brand pattern item
	 	brand pattern silver
	 	brand pattern sterling silver
	 	brand pattern silver item
	 	pattern silver
	 	pattern silver item
	 
	 - improve individual item page meta description for more human readable description to appear in google results. 
	 	
	 - analyze index page keywords, add all of the patterns, makers etc items to the main page keyword description
	 
	 - add open graph object to index page
	 
	 - analyze the description section, make it more meaningful. 
	 
	 - add misspelling generator ? not needed, google apparently corrects and searches based on proper spellings
	 	- create pattern and brand keywords with misspellings 
	 		- replace "ll" with "l", "ww" with "w" etc etc
	 		- do a soundex replace to generate more misspellings? 
	 		
	  DO ADD a generator for hollowware and holloware
	 		
	 - add special character variant
	   have both "-" and "/" in item names, patterns etc
	 	lunch/place fork 
	 		lunch place fork
	 		lunch-place fork
	 	
	
	 - investigate ways to reduce bounce rate
	 
	 - look at new and improved repairs main page
	 	- increase keywords 
	 	- rename to /silver-repair/ photos, borrowing jeff herman's keywords if necessary
	 	- online quote tool? 
	 		
	 - add more keywords to title, after informative item listing data
	 	- add specific category
	 	- add generic "silverware" 
	 	
	 	
	 - add top brands/patterns direct links to home page

	 - create datafeed for bing shopping 
	 	- downloaded spec 1/29/13
	 	
	 - investigate negative keywords (for ads only)
	
	- create script to create keywords for main site page 

1/29/13 -1 hr 15 min
	-	working on category links in index page footer

	- modifying page title to have h1 with silver shop name, h2 with subtitle


1/30/13 - 
	1 hr 
		- redesigning homepage to have h1 and h2 information and meaningfully incorporate SEO text as
		- researching possible techniques to improve performance of MS access 2003 on Windows 7 machine
	TO DO: modify category footer link script to make links H3 links as well

1/31/13 - .5
		- attempting to check slow computer via gotomypc, installing latest sales program on back office computer
		- compacting and repairing network database
		
		.25
		- researching and modifying category main page meta descriptions for improved SEO


2/4/13
	-.5 completing redesign on homepage to have SEO paragraph and category links at page bottom
	

2/8/13
	- thoughts: add most popular items link? 


2/14/13
	- 5 min - gift certificate code generating script initial development
			- generates 8 digit random alphanumeric string in XXXX-XXXX format
2/15/13
	- 10 min - fixing style display for complete sets

2/18/13
	- 30 min 
		- adding redirect for outdated back links for following pages
		http://www.asyoulikeitsilvershop.co​m/search/All/1847-rogers-brothers-s​terling-silver.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/la-pierre-mfg-sterling​-silver.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/mueck-cary-company-inc​-sterling-silver.html
		http://asyoulikeitsilvershop.com/se​arch/All/wallace-sterling-silver.ht​ml √
		www.asyoulikeitsilvershop.com/search/All/reed-and-barton-sterling-silver.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/gorham-sterling-silver​.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/first-love-by-1847-rog​ers-brothers-sterling-silver.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/international-sterling​-silver.html √
		http://www.asyoulikeitsilvershop.co​m/search/All/alvin-sterling-silver.​html √
		http://www.asyoulikeitsilvershop.co​m/staticHTML/Hollowware/_UNKNOWN/_/​STERLING-SILVER-SUGAR SCUTTLE-40897
		
2/19/13
	- 15 min
		 updating website FTP scripts with new site login password after apparent email account hacking. 
		
	2/23/13
	- 30 min
		 repairing database
		 checking sales program for performance
		 
2/25/13
	- 45 min
		- checking into program stalling on Duncan's computer
		- problem found to be with outdated sales program install 


3/14/13

3/17/13

	- 15 min brainstorming best integration method for new gift card into shopping cart
3/18/13 
- 1hr 15 min 
	- designing gift card entry form
	- adding on the fly jquery form validation and CSS design


- 2.5 hr
	- adding gift card cookie generation for checkout
	- modifying orderdetails.php to correctly display gift cards along with standard inventory
	- modifying store.js script to correctly remove gift card

TODO
- add cookie generation for add to cart, then checkout √
	- simply add to cart create special cookie √
	- order details has bug with gift cards 
		- can remove gift card by specifying 0 √
		- updating price creates new one at new price 
			- fixed
			
		- added unique timestamp to gift card generation
		
		
	- shopping basket displaying incorrect number of items
	
	
	-make viewshoppingcart.php instead of orderdetails.php

TODO:
	check checkoutcalculations, make sure gift card isn't added for shipping etc
	have order processing check for gift cards
		- if there's a gift card, create a unique id and record in the mysql database
		- create confirmation email for recipient of gift card
		
	
	improve additem.php script to modify cookie if people are clicking add to cart for item that's already there. 	
	
	add gift card redemption to standard checkout form
		
		
- make new checkout form?

- add best practices to checkout form and gift card form

-improve CSS for field validation confirmation


3/19/13
	- 2-3pm developing new checkout page template to simplify sheckout process


3/20/13
	-15  modifying website search scripts to remove troy ounce data for flatware


3/21/13
	- 15 minutes
		- adding "CS" category to store sales program, finding problem with updating 
		- adding 'cs' to store-website_Update.php script
			need to text and determine why management inventory updating screen is not adding items to website
			
			
3/23/13
	- gift card thoughts: will have to add a check field to only use billing information if someone is only purchasing a gift card
	
- 	30 min
	- correcting cart graphic setting
	- having gift card only checkouts go directly to billing information
45 min
	- adding gift card redemption code and amount codes to customer databases
	- adding gift card redemption code field to billing information form
	- tested checking code and applying appropriate gift card amount to invoice
	
	TO DO:
		- circumvent billing information requirement if gift card amount is greater than invoice total	
		- update gift card remaining balance after processing sale.
			- can circumvent credit card processing if total is 0
			-  don't allow users to edit applied gift card amount?
		- having card field is causing validation error

		- complete new checkout process
		
		- add gift card validation
			- have shipping not appear for 
		- complete credit card validation
		

3/24/13
	- 5 min
		creating standalone gift card amount retreival script
		
3/25/13
	1 hr
		- designing and testing new checkout form	
		
		
3/25/13 5:30 - 6:15pm

	- making coin silver main page on website
		- create new folder √
		- modify makeIncs script √
		- modify all category arrays to include CS category √
		- add cs to management edit page √
		- add cs to sideMenu.php √
		- update ayliss_style_uni.css to include coinSilver* classes √
		- add coin silver to sideMenuNav.html √
		
		- add blurb section to coin silver and templates in general √
		
		
3/25/13
	- messed up validation script, need to debug  √
		
3/26/13
	2 hr
		- modifying refining report to include all inventory
		- testing sales program to add current coin silver inventory to website
		- creating new sales program update
		- modifying name of searchTest to silver-search
		- testing coin silver searching, all good except "coin silver" term not returning category
		


TO DO for Checkout:
*/
4/4/13
	- 2 hrs{
	/*	
	
	add modern look and validation
		- fix current checkout.php page see why validation's not working
		- add ajax functionality
			- have checkout.php do all front-end, using ajax requests to processinfo to update all shipping and billing
			
			- shipping info being display correcting √
				- move small order summary to right part of screen instead of bottom, have updates appear immediately
				
				
		- modify formvalidation.js to use .data() instead of attr to filter 
		- make new temp customer ID to better track order processing
		
DONE:
	- added order summary to checkout page
	- added improved flow process steps to page header
	
*/	
}	
4/5/13 2.5 hrs{
/*
	- modified order summary box on checkout √
	
	- add code to update shipping automatically when choosing different options √
	
	- add code to remove and then add hidden inputs with invoice data when saving shipping information
		- update the values, then submit 
		- shipping base rate is not stored, shipping method (really the surcharge) shipping total are stored
		- don't store any of that information until later on
			
			
	 - add form validation and checking to shipping step
	 	- initial field validation done:45 minutes	√ 
	 	- need to do overall formchecking on submit 
	 
	 - add tempsession cookie data to prevent people from over-riding any data forms
	 	- create cookie and store all subtotal data with temp ID
	 		- commit all changes to server, and then on submit request values so they can't simply edit the hidden values
	 	
	 rules are 
*/}		
4/7/13{
/*
	- finished form validation for shipping information √
	- variables not being posted on form submit  √
	- added code to update the step process indicators √ 
	- removed invoice summary from old billing information form √
	- modified confirm order form for AJAX use
	- need to change code so temporary session data is stored in temp table and not customers table, transfer all information at end of 
	
	- need to modify the form validation to check on load if form is properly filled
		- loop through each element and check rules
		-  so if someone edits their shipping and then goes back to the credit
		
	-	fixed insert with temporary invoice data
*/	
}
4/8/13 12:45-1:30  4 hrs total{
/*
	- add gift card redemption
		- create field to find 
		- create new script to retrieve information √
		- add function to gift wrap field to update order summary box √
		
		- improve shipping editing
			- have form automatically validate
			- stop data posting to url	
			- 
			- correct note saving extra characters √
				- from file, grr. 
			- have gift wrap field be updated with stored information √
			- fix edit shipping button 
				- bug preventing trigger fixed √
				- ajax call not receiving post data √
				- add fade in fade out ?
			- updateOrderSummary
			

		- add auto validation to shipping data when customer navigates back to it √
		- fix error with billing information being loaded after customer record is created √
		- modified CSS of shipping and billing form for better presentation
		- modified code for better performance
			TO DO: 
		- figure out why gift card code request is executing automatically
			- all function definitions are being executed! fixed that by moving outside of the document.ready function call. 
			
		- tax being calculated? 

		for order-summary page: 
			- have checkout-form section empty and get resized to 2 columns
			- have #order-summary section resize to 10 columns
*/
}

4/10/13{
/*	10:39 - 12
			
		- modified form validation to work for selects on change rather than blur
		- converted state field to validated select box
		- created new size select input for better presentation
		- modified CSS of select input to for betted presentation
		- add review order step to checkout

 30 min - creat
*/

}

4/11/13{
	1 hr
	// converting old page loading forms with built-in ajax forms
	// moved all jquery binding to one call to speed things up. 
}
	


?>
