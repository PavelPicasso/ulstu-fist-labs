<!DOCTYPE html>
<html>
<head>
    <title>Slick Playground</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        .slider {
            width: 50%;
            margin: 100px auto;
        }

        .slick-slide {
            margin: 0px 20px;
        }

        .slick-slide img {
            width: 100%;
        }

        .slick-prev:before,
        .slick-next:before {
            color: black;
        }
    </style>
</head>
<body>

<section class="regular slider">
    <div>
        <img src="img/slider/back1.jpg">
    </div>
    <div>
        <img src="img/slider/dem1.jpg">
    </div>
    <div>
        <img src="img/slider/homecoming_2.jpg">
    </div>
    <div>
        <img src="img/slider/gor1.jpg">
    </div>
    <div>
        <img src="img/slider/fir1.jpg">
    </div>
    <div>
        <img src="img/slider/homecoming2.jpg">
    </div>
    <div>
        <img src="img/slider/im2.jpg">
    </div>
    <div>
        <img src="img/slider/imag1.jpg">
    </div>
    <div>
        <img src="img/slider/image_1.jpg">
    </div>
    <div>
        <img src="img/slider/image1.jpg">
    </div>
    <div>
        <img src="img/slider/img2.jpg">
    </div>
    <div>
        <img src="img/slider/la1.jpg">
    </div>
</section>

<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).on('ready', function() {


        iambolbes(img,rows);

    });

    var img_src = [
        'img/slider/back1.jpg',
        'img/slider/dem1.jpg',
        'img/slider/homecoming_2.jpg',
        'img/slider/gor1.jpg',
        'img/slider/fir1.jpg',
        'img/slider/homecoming2.jpg',
        'img/slider/im2.jpg',
        'img/slider/imag1.jpg',
        'img/slider/image_1.jpg',
        'img/slider/image1.jpg',
        'img/slider/img2.jpg',
        'img/slider/la1.jpg'
    ];
    $(document).ready ( function() {
//        for (var q = 0; q < img_src.length; q++) {
            //if (id_img(img_src[q]) == "1") {
                //$(".regular.slider").append("<div><img src="+img_src[q]+"> </div>");
                img = 2;
                rows = 2;
                //iambolbes(img,rows);
            //} else {

              //  img = 1;
               // rows = 1;
               // iambolbes(img,rows);
            //}
//        }
    });
//    var id, j;
//    function id_img(img) {
//        if (j = img.indexOf(".")) {
//            id = j - 1;
//        }
//        return img.charAt(id);
//    }


    function iambolbes(img,rows) {
        $(".regular").slick({
            dots: true,
            rows: rows,
            infinite: false,
            speed: 300,
            slidesToShow: img,
            slidesToScroll: 2,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
</script>

</body>
</html>