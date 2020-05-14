<?php
require_once '../php/db.php';
require_once '../php/functions.php';

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    //直接轉跳到 login.php
    header("Location: login.php");
}

//取得文章資料，從網址上的 i 取得文章id
$data = get_a_bigthings($_GET['i']);
if (is_null($data)) {
    //如果文章是null就轉回列表頁
    header("Location: article_list.php");
}

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>大事紀編輯</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 載入 bootstrap 的 css 方便我們快速設計網站-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="shortcut icon" href="../images/favicon.ico">
</head>

<body>
    <!-- 頁首 -->
    <?php include_once 'mainav.php'; ?>

    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <!-- 建立第一個 row 空間，裡面準備放格線系統 -->
            <div class="row">
                <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
                <div class="col-xs-12">
                    <form id="edit_article_form">
                        <input type="hidden" id="id" value="<?php echo $data['id']; ?>">
                        <div class="form-group">
                            <label for="event_date">事紀日期</label>
                            <input type="date" class="form-control" id="event_date" value="<?php echo $data['event_date']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="content">事紀內容</label>
                            <textarea type="input" class="form-control" id="content"><?php echo $data['content']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="radio-inline">
                                <input type="radio" name="publish" value="1" <?php echo ($data['publish'] == 1) ? "checked" : ""; ?>> 發布
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="publish" value="0" <?php echo ($data['publish'] == 0) ? "checked" : ""; ?>> 不發佈
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-default">送出</button>
                        <div class="loading text-center"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 頁底 -->
    <?php include_once 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
        $(document).on("ready", function() {
            //表單送出
            $("#edit_article_form").on("submit", function() {
                //加入loading icon
                $("div.loading").html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');

                if ($("#title").val() == '' || $("#content").val() == '') {
                    alert("請填入標題或內文");

                    //清掉 loading icon
                    $("div.loading").html('');
                } else {
                    //使用 ajax 送出 帳密給 verify_user.php
                    $.ajax({
                        type: "POST",
                        url: "../php/update_bigthings.php", //因為此檔案是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入
                        data: {
                            id: $("#id").val(), //
                            event_date: $("#event_date").val(), //
                            content: $("#content").val(), //使用者帳號
                            publish: $("input[name='publish']:checked").val() //使用者密碼
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候

                        if (data == "yes") {
                            //註冊新增成功，轉跳到登入頁面。
                            alert("更新成功，點擊確認回列表");
                            window.location.href = "bigthings_list.php";
                        } else {
                            alert("更新錯誤");
                        }

                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        //失敗的時候
                        alert("有錯誤產生，請看 console log");
                        console.log(jqXHR.responseText);
                    });
                }
                return false;
            });
        });
    </script>
</body>

</html>