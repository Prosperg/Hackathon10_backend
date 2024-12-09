<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify</title>
</head>
<body>
   
    <button class="answer" id="answer" data-ans="54" data-case="[1,2,8]">Clic me</button>
    <br>
    <span id="showReturn"></span>
    <script>
        document.getElementById("answer").addEventListener('click', function(){
            var res = parseInt(this.getAttribute('data-ans'));
            var data = JSON.parse(this.getAttribute('data-case'));
            // alert(res);
            $helperFunc = `{{ greeting('${res}','${data}') }}`;
            console.log($helperFunc);
            document.getElementById("showReturn").innerHTML = $helperFunc;
            
        })
    </script>
</body>
</html>
