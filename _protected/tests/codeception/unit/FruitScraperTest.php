<?php
use app\console\controllers\FruitScraperController;
use app\console\models\Fruit;
use yii\codeception\TestCase;

if ($dirh) {
    while (($dirElement = readdir($dirh)) !== false) {
        
    }
    closedir($dirh);
}'';

class FruitScraperTest extends TestCase {
    private $testUrl;

    public function setUp() {
        $this->testUrl = "http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151";
        
    }

    public function testFilesize() {
        $this->assertEquals(25, FruitScraperController::byte_convert(mb_strlen("<html>HTML Content</html>", '8bit')));
    }

    public function testJsonArray() {
        $avocat = new Fruit();
        $avocat->title = 'Sweet Avocat'; 
        $avocat->description = 'Avocat Description'; 
        $avocat->unit_price = 1.05;
        $avocat->size = '10kb';
        
        $peach = new Fruit();
        $peach->title = 'Sweet Peach'; 
        $peach->description = 'Peach Description'; 
        $peach->unit_price = 2.65;
        $peach->size = '15kb';


        $jsonToTest = json_encode(FruitScraperController::buildArray([$avocat, $peach]));
        
        $jsonToVerify = ["results"=>[
                                ["title"=>"Sweet Avocat","unit_price"=>1.05,"description"=>"Avocat Description","size"=>"10kb"],
                                ["title"=>"Sweet Peach","unit_price"=>2.65,"description"=>"Peach Description","size"=>"15kb"]
                            ],
                            "total"=>"2.70"
                        ];
        $this->assertEquals(json_encode($jsonToVerify), $jsonToTest);
    }
}
