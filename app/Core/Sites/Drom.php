<?php
/**
 * Created by PhpStorm.
 * User: ONLY. Digital Agency | Kirill
 * Date: 22/10/2017
 * Time: 14:23
 */

namespace App\Core\Sites;

use App\Car;
use App\Core\Parser;
use App\Core\Telegramify;
use Illuminate\Database\QueryException;
use Symfony\Component\DomCrawler\Crawler;

class Drom extends Parser
{
    use Telegramify;

    protected $url = 'http://auto.drom.ru';
    protected $depthLooking = false;
    protected $baseUriConstruction = "/manufacturer/model/";
    protected $placeholders = [
        'manufacturer', 'model'
    ];
    protected $pagination = 'page/{num}';
    protected $replace = [];
    protected $siteName = 'drom.ru';

    public function __construct($manufacturer, $model)
    {
        array_push($this->replace, strtolower($manufacturer), strtolower($model));
    }

    public function doParse()
    {
        $baseTargetUrl = $this->url . str_replace($this->placeholders, $this->replace, $this->baseUriConstruction);
        $stuff = file_get_contents($baseTargetUrl);
        $crawler = new Crawler($stuff);

        $regularCars = $crawler
            ->filter('.b-media-cont > .b-advItem')
            ->each(function(Crawler $node) {
                $firstNode = array_map('trim', explode("\n", trim($node->filter('.b-advItem__params')->first()->text())));
                $lastNode =  array_map('trim', explode("\n", trim($node->filter('.b-advItem__params')->last()->text())));
                $page = new Crawler(file_get_contents($node->attr('href')));
                $carID = '';
                preg_match('/[0-9]{8}/', $page->filter('.b-flex__item > .b-media-cont_margin_tiny > .b-text_size_ss')->text(), $carID);

                return [
                    'name' => $node->filter('.b-advItem__title')->text(),
                    'link' => $node->attr('href'),
                    'site' => $this->siteName,
                    'image' => $node->filter('.b-image-cont__inner')->attr('src'),
                    'unique_car_id' => $carID[0],
                    'engine_type' => ($firstNode[0] ?? null . " " . $firstNode[1] ?? null) != " "
                        ? $firstNode[0] ?? null . " " . $firstNode[1] ?? null : null,
                    'transmission' => $firstNode[2] ?? null,
                    'drive' => $firstNode[3] ?? null,
                    'mileage' => $firstNode[4] ?? null,
                    'city' => $lastNode[0] ?? null,
                    'time' => $lastNode[1] ?? null,
                    'price' => str_replace('q' , 'руб.', trim($node->filter('.b-advItem__price ')->text())),
                ];
            });

        return $regularCars;
    }

    protected function store(array $data)
    {
        foreach($data as $v) {
            $car = new Car;
            $car->site = $v['site'];
            $car->unique_car_id = $v['unique_car_id'];
            $car->name = $v['name'];
            $car->link = $v['link'];
            $car->image_link = $v['image'];
            $car->engine_type =$v['engine_type'];
            $car->transmission = $v['transmission'];
            $car->drive = $v['drive'];
            $car->mileage = $v['mileage'];
            $car->city = $v['city'];
            $car->price = $v['price'];
            $car->on_sale = true;

            try {
                $car->save();
            } catch (QueryException $e) {
                if($e->errorInfo[1] === 1062) {
                    continue;
                }
            }
        }
    }

    protected function notify()
    {
        $this->sendMessage(Car::all()->toArray());
    }

    public function handle()
    {
        $drom = new self('Mitsubishi', 'Lancer');
        $data = $drom->doParse();
        $this->store($data);
        $this->notify();
    }
}