<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js sidebar-large lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js sidebar-large lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js sidebar-large lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js sidebar-large"><!--<![endif]-->

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="360 Me is a leads generation and tracking application. Specially crafted for the Chase group of companies, Kenya">
        <meta name="keywords" content="chase, 360, 360 me, leads, lead generation, lead tracking, chase group" />
        <meta name="author" content="QET Systems Ltd." >
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <title>Momentum - Business in motion</title>
        <!--Master css-->
        <link href="css/root.css" rel="stylesheet">
        <script type="text/javascript">
            var timer;
            function startTimer () {
                resetTimer();
            }

            function autoLogout () {
                clearInterval(timer);
                window.momentum.execute("logout:show");
            }

            function resetTimer () {
                //console.log('reset');
                clearInterval(timer);           
                timer = setInterval(function(){
                    autoLogout();
                }, 3000000);
            }
            startTimer();
        </script>
    </head>

    <body id="body" onmousemove="resetTimer()" onclick="resetTimer()" onkeypress="resetTimer()" onscroll="resetTimer()">
        <div class="loading"><img src="img/loading.gif" alt="loading-img"></div>
        <div id="menu"></div>
        <div id="content" class="content main"></div>
        
        <script data-main="assets/js/require_main.js?bust=v1" src="assets/js/vendor/require.js?bust=v1"></script>
    </body>
</html>