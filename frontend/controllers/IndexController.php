<?php
namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsGallery;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Index;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;

class IndexController extends Controller{

    public function actionIndex(){
        //获取id
        $id = \Yii::$app->user->id;
        //根据id获取数据
        $model = Member::findOne(['id'=>$id]);
        //显示页面
        return $this->render('index',['model'=>$model]);
    }

    public function actionSee(){

        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['index/index']);
    }

    //商品展示
    public function actionList($id){
        //根据id查询数据
        $model = Goods::find()->where(['goods_category_id'=>$id])->all();
        //显示页面
        return $this->render('list',['model'=>$model]);
    }

    //商品详情
    public function actionDetails($id){
        //添加浏览数
        $goods = \frontend\models\Goods::findOne(['id'=>$id]);
        //创建request对象
        $request = \Yii::$app->request;
        if ($goods->validate()){
                $goods->view_times = $goods->view_times+1;
                $goods->save();
        }
        //根据id查询数据
        $model = Goods::findOne(['id'=>$id]);
        //显示页面
        return $this->render('goods',['model'=>$model]);
    }

    //添加购物车提示
    public function actionRemind($goods_id,$amount){
        //var_dump($goods_id);exit;
        //接收数据
        $request = \Yii::$app->request;
        $k = \Yii::$app->user->isGuest;
        //判断是否登录
        if ($k==false){
            //保存数据库
            //创建模型对象
                $cart = new Cart();
                $member_id = \Yii::$app->user->id;
//            var_dump($member_id);exit;
                    $carts  =Cart::find()->where(['member_id'=>$member_id])->andWhere(['goods_id'=>$goods_id])->one();
//                    var_dump($carts);exit;
                    if ($carts){
                        $carts->amount = $carts->amount+$amount;
                        $carts->save(0);

                    }else{
                        $cart->load($request->get(),'');
                        if ($cart->validate()){
                            $cart->member_id = \Yii::$app->user->id;
                            $cart->save();
                        }
                    }
        }else{
            $request = \Yii::$app->request;
            $goods = $request->get();
//            var_dump($goods);exit;
            $goods_id = $goods['goods_id'];
            $amount = $goods['amount'];
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //如果购物车存在该商品,则该商品的数量累加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id]=$amount;
            }
            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = 7*24*3600+time();
            //$cookie->expire
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }
        return $this->render('remind');
    }

    //购物车
    public function actionFlow(){

        if (\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
        }else{
            $cartss = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
//            var_dump($carts);exit;
            $carts = [];
            foreach ($cartss as $cart){
                $carts[$cart['goods_id']]=$cart['amount'];
            }
        }
        //var_dump($carts);exit;
        //显示页面
        return $this->render('flow1',['carts'=>$carts]);
    }

    //删除
    public function actionEdit($goods_id,$amount){
//var_dump($goods_id);
        if (\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //判断cookie里是否存在此商品
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //如果购物车存在该商品,则改变改商品
            if($amount){
                $carts[$goods_id] = $amount;
            }else{
                //如果没有该商品则删除该商品
                unset($carts[$goods_id]);
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookie->expire = 7*24*3600+time();
                $cookies = \Yii::$app->response->cookies;
                $cookies->add($cookie);
                return 'success';
            }
            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = 7*24*3600+time();
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{
            //实例化request组件
            $request = \Yii::$app->request;
            //实例化数据表
//            $model = new Cart();
            $carts = Cart::findOne(['goods_id'=>$goods_id]);
            if ($amount){
                $carts->amount = $amount;
                $carts->save(0);
            }else{
                $carts->delete();
                return 'success';
            }
        }
    }

    //结算
    public function actionFlow2(){
        $member_id = \Yii::$app->user->id;
        //查询地址
        $model = Address::find()->where(['member_id'=>$member_id])->all();
//        var_dump($model);exit;
        $cart = Cart::find()->where(['member_id'=>$member_id])->all();

        //配送方式
        $del = Delivery::find()->all();

        //支付方式
        $pay = Payment::find()->all();
        //显示页面
        return $this->render('flow2',['model'=>$model,'cart'=>$cart,'del'=>$del,'pay'=>$pay]);
    }

    //提交订单
    public function actionOrder(){
        //创建request对象
        $request = \Yii::$app->request;
        //接收数据
        if ($request->isPost){
            $order = new Order();
            //加载数据
            $order->load($request->post());
//var_dump($request->post());exit;
           $address = Address::findOne(['id'=>$request->post('address_id')]);
           $del = Delivery::findOne(['delivery_id'=>$request->post('delivery')]);
           $pay = Payment::findOne(['payment_id'=>$request->post('pay')]);
           $order->member_id = \Yii::$app->user->id;
           $order->name = $address->name;
           $order->province = $address->province;
           $order->city = $address->city;
           $order->area = $address->area;
           $order->address = $address->address;
           $order->tel = $address->phone;
           $order->delivery_id = $del->delivery_id;
           $order->delivery_price = $del->delivery_price;
           $order->delivery_name = $del->delivery_name;
           $order->payment_id = $pay->payment_id;
           $order->payment_name = $pay->payment_name;
           $art = Cart::findOne(['member_id'=>\Yii::$app->user->id]);
           $goods = \frontend\models\Goods::find()->where(['id'=>$art->goods_id])->all();
            $order->total = $order->delivery_price;
           $order->status = 1;
           $order->create_time = time();

            //在操作数据表之前开启事务
            //开启事务
            //\Yii::$app->db->createCommand($sql)->execute();
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //保存数据库
                $order->save();
                //保存订单详情
                $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                foreach ($carts as $cart) {
                    $goods = Goods::findOne(['id' => $cart->goods_id]);
                    //检查库存
                    if ($goods->stock < $cart->amount) {
                        //如果商品库存不足,抛出异常
                        throw new Exception('商品[' . $goods->name . ']库存不足');

                    }
                    //扣减商品库存
                    $goods->stock -= $cart->amount;
                    $goods->save();
                    $orderGoods = new OrderGoods();
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $goods->id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->price = $goods->shop_price;
                    //.....
                    $orderGoods->total = $orderGoods->price * $orderGoods->amount;
                    //订单总金额累加
                    $order->total += $orderGoods->total;
                    $orderGoods->save();
                }
                $order->save();

                //清除购物车
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                //提交事务
                $transaction->commit();
            }catch (Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }

       return $this->redirect(['index/orders']);
    }

    //订单列表
    public function actionOrders(){

        $orders = Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();

        return $this->render('catr',['orders'=>$orders]);
    }
}