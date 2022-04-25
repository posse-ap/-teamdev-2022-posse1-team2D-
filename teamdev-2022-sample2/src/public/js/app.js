// ----------------Hiroki's area-------------------------------------------

// スクロール時にヘッダーにタグで絞り込むボタンを表示

// jQuery(function($){
//     $(window).on('scroll', function(){
//       if ($(window).scrollTop() > 300) {
//         $('#pagetop').fadeIn(400);
//       } else {
//         $('#pagetop').fadeOut(400);
//       }
//     });
//   });

const tags = document.querySelectorAll('.tag');

tags.forEach(tag =>{
tag.addEventListener('click', function(){
        // console.log(this);
        tag.classList.toggle('bi-check-lg');
    })
});

// ---------------Miu's area----------------------------------------------
