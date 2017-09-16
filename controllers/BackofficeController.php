<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\Product;

class BackofficeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->layout='main-backoffice';

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays a hell of a stock.
     *
     * @return string
     */
    public function actionStock()
    {
        $app = Yii::$app;
        $cache = $app->memcache;
        $apc = $app->apcache;
        $currentPage = $app->request->get('page');
        $key = Stock::class.$currentPage;
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

        return $this->render('stock',[
            'stock' => $result['stock'],
            'pagination' => $pagination,
        ]);
    }

    /**
     * Add new stock.
     *
     * @return string
     */
    public function actionAddStock()
    {
        $model = new Product();

        return $this->render('add-stock', [
            'model' => $model,
        ]);
    }


    public function getData($cache, $key, $currentPage, $limit)
    {
        $result = $cache->get($key);
        if (false === $result) {
            $result = $this->getStock($currentPage, $limit);
            $cache->set($key, $result);
        }

        return $result;
    }

    private function getStock($currentPage, $limit)
    {
        $query = Product::find();
        $total = $query->count();
        $stock = $query->orderBy('name')
                        ->offset(($currentPage-1)*$limit)
                        ->limit($limit)
                        ->all();

        return ['total' => $total, 'stock' => $stock];

    }
}
