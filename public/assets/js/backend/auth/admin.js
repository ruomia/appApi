layui.use(['formSelects','layer','table'], function () {
  var table = layui.table;
  let form = layui.form;
  let formSelects = layui.formSelects;
  let layer = layui.layer;
  layer.load(2);
  table.render({
    elem: '#demo'
    , height: 'auto'
    , url: '/admin/auth/admin/index?type=2' //数据接口
    , toolbar: '#toolbarDemo'
    // , page: true //开启分页
    , cols: [[ //表头
      { type: 'checkbox', fixed: 'left' }
      , { field: 'id', title: 'ID', width: 60 }
      // , { field: 'pid', title: 'PID', width: 80 }
      , { field: 'username', title: '用户名', width: 100 }
      , { field: 'nickname', title: '昵称', width: 100 }
      , { field: 'group', title: '所属组别', templet: '#group' }
      , { field: 'email', title: 'Email', width: 200 }
      , {
        field: 'status', title: '状态', width: 80, templet: function (d) {
          if (d.status == 1) {
            return '<span class="layui-badge-dot layui-bg-green"></span>&nbsp;&nbsp;<span style="color: #5FB878">正常</span>'
          } else {
            return '<span class="layui-badge-dot layui-bg-gray"></span>&nbsp;&nbsp;<span style="color: #e2e2e2">隐藏</span>'
          }
        }
      }
      , { fixed: 'right', title: '操作', width: 160, align: 'center', toolbar: '#barDemo' } //这里的toolbar值是模板元素的选择器
    ]],
    done: function () {
      layer.closeAll('loading')
    }
  });


  // 头工具栏事件
  table.on('toolbar(test)', function (obj) {
    let checkStatus = table.checkStatus(obj.config.id);
    switch (obj.event) {
      case 'add':
        $.ajax({
          url: '/admin/auth/admin/add',
          type: 'get',
          dataType: 'json',
          success: function (res) {
            openPage(res)
          }
        })
        break;
      case 'delete':
        var data = checkStatus.data;
        del(data);
        break;

    }

  })
  // 监听工具条
  table.on('tool(test)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
    let data = obj.data; //获得当前行数据
    let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

    if (layEvent === 'detail') { //查看

    } else if (layEvent === 'del') { //删除
      layer.confirm('真的删除行么', function (index) {
        // obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
        layer.close(index);
        // 向服务器发送删除指令
        $.ajax({
          url: '/admin/auth/admin/del?ids=' + data.id,
          type: 'get',
          success: function (ret) {
            // 刷新表格
            table.reload('demo');
          }
        })
      })
    } else if (layEvent === 'edit') { //编辑

      $.ajax({
        url: '/admin/auth/admin/edit?id=' + data.id,
        type: 'get',
        dataType: 'json',
        success: function (res) {
          openPage(res)
        }
      })
    }
  })
  //监听提交
  form.on('submit(*)', function (data) {
    let loadIndex = layer.load(1, {
      shade: [0.1, '#fff']
    });
    $.ajax({
      url: data.form.action,
      type: 'post',
      data: data.field,
      dataType: 'json',
      success: function (ret) {
        if (ret.code === 1) {
          layer.msg('操作成功！');
          parent.layer.close(index)

        } else {
          layer.close(loadIndex)
          layer.msg(ret.msg);
        }
      }
    })
    return false;
  });
  function openPage(str) {
    layer.open({
      type: 1,
      area: ['60%', '75%'],
      content: str,
      success: function (res) {
        form.render();
        formSelects.render('selectId');
      }
    })
  }
  function del(datas) {
    let arr = [];
    for (data of datas) {
      arr.push(data.id);
    }
    arr = arr.join(',')
    // layer.msg(JSON.stringify(arr))
    if (arr.length < 1) {
      layer.msg('请选中需要删除的记录');
      return;
    }
    layer.confirm('确定要删除选中记录？', function () {
      $.ajax({
        url: '/admin/auth/admin/del?ids=' + arr,
        type: 'get',
        success: function (ret) {
          if (ret.code === 1) {
            layer.msg('删除成功');
            table.reload('demo');
          } else {
            layer.msg(ret.msg);
          }

        }
      })
    })
  }
});


