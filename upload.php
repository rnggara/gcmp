<html>
    <head>
        <meta charset="UTF-8">
        <title>GCP Storage File Upload using PHP</title>
    </head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #000;
            color: #fff;
            text-align: center;
        }
    </style>
    <body>
        <div class="container rounded mt-3 p-3">
            <button type="button" id="fileUploadForm" class="btn btn-primary">Upload Files</button>
            <hr/>
            <div class="spinner-border" id="spin" style="display: none;width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="alert alert-success" style="display: none;" role="alert">
                Upload Success
            </div>
            
            <hr/>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
        <script>
            $("#spin").hide()
            $("#fileUploadForm").click(function(e){
                $("#spin").show()
                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                console.log(percentComplete);
                                $('.progress').css({
                                    width: percentComplete * 100 + '%'
                                });
                                if (percentComplete === 1) {
                                    $('.progress').addClass('hide');
                                }
                            }
                        }, false);
                        xhr.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                console.log(percentComplete);
                                $('.progress').css({
                                    width: percentComplete * 100 + '%'
                                });
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'GET',
                    url: "request.php?action=upload",
                    // data: data,
                    success: function (data) {
                        $("#spin").hide()
                        $("div.alert").show()
                    }
                })
            })
        </script>
    </body>
</html>