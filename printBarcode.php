<?php
$EAN13 = $_GET['ean13'];
$qty = $_GET['qty'];
?> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/slate/bootstrap.min.css" rel="stylesheet" integrity="sha384-G9YbB4o4U6WS4wCthMOpAeweY4gQJyyx0P3nZbEBHyz+AtNoeasfRChmek1C2iqV" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
    
   <!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">    -->
<script type='text/javascript'>


    onDrawImageFile();
    printBarcodes();



function onDrawImageFile() {
    console.log('draw ran...')
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        $.ajax({
            url: "getBarCode.php",
            type: 'GET',
            data: {
                ean13: "<?php echo $EAN13?>"
            }
        }).done(function (result) {
            console.log(result)

        var context = canvas.getContext('2d');

        var image = new Image();

        image.src = "img/barcodes/<?php echo $_GET['ean13']?>.png"

        image.onload = function () {
            var x = parseInt(document.getElementById('positionX').value);
            var y = parseInt(document.getElementById('positionY').value);

            var stretch = document.getElementById('stretch').value;

            var srcWidth   = image.width;
            var srcHeight  = image.height;
            var destWidth  = image.width  * stretch / 100;
            var destHeight = image.height * stretch / 100;

            context.drawImage(image, 0, 0, srcWidth, srcHeight, x, y, destWidth, destHeight);

            document.getElementById('positionY').value = y + destHeight + 16;

        }

        image.onerror = function () {
            alert('Image file was not able to be loaded.');
        }
    }) 
 }
}

function printBarcodes() {               
    var qty = <?=$_GET['qty'] ?>;
    var i = 0;
        while (i < qty) {
        onSendMessage();
        i++;
        }
}


function onSendMessage() {
    var url              = document.getElementById('url').value;
    var papertype        = document.getElementById('papertype').value;
    
    
    var trader = new StarWebPrintTrader({url:url, papertype:papertype});

    trader.onReceive = function (response) {
        var msg = '- onReceive -\n\n';
        msg += 'TraderSuccess : [ ' + response.traderSuccess + ' ]\n';
        msg += 'TraderStatus : [ ' + response.traderStatus + ',\n';
        if (trader.isCoverOpen            ({traderStatus:response.traderStatus})) {msg += '\tCoverOpen,\n';}
        if (trader.isOffLine              ({traderStatus:response.traderStatus})) {msg += '\tOffLine,\n';}
        if (trader.isCompulsionSwitchClose({traderStatus:response.traderStatus})) {msg += '\tCompulsionSwitchClose,\n';}
        if (trader.isEtbCommandExecute    ({traderStatus:response.traderStatus})) {msg += '\tEtbCommandExecute,\n';}
        if (trader.isHighTemperatureStop  ({traderStatus:response.traderStatus})) {msg += '\tHighTemperatureStop,\n';}
        if (trader.isNonRecoverableError  ({traderStatus:response.traderStatus})) {msg += '\tNonRecoverableError,\n';}
        if (trader.isAutoCutterError      ({traderStatus:response.traderStatus})) {msg += '\tAutoCutterError,\n';}
        if (trader.isBlackMarkError       ({traderStatus:response.traderStatus})) {msg += '\tBlackMarkError,\n';}
        if (trader.isPaperEnd             ({traderStatus:response.traderStatus})) {msg += '\tPaperEnd,\n';}
        if (trader.isPaperNearEnd         ({traderStatus:response.traderStatus})) {msg += '\tPaperNearEnd,\n';}
        msg += '\tEtbCounter = ' + trader.extractionEtbCounter({traderStatus:response.traderStatus}).toString() + ' ]\n';
        //alert(msg);
    }
    trader.onError = function (response) {
        var msg = 'There was an error printing \n';
        msg += 'Do you want to retry?\n';
        var answer = confirm(msg);
            if (answer) {
                console.log('print function called again..')
                onSendMessage();
            }
                else {
                    console.log('print cancelled..')
                }
    }
            try {
                var canvas = document.getElementById('canvasPaper');

                if (canvas.getContext) {
                    var context = canvas.getContext('2d');

                    var builder = new StarWebPrintBuilder();

                    var request = '';

                    request += builder.createInitializationElement();

                    request += builder.createBitImageElement({context:context, x:0, y:0, width:canvas.width, height:canvas.height});

                    request += builder.createCutPaperElement({feed:true});

                    trader.sendMessage({request:request});
                }
            }
                catch (e) {
                    alert(e.message);
                }
}


</script>
</head>
<body>
<div class="jumbotron" id="page">
    <div class="row justify-content-md-center">
        <div class="card" style="width: 25rem; text-align: center " id="contenu">
            <div class="card-header">
                
            </div>
            <form onsubmit='return false;' id="form">
		<header>
			<h1></h1>
		</header>

		<div class="container">
			<div class="wrapper">
				<div id="canvasBlock">
					<div id='canvasFrame'>
						<canvas id='canvasPaper' height="230"> 
                            
						</canvas>
					</div>
				</div>
			</div>
			<div id="optionBlock" >
				<dl style= "visibility: hidden; display: none;">
					<dt >Stretch</dt>
					<dd>:
						<select id='stretch'>
							<option selected='selected'>70</option>
							<option >100</option>
							<option>144</option>
						</select> %
					</dd>
				</dl>
				<dl style= "visibility: hidden; display: none;" >
					<dt>Position</dt>
					<dd>:
						X=
                        <input id='positionX' type='text' size='5' value='0' />
                        Y=
                        <input id='positionY' type='text' size='5' value='0' />
					</dd>
				</dl>			
			
                <div class="input-group">
                    <input type="number" id='printQuantity'class="form-control" value=<?=$qty?> aria-label="Amount (to the nearest dollar)">
                    <div class="input-group-append">
                    <span class="input-group-text">Quantity</span>
                </div>      
			</div>
			<hr>
			<footer>
				<dl style= "visibility: hidden; display: none;">
					<dt>URL</dt>
					<dd>:
                    <input id="url" type="text" value="http://localhost:8001/StarWebPRNT/SendMessage" />
                    </dd>
				</dl>
                <d1 >
                    <dt style= "visibility: hidden ; display: none;">Paper Type</dt>
                    <dd style= "visibility: hidden ; display: none;">:
                        <select id='papertype'>
                            <option value='' selected='selected'>-</option>
                            <option value='normal'>Normal</option>
                            <option value='black_mark'>Black Mark</option>
                            <option value='black_mark_and_detect_at_power_on'>Black Mark and Detect at Power On</option>
                        </select>
                    </dd>
                </dl>
				<button class="btn btn-warning btn-lg btn-block mb-3"id="sendBtn" type="button" value="Send" onclick="printBarcodes()" >Send to printer!</button>
			</footer>
		</div>
	</form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script type='text/javascript' src='js/StarWebPrintBuilder.js'></script>
<script type='text/javascript' src='js/StarWebPrintTrader.js'></script>

</body>
</html>

