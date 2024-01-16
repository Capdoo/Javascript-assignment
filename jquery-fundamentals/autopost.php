<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Deserunt eius cumque non incidunt beatae quas quibusdam excepturi nihil provident esse officiis explicabo nesciunt ratione earum, aspernatur fuga iusto quia magnam.
    </p>

    <form id="target">
        <input type="text" name="one" value="Hello there"
            style="vertical-align:middle;">
    
        <img id="spinner" src="spinner.gif"  height="25"
            style="vertical-align: middle; display:none;">
    </form>

    <hr/>
        <div id="result"></div>
    <hr/>

    <!--Llamamos a la libreria JQuery-->
    <script type="text/javascript" src="jquery.min.js"></script>
    <!--Script para la operacion-->
    <script type="text/javascript">
        $('#target').change(function(event){
                $('#spinner').show();
                /*Assign to form the shape from the original html form*/
                var form = $('#target');
                /*Search inside form the input with the name given, stored in txt*/
                var txt = form.find('input[name="one"]'.val());
                /*Console output*/
                window.console && console.log('Sending POST');
                
                /*For the server interaction through POST method*/
                $.post('autoecho.php', {'val': txt},
                
                    /*Function to recive the data (response from the server)*/
                    function(data){
                        window.console && console.log(data);
                        /*Clean div result and add data*/
                        $('#result').empty().append(data);
                        /*Stop showing spinner gif*/
                        $('#spinner').hide();
                    }
                
                ).error(function(){
                    $.('#target').css('background-color','red');
                    alert('dang');
                });
        });

    </script>

</body>
</html>



