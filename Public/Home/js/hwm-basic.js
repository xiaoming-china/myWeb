$(document).ready(function(){
  //分类控制
  $(".item-list-partent").click(function(){
  	$('.item-list-sub').hide(400);//先全部隐藏
    $(this).parent('.item-list').siblings('.item-list-sub').toggle(500);//再打开子级元素
    $('.item-list').removeClass('on');
    $(this).parent('.item-list').fadeIn("fast").addClass('on');
  });
  //配置滚动条插件
     $("html").niceScroll({
      styler:"fb",
      cursorcolor:"#18b2a6", 
      cursorwidth: '6', 
      cursorborderradius: '10px', 
      background: '#000', 
      cursorborder: '', 
      zindex: '1000'
    });
    //首页滚动条事件
    $(window).scroll(function(){
      var d_height = $(document).scrollTop();
      var c_height = $("#index-left").outerHeight(true);
          if(d_height > c_height ){
            $('#navbar-wrapper').addClass('navbar-wrapper animated bounceInDown');
          }else{
            $('#navbar-wrapper').removeClass('animated bounceInDown navbar-wrapper');
          }
     });
});
  //加载回复框
  $('.article-reply').click(function(event) {
    var text = $(this).text();
    if(text == '取消回复'){
      $(this).text('回复');
      $(this).parent('.comment-fotter').parent('.comment-box').find('.reply-box').remove();
    }else{
      //将所有回复框全部清除
      $('.comment-main-box').find('.reply-box').remove();
      $('.article-reply').text('回复');
      $('.emjoy-box').remove();
      //添加新的回复框
      $(this).text('取消回复');
      var html = "";
      html += '<div class="reply-box">';
      html += '<div class="clearfix"></div>';
      html += '<div class="add-comment-box">';
      html += '<div class="textarea-box"><textarea name="content"></textarea></div>';
      html += '<div class="add-file">';
      html += '<span class="icon-smile emjoy"></span>';
      // html += '<span class="icon-picture pull-left file-left"></span>';
      html += '<span class="pull-right">';
      html += '<button type="button" class="btn btn-info">评论</button>';
      html += '</span>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      $(this).parent('.comment-fotter').after(html);
    }
  });
  //加载表情
  $(document).on('click', '.emjoy', function() {
    $('.emjoy-box').remove();//清除全部表情
    if ($(this).find('.emjoy-box').length <= 0){
       var emjoy = '';
       emjoy += '<div class="emjoy-box">';
       for (var i = 1; i <= 75; i++) {
        emjoy += '<img src = "/Public/Home/images/emjoy/'+i+'.gif" alt="[emjoy:'+i+']"/>';
       }
       emjoy += '</div>';
      $(this).append(emjoy);
    }  
  });
  //表情点击事件
$(document).on('click', '.emjoy-box > img', function() {
  // //var content  = $(this).parent().parent().siblings('.textarea-box').find('textarea[name="content"]').val();
  var content  = $('textarea[name="content"]').val();
  var addEmjoy = $(this).attr('alt');
  var newVal   = content + addEmjoy;
  // //$(this).parent().parent().siblings('.textarea-box').find('textarea[name="content"]').val(newVal);
  $('textarea[name="content"]').val(newVal);
  $('.emjoy-box').remove();//清除全部表情
  // alert($('.emjoy-box').length);
});
//点击表情之外的区域
// $(document).not($(".emjoy-box")).click(function(){
//   // alert($('.emjoy-box').length);
//     if($('.emjoy-box').length > 0){
//       //alert($('.emjoy-box').length);
//       $(".emjoy-box").show();
//     }else{
//       $(".emjoy-box").hide();
//     }
//     /*防止事件冒泡*/
//     $(".emjoy-box").click(function(event){
//         // alert('test2中的内容是：'+$("#test2").html());
//         event.stopPropagation();
//     })
//   });
//相册信息
$(".photo").on("mousemove", function () {
    var $this = $(this);
    $this.find(".photo-info").slideDown('slow');
}).on("mouseout", function () {
    var $this = $(this);
    $this.find(".photo-info").fadeOut('slow');
});



