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
                name : '日志管理',
                href : '#',
                cite : '日志管理',
            },
            {
                name : '备份管理',
                href : '#',
                cite : '备份管理',
            },
            {
                name : '缓存管理',
                href : '#',
                cite : '缓存管理',
            },
        ]
    },
    {
        name : '评论&收藏',
        href : '#',
        icon : 'layui-icon layui-icon-auz',
        cite : '评论&收藏',
    },
    {
        name : '日记&相册',
        href : '#',
        icon : 'layui-icon layui-icon-auz',
        cite : '日记&相册',
    },



];
