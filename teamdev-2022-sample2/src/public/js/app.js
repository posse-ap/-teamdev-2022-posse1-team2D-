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

const tags = document.querySelectorAll(".tag");

tags.forEach((tag) => {
  tag.addEventListener("click", function () {
    // console.log(this);
    tag.classList.toggle("bi-check-lg");
  });
});

// 指定箇所へのスムーススクロール
$(function () {
  // #で始まるアンカーをクリックした場合に処理
  $("a[href^=#]").click(function () {
    // スクロールの速度
    var speed = 400; // ミリ秒
    // アンカーの値取得
    var href = $(this).attr("href");
    // 移動先を取得
    var target = $(href == "#" || href == "" ? "html" : href);
    // 移動先を数値で取得
    var position = target.offset().top;
    // スムーススクロール
    $("body,html").animate({ scrollTop: position }, speed, "swing");
    return false;
  });
});

// ドロップダウンメニュー項目を押してもドロップダウンメニューが閉じないようにする
$(".dropdown-item").on("click.bs.dropdown.data-api", (event) =>
  event.stopPropagation()
);

// モーダル
$(function () {
  $(".js-modal-open").on("click", function () {
    $(".js-modal").fadeIn();
    return false;
  });
  $(".js-modal-close").on("click", function () {
    $(".js-modal").fadeOut();
    return false;
  });
});
// ---------------Miu's area----------------------------------------------

// 検索結果画面：キープボタンクリック時の☆アイコン切り替え
// $('.keep-btn').click(function(){
//      $('.white-star').hide();
//      $('.black-star').show();
// });

let changeStars = document.querySelectorAll(".keep-btn");

changeStars.forEach((changeStar) => {
  changeStar.addEventListener("click", function () {
    console.log("a");
    changeStar.classList.remove("bi-star");
    changeStar.classList.remove("white-star");
    changeStar.classList.add("bi-star-fill");
    changeStar.classList.add("black-star");
  });
});

// プライバシーポリシーに同意したらお申込み送信
$(function(){
   $('.Form-CheckItem-Label').on('click', function(){
   if ($('#JS_CheckItem').prop("checked") == true) {
   $('.send').addClass('isActive');
   } else {
   $('.send').removeClass('isActive');
   }
   });
   });

// $(function () {
//   //始めにjQueryで送信ボタンを無効化する
//   $(".send").prop("disabled", true);

//   //始めにjQueryで必須欄を加工する
//   $("form input:required").each(function () {
//     $(this).prev("label").addClass("required");
//   });

//   //入力欄の操作時
//   $("form input:required").change(function () {
//     //必須項目が空かどうかフラグ
//     let flag = true;
//     //必須項目をひとつずつチェック
//     $("form input:required").each(function (e) {
//       //もし必須項目が空なら
//       if ($("form input:required").eq(e).val() === "") {
//         flag = false;
//       }
//     });
//     //全て埋まっていたら
//     if (flag) {
//       //送信ボタンを復活
//       $(".send").prop("disabled", false);
//     } else {
//       //送信ボタンを閉じる
//       $(".send").prop("disabled", true);
//     }
//   });
// });




let graduation = document.getElementById("graduation");
let defaultWord = document.querySelector(".default-word");

  graduation.addEventListener("change", function () {
     this.classList.add("text-dark");
     console.log(defaultWord)
     defaultWord.style.display="none";
  });



