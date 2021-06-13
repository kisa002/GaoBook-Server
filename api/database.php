<?php
    header('Content-Type: text/html; charset=UTF-8');

    $connect = connectDB();

    // 성공
    const SUCCESS = 0;

    // 파라미터
    const UNKNOWN_ERROR = -1;
    const WRONG_PARAMETER = -1000;
    const TOO_LONG = -1001;

    // 로그인
    const UNKNOWN_USERNAME = -1010;
    const WRONG_PASSWORD = -1011;

    // 회원가입
    const EXISTS_USERNAME = -1020;

    // 성공
    $result[SUCCESS] = '성공';

    // 파라미터
    $result[WRONG_PARAMETER] = '잘못된 파라미터 입니다.';
    $result[TOO_LONG] = '입력 가능한 글자를 초과합니다.';
    $result[UNKNOWN_USERNAME] = '존재하지 않는 사용자이름 입니다.';
    $result[UNKNOWN_ERROR] = '알 수 없는 오류가 발생하였습니다.';
    
    // 로그인
    $result[WRONG_PASSWORD] = '잘못된 비밀번호입니다.';

    // 회원가입
    $result[EXISTS_USERNAME] = '이미 존재하는 사용자이름 입니다.';
    
    function connectDB() {
        $connect = mysqli_connect('localhost', 'gaobook', 'rkdhqnr3#', 'gaobook');
        if (mysqli_connect_errno($connect))
            echo 'connect failed.'; // mysqli_connect_error()
        
        return $connect;
    }

    function signIn($username, $password) {
        global $connect;
        
        if(empty($username) || empty($password))
            return responseDefault(WRONG_PARAMETER);
        
        $result = mysqli_query($connect, "SELECT username FROM user WHERE username='$username' AND password='$password'");
        
        if(empty($result))
            responseDefault(UNKNOWN_USERNAME);
        else
            updateToken($username);
    }

    function signUp($nickname, $username, $password) {
        global $connect;
        $token = generateToken();

        if(empty($nickname) || empty($username) || empty($password))
            return responseDefault(WRONG_PARAMETER);
        
        if(mb_strlen($nickname) > 8 || mb_strlen($username) > 12 || mb_strlen($password) > 12)
            return responseDefault(TOO_LONG);

        if(existsUsername($username))
            return responseDefault(EXISTS_USERNAME);
            
        $result = mysqli_query($connect, "INSERT INTO user (nickname, username, password, token) VALUES ('$nickname', '$username', '$password', '$token')");

        if($result == true)
            responseDefault(SUCCESS);
        else
            responseDefault(UNKNOWN_ERROR);
    }

    function existsUsername($username) {
        global $connect;

        $result = mysqli_query($connect, "SELECT (username) as cnt FROM user WHERE username='$username'");
        return $result->num_rows > 0;
    }

    function updateToken($username) {
        global $connect;

        $token = generateToken();
        $result = mysqli_query($connect, "UPDATE user SET token='$token' WHERE username='$username'");
        
        if($result)
            responseDefault(SUCCESS);
        else
            responseDefault(UNKNOWN_ERROR);
    }

    function generateToken() {
        $key = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789^/';
        $token = '';

        for ($i = 0; $i < 12; $i++)
            $token .= $key[rand(0, 63)];

        return $token;
    }

    function responseDefault($code) {
        global $result;

        $response['code'] = $code;
        $response['msg'] = $result[$code];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
?>