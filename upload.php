<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="msg"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
        var s = 1;
        var interval = setInterval(() => {
            if(s > 5){
                clearInterval(interval)
            } else {
                $.ajax({
                    url : "request.php?action=upload&v=" + s,
                    type : "get",
                    dataType : "json",
                    success : function(resp){
                        $("#msg").append(`File v${s}.txt : ${resp.msg} <br>`)
                    }
                })
                s++
            }
        }, 1000);
    </script>
</body>
</html>