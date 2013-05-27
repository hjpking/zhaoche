<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午7:23
 * To change this template use File | Settings | File Templates.
 */
class car extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('model_car', 'car');
        $carInfo = $this->car->getCar(1000);

        $carLevelInfo = $this->car->getCarLevel(1000);

        $data = array(
            'data' => $carInfo,
            'car_level_data' => $carLevelInfo,
            'is_car_model' => config_item('is_car_model'),
        );

        $this->load->view('car/index', $data);
    }

    public function create()
    {
        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $carInfo = $this->car->getCar(1000);

        $this->load->view('car/create', array('car_level_data' => $carLevelInfo, 'car_data' => $carInfo));
    }

    public function edit()
    {
        $carId = $this->uri->segment(3);

        if (!$carId) {
            show_error('车辆ID为空！');
        }

        $this->load->model('model_car', 'car');
        $carInfo = $this->car->getCarById($carId);

        $carLevelInfo = $this->car->getCarLevel(1000);

        $carData = $this->car->getCar(1000);

        $data = array(
            'data' => $carInfo,
            'car_level_data' => $carLevelInfo,
            'car_data' => $carData,
            'isEdit' => 1
        );
        $this->load->view('car/create', $data);
    }

    public function save()
    {
        $name = $this->input->get_post('name');
        $parentId = $this->input->get_post('parent_id');
        $lId = $this->input->get_post('lid');
        $descr = $this->input->get_post('descr');
        $isCarModel = $this->input->get_post('is_car_model');
        $carId = $this->input->get_post('car_id');

        $data = array(
            'lid' => $lId,
            'parent_id' => $parentId,
            'name' => $name,
            'descr' => $descr,
            'is_car_model' => $isCarModel,
        );

        $this->load->model('model_car', 'car');
        $this->car->save($data, $carId);

        $this->load->helper('url');
        redirect('car/index');
    }

    public function delete()
    {
        $carId = $this->uri->segment(3);

        if (!$carId) {
            show_error('车辆ID为空!');
        }

        $this->load->model('model_car', 'car');
        $this->car->delete($carId, 0);

        $this->load->helper('url');
        redirect('car/index');
    }




    public function service_type_index()
    {
        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->view('car/service_type_index', array('sf_data' => $sfInfo));
    }

    public function service_type_create()
    {
        $this->load->view('car/service_type_create');
    }

    public function service_type_edit()
    {
        $sId = $this->uri->segment(3);

        if (!$sId) {
            show_error('服务类别ID为空!');
        }

        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceTypeById($sId);

        $this->load->view('car/service_type_create', array('data' => $sfInfo, 'isEdit' => 1));
    }

    public function service_type_save()
    {
        $name = $this->input->get_post('name');
        $descr = $this->input->get_post('descr');
        $sId = $this->input->get_post('sid');

        $data = array(
            'name' => $name,
            'descr' => $descr,
        );

        $this->load->model('model_service_type', 'st');
        $this->st->save($data, $sId);

        $this->load->helper('url');
        redirect('car/service_type_index');
    }

    public function service_type_delete()
    {
        $sId = $this->uri->segment(3);

        if (!$sId) {
            show_error('服务类别ID为空!');
        }

        $this->load->model('model_service_type', 'st');
        $this->st->delete($sId, 0);

        $this->load->helper('url');
        redirect('car/service_type_index');
    }






    public function car_level_index()
    {
        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->view('car/car_level_index', array('data' => $carLevelInfo));
    }

    public function car_level_create()
    {
        $this->load->view('car/car_level_create');
    }

    public function car_level_edit()
    {
        $lId = $this->uri->segment(3);

        if (!$lId) {
            show_error('车辆等级ID为空!');
        }

        $this->load->model('model_car', 'car');
        $clInfo = $this->car->getCarLevelById($lId);

        $this->load->view('car/car_level_create', array('data' => $clInfo, 'isEdit' => 1));
    }

    public function car_level_save()
    {
        $name = $this->input->get_post('name');
        $descr = $this->input->get_post('descr');
        $price = intval($this->input->get_post('price'));
        $lId = intval($this->input->get_post('lid'));
        $price = fPrice($price, 4);

        $data = array(
            'name' => $name,
            'price' => $price,
            'descr' => $descr,
        );

        $this->load->model('model_car', 'car');
        $this->car->carLevelSave($data, $lId);

        $this->load->helper('url');
        redirect('car/car_level_index');
    }

    public function car_level_delete()
    {
        $lId = $this->uri->segment(3);

        if (!$lId) {
            show_error('车辆等级ID为空!');
        }

        $this->load->model('model_car', 'car');
        $this->car->carLevelDelete($lId, 0);

        $this->load->helper('url');
        redirect('car/car_level_index');
    }
}
