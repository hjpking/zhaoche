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
        $uName = $this->input->get_post('uname');
        $uPhone = $this->input->get_post('user_phone');
        $uSex = $this->input->get_post('user_sex');
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
            //$ruleData = $this->rule->getRuleById($carType, '*', array('sid' => $serviceType));
            $ruleData = $this->rule->getRule(1, 0, '*', array('sid' => $serviceType, 'lid' => $carType, 'city_id' => $cityId));
            foreach ($ruleData as $v) { $ruleData = $v; }

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
                'uname' => $uName ? $uName : $uData['uname'],//如果有传用户名/手机/性别则是派车接车
                'user_phone' => $uPhone ? $uPhone : $uData['phone'],
                'user_sex' => $uSex ? $uSex : $uData['sex'],
                'amount' => 0,//$amount,
                'base_price' => $ruleData['base_price'],
                'km_price' => $ruleData['km_price'],
                'service_km' => $ruleData['service_km'],
                'time_price' => $ruleData['time_price'],
                'service_time' => $ruleData['service_time'],
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

            $message = '您在'.APP_NAME.'成功下单，正在为您分配车辆。';
            $this->sendMessage($uData['phone'], $message);

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
            $field = 'order_sn,city_id,chauffeur_id,uid,uname,user_phone,chauffeur_login_name,chauffeur_phone,amount,status,sid,car_time,lid,car_length,
                train_address,getoff_address,train_time,getoff_time,create_time,is_invoice,payable,content,mailing_address,leave_message, train_address_desc,getoff_address_desc';
            $orderData = $this->order->getOrder($defLimit, $defOffset, $field, array('uid' => $uId));

            if (isset ($orderData['chauffeur_id'])) {
                $this->load->model('model_chauffeur', 'chauffeur');
                $chauffeurData = $this->chauffeur->getChauffeurById($orderData['chauffeur_id']);

                $this->load->model('model_car', 'car');
                $carData = $this->car->getCarById($chauffeurData['car_id']);
                $data['car_no'] = $chauffeurData['car_no'];
                $data['car_name'] = $carData['name'];
            }


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

            $startTime = date('Y-m-d H:i:s', strtotime($startTime));//.' 00:00:00';
            $endTime = date('Y-m-d H:i:s', strtotime($endTime));//.' 23:59:59';

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $field = '*';

            $this->load->model('model_service_type', 'service');
            $serviceData = $this->service->getServiceType(1000);

            //$field = '*';
            $where = array(
                'chauffeur_id' => $chauffeurId,
                'create_time >' => $startTime,
                'create_time <' => $endTime,
            );
            $status && $where['status'] = $status;

            $this->load->model('model_order', 'order');
            $orderData = $this->order->getOrder($limit, $offset, $field, $where);

            foreach ($orderData as &$value) {
                foreach ($serviceData as $sd) {
                    if ($sd['sid'] == $value['sid'])
                        $value['service_name'] = $sd['name'];
                }
            }

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

            $this->load->model('model_service_type', 'service');
            $serviceData = $this->service->getServiceType(1000);

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn, '*', array('chauffeur_id' => $chauffeurId));
            foreach ($serviceData as $sd) {
                if ($sd['sid'] == $data['sid'])
                    $data['service_name'] = $sd['name'];
            }

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

            if ($orderData['status'] == 1) {
                $response = error(10027);//此订单已完成
                break;
            }

            if ($orderData['status'] == 2) {
                break;
            }

            if ($orderData['uid'] != $uData['uid']) {
                $response = error(10033);//订单不属于您
                break;
            }

            if ($orderData['status'] == 4) {
                $response = error(10041);//服务中不可取消订单
                break;
            }

            $s = $this->order->cancelOrderByUser($uId, $orderSn);
            if (!$s) {
                $response = error(10025);//取消订单失败
                break;
            }

            //$this->db->set(array('amount' => 'amount+'.$orderData['amount']), '', false)->where('uid', $uData['uid'])->update('user');

            $orderTime = strtotime($orderData['create_time']);
            $times = TIMESTAMP - $orderTime;

            //判断用户是在司机接单前取消订单还是接单后取消订单
            if (empty ($orderData['chauffeur_id'])) {
                if (!empty ($orderData['user_phone'])) {
                    $msg = '您的订单已取消，感谢你选择'.APP_NAME.'。';
                    $this->sendMessage($orderData['user_phone'], $msg);
                }
            } else {

                //计算订单产生的劳务费用
                if ($times > CHAUFFEUR_TIMEOUT) {
                    $uText = '因这司机迟到，将不产生费用。';
                    $cText = '因为您的迟到，没有相应佣金。';
                } else {
                    $laborCost = $orderData['status'] == 7 ? 20 : 10;
                    $uText = '取消订单产生的司机劳务费 '.$laborCost.' 元。已从你的账号内扣除。';
                    $cText = '您此次出车将获得 '.$laborCost.' 元 佣金。';
                }

                if (!empty ($orderData['user_phone'])) {
                    $msg = '您的订单已取消，'.$uText;
                    $this->sendMessage($orderData['user_phone'], $msg);
                }

                if (!empty ($orderData['chauffeur_phone'])) {
                    $msg = '用户已取消订单，'.$cText;
                    $this->sendMessage($orderData['chauffeur_phone'], $msg);
                }
            }

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
            if ($a['distance'] == $b['distance']) {
                return 0;
            }

            return ($a['distance'] < $b['distance']) ? -1 : 1;
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

            $this->load->model('model_service_type', 'service');
            $serviceData = $this->service->getServiceType(1000);

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

            //*
            //对数据进行截取
            $rData = array();
            $i = 0;
            foreach ($data as $value) {
                if (!isset ($value['order_sn'])) continue;

                foreach ($serviceData as $sd) {
                    if ($sd['sid'] == $value['sid'])
                        $value['service_name'] = $sd['name'];
                }
                if ($offset == $i || $i < $limit) $rData[] = $value;

                $i++;
            }

            //*/
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

            $code = rand(1000, 9999);
            $cData = array(
                'chauffeur_id' => $chauffeurData['chauffeur_id'],
                'chauffeur_login_name' => $chauffeurData['cname'],
                'chauffeur_phone' => $chauffeurData['phone'],
                'pay_password' => $code,
            );
            $s = $this->order->chauffeurDetermineOrder($cData, $orderSn);
            if (!$s) {
                $response = error(10029);//接单失败
                break;
            }

            $color = config_item('color');
            if (!empty ($data['user_phone'])) {
                $msg = '您的订单分配车辆成功，司机'.$chauffeurData['realname'].'电话：'.$chauffeurData['phone'].'驾驶. '.$color[$chauffeurData['color_id']]['name'].'车,';
                $msg .= '车牌号:'.$chauffeurData['car_no'].'已经出发，您可以随时登陆客户端查看车辆的行驶轨迹。支付密码：'.$code.'。';
                $this->sendMessage($data['user_phone'], $msg);
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
        $mileage = intval($this->input->get_post('mileage'));
        $travelTime = intval($this->input->get_post('travel_time'));
        $highSpeedCharge = intval($this->input->get_post('high_speed_charge'));
        $airportServiceCharge = intval($this->input->get_post('airport_service_charge'));
        $parkCharge = intval($this->input->get_post('park_charge'));


        $response = array('code' => '0', 'msg' => '确认成功');

        do {
            if (empty ($chauffeurId) || empty ($orderSn) || empty ($mileage) || empty ($travelTime)) {
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

            /* 计算费用开始 */
            $currentHours = date('H', TIMESTAMP);
            $nightServiceCharge = ($currentHours >= NIGHT_START_TIME && $currentHours <= NIGHT_END_TIME) ? $data['night_service_charge'] : 0;

            $exceedKm = ceil($mileage - $data['service_km']);//超出公里数
            $exceedKm = ($exceedKm < 1) ? 0 : $exceedKm;
            $exceedTIme = ceil($travelTime - $data['service_time']);//超出时间
            $exceedTIme = ceil($exceedTIme / $data['time']);
            $exceedTIme = ($exceedTIme < 1) ? 0 : $exceedTIme;

            $exceedKmFee = ceil($exceedKm * $data['km_price']);//超出公里数费用
            $exceedTImeFee = ceil($exceedTIme * $data['time_price']);//超出时间费用

            '基础价格＋(超出公里数＊公里单价)+(超时时长＊超时单价)+高速费+停车费+夜间服务费+机场服务费';

            //整体费用
            $totalPrice = $data['base_price'] + $exceedKmFee + $exceedTImeFee + $highSpeedCharge + $airportServiceCharge + $parkCharge + $nightServiceCharge;
            /* 计算费用结束 */

            $upData = array(
                'total_price' => $totalPrice,
                'exceed_km' => $exceedKm,
                'exceed_km_fee' => $exceedKmFee,
                'exceed_time' => $exceedTIme,
                'exceed_time_fee' => $exceedTImeFee,
                'high_speed_fee' => $highSpeedCharge,
                'park_fee' => $parkCharge,
                'air_service_fee' => $airportServiceCharge,
                'mileage' => $mileage,
                'travel_time' => $travelTime,
            );
            //$totalPrice = 15000;
            $s = $this->order->confirmArrival($chauffeurId, $orderSn, $upData);
            if (!$s) {
                $response = error(10031);//确认到达失败
                break;
            }

            $rData = array(
                'total_price' => $totalPrice,
                'exceed_km' => $exceedKm,
                'exceed_time' => $exceedTIme,
                'exceed_km_fee' => $exceedKmFee,
                'exceed_time_fee' => $exceedTImeFee,
                'base_fee' => $data['base_price'],
                'high_speed_fee' => $highSpeedCharge,
                'night_service_fee' => $nightServiceCharge,
                'park_fee' => $parkCharge,
                'air_service_fee' => $airportServiceCharge,
                'mileage' => $mileage,
                'travel_time' => $travelTime,
            );
            $response['data'] = $rData;

            $msg = '尊敬的：'.$data['user_phone'].', 您于'.date('Y-m-d H:i').'使用'.APP_NAME.'服务共消费：'.fPrice($rData['total_price']).'元,您需要支付：';
            $msg .= fPrice($rData['total_price']).'元。';
            $this->sendMessage($data['user_phone'], $msg);
        } while (false);

        $this->json_output($response);
    }

    /**
     * 订单支付
     */
    public function orderPay()
    {
        $orderSn = intval($this->input->get_post('order_sn'));
        $payPassword = $this->input->get_post('password');

        $response = array('code' => '0', 'msg' => '支付成功');

        do {
            if (empty ($orderSn) || empty ($payPassword)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_order', 'order');
            $data = $this->order->getOrderById($orderSn);
            if (empty ($data)) {
                $response = error(10024);//订单不存在
                break;
            }

            if ($data['status'] == '0') {
                $response = error(10030);//此订单未被接单
                break;
            }

            if ($data['status'] == '2') {
                $response = error(10028);//此订单已取消
                break;
            }

            if ($data['status'] != '1') {
                $response = error(10046);//此订单未完成
                break;
            }

            if ($payPassword != $data['pay_password']) {
                $response = error(10047);//支付密码错误
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($data['uid']);
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            //判断用户余额是否小于订单余额
            if ($uData['amount'] < $data['amount']) {
                $response = error(10023);//余额不足
                break;
            }

            $s = $this->db->set(array('amount' => 'amount-'.$data['amount']), '', false)->where('uid', $data['uid'])->update('user');
            if (!$s) {
                $response = error(10048);//支付失败
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
                //$response = error(10030);//此订单未被接单
                //break;
            }

            if ($data['status'] == '4') {
                $response = error(10041);//服务中不可取消订单
                break;
            }

            $s = $this->order->cancelOrderByChauffeur($chauffeurId, $orderSn);
            if (!$s) {
                $response = error(10034);//取消订单失败
                break;
            }

            if (empty ($data['arrival_time'])) {
                if (!empty ($data['user_phone'])) {
                    $msg = '接您的司机 '.$chauffeurData['realname'].' 因故无法到达，司机已取消订单。';
                    $this->sendMessage($data['user_phone'], $msg);
                }

                if (!empty ($data['chauffeur_phone'])) {
                    $msg = '您已成功取消订单，系统将扣除您账号内10元，赔偿给用户。';
                    $this->sendMessage($data['chauffeur_phone'], $msg);
                }
            } else {
                $arrivalTime = strtotime($data['arrival_time']);
                $arrivalTime = TIMESTAMP - $arrivalTime;
                if ($arrivalTime > CHAUFFEUR_USER_TRAIN_TIMEOUT) {
                    if (!empty ($data['user_phone'])) {
                        $t = intval(CHAUFFEUR_USER_TRAIN_TIMEOUT / 60);
                        $msg = '由于等候的时间已经超过'.$t.'分钟,并且您也不同意开始计费。司机'.$chauffeurData['realname'].'已离开，此次将产生10元司机服务费，敬请谅解！';
                        $this->sendMessage($data['user_phone'], $msg);
                    }

                    if (!empty ($data['chauffeur_phone'])) {
                        $msg = '您已成功取消订单，由于用户长时间不车。';
                        $this->sendMessage($data['chauffeur_phone'], $msg);
                    }
                }
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
                //$response = error(10030);//此订单未被接单
                //break;
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

            if ($data['status'] == '1') {
                $response = error(10027);//此订单已完成
                break;
            }

            if ($data['status'] == '2') {
                $response = error(10028);//此订单已取消
                break;
            }

            if ($data['status'] == '6') {
                //$response = error(10043);//司机未出发
                //break;
            }

            if ($data['status'] == '7') {
                $response = error(10044);//用户未上车
                break;
            }

            if ($chauffeurData['chauffeur_id'] != $data['chauffeur_id']) {
                $response = error(10033);//订单不属于您
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
                //$response = error(10036);//您离用户上车地点超过1000米
                //bin_addressreak;
            }

            $color = config_item('color');
            $msg = $data['uname'].' 您好，接您的车已到达您的上车位点，您可以出发了。';//'司机：'.$chauffeurData['realname'].'，车牌号：'.$chauffeurData['car_no'];
            //$msg .= ',车颜色：'.$color[$chauffeurData['color_id']]['name'].'。';
            $this->sendMessage($data['user_phone'], $msg);

            $upData = array(
                'status' => '7',
                'arrival_time' => date('Y-m-d H:i:s', TIMESTAMP)
                );
            $this->db->where('order_sn', $orderSn);
            $this->db->where('chauffeur_id', $chauffeurId);
            $this->db->update('order', $upData);
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
                //$response = error(10030);//此订单未被接单
                //break;
            }

            $this->db->where('order_sn', $orderSn);
            $this->db->where('chauffeur_id', $chauffeurId);
            $this->db->update('order', array('status' => '4','train_time' => date('Y-m-d H:i:s', TIMESTAMP)));
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

            if (isset ($data['chauffeur_id'])) {
                $this->load->model('model_chauffeur', 'chauffeur');
                $chauffeurData = $this->chauffeur->getChauffeurById($data['chauffeur_id']);

                $this->load->model('model_car', 'car');
                $carData = $this->car->getCarById($chauffeurData['car_id']);
                $data['car_no'] = $chauffeurData['car_no'];
                $data['car_name'] = $carData['name'];
            }



            $response['data'] = $data;
            //$this->db->where('order_sn', $orderSn);
            //$this->db->where('chauffeur_id', $chauffeurId);
            //$this->db->update('order', array('train_time' => date('Y-m-d H:i:s', TIMESTAMP)));
        } while (false);

        $this->json_output($response);
    }

    /**
     * 车辆已出发
     */
    public function carBeStart()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $orderSn = intval($this->input->get_post('order_sn'));

        $response = array('code' => '0', 'msg' => '出发成功');

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

            if ($data['status'] == '6') {
                break;
            }

            $this->db->where('order_sn', $orderSn);
            $this->db->where('chauffeur_id', $chauffeurId);
            $this->db->update('order', array('status' => '6'));

		$msg = '车辆已出发，登陆客户端查看车辆行驶路线。';
		$this->sendMessage($data['user_phone'], $msg);
        } while (false);

        $this->json_output($response);
    }

    /**
     * 系统检查订单是否超过35分钟，如果超过则给用户发短信并取消订单
     */
    public function systemCheckOrder()
    {
        $this->load->model('model_order', 'order');
        $data = $this->order->getOrder(1000, 0, $field= '*', array('status' => '0'));

        foreach ($data as $v) {
            $orderTime = strtotime($v['create_time']);
            $diffTime = TIMESTAMP - $orderTime;
            if ($diffTime > ORDER_TIMEOUT) {
                if (!empty ($data['user_phone'])) {
                    $msg = '已经半个多小时了，附近没有符合你要要求的车型，订单被取消,十分抱歉。';
                    $this->sendMessage($data['user_phone'], $msg);
                }

                $this->order->saveOrder(array('status' => '2'), $v['order_sn']);
            }
        }
    }
}
