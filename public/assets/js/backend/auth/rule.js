layui.use(['treetable'], function () {
  var treetable = layui.treetable;
  var table = layui.table;
  let form = layui.form;

  let renderTable = function () {
    layer.load(2);
    treetable.render({
      treeColIndex: 1,//树形图标显示在第几列
      treeSpid: 0,//最上级的父级id
      treeIdName: 'id',//id字段的名称
      treePidName: 'pid',//pid字段的名称
      treeDefaultClose: true,//是否默认折叠
      treeLinkage: true,//父级展开时是否自动展开所有子级
      elem: '#demo'
      , height: 'auto'
      , url: '/admin/auth/rule/index?type=2' //数据接口
      , toolbar: '#toolbarDemo'
      // , page: true //开启分页
      , cols: [[ //表头
        // {field: 'checkout', fixed: 'left'}
        { field: 'id', title: 'ID', width: 80 }
        // , { field: 'pid', title: 'PID', width: 80 }
        , { field: 'title', title: '规则名称', }
        , { field: 'icon', title: '图标', width: 60, templet: '<div><i class="{{d.icon}}"></i></div>' }
        // , { field: 'condition', title: '条件', width: 80, sort: true }
        , {
          field: 'status', title: '状态', width: 80, templet: function (d) {
            if (d.status == 1) {
              return '<span class="layui-badge-dot layui-bg-green"></span>&nbsp;&nbsp;<span style="color: #5FB878">正常</span>'
            } else {
              return '<span class="layui-badge-dot layui-bg-gray"></span>&nbsp;&nbsp;<span style="color: #e2e2e2">隐藏</span>'
            }
          }
        }
        , { field: 'name', title: '规则', width: 200 }
        , { field: 'ismenu', title: '菜单', width: 85, templet: '#switchTpl', unresize: true }
        , { fixed: 'right', title: '操作', width: 160, align: 'center', toolbar: '#barDemo' } //这里的toolbar值是模板元素的选择器
      ]],
      done: function () {
        layer.closeAll('loading')
      }
    });
  }

  renderTable();
  // 头工具栏事件
  table.on('toolbar(test)', function (obj) {
    // let checkStatus = table.checkStatus(obj.config.id);
    let layEvent = obj.event;
    if (layEvent === 'add') {
      layer.open({
        type: 2,
        area: ['60%', '75%'],
        content: '/admin/auth/rule/add',
        success: function (ret) {
          // console.log(ret)
        },
        end: function () {
          // console.log('22333')
          renderTable();
        }
      })
    }
  })
  // 监听工具条
  table.on('tool(test)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
    let data = obj.data; //获得当前行数据
    let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
    let tr = obj.tr;   //获得当前行tr 的DOM对象

    if (layEvent === 'detail') { //查看

    } else if (layEvent === 'del') { //删除
      layer.confirm('真的删除行么', function (index) {
        // obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
        layer.close(index);
        // 向服务器发送删除指令
        $.ajax({
          url: '/admin/auth/rule/del?ids=' + data.id,
          type: 'get',
          success: function (ret) {
            // 刷新表格
            renderTable();
          }
        })
      })
    } else if (layEvent === 'edit') { //编辑

      layer.open({
        type: 2,
        area: ['60%', '75%'],
        content: '/admin/auth/rule/edit?id=' + data.id,
        success: function (ret) {
          // console.log(ret)
        },
        end: function () {
          // console.log('22333')
          renderTable();
        }

      })
      // 同步更新缓存对应的值
      // obj.update({
      //   username: '123'
      //   ,title: 'xxx'
      // });
    }
  })
  // 监听菜单操作
  form.on('switch(ismenu)', function (obj) {
    // layer.tips(this.value + ' ' + this.name + ': ' + JSON.stringify(obj), obj.othis);
    let value = obj.elem.checked ? 1 : 0;
    $.ajax({
      url: '/admin/auth/rule/edit?id='+this.value,
      type: 'post',
      data: {ismenu: value},
      dataType: 'json',
      success:function(ret) {
        if(ret.code === 1) {
          renderTable();
        } else {
          layer.msg(ret.msg);
        }
      }
    })
  });
});
