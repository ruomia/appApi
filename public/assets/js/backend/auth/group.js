layui.config({
  base: '/assets/layui_exts/'
}).extend({
  authtree: 'authtree/authtree',
});
layui.use(['treetable', 'authtree'], function () {
  var treetable = layui.treetable;
  var table = layui.table;
  let form = layui.form;
  let authtree = layui.authtree;
  let tree = '#LAY-auth-tree-index';
  let renderTable = function () {
    layer.load(2);
    treetable.render({
      treeColIndex: 2,//树形图标显示在第几列
      treeSpid: 0,//最上级的父级id
      treeIdName: 'id',//id字段的名称
      treePidName: 'pid',//pid字段的名称
      treeDefaultClose: false,//是否默认折叠
      treeLinkage: true,//父级展开时是否自动展开所有子级
      elem: '#demo'
      , height: 'auto'
      , url: '/admin/auth/group/index?table=1' //数据接口
      , toolbar: '#toolbarDemo'
      // , page: true //开启分页
      , cols: [[ //表头
        // {field: 'checkout', fixed: 'left'}
        { field: 'id', title: 'ID', width: 80 }
        , { field: 'pid', title: 'PID', width: 80 }
        , { field: 'name', title: '名称', }
        , {
          field: 'status', title: '状态', width: 100, templet: function (d) {
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
  }

  renderTable();
  // 头工具栏事件
  table.on('toolbar(test)', function (obj) {
    // let checkStatus = table.checkStatus(obj.config.id);
    let layEvent = obj.event;
    if (layEvent === 'add') {
      $.ajax({
        url: '/admin/auth/group/add',
        type: 'get',
        dataType: 'json',
        success: function (res) {
          openPage(res);
          // 渲染树状结构
          authTree(authtree);
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
      $.ajax({
        url: '/admin/auth/group/edit?id=' + data.id,
        type: 'get',
        dataType: 'json',
        success: function (res) {
          openPage(res);
          // 渲染树状结构
          authTree(authtree, data.pid, data.id);
        }
      })
    }
  })
  // 监听菜单操作
  form.on('switch(ismenu)', function (obj) {
    // layer.tips(this.value + ' ' + this.name + ': ' + JSON.stringify(obj), obj.othis);
    let value = obj.elem.checked ? 1 : 0;
    $.ajax({
      url: '/admin/auth/rule/edit?id=' + this.value,
      type: 'post',
      data: { ismenu: value },
      dataType: 'json',
      success: function (ret) {
        if (ret.code === 1) {
          renderTable();
        } else {
          layer.msg(ret.msg);
        }
      }
    })
  });
  form.on('select(aihao)', function (data) {
    authTree(authtree, data.value);
  });
  form.on('checkbox(checkAll)', function (data) {
    if (data.elem.checked) {
      authtree.checkAll(tree)
    } else {
      authtree.uncheckAll(tree)
    }
  });
  form.on('checkbox(showAll)', function (data) {
    if (data.elem.checked) {
      authtree.showAll(tree)
    } else {
      authtree.closeAll(tree)
    }
  });
  form.on('submit(*)', function (data) {
    $.ajax({
      url: dada.form.action,
      type: 'POST',
      data: data.field,
      dataType: 'json',
      success: function (res) {
        // console.log(data);
        if (res.code === 1) {
          layer.msg('操作成功！');
          parent.layer.close(index)
        } else {
          layer.msg(res.msg)
        }
      }
    })
    return false;
  })

  function openPage(str) {
    layer.open({
      type: 1,
      area: ['60%', '75%'],
      content: str,
      success: function (res) {
        form.render();
      }
    })
  }
});
// 弹出表单

function authTree(authtree, pid = '', id = '') {
  $.ajax({
    url: '/admin/auth/group/getAuthRule?pid=' + pid + '&id=' + id,
    dataType: 'json',
    success: function (data) {
      var trees = authtree.listConvert(data.data.list, {
        primaryKey: 'id'
        , startPid: 0
        , parentKey: 'pid'
        , nameKey: 'title'
        , valueKey: 'id'
        , checkedKey: data.data.checkedId
      });
      // console.log(data)
      // 如果后台返回的不是树结构，请使用 authtree.listConvert 转换
      authtree.render('#LAY-auth-tree-index', trees, {
        inputname: 'rules[]',
        layfilter: 'lay-check-auth',
        autowidth: true,
      });
    }
  });
}
