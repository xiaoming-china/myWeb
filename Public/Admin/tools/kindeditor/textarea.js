var editor;
KindEditor.ready(function(K) {
    editor = K.create('#content', {
        filterMode:false,
        width:'669px',
        height:'300px',
        themeType : 'simple',
        allowFileManager: true,
        allowFlashUpload: false,
        allowMediaUpload: false,
        allowFileUpload:false,
        uploadJson:"/Admin_Ajax_kindupfile",
        fileManagerJson : "/Admin_Ajax_adminupfile",
        afterBlur:function(){
            this.sync();
        },
        items:['source', '|', 'undo', 'redo', '|','cut','copy','paste','plainpaste','selectall','preview','fullscreen']
    });
});