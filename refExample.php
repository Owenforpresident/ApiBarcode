<script> 

function onDrawTextFile() {
    console.log('draw ran...')
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        $.ajax({
            url: "getRef.php",
            type: 'GET',
            data: {
                ref: "<?php echo $ref?>"
            }
        }).done(function (result) {
            console.log(result)

        var context = canvas.getContext('2d');

        var image = new Image();

        image.src = "img/refs/<?php echo $_GET['ref']?>.png"

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
</script>
