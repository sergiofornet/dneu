<?php
    $fileSystemIterator = new FilesystemIterator( 'img/' );

    $images = array();

    // 
    foreach ( $fileSystemIterator as $fileInfo ) {
        $filetypes = array( "jpg", "png" );
        $filetype = pathinfo( $fileInfo, PATHINFO_EXTENSION );
        if ( in_array( strtolower( $filetype ), $filetypes ) ) {
            $images[] = $fileInfo->getFilename();
        }
    }
?>

<script>
    ( function () {   
        var images = <?php echo json_encode( $images ); ?>;
        document.addEventListener("DOMContentLoaded", function () {
            "use strict";

            function randImage(images) {
                return images[Math.floor(Math.random() * images.length)];
            }

            function createNewGroup() {
                var randImages = [];
                randImages[0] = randImage(images);
                do {
                    randImages[1] = randImage(images);
                } while (randImages[1] === randImages[0]);
                do {
                    randImages[2] = randImage(images);
                } while (randImages[2] === randImages[1] || randImages[2] === randImages[0]);

                // preload images
                var container = document.createElement("div");
                container.setAttribute("id", "container");
                container.setAttribute("class", "preload");

                randImages.forEach(function (image) {
                    var img = document.createElement("img");
                    img.src = "img/" + image;
                    container.appendChild(img);
                });

                document.body.appendChild(container);
                var imgLoad = imagesLoaded(container);

                imgLoad.on("done", function () {
                    console.log('inages loaded');

                    if (document.querySelectorAll(".slides img")) {
                        var slides = document.querySelectorAll(".slides img");
                        var counter = 0;
                        while (counter < slides.length) {
                            slides[counter].parentNode.removeChild(slides[counter]);
                            counter += 1;
                        }
                    }

                    if (container.parentNode) {
                        container.parentNode.removeChild(container);
                    }

                    randImages.forEach(function (image, index) {
                        var slide = document.getElementById("slide" + (index + 1));
                        var img = document.createElement("img");
                        img.src = "img/" + image;
                        img.alt = "dneuwork. oscar : dneu portfolio";
                        slide.appendChild(img);
                    });

                    setTimeout(function () {
                        createNewGroup();
                    }, 4000);
                });
            }

            createNewGroup();

            var page = document.getElementById("page");
            var info = document.getElementById("info");

            function toggle() {
                var header = document.getElementById("header");
                var infoText = document.getElementById("info-text");
                header.classList.toggle("pink");
                infoText.classList.toggle("visible");
                page.classList.toggle("toggled");
            }

            info.addEventListener("mouseup", function (e) {
                toggle();
                e.stopPropagation();
            });

            document.body.addEventListener("mouseup", function () {
                if (page.classList.contains("toggled")) {
                    toggle();
                }
            });
        });
    } )();
</script>