<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-13
 * Time: 下午9:12
 * To change this template use File | Settings | File Templates.
 */
class order extends MY_Controller
{
    /**
     * 下单
     */
    public function orders()
    {
        $cityId = intval($this->input->get_post('city_id'));
        $serviceType = intval($this->input->get_post('service_type'));
        $carType = intval($this->input->get_post('car_type'));
        $carTime = $this->input->get_post('car_time');
        $carLength = intval($this->input->get_post('car_length'));
        $trainAddress = $this->input->get_post('train_address');
        $trainAddressDesc = $this->input->get_post('train_address_desc');
        $getoffAddress = $this->input->get_post('getoff_address');
        $getoffAddressDesc = $this->input->get_post('getoff_address_desc');
        $addressSupplemental = $this->input->get_post('address_supplemental');
        $token = $this->input->get_post('token');
        $isInvoice = intval($this->input->get_post('is_invoice'));
        $payable = $this->input->get_post('payable');
        $content = $this->input->get_post('content');
        $mailingAddress = $this->input->get_post('mailing_address');
        //$amount = intval($this->input->get_post('amount'));
        $leaveMessage = $this->input->get_post('leave_message');
        $flight = $this->input->get_post('flight');
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '下单成功');

        //'?city_id=1&service_type=1&car_type=1&car_time=2013-03-28 12:00:00&car_length=30&train_address=39.903021,116.440487&getoff_address=39.903021,116.440487&address_supplemental=天安门广场&leave_message=麻烦快点过来&token=529262eaa1f89e6dc5153ea8577e58c6&is_invoice=1&payable=东方佛祖&content=租车&mailing_address=东方佛祖广场整栋&amount=25000&leave_message=你要快点到哦&flight=HU7636';
        do {
            if (empty ($cityId) || empty ($serviceType) || empty ($carType) || empty ($carTime) || empty ($trainAddressDesc) ||
                empty ($getoffAddressDesc) || empty ($trainAddress) || empty ($getoffAddress) || empty ($token)) {
                $response = error(10001);//参数不全
                break;
            }

            $uInfo = $this->analyzeToken($token);
            if (!$uInfo) {
                $response = error(10011);//用户未登陆
                break;
            }

            $uId = $uInfo[0];
            if (!$uId) {
                $response = error(10009);//错误的token
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($uId, 'uid, uname, password, realname, amount, sex, phone,binding_type, is_del, create_time');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != $uInfo[2]) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            //if ($uData['amount'] < $amount) {
                //$response = error(10023);//余额不足
                //break;
            //}

            $this->load->model('model_city', 'city');
            $cityData = $this->city->getCityById($cityId);
            if (!$cityData) {
                $response = error(10014);//城市不存在
                break;
            }

            $this->load->model('model_service_type', 'st');
            $sfData = $this->st->getServiceTypeById($serviceType);
            if (!$sfData) {
                $response = error(10015);//服务类别不存在
                break;
            }

            $this->load->model('model_rule', 'rule');
            $ruleData = $this->rule->getRuleById($carType, '*', array('sid' => $serviceType));
            //$this->load->model('model_car', 'car');
            //$carData = $this->car->getCarById($carType);
            if (!$ruleData) {
                $response = error(10017);//车型不存在
                break;
            }

            $this->load->model('model_order', 'order');
            if ($orderSn) {
                $orderData = $this->order->getOrderById($orderSn);
                if (empty ($orderData)) {
                    $response = error(10024); //订单不存在
                    break;
                }
            }

            //p($uData);

            $data = array(
                'city_id' => $cityId,
                'sid' => $serviceType,
                'lid' => $carType,
                'uid' => $uData['uid'],
                'uname' => $uData['uname'],
                'user_phone' => $uData['phone'],
                'user_sex' => $uData['sex'],
                'amount' => 0,//$amount,
                'base_price' => $ruleData['base_price'],
                'km_price' => $ruleData['km_price'],
                'time_price' => $ruleData['time_price'],
                'night_service_charge' => $ruleData['night_service_charge'],
                'kongshi_fee' => $ruleData['kongshi_fee'],
                'status' => '0',
                'car_time' => $carTime,
                'car_length' => $carLength,
                'train_address' => $trainAddress,
                'train_address_desc' => $trainAddressDesc,
                'address_supplemental' => $addressSupplemental,
                'getoff_address' => $getoffAddress,
                'getoff_address_desc' => $getoffAddressDesc,
                'is_invoice' => $isInvoice,
                'payable' => $payable,
                'content' => $content,
                'mailing_address' => $mailingAddress,
                'leave_message' => $leaveMessage,
                'notice' => $flight,
            );

            $lastId = $this->order->saveOrder($data, $orderSn);

            if (!$lastId) {
                $response = error(10016);//下单失败
                break;
            }

            //判断返回的值是否为布尔值，如果不是则将订单号重置
            if (!is_bool($lastId)) $orderSn = $lastId;

            //$s = $this->user->save(array('amount' => "amount-$amount"), $uData['uid']);
            //$this->db->set(array('amount' => 'amount-'.$amount), '', false)->where('uid', $uData['uid'])->update('user');
            $response['data'] = $orderSn;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 我的订单
     */
    public function myOrder()
    {
        $defLimit = 20;
        $defOffset = 0;

        $token = trim($this->input->get_post('token'));
        $limit = trim($this->input->get_post('limit'));
        $offset = trim($this->input->get_post('offset'));

        $limit && $defLimit = $limit;
        $offset && $defOffset = $offset;

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($token)) {
                $response = error(10001);//参数不全
                break;
            }

            $uInfo = $this->analyzeToken($token);
            if (!$uInfo) {
                $response = error(10011);//用户未登陆
                break;
            }

            $uId = $uInfo[0];
            if (!$uId) {
                $response = error(10009);//错误的token
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($uId, 'uname, password, realname, amount, sex, phone,binding_type, is_del, create_time');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != $uInfo[2]) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }



