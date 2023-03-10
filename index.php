<?php

    // $dir    = 'videos';
    // $scanned_directory = array_diff(scandir($dir), array('..', '.'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Player</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
</head>
<body class="bg-secondary">
    <div class="container rounded mt-3 p-3 bg-white">
        <div class="row">
            <div class="col-4">
                <button type="button" class="btn btn-primary btn-sm" onclick="load_videos()">Refresh</button>
                <ul class="list-group list-group-flush list-group-numbered" id="list-num">
                    
                </ul>
            </div>
            <div class="col-8">
                <video width="100%" height="500px" id="video" controls>
                    <source src="#" id="src" type="video/mp4">
                    Your browser does not support HTML video.
                </video>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
        function change_video(file_name){
            $("#src").attr("src", file_name)
            $("#video")[0].load()
        }
        function load_videos(){
            $("#list-num").html("")
            $.ajax({
                url : "request.php?action=list",
                type : "get",
                dataType : "json",
                success : function(resp){
                    var li = ""
                    if(resp.success){
                        var _li = 0
                        if(resp.data.length > 0){
                            resp.data.forEach(function(item){
                                var _name = item.name.split("/")
                                if(_name[1] !== ""){
                                    _li++
                                    li += `<li class='list-group-item'><a href='#' onclick="change_video('${item.mediaLink}')">${_name[1]}</a></li>`
                                }
                            })

                            if(_li == 0){
                                li += `<li class='list-group-item'>No Data Found</li>`
                            }

                        } else {
                            li += `<li class='list-group-item'>No Data Found</li>`
                        }
                    } else {
                        li += `<li class='list-group-item'>No Data Found</li>`
                    }

                    $("#list-num").append(li)
                }
            })
        }

        $(document).ready(function(){
            load_videos()
        })
    </script>
</body>
</html>