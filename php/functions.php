<?php

@session_start();

function verify_user($username, $password)
{
    //宣告要回傳的結果
    $result = null;
    //先把密碼用md5加密
    $password = md5($password);
    //將查詢語法當成字串，記錄在$sql變數中
    $sql = "SELECT * FROM `user` WHERE `username` = '{$username}' AND `password` = '{$password}'";

    //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
    $query = mysqli_query($_SESSION['link'], $sql);

    //如果請求成功
    if ($query) {
        //使用 mysqli_num_rows 回傳 $query 請求的結果數量有幾筆，為一筆代表找到會員且密碼正確。
        if (mysqli_num_rows($query) == 1) {
            //取得使用者資料
            $user = mysqli_fetch_assoc($query);

            //在session李設定 is_login 並給 true 值，代表已經登入
            $_SESSION['is_login'] = TRUE;
            //紀錄登入者的id，之後若要隨時取得使用者資料時，可以透過 $_SESSION['login_user_id'] 取用
            $_SESSION['login_user_id'] = $user['id'];

            //回傳的 $result 就給 true 代表驗證成功
            $result = true;
        }
    } else {
        echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    //回傳結果
    return $result;
}
//讀取大事紀
function get_all_bigthings()
{
    $datas = array();
    $sql_1 = "SELECT * FROM bigthings ORDER BY event_date DESC";
    $query = mysqli_query($_SESSION['link'], $sql_1);
    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $datas[] = $row;
            }
        }
        mysqli_free_result($query);
    } else {
        echo "{$sql_1} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    return $datas;
}

/**
 * 新增文章
 */
function add_bigthings($event_date, $content, $publish)
{
    //宣告要回傳的結果
    $result = null;
    //建立現在的時間
    $create_date = date("Y-m-d H:i:s", (time() + 8 * 3600));
    //內容處理html
    $content = htmlspecialchars($content);
    //取得登入者的id
    $creater_id = $_SESSION['login_user_id'];
    //新增語法
    $sql = "INSERT INTO `bigthings` (`event_date`, `content`, `publish`, `create_date`, `creater_id`)
  				VALUES ('{$event_date}', '{$content}', {$publish}, '{$create_date}', '{$creater_id}');";

    //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
    $query = mysqli_query($_SESSION['link'], $sql);

    //如果請求成功
    if ($query) {
        //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
        if (mysqli_affected_rows($_SESSION['link']) == 1) {
            //取得的量大於0代表有資料
            //回傳的 $result 就給 true 代表有該帳號，不可以被新增
            $result = true;
        }
    } else {
        echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    //回傳結果
    return $result;
}
//讀取單筆修改大事紀
function get_a_bigthings($id)
{
    $result = null;

    //將查詢語法當成字串，記錄在$sql變數中
    $sql = "SELECT * FROM `bigthings` WHERE `id` = {$id};";

    //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
    $query = mysqli_query($_SESSION['link'], $sql);

    //如果請求成功
    if ($query) {
        //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
        if (mysqli_num_rows($query) == 1) {
            //取得的量大於0代表有資料
            //while迴圈會根據查詢筆數，決定跑的次數
            //mysqli_fetch_assoc 方法取得 一筆值
            $result = mysqli_fetch_assoc($query);
        }

        //釋放資料庫查詢到的記憶體
        mysqli_free_result($query);
    } else {
        echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    //回傳結果
    return $result;
}

/**
 * 更新文章
 */
function update_bigthings($id, $event_date, $content, $publish)
{
    //宣告要回傳的結果
    $result = null;
    //建立現在的時間
    $modify_date = date("Y-m-d H:i:s");
    //內容處理html
    $content = htmlspecialchars($content);
    //更新語法
    $sql = "UPDATE `bigthings` SET `event_date` = '{$event_date}', `content` = '{$content}', `publish` = {$publish}, `modify_date` = '{$modify_date}'
  				WHERE `id` = {$id};";

    //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
    $query = mysqli_query($_SESSION['link'], $sql);

    //如果請求成功
    if ($query) {
        //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
        if (mysqli_affected_rows($_SESSION['link']) == 1) {
            //取得的量大於0代表有資料
            //回傳的 $result 就給 true 代表有該帳號，不可以被新增
            $result = true;
        }
    } else {
        echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    //回傳結果
    return $result;
}

/**
 * 刪除文章
 */
function del_bigthings($id)
{
    //宣告要回傳的結果
    $result = null;
    //刪除語法
    $sql = "DELETE FROM `bigthings` WHERE `id` = {$id};";

    //用 mysqli_query 方法取執行請求（也就是sql語法），請求後的結果存在 $query 變數中
    $query = mysqli_query($_SESSION['link'], $sql);

    //如果請求成功
    if ($query) {
        //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
        if (mysqli_affected_rows($_SESSION['link']) == 1) {
            //取得的量大於0代表有資料
            //回傳的 $result 就給 true 代表有該帳號，不可以被新增
            $result = true;
        }
    } else {
        echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['link']);
    }

    //回傳結果
    return $result;
}
?>