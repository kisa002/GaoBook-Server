<?php
    header('Content-Type: text/html; charset=UTF-8');

    $connect = connectDB();

    // 성공
    const SUCCESS = 0;

    // 파라미터
    const WRONG_PARAMETER = -1000;
    const TOO_LONG = -1001;

    // 로그인
    const UNKNOWN_USERNAME = -1010;
    const WRONG_PASSWORD = -1011;

    $result[SUCCESS] = "성공";
    $result[TOO_LONG] = "입력 가능한 글자를 초과합니다.";
    $result[UNKNOWN_USERNAME] = "존재하지 않는 사용자이름 입니다.";
    $result[WRONG_PASSWORD] = "잘못된 비밀번호입니다.";
    
    function connectDB() {
        $connect = mysqli_connect("localhost", "gaobook", "rkdhqnr3#", 'gaobook');
        if (mysqli_connect_errno($connect)) {
            echo "connect failed.";
            //echo "데이터베이스 연결 실패: ".mysqli_connect_error();
        }
        return $connect;
    }

    function signIn($username, $password) {
        global $connect;
        
        $result = mysqli_query($connect, "SELECT username FROM user WHERE username='{$username}'");
        $row = mysqli_fetch_array($result);

        if(empty($row)) {
            // var_dump(http_response_code(404));
            echo responseDefault(UNKNOWN_USERNAME);
        }
        else
            echo responseDefault(SUCCESS);
    }

    function signUp($nickname, $username, $password) {
        global $connect;
        // $result = mysqli_query($connect, "INSERT INTO user (nickname, username, password, token) VALUES ('$nickname', '$username', '$password', 'ASD')");

        $result = mysqli_query($connect, "SELECT username FROM user WHERE username='{$username}'");
		$arr = mysqli_fetch_array($result);
        
        print_r($arr);
        // $row = mysqli_fetch_array($result);
        // echo $row;
    }

    function responseDefault($code) {
        global $result;

        $response['code'] = $code;
        $response['msg'] = $result[$code];

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    // function responseDefault($code, $msg) {
    //     $response['code'] = $code;
    //     $response['msg'] = $msg;

    //     return json_encode($response, JSON_UNESCAPED_UNICODE);
    // }
?>