            $this->load->model('model_order', 'order');
            $field = 'order_sn,city_id,chauffeur_id,uid,uname,user_phone,chauffeur_login_name,chauffeur_phone,amount,status,sid,
                train_address,getoff_address,train_time,getoff_time,create_time,is_invoice,payable,content,mailing_address,leave_message, train_address_desc,getoff_address_desc';
            $orderData = $this->order->getOrder($defLimit, $defOffset, $field, array('uid' => $uId));

            $response['data'] = $orderData;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机订单
     */
    public function chauffeurOrder()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $startTime = $this->input->get_post('start_time');
        $endTime = $this->input->get_post('end_time');
        $start = intval($this->input->get_post('limit'));
        $number = intval($this->input->get_post('offset'));
        $status = intval($this->input->get_post('status'));

        $limit = 20;
        $offset = 0;
        $start && $limit = $start;
        $number && $offset = $number;

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($chauffeurId) || empty ($startTime) || empty ($endTime)) {
                $response = error(10001);//参数不全
                break;
            }

            $startTime = date('Y-m-d', strtotime($startTime)).'00:00:00';
            $endTime = date('Y-m-d', strtotime($endTime)).'23:59:59';

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $field = 'order_sn,city_id,chauffeur_id,uid,uname,user_phone,chauffeur_login_name,chauffeur_phone,amount,status,sid, 
                train_address,getoff_address,train_time,getoff_time,create_time,is_invoice,payable,content,mailing_address,leave_message, train_address_desc,getoff_address_desc';
            $where = array(
                'chauffeur_id' => $chauffeurId,
                'create_time >=' => $startTime,
                'create_time <=' => $endTime,
            );
            $status && $where['status'] = 1;

            $this->load->model('model_order', 'order');
            $orderData = $this->order->getOrder($limit, $offset, $field, $where);
            $response['data'] = $orderData;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机订单详情
     */
    public function chauffeurOrderInfo()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (!$data) {
                $response = error(10018);//订单不存在
                break;
            }

            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 用户取消订单
     */
    public function userCancelOrder()
    {
        $orderSn = intval($this->input->get_post('order_sn'));
        $token = trim($this->input->get_post('token'));

        $response = array('code' => '0', 'msg' => '取消成功');

        do {
            if (empty ($orderSn) || empty ($token)) {
                $response = error(10001);//参数不全
                break;
            }

            $uInfo = $this->analyzeToken($token);
            if (!$uInfo) {
                $response = error(10011);//用户未登陆
                break;
            }

            $uId = $uInfo[0];
            if (!$uId) {
                $response = error(10009);//错误的token
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($uId, 'uid, uname, password, realname, amount, sex, phone,binding_type, is_del, create_time');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != $uInfo[2]) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            $this->load->model('model_order', 'order');
            $orderData = $this->order->getOrderById($orderSn, '*', array('uid' => $uId));

            if (empty ($orderData)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($orderData['status'] == 2) {
                break;
            }

            $s = $this->order->cancelOrderByUser($uId, $orderSn);
            if (!$s) {
                $response = error(10025);//取消订单失败
                break;
            }

            $this->db->set(array('amount' => 'amount+'.$orderData['amount']), '', false)->where('uid', $uData['uid'])->update('user');
        } while (false);

        $this->json_output($response);
    }

    /**
     * 待接订单
     */
    public function pendingOrders()
    {
        function cmp($a, $b)
        {
            $a = $a['distance'];
            $b = $b['distance'];
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }

        $cityId = intval($this->input->get_post('city_id'));
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');
        $start = intval($this->input->get_post('limit'));
        $number = intval($this->input->get_post('offset'));

        $limit = 50;
        $offset = 0;
        $start && $limit = $start;
        $number && $offset = $number;

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($cityId) || empty ($longitude) || empty ($latitude)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrder(1000, 0, '*', array('status' => '0', 'city_id' => $cityId));

            foreach ($data as &$v) {
                if (empty ($v['train_address'])) {
                    unset ($v);
                    continue;
                }
                $arr = explode(',', $v['train_address']);
                if (empty ($arr[0]) || empty ($arr[1])) {
                    unset ($v);
                    continue;
                }
                $v['distance'] = getDistance($longitude, $latitude, $arr[0], $arr[1]);
                //$v['distance'] = $distance;
            }

            //对距离进行升序排序
            uasort($data, "cmp");

            //对数据进行截取
            $rData = array();
            $i = 0;
            foreach ($data as $v) {
                if (!isset ($v['order_sn'])) continue;

                if ($offset == $i || $i < $limit) $rData[] = $v;

                $i++;
            }

            $response['data'] = $rData;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机确定接单
     */
    public function determineOrder()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '接单成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn);
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '1') {
                $response = error(10027);//此订单已完成
                break;
            }

            if ($data['status'] == '2') {
                $response = error(10028);//此订单已取消
                break;
            }

            if ($data['status'] != '0') {
                $response = error(10026);//此订单已被其他司机接单
                break;
            }

            $cData = array(
                'chauffeur_id' => $chauffeurData['chauffeur_id'],
                'chauffeur_login_name' => $chauffeurData['cname'],
                'chauffeur_phone' => $chauffeurData['phone'],
            );
            $s = $this->order->chauffeurDetermineOrder($cData, $orderSn);
            if (!$s) {
                $response = error(10029);//接单失败
                break;
            }
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机确认到达
     */
    public function confirmArrival()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '确认成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($data['status'] == '1') {
                $response = error(10027);//此订单已完成
                break;
            }

            if ($data['status'] == '2') {
                $response = error(10028);//此订单已取消
                break;
            }

            $s = $this->order->confirmArrival($chauffeurId, $orderSn);
            if (!$s) {
                $response = error(10031);//确认到达失败
                break;
            }
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机取消接单
     */
    public function chauffeurCancelOrder()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '取消成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($data['status'] == '1') {
                $response = error(10032);//订单已成功，无法取消
                break;
            }

            if ($chauffeurData['chauffeur_id'] != $data['chauffeur_id']) {
                $response = error(10033);//订单不属于您
                break;
            }

            if ($data['status'] != '3') {
                $response = error(10030);//此订单未被接单
                break;
            }

            $s = $this->order->cancelOrderByChauffeur($chauffeurId, $orderSn);
            if (!$s) {
                $response = error(10034);//取消订单失败
                break;
            }
        } while (false);

        $this->json_output($response);
    }

    /**
     * 回报车辆行驶路径
     */
    public function reportRunRd()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');

        $response = array('code' => '0', 'msg' => '回报成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn) || empty ($longitude) || empty ($latitude)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($chauffeurData['chauffeur_id'] != $data['chauffeur_id']) {
                $response = error(10033);//订单不属于您
                break;
            }

            if ($data['status'] != '3') {
                $response = error(10030);//此订单未被接单
                break;
            }

            $data = array(
                'order_sn' => $orderSn,
                'chauffeur_id' => $chauffeurId,
                'longitude' => $longitude,
                'latitude' => $latitude,
            );
            $s = $this->order->reportRunRd($data);
            if (!$s) {
                $response = error(10013);//回报位置失败
                break;
            }
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机到达用户上车地点
     */
    public function chauffeurArrivalUserTrainLocation()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');

        $response = array('code' => '0', 'msg' => '通知成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn) || empty ($longitude) || empty ($latitude)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($chauffeurData['chauffeur_id'] != $data['chauffeur_id']) {
                $response = error(10033);//订单不属于您
                break;
            }

            if ($data['status'] != '3') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if (empty ($data['train_address'])) {
                $response = error(10035);//用户上车地址错误
                break;
            }

            $arr = explode(',', $data['train_address']);
            if (empty ($arr[0]) || empty ($arr[1])) {
                $response = error(10035);//用户上车地址错误
                break;
            }
            $distance = getDistance($longitude, $latitude, $arr[0], $arr[1]);

            if ($distance > 1000) {
                $response = error(10036);//您离用户上车地点超过1000米
                break;
            }

            $content = $data['uname'].' 您好，司机：'.$data['chauffeur_login_name'].'已达到你要上车的地点。请与其联系：'.$data['chauffeur_phone'];
            sendMessage($data['user_phone'], $content);
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机开始订单
     */
    public function startOrder()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '开始成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($chauffeurData['chauffeur_id'] != $data['chauffeur_id']) {
                $response = error(10033);//订单不属于您
                break;
            }

            if ($data['status'] != '3') {
                $response = error(10030);//此订单未被接单
                break;
            }

            $this->db->where('order_sn', $orderSn);
            $this->db->where('chauffeur_id', $chauffeurId);
            $this->db->update('order', array('train_time' => date('Y-m-d H:i:s', TIMESTAMP)));
        } while (false);

        $this->json_output($response);
    }

    /**
     * 获取订单信息 -- 通过订单ID
     */
    public function getOrderByOrderSn()
    {
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($orderSn)) {
                $response = error(10001); //参数不全
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn);
            if (empty ($data)) {
                $response = error(10024); //订单不存在
                break;
            }

            $response['data'] = $data;
            //$this->db->where('order_sn', $orderSn);
            //$this->db->where('chauffeur_id', $chauffeurId);
            //$this->db->update('order', array('train_time' => date('Y-m-d H:i:s', TIMESTAMP)));
        } while (false);

        $this->json_output($response);
    }



}
