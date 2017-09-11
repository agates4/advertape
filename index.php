<?
    require __DIR__ . '/vendor/autoload.php';

    # This function reads your DATABASE_URL configuration automatically set by Heroku
    # the return value is a string that will work with pg_connect
    function pg_connection_string() {
        $dbopts = parse_url(getenv('DATABASE_URL'));
        return "dbname=".ltrim($dbopts["path"],'/')." host=".$dbopts["host"]." port=".$dbopts["port"]." user=".$dbopts["user"]." password=".$dbopts["pass"]." sslmode=require";
    }
    
    # Establish db connection
    $db = pg_connect(pg_connection_string());
    if (!$db) {
        echo "Database connection error.";
        exit;
    }
    
    $result = pg_query($db, "SELECT * FROM ads");

    var_dump(pg_fetch_row($result));
?>

<html>

<head>
    <link rel="stylesheet" href="resources/css/spectre/spectre.css"/>
    <link rel="stylesheet" href="resources/css/spectre/spectre-exp.css"/>
    <link rel="stylesheet" href="resources/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="resources/css/custom-input-file.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300">
    <link rel="stylesheet" href="https://cdn.rawgit.com/yahoo/pure-release/v0.6.0/pure-min.css">
    <link rel="stylesheet" href="resources/css/auto-complete.css">
    <script src="resources/js/jquery-v1.min.js"></script>
    <script src="resources/js/qr-code.min.js"></script>
    <script src="resources/js/auto-complete.js"></script>

    <title>AdverTape Rolls</title>
    <meta name="description" content="Purchase tape with ads - and have is distributed straight to your target.">
</head>

<style>
    .imageWrapper {
        position: relative;
    }
    .overlayImage {
        position: absolute;
        top: 0;
        left: 0;
    }
    .resize {
    background-size: contain;
    }
    .center {
        margin: auto;
        width: 50%;
    }
    .imgWrap:after{
        content:"";
        position:absolute;
        top:0; bottom:0; left:0; right:0;
        opacity:0.5;
        border:5px solid transparent;
    }
    .logo {
        height: 140;
        width: 522;
    }
    img {
        max-width: 100%;
        height: auto;
        width: auto\9;
    }
</style>

<div class="panel">
    <div class="panel-header text-center">
        <img class="logo resize center" src="resources/img/advertape.png">
        <div class="panel-subtitle">Marketing on a <u>roll</u></div>
    </div>
</div>
<div class="columns">
    <div class="column col-xs-12">
        <div class="modal-temp modal-sm">
            <div class="modal-overlay"></div>
            <div class="modal-container" role="document">
                <div class="modal-body">
                    <div class="content">
                        <div class="panel-nav">
                            <ul class="tab tab-block">
                                <li class="tab-item active">
                                    <a>
                                            Customize
                                    </a>
                                </li>
                            </ul>
                        </div>
                            <div class="tile tile-centered">
                                <div class="tile-content">
                                    
                                    <div class="columns">
                                        <div class="column center col-xs-6">
                                            Upload your logo
                                        </div>
                                        <div class="column center col-xs-6">
                                            <div id="uploadDesign">
                                                <input type="file" name="file-2[]" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected" multiple="">
                                                <label for="file-2">
                                                    <svg width="20" height="17" viewBox="0 0 20 17">
                                                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                                                    </svg> 
                                                    <span id="imageName">Choose File</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <br/>

                                    <div class="columns">
                                        <div class="column center col-xs-6">
                                            Which Universities do you want to target?
                                             <div style="top:25;opacity: 0.0;" class="badge" id="placeHolder" data-badge="OSU"></div> 
                                             <div style="top:25;opacity: 0.0;" class="badge" id="placeHolder" data-badge="OSU"></div> 
                                        </div>
                                        
                                        <div class="column center col-xs-6">
                                            <!-- <input class="form-input" type="number" id="input-example-13" placeholder="00" value="1000"> -->
                                            <div style="max-width:900px;margin:auto;">
                                                <form onsubmit="return false;" style="top:50;" class="pure-form" style="margin:30px 0 40px">
                                                    <input id="advanced-demo" type="text" name="q" placeholder="Choose Universities" style="width:100%;max-width:600px;outline:0" autocomplete="off">
                                                    <span style="top:25;opacity: 0.0;right:50;" class="badge" id="placeHolder" data-badge=" "></span>
                                                    <div id="appendBadges"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="columns">
                                        <div class="column center col-xs-6">
                                            How much will it cost?
                                        </div>
                                        <div class="column center col-xs-6">
                                            <div class="toast toast-success" id="toast" style="width:125;">
                                                0
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <br/>
                                </div>
                            </div>
                            <br/>
                        <div class="panel-footer">
                            <button class="btn btn-primary btn-block">Purchase</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column col-xs-12">
        <div class="modal-temp modal-sm">
            <div class="modal-overlay"></div>
            <div class="modal-container" role="document">
                <div class="modal-body">
                    <div class="content">
                        <div class="panel-nav">
                            <ul class="tab tab-block">
                                <li class="tab-item active">
                                    <a>
                                            Preview
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tile tile-centered">
                            <div class="tile-content">

                                <br/>

                                <div class="container">
                                    <div class="">
                                        <div class="imageWrapper">
                                            <div class="parallax">
                                                <div class="parallax-top-left"></div>
                                                <div class="parallax-top-right"></div>
                                                <div class="parallax-bottom-left"></div>
                                                <div class="parallax-bottom-right"></div>
                                                <div class="parallax-content" id="parallaxContent">
                                                    <div class="parallax-back">
                                                        <img src="resources/img/tape.png" id="orgBox" style="width:778; height:355;" class="img-fit-contain rounded center" />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <img src="resources/img/tape.png" id="orgBox" class="overlayImage"> -->
                                            <img src="" class="overlayImage resize cover" id="boxOverlay" style="width:400; height:187; left: 363; top: 20;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            
