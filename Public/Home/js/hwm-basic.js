$(document).ready(function(){
  //分类控制
  $(".item-list-partent").click(function(){
    $(".item-list-partent").text('+');
    if($(this).text() == '+'){
      $(this).text('-');
    }else{
      $(this).text('+');
    }
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
  $(document).on('click', '.emjoy', function(event) {
    event.stopPropagation();//阻止冒泡
    $('.message-reply').hide();//设置回复全部隐藏
    $('.emjoy-box').remove();//清除全部表情
    if ($(this).find('.emjoy-box').length <= 0){
       var emjoy = '';
       emjoy += '<div class="emjoy-box" id="emjoy-box">';
       for (var i = 1; i <= 75; i++) {
        emjoy += '<img class="emjoy-img" src = "/Public/Home/images/emjoy/'+i+'.gif" alt="[emjoy:'+i+']"/>';
       }
       emjoy += '</div>';
      $(this).after(emjoy);
    }  
  });
  //表情点击事件
$(document).on('click', '.emjoy-box > img', function() {
  //var content  = $(this).parent().parent().siblings('.textarea-box').find('textarea[name="content"]').val();
  var content  = $('textarea[name="content"]').val();
  var addEmjoy = $(this).attr('alt');
  var newVal   = content + addEmjoy;
  // //$(this).parent().parent().siblings('.textarea-box').find('textarea[name="content"]').val(newVal);
  $('textarea[name="content"]').val(newVal);
  $('.emjoy-box').remove();//清除全部表情
});
//点击表情之外的区域
$(document).click(function(event) {
  $('.message-reply').hide();//设置回复全部隐藏
  if($('.emjoy-box').length > 0){
    $('.emjoy-box').remove();//清除全部表情
  }
});
//相册信息
$(".photo").on("mousemove", function () {
    var $this = $(this);
    $this.find(".photo-info").slideDown('slow');
}).on("mouseout", function () {
    var $this = $(this);
    $this.find(".photo-info").fadeOut('slow');
});
//显示当前留言的回复
$('.message-list').click(function(event) {
    event.stopPropagation();//阻止冒泡
    $('.message-reply').hide();//设置全部隐藏
    $('.emjoy-box').remove();//清除全部表情
    $(this).find('.message-reply').show();
});
/**
 * [description关闭留言回复按钮操作]
 * @Author:xiaoming
 * @DateTime        2017-02-07T20:56:55+0800
 * @param           {[type]}                 ){               alert();} [description]
 * @return          {[type]}                     [description]
 */
$(document).on('click', '.close-reply', function() {
  alert();
});
//点击body的时候的操作是全部隐藏
$(document).click(function(){
    $('.message-reply').hide();//设置全部隐藏
});
/**
 * [description 添加留言]
 * @Author:xiaoming
 * @DateTime        2017-02-08T08:45:47+0800
 * @param           {[type]}                 event) {               var content [description]
 * @return          {[type]}                        [description]
 */
$('.add_message').click(function(event) {
    var content = $('textarea[name="content"]').val();
    if(content == ''){
      layer.msg('留言内容不能为空', {
        time: 1000 //1秒关闭（如果不配置，默认是3秒）
      });
    }else{
      $(this).html('正在加载。。。');
      $.post(url, {message: content}, function(rs) {
            if(rs.status == 'success'){
                layer.msg(rs.info, {
                  time: 1000 //1秒关闭（如果不配置，默认是3秒）
                }, function(){
                  location.reload();
                });
            }else{
                layer.msg(rs.info);
            }
        },'json');
    }
});
//评论文章
$(document).on('click','.comment-add',function(event) {
    var content = $('textarea[name="content"]').val();
    if(content == ''){
      layer.msg('评论内容不能为空', {
        time: 1000 //1秒关闭（如果不配置，默认是3秒）
      });
    }else{
      $(this).html('正在加载。。。');
      $.post(url, {content: content,aId:aId}, function(rs) {
            if(rs.status == 'success'){
                layer.msg(rs.info, {
                  time: 1000 //1秒关闭（如果不配置，默认是3秒）
                }, function(){
                  location.reload();
                });
            }else{
                layer.msg(rs.info);
            }
        },'json');
    }
});



