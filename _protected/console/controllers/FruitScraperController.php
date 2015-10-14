<?php
namespace app\console\controllers;

use yii\helpers\Console;
use yii\console\Controller;
use Yii;
use app\console\models\Fruit;

class FruitScraperController extends Controller
{
    /**
     * Initializes the web scraper app.
     */

    public function actionInit()
    {
        $fruits = [];
        //Link to scrap
        $url = 'http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true'; 
        
        //Get links and requered info from DOM Object to an array of Fruit objects
        foreach ($this->getLinks($url) as $value) 
           $fruits[]= $this->getProductData($value['link']);
        
        //Build final array, encode to JSON and present to screen with formated JSON Pretty Print Option
        $this->stdout(json_encode($this->buildArray($fruits), JSON_PRETTY_PRINT), Console::FG_GREEN);
         
        $this->stdout("\n\nAll Fruits Downloaded Correctly.\n", Console::FG_GREEN);
    }
    
    /**
     * Get Links
     * Find and Extracts the links from Dom Object that will be 
     * used to get the remaining info to buid the Fruits array
     * @param string $url
     * @return array $links
     */    
    public function getLinks($url) {
        $links = [];
        //Create DOM Object
        $html = $this->getHtml($url);
        //Iterates through all h3 elements in the div that has productInfo class
        foreach($html->find('div .productInfo h3') as $article) {
            //The href tag from anchor elements is collected to the links array
            $item['link'] = $article->find('a',0)->href;
            $links[] = $item;
        }
        
        $html->clear(); //Clear DOM Object
        unset($html); //Unset DOM variable
        
        return $links;
    }
    
    /**
     * Get Links
     * Find and Extracts the required info from Dom Object obtained through $links array 
     * and it stores the info retrieved into an Fruit Object
     * @param string $link Product link to use
     * @return Fruit Object $fruit Obejct containing all the retrieved info
     */     
    public function getProductData($link) {
        $html = $this->getHtml($link);  
        
        //Create a new Fruit Object to save HTML data retrieved from DOM Object
        $fruit = new Fruit();
        
        $fruit->size = $this->byte_convert(mb_strlen($html, '8bit'));
        
        $fruit->title = $html->find('div.productTitleDescriptionContainer h1',0)->plaintext;
     
        $fruit->description = $html->find('div .productText',0)->first_child()->plaintext;

        $fruit->unit_price = number_format(floatval(substr(substr($html->find('p.pricePerUnit',0)->plaintext, 9),0, -8)), 2);
        
        $html->clear(); //Clear DOM Object
        unset($html); //Unset DOM variable
        
        return $fruit;
    }

    /**
     * Build Array
     * Builds the array to be encoded to JSON
     * @param array $fruits  - Receives the array of Fruits extracted from DOM Object
     * @return array $to_encode - Array ready to be encoded
     */    
    public function buildArray($fruits)
    {        
        $total = 0;
        
        foreach ($fruits as $value)
           $total = $total + $value->unit_price;
        
        $to_encode = ['Results'=>$fruits, 'Total' =>number_format($total,2)];
        
        return $to_encode;
    }
      
    /**
     * Get HTML
     * Get remote HTML data from url using Curl and creates a new DOM Object
     * @param string $url 
     * @return DOM object
     */ 
    public function getHtml($url){        
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_USERAGENT, "My Scrap bot"); 
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($c, CURLOPT_COOKIEFILE, 'cookies.txt');
        
	$result=curl_exec($c);
        $status=curl_getinfo($c);
        curl_close($c);
        
        if($status['http_code']==200) {
            return \serhatozles\simplehtmldom\SimpleHTMLDom::str_get_html(mb_convert_encoding($result, 'HTML-ENTITIES', 'utf-8'));
        } 
	//if not met the return criteria above, then show error
	return "\nERRORCODE22 with $url!!\nLast status codes:\n".json_encode($status)."\n\nLast data got:\n$data\n";
    }
    
    /**
     * Byte Convert
     * Convert file size into byte, kb and mb
     * @param int $size
     * @return string
     */
    public function byte_convert($size) {
      # size smaller then 1kb
      if ($size < 1024) return $size . ' Byte';
      # size smaller then 1mb
      if ($size < 1048576) return sprintf("%4.2f KB", $size/1024);
      # size smaller then 1gb
      if ($size < 1073741824) return sprintf("%4.2f MB", $size/1048576);
    }
}