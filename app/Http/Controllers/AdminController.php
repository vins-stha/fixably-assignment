<?php

namespace App\Http\Controllers;

use App\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use League\Flysystem\Config;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('orders.index', ['token' => $this->getToken()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $id = "")
    {
        $uri = $request->getRequestUri();
        $url = 'https://careers-api.fixably.com/orders';


        if ($request->get('page') != null) {
            $url = 'https://careers-api.fixably.com' . $request->getRequestUri();
            $startPage = $request->get('page');
        }

        if ($request->get('page') == null && !(str_contains($uri, 'create'))) {
            $startPage = 1;

            if (str_contains($uri, 'statuses')) {
                //
                $this->orderByStatus();
            }
        }

        $token = $this->getToken();
        $orders = "";

        if ($token != null) {
            $response = Http::withHeaders([
                'X-Fixably-Token' => $token,
            ])->acceptJson()->get($url);

            if ($response->getStatusCode() == 200) {
                if ($response->json() != null) {
                    $orders = $response->json();
                    $totalOrders = $orders['total'];
                    $orders = $orders['results'];
                    $ordersPerPage = count($orders);

                    if ($orders % $ordersPerPage == 0) {
                        $totalPages = $totalOrders / $ordersPerPage;
                    } else {
                        $totalPages = ($totalOrders / $ordersPerPage);
                    }
                } else {
                    $orders = "";
                }
            }
        }
        $lastPage = $startPage + 15;
        return view('orders.orders', [
            'orders' => $orders,
            'startPage' => $startPage,
            'lastPage' => $lastPage,
            'total_pages' => $totalPages
        ]);
    }

    /*
     *
     * Get X-Fixably-token
     */
    public function getToken()
    {
        $url = 'https://careers-api.fixably.com/token';
        $token = '';
        $response = Http::asForm()->post($url, ['Code' => '70249670']);


        if (!$response->failed() && $response->getStatusCode() == 200) {
            $token = $response->json()['token'];

        }

        return $token;
    }

    public function status(Request $request)
    {
        set_time_limit(320);

        $ordersUrl = 'https://careers-api.fixably.com/orders?page=';

        $data['Open'] = $data['Closed'] = $data['Assigned'] = $data['Unpaid'] = 0;

        $url = 'https://careers-api.fixably.com/statuses';
        $token = $this->getToken();
        $orders = "";
        $lastPage = 10;


        if ($token != null) {
            for ($id = 1; $id <= 289; $id++) {
                $response = Http::withHeaders([
                    'X-Fixably-Token' => $token,
                ])->acceptJson()->get($ordersUrl . $id);

                if ($response->getStatusCode() == 200) {
                    $results = $response->json();
                    //file_put_contents($id.".txt", print_r($results, true));
                    $results = $results['results'];
                    foreach ($results as $result) {
                        if ($result['status'] == 1) {
                            $data['Open']++;
                        }
                        if ($result['status'] == 2) {
                            $data['Closed']++;
                        }
                        if ($result['status'] == 3) {
                            $data['Assigned']++;
                        }
                        if ($result['status'] == 4) {
                            $data['Unpaid']++;
                        }
                    }
                }
            }
            asort($data);

            return view('orders.statuses', [
                'data' => $data,
            ]);
        }
    }

    /*
     *
     *
     */
    public function create(Request $request)
    {
        $message = "";
        if ($request->getMethod() == 'POST') {
            $data['DeviceManufacturer'] = $request->post('manufacturer');
            $data['DeviceBrand'] = $request->post('brand');
            $data['DeviceType'] = $request->post('device');
            $token = $this->getToken();


            $response = Http::withHeaders(['X-Fixably-Token' => $token,])->asForm()->post('https://careers-api.fixably.com/orders/create', [
                'DeviceManufacturer' => $data['DeviceManufacturer'],
                'DeviceBrand' => $data['DeviceBrand'],
                'DeviceType' => $data['DeviceType']
            ]);

            if ($response->getStatusCode() == 200) {
                $message = $response->json()['message'];
                $message .= 'with id = ' . $response->json()['id'];
                $request->session()->flash('message', $message);
                //return view('orders.create');
            }

        }
        return view('orders.create', ['message', $message]);
    }

    public function search(Request $request)
    {
        $message = "";
        // if ($request->getMethod() == 'POST')
        {
            $data['notes'] = '';//;$request->post('notes');
            $data['statuses'] = 3;//;$request->post('notes');$request->post('status');
            $data['DeviceType'] = 'iphone';//;$request->post('notes');$request->post('device');
            $data['technicians'] = '';//;$request->post('notes');$request->post('technicians');
            $token = $this->getToken();


            $response = Http::withHeaders(['X-Fixably-Token' => $token,])->asForm()->post('https://careers-api.fixably.com/search/', [
                // 'notes' => $data['notes'],'
                //'statuses' => $data['statuses'],
                //'devices' =>$data['DeviceType'],'
                // 'technicians' => $data['technicians']
                'Criteria' => 'iPhone'
            ]);

            if ($response->getStatusCode() == 200) {
                $message = $response->json()['message'];
                $message .= 'with id = ' . $response->json()['id'];
                $request->session()->flash('message', $message);
                return view('orders.create');
            }

        }
        return view('orders.create', ['message', $message]);
    }

    public function generateReport(Request $request)
    {
        set_time_limit(300);
        //$data['fromDate'] = '2020-11-01';//;$request->post('notes');$request->post('device');
        //$data['toDate'] = '2020-11-30';//;$request->post('notes');$request->post('technicians');
        $token = $this->getToken();
        $sum1=$sum2=$sum3=$sum4=$sum5=0;
        $response =  Http::withHeaders([  'X-Fixably-Token' => $token,])->post('https://careers-api.fixably.com/report/2020-11-01/2020-11-30');

        if ($response->getStatusCode() == 200)
        {
            $results = $response->json();
            $total = $results['total'];
            $totalPage = $total % 10 == 0 ? $total / 10 : (intdiv($total , 10) + 1);

            //https://careers-api.fixably.com/report/2020-11-01/2020-11-30?page=2'
            for ($page = 1; $page <=$totalPage; $page++)
            {
                $response =  Http::withHeaders(['X-Fixably-Token' => $token,])->post('https://careers-api.fixably.com/report/2020-11-01/2020-11-30?page='.$page);
                $results = $response->json();
                foreach ( $results['results'] as $result)
                {
                    //foreach ($result as $key=>$value)
                    {
                        //$dateTime = $result['created'];
                        $dateTime = strtotime($result['created']);
                        $date = date('Y-m-d', $dateTime);
                        $dateArray = explode("-",$date);

                        $day = $dateArray[2];

                        if ($day < 8)
                        {
                            $week = 1;
                            $data[$week]['week'] = $week;
                            $data[$week]['amount'][] = $result['amount'];
                            $data[$week]['count'] = count($data[$week]['amount']);
                            $sum1 +=  $result['amount'];
                            $data[$week]['totalSum'] = $sum1;

                        }
                        elseif ($day < 15 && $day >=8)
                        {
                            $week = 2;
                            $data[$week]['week'] = $week;
                            $data[$week]['amount'][] = $result['amount'];
                            $data[$week]['count'] = count($data[$week]['amount']);
                            $sum2 +=  $result['amount'];
                            $data[$week]['totalSum'] = $sum2;
                        }
                        elseif ($day < 22 && $day >=15)
                        {
                            $week = 3;
                            $data[$week]['week'] = $week;
                            $data[$week]['amount'][] = $result['amount'];
                            $data[$week]['count'] = count($data[$week]['amount']);
                            $sum3 +=  $result['amount'];
                            $data[$week]['totalSum'] = $sum3;
                        }
                        elseif ($day < 29 && $day >=22)
                        {
                            $week = 4;
                            $data[$week]['week'] = $week;
                            $data[$week]['amount'][] = $result['amount'];
                            $data[$week]['count'] = count($data[$week]['amount']);
                            $sum4 +=  $result['amount'];
                            $data[$week]['totalSum'] = $sum4;
                        }
                       else
                        {
                            $week = 5;
                            $data[$week]['week'] = $week;
                            $data[$week]['amount'][] = $result['amount'];
                            $data[$week]['count'] = count($data[$week]['amount']);
                            $sum5 +=  $result['amount'];
                            $data[$week]['totalSum'] = $sum5;
                        }
                    }
                }

            }
        }
        //var_dump($data);

        return view('orders.report', ['datas' => $data]);
    }



}
