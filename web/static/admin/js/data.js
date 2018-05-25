var data = [
    {
        name : '内容管理',
        cite : '内容管理',
        icon : 'layui-icon layui-icon-file-b',
        childs : [
            {
                name : 'category',
                href : '/index.php?r=admin/content/category/index',
                cite : '分类管理',
            },
            {
                name : 'topic',
                href : '/index.php?r=admin/content/topic/index',
                cite : '话题管理',
            },
            {
                name : 'article',
                href : '/index.php?r=admin/content/article/index',
                cite : '文章管理',
            },

        ]
    },
    {
        name : '日记相册',
        icon : 'layui-icon layui-icon-picture',
        cite : '日记相册',
        childs : [
            {
                name : 'notebokk',
                href : '/index.php?r=admin/notebook/index/index',
                cite : '日记管理',
            },
            {
                name : 'album',
                href : '/index.php?r=admin/notebook/album/index',
                cite : '相册管理',
            },
        ]
    },
    {
        name : '评论收藏',
        icon : 'layui-icon layui-icon-flag',
        cite : '评论收藏',
        childs : [
            {
                name : 'comment',
                href : '/index.php?r=admin/comment/comment/comment',
                cite : '评论管理',
            },
            {
                name : 'reply',
                href : '/index.php?r=admin/comment/comment/reply',
                cite : '回复管理',
            },
        ]
    },
    {
        name : '成员管理',
        cite : '成员管理',
        icon : 'layui-icon layui-icon-user',
        childs : [
            {
                name : 'member',
                href : '/index.php?r=admin/member/user/index',
                cite : '成员管理',
            },
        ]
    },
    {
        name : '友链管理',
        cite : '友链管理',
        icon : 'layui-icon layui-icon-link',
        childs : [
            {
                name : 'member',
                href : '/index.php?r=admin/friend/index/index',
                cite : '友链管理',
            },
        ]
    },
    {
        name : '站点设置',
        cite : '站点设置',
        icon : 'layui-icon layui-icon-set',
        childs : [
            {
                name : '站点信息',
                href : '/index.php?r=admin/setting/metas/index',
                cite : '站点信息',
            },
            {
                name : '日志管理x',
                href : '#',
                cite : '日志管理x',
            },
            {
                name : '备份管理x',
                href : '#',
                cite : '备份管理x',
            },
            {
                name : '缓存管理x',
                href : '#',
                cite : '缓存管理x',
            },
        ]
    },




];