<script>
    
    function boxRatio() {
        var width = $("#orgBox").width();
        var height = (355 / 778) * width;

        $("#boxOverlay").width((400 / 777) * width);
        var left = (363 / 777) * width;
        document.getElementById('boxOverlay').style.setProperty("left", left.toString());

        $("#boxOverlay").height((187 / 355) * height); 
        var top = 178 - height / 2.25;
        document.getElementById('boxOverlay').style.setProperty("top", top.toString());
    }

    window.onload = function() {
        boxRatio();
    }

    window.onresize = function(event) {
        boxRatio();
    };

    document.getElementById('file-2').addEventListener('change', readURL, true);

    function readURL() {
        var file = document.getElementById("file-2").files[0];
        var reader = new FileReader();
        reader.onloadend = function() {
            var rotated = rotateBase64Image90deg(reader.result, false);
            document.getElementById('boxOverlay').style.backgroundImage = "url(" + rotated + ")";
        }
        if (file) {
            reader.readAsDataURL(file);
            document.getElementById("imageName").innerHTML = file["name"];
        } else {}
    }
    $('#advanced-demo').click(function () {
        if ($(this).is(':focus')) {
            $('#theOptions').show();
        }
    });

    function rotateBase64Image90deg(base64Image, isClockwise) {
        // create an off-screen canvas
        var offScreenCanvas = document.createElement('canvas');
        offScreenCanvasCtx = offScreenCanvas.getContext('2d');
        
        // cteate Image
        var img = new Image();
        img.src = base64Image;

        // set its dimension to rotated size
        offScreenCanvas.height = img.width;
        offScreenCanvas.width = img.height;

        // rotate and draw source image into the off-screen canvas:
        if (isClockwise) { 
            offScreenCanvasCtx.rotate(90 * Math.PI / 180);
            offScreenCanvasCtx.translate(0, -offScreenCanvas.width);
        } else {
            offScreenCanvasCtx.rotate(-90 * Math.PI / 180);
            offScreenCanvasCtx.translate(-offScreenCanvas.height, 0);
        }
        offScreenCanvasCtx.drawImage(img, 0, 0);

        // encode image to data-uri with base64
        return offScreenCanvas.toDataURL("image/png", 100);
    }

        var demo2 = new autoComplete({
            selector: '#advanced-demo',
            minChars: 0,
            source: function(term, suggest){
                term = term.toLowerCase();
                var choices = [
                    ['Ashland University', 'AU'], 
                    ['Case Western Reserve University', 'CWRU'],
                    ['Cleveland State University', 'CSU'], 
                    ['Ohio State University', 'OSU'], 
                    ['University of Akron', 'UA']];
                var suggestions = [];
                for (i=0;i<choices.length;i++)
                    if (~(choices[i][0]+' '+choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                suggest(suggestions);
            },
            renderItem: function (item, search){
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-langname="'+item[0]+'" data-lang="'+item[1]+'" data-val="'+search+'">'+item[0].replace(re, "<b>$1</b>")+'</div>';
            },
            onSelect: function(e, term, item){
                console.log('Item "'+item.getAttribute('data-langname')+' ('+item.getAttribute('data-lang')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
                // var useless = item.getAttribute('data-langname')+' ('+item.getAttribute('data-lang')+')';
                // document.getElementById('advanced-demo').value = "Click here to add more!";
                $('<span style="top:15;right:20;" class="badge" data-badge="' + item.getAttribute('data-lang').toString() + '"></span><span>   </span>').insertBefore('#appendBadges');
                switch (item.getAttribute('data-lang')) {
                    case "AU":
                        $("#toast").text((9519 + Number($("#toast").text())).toString());
                        break;
                    case "CWRU":
                        $("#toast").text((19478.88 + Number($("#toast").text())).toString());
                        break;
                    case "CSU":
                        $("#toast").text((28824.2 + Number($("#toast").text())).toString());
                        break;
                    case "OSU":
                        $("#toast").text((110296.82 + Number($("#toast").text())).toString());
                        break;
                    case "UA":
                        $("#toast").text((42045.59 + Number($("#toast").text())).toString());
                        break;
                    default:
                        break;
                }
                $('#theOptions').blur();
            }
        });

        if (~window.location.href.indexOf('http')) {
            (function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();
            (function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=114593902037957";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
            document.getElementById('github_social').innerHTML = '\
                <iframe style="float:left;margin-right:15px" src="//ghbtns.com/github-btn.html?user=Pixabay&repo=JavaScript-autoComplete&type=watch&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe>\
                <iframe style="float:left;margin-right:15px" src="//ghbtns.com/github-btn.html?user=Pixabay&repo=JavaScript-autoComplete&type=fork&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe>\
            ';
        }
</script>



</html>