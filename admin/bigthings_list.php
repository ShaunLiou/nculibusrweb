<?php
require_once '../php/db.php';
require_once '../php/functions.php';
$gbthings = get_all_bigthings();
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7568312dca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>大事紀清單</title>
</head>
<?php include_once 'mainav.php'; ?>

<body>
    <!-- 網站內容 -->
    <div class="content">
        <div class="container">
            <!-- 建立第一個 row 空間，裡面準備放格線系統 -->
            <div class="row">
                <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
                <div class="col-xs-12">
                    <a href='bigthings_add.php' class="btn btn-default">新增事紀</a>
                </div>
                <div class="col-xs-12">
                    <!-- 資料列表 -->
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>日期</th>
                                <th>活動內容</th>
                                <th>建立時間</th>
                                <th>管理</th>
                            </tr>
                            <?php if ($gbthings) : ?>
                                <?php foreach ($gbthings as $a_data) : ?>
                                    <tr>
                                        <td><?php echo $a_data['id']; ?></td>
                                        <td><?php echo $a_data['event_date']; ?></td>
                                        <td><?php echo $a_data['content']; ?></td>
                                        <td><?php echo $a_data['create_date'] ?></td>
                                        <td>
                                            <a href='bigthings_edit.php?i=<?php echo $a_data['id']; ?> ' class="btn btn-default">編輯</a>
                                            <a href='javascript:void(0);' class='btn btn-default del_bigthings' data-id="<?php echo $a_data['id']; ?>">刪除</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">無資料</td>
                                </tr>
                            <?php endif; ?>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php include_once 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
        $(document).on("ready", function() {

            //表單送出
            $("a.del_bigthings").on("click", function() {
                //宣告變數
                var c = confirm("您確定要刪除嗎？"),
                    this_tr = $(this).parent().parent();
                if (c) {
                    $.ajax({
                        type: "POST",
                        url: "../php/del_bigthings.php", //因為此檔案是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 add_article.php
                        data: {
                            id: $(this).attr("data-id") //文章id
                        },
                        dataType: 'html' //設定該網頁回應的會是 html 格式
                    }).done(function(data) {
                        //成功的時候

                        if (data == "yes") {
                            //註冊新增成功，轉跳到登入頁面。
                            alert("刪除成功，點擊確認從列表移除");
                            this_tr.fadeOut();
                        } else {
                            alert("刪除錯誤:" + data);
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