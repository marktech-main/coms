<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '-1');
ini_set('display_errors', 'On');
class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        # $this->load->model('get_data');
        $this->load->model('transactions_model');
        $this->load->model('report_model');
        if(!is_logged_in()){ // authentication verify by session login
            redirect('login', 'refresh'); // if not verify then redirect to login page
        }else{
            $this->user = decrypt($this->session->userdata('user_data'));
            $this->user_role = $this->user['user_role'];
            $this->division_id  = $this->user['division'];
            $this->user_id = $this->user['user_id'];
            $this->privilege = verify_all_privilege($this->user);
        }
    }

    public function index()
    {
        # debug code
        $data['active_menu'] = 'report';
        $data = array_merge($data , $this->privilege); // can_create_request, can_view_report, can_update_request, is_cs_team, is_payment_team, is_administrator, is_tech_team
        $this->load->view('report', $data); // load report.php with dataset
    }

    // main function to generate and display report html format
    public function display_report_data(){
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $state = FALSE;
        if((empty($from_date) && empty($to_date)) && ((bool)strtotime($from_date) && (bool)strtotime($to_date))){
            $state = TRUE; // if valid date set state TRUE
        }
        # 1 Average time taken to complete each request
        $report_average_time_taken_to_complete = $this->get_report_average_time_taken_to_complete( $from_date, $to_date );
        $data['report_average_time_taken_to_complete'] = $report_average_time_taken_to_complete;

        # 2 Fastest time taken to complete each request
        $report_fastest_time_taken_to_complete = $this->get_report_fastest_time_taken_to_complete( $from_date, $to_date );
        $data['report_fastest_time_taken_to_complete'] = $report_fastest_time_taken_to_complete;

        # 3 Longest time taken to complete each request
        $report_longest_time_taken_to_complete = $this->get_report_longest_time_taken_to_complete( $from_date, $to_date );
        $data['report_longest_time_taken_to_complete'] = $report_longest_time_taken_to_complete;

        # 4 Total completed request for each request
        $report_total_completed_request = $this->get_report_total_completed_request( $from_date, $to_date );
        $data['report_total_completed_request'] = $report_total_completed_request;

        # 5 Statistic of busiest day and time
        $report_statistic_of_busiest_day_and_time = $this->get_report_statistic_of_busiest_day_and_time( $from_date, $to_date );
        $data['report_statistic_of_busiest_day_and_time'] = $report_statistic_of_busiest_day_and_time;


        // $data['report_fastest_time_taken_to_complete'] = $report_average_time_taken_to_complete;
        //
        // print_r($data);
        // die();
        // $data[''] = '';
        echo json_encode(['state' => $state, 'data' => $data]); // state, message, status,
    }

    public function get_report_average_time_taken_to_complete( $from_date, $to_date ){
        $report_data = $this->report_model->get_report_average_time_taken_to_complete([ $from_date, $to_date ]);
        return $report_data;
    }

    public function get_report_fastest_time_taken_to_complete( $from_date, $to_date ){
        $report_data = $this->report_model->get_report_fastest_time_taken_to_complete([ $from_date, $to_date ]);
        return $report_data;
    }

    public function get_report_longest_time_taken_to_complete( $from_date, $to_date ){
        $report_data = $this->report_model->get_report_longest_time_taken_to_complete([ $from_date, $to_date ]);
        return $report_data;
    }

    public function get_report_total_completed_request( $from_date, $to_date ){
        $report_data = $this->report_model->get_report_total_completed_request([ $from_date, $to_date ]);
        return $report_data;
    }

    public function get_report_statistic_of_busiest_day_and_time( $from_date, $to_date ){
        $report_data = $this->report_model->get_report_statistic_of_busiest_day_and_time([ $from_date, $to_date ]);
        return $report_data;
    }

    public function export_to_excel(){
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];

        $this->generate_excel( $from_date, $to_date );
        
        // test
        // $from_date = '2018-08-02';
        // $to_date = '2018-08-02';
        // $each_pic_performance = $this->report_model->get_report_each_pic_performance_detail([ $from_date, $to_date ]);
        // echo "<pre>";
        // print_r($each_pic_performance);
        // echo "</pre>";
        // exit();
        
    }

    public function generate_excel( $from_date, $to_date ){
        $this->load->library('excel');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("COMS")
            ->setLastModifiedBy("COMS")
            ->setTitle("COMS Report");

        // sheet 1 average_time_taken_to_complete
        $objPHPExcel->getActiveSheet()->setTitle('AVG time taken to completed');

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getDefaultStyle()
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //HORIZONTAL_CENTER //VERTICAL_CENTER

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Index')
            ->setCellValue('B1', 'Transaction Type')
            ->setCellValue('C1', 'AVG time taken to completed');

        $start_row=2;
        $i_num=0;
        $average_time_taken_to_complete = $this->report_model->get_report_average_time_taken_to_complete([ $from_date, $to_date ]);
        if(count($average_time_taken_to_complete)>0){
            foreach($average_time_taken_to_complete as $row){
                $i_num++;

                // หากอยากจัดข้อมูลราคาให้ชิดขวา
                $objPHPExcel->getActiveSheet()
                    ->getStyle('C'.$start_row)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                // หากอยากจัดให้รหัสสินค้ามีเลย 0 ด้านหน้า และแสดง 3     หลักเช่น 001 002
                $objPHPExcel->getActiveSheet()
                    ->getStyle('B'.$start_row)
                    ->getNumberFormat()
                    ->setFormatCode('000');

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$start_row, $i_num)
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, gmdate("H:i:s", $row['average_time_taken_to_complete']));

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 2 fastest_time_taken_to_complete
        $objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('Fast time taken to completed');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'Index')
            ->setCellValue('B1', 'Transaction Type')
            ->setCellValue('C1', 'Website')
            ->setCellValue('D1', 'PIC')
            ->setCellValue('E1', 'Fastest time taken to completed');
        $start_row=2;
        $i_num=0;
        $fastest_time_taken_to_complete = $this->report_model->get_report_fastest_time_taken_to_complete([ $from_date, $to_date ]);
        if(count($fastest_time_taken_to_complete)>0){
            foreach($fastest_time_taken_to_complete as $row){
                $i_num++;

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A'.$start_row, $i_num)
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, $row['website_name'])
                    ->setCellValue('D'.$start_row, $row['pic_username'])
                    ->setCellValue('E'.$start_row, gmdate("H:i:s", $row['fastest_time_taken_to_complete']));

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 3 longest_time_taken_to_complete
        $objWorkSheet = $objPHPExcel->createSheet(2); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(2);
        $objPHPExcel->getActiveSheet()->setTitle('Longest time taken to completed');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A1', 'Index')
            ->setCellValue('B1', 'Transaction Type')
            ->setCellValue('C1', 'Website')
            ->setCellValue('D1', 'PIC')
            ->setCellValue('E1', 'Longest time taken to completed');
        $start_row=2;
        $i_num=0;
        $longest_time_taken_to_complete = $this->report_model->get_report_longest_time_taken_to_complete([ $from_date, $to_date ]);
        if(count($longest_time_taken_to_complete)>0){
            foreach($longest_time_taken_to_complete as $row){
                $i_num++;

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(2)
                    ->setCellValue('A'.$start_row, $i_num)
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, $row['website_name'])
                    ->setCellValue('D'.$start_row, $row['pic_username'])
                    ->setCellValue('E'.$start_row, gmdate("H:i:s", $row['longest_time_taken_to_complete']));

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 4 total_completed_request
        $objWorkSheet = $objPHPExcel->createSheet(3); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(3);
        $objPHPExcel->getActiveSheet()->setTitle('Total completed request');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('A1', 'Index')
            ->setCellValue('B1', 'Transaction Type')
            ->setCellValue('C1', 'Total completed');
        $start_row=2;
        $i_num=0;
        $total_completed = $this->report_model->get_report_total_completed_request([ $from_date, $to_date ]);
        if(count($total_completed)>0){
            foreach($total_completed as $row){
                $i_num++;

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(3)
                    ->setCellValue('A'.$start_row, $i_num)
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, $row['total_completed_request']);

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 5 statistic_of_busiest_day_and_time
        $objWorkSheet = $objPHPExcel->createSheet(4); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(4);
        $objPHPExcel->getActiveSheet()->setTitle('Statistic of busiest day');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

        $objPHPExcel->setActiveSheetIndex(4)
            ->setCellValue('A1', 'Hour range')
            ->setCellValue('B1', 'Total request');
        $start_row=2;
        $i_num=0;
        $statistic_of_busiest_day_and_time = $this->report_model->get_report_statistic_of_busiest_day_and_time([ $from_date, $to_date ]);
        if(count($statistic_of_busiest_day_and_time)>0){
            foreach($statistic_of_busiest_day_and_time as $row){
                $i_num++;

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(4)
                    ->setCellValue('A'.$start_row, $row['hour_range'])
                    ->setCellValue('B'.$start_row, $row['number_of_request']);

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 6 Each PIC performance for each request
        $objWorkSheet = $objPHPExcel->createSheet(5); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(5);
        $objPHPExcel->getActiveSheet()->setTitle('Each PIC performance');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);

        $objPHPExcel->setActiveSheetIndex(5)
            ->setCellValue('A1', 'Division')
            ->setCellValue('B1', 'Role')
            ->setCellValue('C1', 'Username')
            ->setCellValue('D1', 'Complete name')
            ->setCellValue('E1', 'Completed deposit request')
            ->setCellValue('F1', 'Completed withdraw request')
            ->setCellValue('G1', 'Completed transfer request')
            ->setCellValue('H1', 'Total completed')
            ->setCellValue('I1', 'Fastest time taken to completed')
            ->setCellValue('J1', 'Longest time taken to completed')
            ->setCellValue('K1', 'Average time taken to completed');

        $start_row=2;
        $i_num=0;
        $each_pic_performance = $this->report_model->get_report_each_pic_performance([ $from_date, $to_date ]);
        if(count($each_pic_performance)>0){
            foreach($each_pic_performance as $row){
                $i_num++;

                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(5)
                    ->setCellValue('A'.$start_row, $row['division_name'])
                    ->setCellValue('B'.$start_row, $row['user_role_name'])
                    ->setCellValue('C'.$start_row, $row['username'])
                    ->setCellValue('D'.$start_row, $row['complete_name'])
                    ->setCellValue('E'.$start_row, $row['completed_deposit_request'])
                    ->setCellValue('F'.$start_row, $row['completed_withdraw_request'])
                    ->setCellValue('G'.$start_row, $row['completed_transfer_request'])
                    ->setCellValue('H'.$start_row, $row['total_completed'])
                    ->setCellValue('I'.$start_row, gmdate("H:i:s", $row['fastest_time_taken_to_complete']))
                    ->setCellValue('J'.$start_row, gmdate("H:i:s", $row['longest_time_taken_to_complete']))
                    ->setCellValue('K'.$start_row, gmdate("H:i:s", $row['average_time_taken_to_complete']));

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 7 Each PIC performance details
        $objWorkSheet = $objPHPExcel->createSheet(6); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(6);
        $objPHPExcel->getActiveSheet()->setTitle('Each PIC performance details');

        foreach(range('A','Z') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('Q')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('S')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('T')->getAlignment()->setWrapText(true);

        $objPHPExcel->setActiveSheetIndex(6)
            ->setCellValue('A1', 'Transaction ID')
            ->setCellValue('B1', 'Transacttion Type')
            ->setCellValue('C1', 'Website')
            ->setCellValue('D1', 'Request By')
            ->setCellValue('E1', 'PIC')
            ->setCellValue('F1', 'Customer ID')
            ->setCellValue('G1', 'Amount')
            ->setCellValue('H1', 'Request time')
            ->setCellValue('I1', 'Process time')
            ->setCellValue('J1', 'Done time')
            ->setCellValue('K1', 'Successful time')
            ->setCellValue('L1', 'Pending time')
            ->setCellValue('M1', 'Time Adjusted')
            ->setCellValue('N1', 'Verified by')
            ->setCellValue('O1', 'Verified start')
            ->setCellValue('P1', 'Verified end')
            ->setCellValue('Q1', 'Verifying time')
            ->setCellValue('R1', 'Fixed by')
            ->setCellValue('S1', 'Fixed start')
            ->setCellValue('T1', 'Fixed end')
            ->setCellValue('U1', 'Fixing time')
            ->setCellValue('V1', 'Request to process')
            ->setCellValue('W1', 'Process to done')
            ->setCellValue('X1', 'Request to done')
            ->setCellValue('Y1', 'Request to done - (pending time)')
            ->setCellValue('Z1', 'Process to done - (pending time, adjustment)')
            ->setCellValue('AA1', 'Process to done - (pending time, adjustment, senior verification)')
            ->setCellValue('AB1', 'Done to successful');

        $start_row=2;
        $i_num=0;
        $each_pic_performance = $this->report_model->get_report_each_pic_performance_detail([ $from_date, $to_date ]);
        if(count($each_pic_performance)>0){
            foreach($each_pic_performance as $row){
                $i_num++;

                // Senior Verifying Report
                $senior_verify_name = '';
                $senior_verify_start = '';
                $senior_verify_end = '';
                $senior_verifying_time = '';
                $sum_verify_time = 0;
                $counter_verify = 0;
                $item_verify_array = json_decode($row['senior_verifying']);
                if(!empty($item_verify_array)) {
                    $item_verify_length = count($item_verify_array);
                    foreach ($item_verify_array as $item) {
                        $counter_verify++;
                        $verify_start = new DateTime($item->verify_start);
                        $verify_end = new DateTime($item->verify_end);
                        $interval_verify = $verify_end->getTimeStamp() - $verify_start->getTimeStamp();
                        $sum_verify_time += $interval_verify;

                        $senior_verify_name .= ($counter_verify == $item_verify_length) ? $item->senior : $item->senior.''.PHP_EOL;
                        $senior_verify_start .= ($counter_verify == $item_verify_length) ? $item->verify_start : $item->verify_start.''.PHP_EOL;
                        $senior_verify_end .= ($counter_verify == $item_verify_length) ? $item->verify_end : $item->verify_end.''.PHP_EOL;
                        $senior_verifying_time .= ($counter_verify == $item_verify_length) ? gmdate("H:i:s",$interval_verify) : gmdate("H:i:s",$interval_verify).''.PHP_EOL;
                    }
                }

                // Senior Fixing Report
                $senior_fix_name = '';
                $senior_fix_start = '';
                $senior_fix_end = '';
                $senior_fixing_time = '';
                $sum_fix_time = 0;
                $counter_fix = 0;
                $item_fix_array = json_decode($row['senior_fixing']);
                if(!empty($item_fix_array)) {
                    $item_fix_length = count($item_fix_array);
                    foreach ($item_fix_array as $item) {
                        $counter_fix++;
                        $fix_start = new DateTime($item->fix_start);
                        $fix_end = new DateTime($item->fix_end);
                        $interval_fix = $fix_end->getTimeStamp() - $fix_start->getTimeStamp();
                        $sum_fix_time += $interval_fix;

                        $senior_fix_name .= ($counter_fix == $item_fix_length) ? $item->senior : $item->senior.''.PHP_EOL;
                        $senior_fix_start .= ($counter_fix == $item_fix_length) ? $item->fix_start : $item->fix_start.''.PHP_EOL;
                        $senior_fix_end .= ($counter_fix == $item_fix_length) ? $item->fix_end : $item->fix_end.''.PHP_EOL;
                        $senior_fixing_time .= ($counter_fix == $item_fix_length) ? gmdate("H:i:s",$interval_fix) : gmdate("H:i:s",$interval_fix).''.PHP_EOL;
                    }
                }


                $total_pending_adjust = $row['time_taken_from_process_to_completed'] - ($row['deduct'] + $row['add_action']);
                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(6)
                    ->setCellValue('A'.$start_row, $row['transaction_id'])
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, $row['website_name'])
                    ->setCellValue('D'.$start_row, $row['request_by'])
                    ->setCellValue('E'.$start_row, $row['pic'])
                    ->setCellValue('F'.$start_row, $row['customer_id'])
                    ->setCellValue('G'.$start_row, $row['amount'])
                    ->setCellValue('H'.$start_row, $row['request_time'])
                    ->setCellValue('I'.$start_row, $row['process_time'])
                    ->setCellValue('J'.$start_row, $row['completed_time'])
                    ->setCellValue('K'.$start_row, $row['successful_time'])
                    ->setCellValue('L'.$start_row, $row['pending_time'])
                    ->setCellValue('M'.$start_row, $this->ppr_adjust_action_checker($row['deduct'], $row['add_action']))
                    ->setCellValue('N'.$start_row, $senior_verify_name)
                    ->setCellValue('O'.$start_row, $senior_verify_start)
                    ->setCellValue('P'.$start_row, $senior_verify_end)
                    ->setCellValue('Q'.$start_row, $senior_verifying_time)
                    ->setCellValue('R'.$start_row, $senior_fix_name)
                    ->setCellValue('S'.$start_row, $senior_fix_start)
                    ->setCellValue('T'.$start_row, $senior_fix_end)
                    ->setCellValue('U'.$start_row, $senior_fixing_time)
                    ->setCellValue('V'.$start_row, gmdate("H:i:s", $row['time_taken_from_request_to_process']))
                    ->setCellValue('W'.$start_row, gmdate("H:i:s", $row['time_taken_from_process_to_completed']))
                    ->setCellValue('X'.$start_row, gmdate("H:i:s", $row['time_taken_from_request_to_completed']))
                    ->setCellValue('Y'.$start_row, gmdate("H:i:s", $row['time_taken_from_request_to_completed_included_pending_time']))
                    ->setCellValue('Z'.$start_row, gmdate("H:i:s", $total_pending_adjust))
                    ->setCellValue('AA'.$start_row, gmdate("H:i:s", $total_pending_adjust - ($sum_verify_time + $sum_fix_time) ))
                    ->setCellValue('AB'.$start_row, gmdate("H:i:s", $row['time_taken_from_completed_to_successful']));

                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }

        // sheet 8 Senior Verification
        $objWorkSheet = $objPHPExcel->createSheet(7); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(7);
        $objPHPExcel->getActiveSheet()->setTitle('Senior performance');

        foreach(range('A','P') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setWrapText(true);

        $objPHPExcel->setActiveSheetIndex(7)
            ->setCellValue('A1', 'Transaction ID')
            ->setCellValue('B1', 'Transacttion Type')
            ->setCellValue('C1', 'Website')
            ->setCellValue('D1', 'PIC')
            ->setCellValue('E1', 'Customer ID')
            ->setCellValue('F1', 'Amount')
            ->setCellValue('G1', 'Verified by')
            ->setCellValue('H1', 'Verified start')
            ->setCellValue('I1', 'Verified end')
            ->setCellValue('J1', 'Verifying time')
            ->setCellValue('K1', 'Verifying Grade')
            ->setCellValue('L1', 'Fixed by')
            ->setCellValue('M1', 'Fixed start')
            ->setCellValue('N1', 'Fixed end')
            ->setCellValue('O1', 'Fixing time')
            ->setCellValue('P1', 'Fixing grade');

        $start_row=2;
        $i_num=0;
        $senior_performance = $this->report_model->get_report_senior_performance([ $from_date, $to_date ]);
        if(count($senior_performance)>0){
            foreach($senior_performance as $row){
                $i_num++;


                // Senior Verifying Report
                $senior_verify_name = '';
                $senior_verify_start = '';
                $senior_verify_end = '';
                $senior_verifying_time = '';
                $sum_verify_time = 0;
                $counter_verify = 0;
                $verifying_grade = '';
                $item_verify_array = json_decode($row['senior_verifying']);
                if(!empty($item_verify_array)) {
                    $item_verify_length = count($item_verify_array);
                    foreach ($item_verify_array as $item) {
                        $counter_verify++;
                        $verify_start = new DateTime($item->verify_start);
                        $verify_end = new DateTime($item->verify_end);
                        $interval_verify = $verify_end->getTimeStamp() - $verify_start->getTimeStamp();
                        $sum_verify_time += $interval_verify;

                        $senior_verify_name .= ($counter_verify == $item_verify_length) ? $item->senior : $item->senior.''.PHP_EOL;
                        $senior_verify_start .= ($counter_verify == $item_verify_length) ? $item->verify_start : $item->verify_start.''.PHP_EOL;
                        $senior_verify_end .= ($counter_verify == $item_verify_length) ? $item->verify_end : $item->verify_end.''.PHP_EOL;
                        $senior_verifying_time .= ($counter_verify == $item_verify_length) ? gmdate("H:i:s",$interval_verify) : gmdate("H:i:s",$interval_verify).''.PHP_EOL;
                        $verifying_grade .= ($counter_verify == $item_verify_length) ? $this->get_senior_grade($interval_verify) : $this->get_senior_grade($interval_verify).''.PHP_EOL;
                    }
                }

                //  Senior Fixing Report
                $senior_fix_name = '';
                $senior_fix_start = '';
                $senior_fix_end = '';
                $senior_fixing_time = '';
                $sum_fix_time = 0;
                $counter_fix = 0;
                $fixing_grade = '';
                $item_fix_array = json_decode($row['senior_fixing']);
                if(!empty($item_fix_array)) {
                    $item_fix_length = count($item_fix_array);
                    foreach ($item_fix_array as $item) {
                        $counter_fix++;
                        $fix_start = new DateTime($item->fix_start);
                        $fix_end = new DateTime($item->fix_end);
                        $interval_fix = $fix_end->getTimeStamp() - $fix_start->getTimeStamp();
                        $sum_fix_time += $interval_fix;

                        $senior_fix_name .= ($counter_fix == $item_fix_length) ? $item->senior : $item->senior.''.PHP_EOL;
                        $senior_fix_start .= ($counter_fix == $item_fix_length) ? $item->fix_start : $item->fix_start.''.PHP_EOL;
                        $senior_fix_end .= ($counter_fix == $item_fix_length) ? $item->fix_end : $item->fix_end.''.PHP_EOL;
                        $senior_fixing_time .= ($counter_fix == $item_fix_length) ? gmdate("H:i:s",$interval_fix) : gmdate("H:i:s",$interval_fix).''.PHP_EOL;
                        $fixing_grade .= ($counter_fix == $item_fix_length) ? $this->get_seniorfix_grade($interval_fix) : $this->get_seniorfix_grade($interval_fix).''.PHP_EOL;
                    }
                }


                // เพิ่มข้อมูลลงแต่ละเซลล์
                $objPHPExcel->setActiveSheetIndex(7)
                    ->setCellValue('A'.$start_row, $row['transaction_id'])
                    ->setCellValue('B'.$start_row, $row['transaction_type_name'])
                    ->setCellValue('C'.$start_row, $row['website_name'])
                    ->setCellValue('D'.$start_row, $row['pic'])
                    ->setCellValue('E'.$start_row, $row['customer_id'])
                    ->setCellValue('F'.$start_row, $row['amount'])
                    ->setCellValue('G'.$start_row, $senior_verify_name)
                    ->setCellValue('H'.$start_row, $senior_verify_start)
                    ->setCellValue('I'.$start_row, $senior_verify_end)
                    ->setCellValue('J'.$start_row, $senior_verifying_time)
                    ->setCellValue('K'.$start_row, $verifying_grade)
                    ->setCellValue('L'.$start_row, $senior_fix_name)
                    ->setCellValue('M'.$start_row, $senior_fix_start)
                    ->setCellValue('N'.$start_row, $senior_fix_end)
                    ->setCellValue('O'.$start_row, $senior_fixing_time)
                    ->setCellValue('P'.$start_row, $fixing_grade);


                // เพิ่มแถวข้อมูล
                $start_row++;
            }
        }


        // sheet 9 Senior Verification
        $objWorkSheet = $objPHPExcel->createSheet(8); //Setting index when creating
        $objPHPExcel->setActiveSheetIndex(8);
        $objPHPExcel->getActiveSheet()->setTitle('Senior payment summary');

        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $objPHPExcel->setActiveSheetIndex(8)
            ->setCellValue('A1', 'Senior')
            ->setCellValue('B1', 'Verified')
            ->setCellValue('C1', 'Fixed')
            ->setCellValue('D1', 'Total');

        $start_row=2;
        $i_num=0;
        $senior_performance = $this->report_model->get_report_senior_performance_summary([ $from_date, $to_date ]);
        if(count($senior_performance)>0){
            foreach($senior_performance as $row){
                $i_num++;

                $total = $row['verify_count'] + $row['fix_count'];

                $objPHPExcel->setActiveSheetIndex(8)
                    ->setCellValue('A'.$start_row, $row['senior'])
                    ->setCellValue('B'.$start_row, $row['verify_count'])
                    ->setCellValue('C'.$start_row, $row['fix_count'])
                    ->setCellValue('D'.$start_row, $total);
                $start_row++;
            }
        }

        // กำหนดรูปแบบของไฟล์ที่ต้องการเขียนว่าเป็นไฟล์ excel แบบไหน ในที่นี้เป้นนามสกุล xlsx  ใช้คำว่า Excel2007
        // แต่หากต้องการกำหนดเป็นไฟล์ xls ใช้กับโปรแกรม excel รุ่นเก่าๆ ได้ ให้กำหนดเป็น  Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  // Excel2007 (xlsx) หรือ Excel5 (xls)

        $filename='COMS-during-'.$from_date.'-to-'.$to_date.' ('.date("dmYHi").').xlsx'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx
//        $filename='test111.xlsx'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx
        // บังคับให้ทำการดาวน์ดหลดไฟล์

//        header('Content-Type: text/csv'); //mime type
//        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
//        header('Cache-Control: max-age=0'); //no cache
        // ob_end_clean();
//        $objWriter->save('php://output'); // ดาวน์โหลดไฟล์รายงาน
        $objWriter->save($filename); // ดาวน์โหลดไฟล์รายงาน
        // หากต้องการบันทึกเป็นไฟล์ไว้ใน server  ใช้คำสั่งนี้ $this->excel->save("/path/".$filename);
        // แล้วตัด header ดัานบนทั้ง 3 อันออก

        // Force the download
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header("Content-Length: " . filesize($filename));
        header("Content-Type: application/octet-stream;");
        readfile($filename);

        exit;
    }

    public function ppr_adjust_action_checker($deduct, $add) {
        $total_time_adjusted = '';
        if(!empty($deduct)) {
            $deduct_action = '-';
            $deduct_adjusted = gmdate("H:i:s", $deduct);
            $total_time_adjusted .= ' '.$deduct_action .' '. $deduct_adjusted;
        }
        if(!empty($add)) {
            $add_action = '+';
            $add_adjusted = gmdate("H:i:s", $add);
            $total_time_adjusted .= ' '.$add_action .' '. $add_adjusted;
        }
        return $total_time_adjusted;
    }
    public function get_senior_grade($seconds) {
        $grade = '';
        switch (true) {
            case ($seconds >= 1 && $seconds <= 120):
                $grade = 'A';
                break;
            case ($seconds >= 121 && $seconds <= 180):
                $grade = 'B';
                break;
            case ($seconds >= 181):
                $grade = 'C';
                break;
        }
        return $grade;
    }

    public function get_seniorfix_grade($seconds) {
        $grade = '';
        switch (true) {
            case ($seconds >= 1 && $seconds <= 300):
                $grade = 'A';
                break;
            case ($seconds >= 301 && $seconds <= 600):
                $grade = 'B';
                break;
            case ($seconds >= 601):
                $grade = 'C';
                break;
        }
        return $grade;
    }
}
