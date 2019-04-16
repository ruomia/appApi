layui.use(['treetable', 'form'], function () {
  var treetable = layui.treetable;
  var table = layui.table;
  let form = layui.form;
  let $ = layui.jquery;

  let tableIns = table.render({
    elem: '#demo'
    , height: 'auto'
    , url: '/admin/shop/verify/index?type=2' //数据接口
    , toolbar: '#toolbarDemo'
    // , page: true //开启分页
    , cols: [[ //表头
      { field: 'id', title: 'ID', width: 80 }
      , { field: 'shop_name', title: '店铺名称' }
      , { field: 'name', title: '姓名', width: 80 }
      , { field: 'mobile', title: '手机号', width: 120 }
      , { field: 'email', title: '邮箱', width: 160 }
      , { field: 'card_id', title: '身份证号', width: 120 }
      , {
        field: 'status',
        title: '状态',
        width: 100,
        align: 'center',
        templet: function (d) {
          return '<span class="layui-badge layui-bg-green">待审核</span>'
        }
      }
      , {
        field: 'logo',
        title: 'LOGO',
        unresize: true,
        sort: false,
        templet: function (d) {
          return `<div class="layer-photos-demo" style="cursor:pointer;">
                    <img layer-pid="图片id，可以不写"  layer-src="/assets/img/photo1.png" 
                      src="/assets/img/photo1.png" alt="图片名">
                  </div>`;
        }
      }
      , {
        fixed: 'right', title: '操作', width: 160, align: 'center',
        templet: function (d) {
        
          return `<a class="layui-btn layui-btn-xs layui-btn-warning" lay-event="disabled">通过审核</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>`
        }
      } //这里的toolbar值是模板元素的选择器
    ]],
    done: function () {
      layer.closeAll('loading')

      layer.photos({
        photos: '.layer-photos-demo'
        , anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
      });
    }
  });


  // 监听工具条
  table.on('tool(test)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
    let data = obj.data; //获得当前行数据
    let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
    let tr = obj.tr;   //获得当前行tr 的DOM对象

    if (layEvent === 'disabled') { //禁用
      layer.confirm('真的禁用行么', function (index) {
        layer.close(index)
        $.ajax({
          url: '/admin/shop/shop/disabled?id=' + data.id,
          type: 'get',
          success: function (ret) {
            tableIns.reload();
          }
        })
      })
    } else if (layEvent === 'del') { //删除
      layer.confirm('真的删除行么', function (index) {
        // obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
        layer.close(index);
        // 向服务器发送删除指令
        $.ajax({
          url: '/admin/shop/shop/del?id=' + data.id,
          type: 'get',
          success: function (ret) {
            // 刷新表格
            tableIns.reload();
          }
        })
      })
    }
  })

});
