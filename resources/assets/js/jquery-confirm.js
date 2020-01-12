jConfirm = {
    dialog : function (title = '', content = '') {
        $.dialog({
            title: title,
            content: content,
            theme: 'my-theme',
            draggable: false,
            animation: 'scale',
        });
    },

    confirm : function (title = ' ', content = ' ', btns = {}) {
        return $.confirm({
            title : title,
            content : content,
            theme : 'my-theme',
            draggable : false,
            animation : 'scale',
            buttons : btns
        });
    }
}