var $li = $('.img-list').find('> li'),
    $links = $li.find('> a'),
    $lightbox = $('.lightbox'),
    $overlay = $('.overlay'),
    $next = $('.mfp-arrow-right'),
    $prev = $('.mfp-arrow-left'),
    $arrow_right = $('.next'),
    $arrow_left = $('.prev'),
    liIndex,
    liIndex_src,
    targetImg,
    $numb = 0;



//img/slider/im2.jpg

var img_src = [
    'img/slider/im2.jpg',
    'img/slider/5.jpg',
    'img/slider/2.jpg',
    'http://placehold.it/360x600?text=4',
    'img/slider/3.jpg',
    'img/slider/1.jpg',
    'img/slider/7.jpg',
    'http://placehold.it/360x300?text=8',
    'http://placehold.it/360x600?text=9',
    'http://placehold.it/360x300?text=10',
    'http://placehold.it/360x300?text=11',
    'http://placehold.it/360x300?text=12'
];


var images = [];
var threephotos;
var number = [0, 4, 8, 12];
$(document).ready(function () {
    for (var q = 0; q <= img_src.length; q++) {
        $('#' + q).attr('src', img_src[q]);
        var k = 0, heightBig = 0;

        $('#' + q).load(function () {
            var height = $(this).height();
            images[k++] = height;
                if (height < 300) {
                    heightBig += height;
                }
                if (height > 300) {
                    $('.photo')[k].setAttribute("class", "img_shadow");
                    $('.photo')[k-1].setAttribute("class", "bigboss")
                  //  $('.bigboss img').height(heightBig);
                }

        });
    }
});

var next = 0;
$arrow_right.click(function () {
    next += 1;
    for (var w = number[next], f = 0; w <= number[next + 1]; w++, f++) {
        $('#' + f).attr('src', img_src[w]);
    }
});
$arrow_right.click(function () {
    if (next == 1){
        $('.gallery').css({'width' : '70%'});
        $('.photo').css({'clear': 'none'});
    }
});

$arrow_left.click(function () {
    if (next == 1){
        $('.gallery').css({'width' : '00%'});
        $('.photo').css({'clear': 'none'});
    }
    next -= 1;
    for (var w = number[next + 1], f = 0 , kost = number[next]; w >= number[next]; w--, f++, kost++) {
        $('#' + f).attr('src', img_src[kost]);
        $('.photo')[f].setAttribute("class", "photo");
    }
});



// var id, j;
// function id_img(img) {
//     if (j = img.indexOf(".")) {
//         id = j - 1;
//     }
//     //alert(img.charAt(id));
//     return img.charAt(id);
// }
// function ShowFolderFileList(folderspec)
// {
//     var fso, f, fc, s;
//     fso = new ActiveXObject('Scripting.FileSystemObject');
//     f = fso.GetFolder(folderspec);
//     fc = new Enumerator(f.files);
//     s = '';
//     for (; !fc.atEnd(); fc.moveNext())
//     {
//         s += fc.item();
//         s += '';
//     }
//     return(s);
// }
// document.write(ShowFolderFileList('C:\openserver\domains\www\img\slider'))


// imageObject = $.get(src="img/2.jpg");
// alert(imageObject);
// imageObject.onload = function() {
//     w = imageObject.width;
//     alert(w);
//     h = imageObject.height;
//     alert(h);
// }
//$arrow_right.click(function () {
    // for (var i = 0; i < 4; i++) {
    //         liIndex_src = img_src[i + 4];
    //         $(".img-list img:eq("+i+")").attr('src', liIndex_src);
    //         $(".img-list a:eq("+i+")").attr('href', liIndex_src);
    //     if (img_src.length == i) {
    //         var arrRight = document.getElementsByClassName("arrow-right");
    //         arrRight[0].style.display = 'none';
    //     }
    //     if (0 == i) {
    //         var arrleft = document.getElementsByClassName("arrow-left");
    //         arrleft[0].style.display = 'block';
    //     }
    // }
    // $(".img-list li")[1].setAttribute("class", "img-jet");
    // $(".img-list li .img-shadow")[0].style.display = 'none';
//});

//$arrow_left.click(function () {
    // for (var i = 0; i < 4; i++) {
    //     liIndex_src = img_src[i];
    //     $(".img-list img:eq("+i+")").attr('src', liIndex_src);
    //     $(".img-list a:eq("+i+")").attr('href', liIndex_src);
    //     if (img_src.length == i) {
    //         var arrRight = document.getElementsByClassName("arrow-right");
    //         arrRight[0].style.display = 'block';
    //     }
    //     $(".img-list li")[1].setAttribute("class", "img-jet");
    //     if (0 == i) {
    //         var arrleft = document.getElementsByClassName("arrow-left");
    //         arrleft[0].style.display = 'none';
    //     }
    // }
    // $(".img-list li")[1].setAttribute("class", "7");
    // $(".img-list li .img-shadow")[0].setAttribute("src", "img/14.jpg");
    // $(".img-list li a")[3].setAttribute("href", "img/14.jpg");
    // $(".img-list li .img-shadow")[0].style.display = 'inline-block';
//});


function replaceImg(scr) {
    $lightbox.find('img').attr('src', scr);
}

function getHref(index) {
    return $li.eq(index).find('> a').attr('href');
}

$links.click(function (event) {
    event.preventDefault();
    document.body.style.overflow = 'hidden';
    liIndex = $(this).parent().index();
    targetImg = $(this).attr('href');
    replaceImg(targetImg);
    $lightbox.fadeIn();
});

$overlay.click(function () {
    document.body.style.overflow = 'auto';
    $lightbox.fadeOut();
});

$next.click(function () {
    if ((liIndex + 1) < $li.length) {
        targetImg = getHref(liIndex + 1);
        liIndex++;

    } else {
        targetImg = getHref(0);
        liIndex = 0;
    }
    replaceImg(targetImg);
});

function slide() {
    if ((liIndex + 1) < $li.length) {
        targetImg = getHref(liIndex + 1);
        liIndex++;

    } else {
        targetImg = getHref(0);
        liIndex = 0;
    }
    replaceImg(targetImg);
};

$prev.click(function () {

    if (liIndex > 0) {
        targetImg = getHref(liIndex - 1);
        liIndex--;
    } else {
        targetImg = getHref($li.length - 1);
        liIndex = $li.length - 1;
    }
    replaceImg(targetImg);
});