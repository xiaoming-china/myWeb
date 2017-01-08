/**
 * [checkAll 全选]
 * @return {[type]} [description]
 */
$('.checkboxAll').click(function() {
  var text = $(this).text();
  if(text == '全选'){
    $(this).text('取消');
    $(":checkbox").attr('checked',true);
  }else{
    $(this).text('全选');
    $(":checkbox").attr('checked',false);
  }
});
/**
 * [checkAfter 全选后操作]
 * @param  {[type]} dom [description]
 * @return {[type]}     [description]
 */
function checkAfter(url,title){
	var arrs = new Array();
    $("input[name='selectId']:checkbox").each(function(){ 
        if($(this).attr("checked")){
            arrs.push($(this).val());
        }
    });
    if(arrs.length == 0){
        layer.msg('请选择数据！');
        return false;
    }
	layer.confirm(title, {
	  btn: ['确定','取消'],
	  title:'提示'
	}, function(){
	  var selectId = arrs.join(",");
      //loading层
      layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
      });
      $.post(url, {id: selectId}, function(data) {
        _callBack(data);//回调
      },'json');
	}, function(){});
}
/**
 * [test 测试函数]
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
function test(data){
  alert(data);
}
/**
 * [onlyHandel 单个操作]
 * @return {[type]} [description]
 */
function onlyHandle(url,id,title){
    layer.confirm(title, {
      btn: ['确定','取消'],
      title:'提示'
    }, function(){
      //loading层
      layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
      });
      $.post(url, {id: id}, function(data) {
        _callBack(data);//回调
      },'json');
    }, function(){});
}
//操作过后的回调函数
 function _callBack(data){
    layer.closeAll();
   if(data.status == 'success'){
      layer.msg(data.info,{
        time: 2000
      },function(){
        location.reload();
       });
   }else{
      layer.alert(data.info, {
        title:'提示'
      });
   }
 }

 //数据提交前的弹窗
 function submitAfter(){
  layer.load(1, {
    shade: [0.1,'#fff'], //0.1透明度的白色背景
    shadeClose: true, //开启遮罩关闭
  });
 }
//提交成功后的函数
 function submitBefter(){
  layer.closeAll();
 }
 


    

