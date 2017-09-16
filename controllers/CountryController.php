<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Country;

class CountryController extends Controller
{


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $app = Yii::$app;
        $cache = $app->memcache;
        $apc = $app->apcache;
        $currentPage = $app->request->get('page');
        $key = Country::class.$currentPage;
        $limit = $app->params['limit'];

        $result = $apc->get($key);
        if (false === $result) {
            $result = $this->getData($cache, $key, $currentPage, $limit);
            $apc->set($key, $result);
        }


        $pagination = new Pagination([
            'defaultPageSize' => $limit,
            'totalCount' => $result['total'],
        ]);

        // get the row whose primary key is "US"
        // $country = Country::findOne('US');

        // modifies the country name to be "U.S.A." and save it to database
        // $country->name = 'U.S.A.';
        // $country->save();

        // create new the country and save it to database
        // $country = new Country();
        // $country->code = 'ct';
        // $country->name = 'Catalunya';
        // $country->population = 7000000;
        // $country->save();

        return $this->render('index',[
            'countries' => $result['countries'],
            'pagination' => $pagination,
        ]);
    }

    public function getData($cache, $key, $currentPage, $limit)
    {
        $result = $cache->get($key);
        if (false === $result) {
            $result = $this->getCountries($currentPage, $limit);
            $cache->set($key, $result);
        }

        return $result;
    }

    private function getCountries($currentPage, $limit)
    {
        $query = Country::find();
        $total = $query->count();

        $countries = $query->orderBy('name')
                        ->offset(($currentPage-1)*$limit)
                        ->limit($limit)
                        ->all();
        return ['total' => $total, 'countries' => $countries];

    }

}
