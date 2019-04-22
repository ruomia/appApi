layui.use(['treetable','form'], function () {
    var treetable = layui.treetable;
    var table = layui.table;
    let form = layui.form;
    let formIndex;
    let renderTable = function () {
      layer.load(2);
      treetable.render({
        treeColIndex: 2,//树形图标显示在第几列
        treeSpid: 0,//最上级的父级id
        treeIdName: 'id',//id字段的名称
        treePidName: 'pid',//pid字段的名称
        treeDefaultClose: true,//是否默认折叠
        treeLinkage: false,//父级展开时是否自动展开所有子级
        elem: '#demo'
        , height: 'auto'
        , url: '/admin/category/index?type=2' //数据接口
        , toolbar: '#toolbarDemo'
        // , page: true //开启分页
        , cols: [[ //表头
          // {field: 'checkout', fixed: 'left'}
          { field: 'id', title: 'ID', width: 80 }
          , { field: 'pid', title: 'PID', width: 80, align: 'center' }
          , { field: 'name', title: '规则名称', }
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
          url: '/admin/category/add',
          type: 'get',
          dataType: 'json',
          success:function(res) {
            openPage(res)
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
            url: '/admin/category/del?id=' + data.id,
            type: 'get',
            success: function (ret) {
              // 刷新表格
              renderTable();
            }
          })
        })
      } else if (layEvent === 'edit') { //编辑
        $.ajax({
          url: '/admin/category/edit?id=' + data.id,
          type: 'get',
          dataType: 'json',
          success:function(res){
            openPage(res)
          }
        })
      }
    })
    //监听提交
    form.on('submit(*)', function (data) {
      $.ajax({
        url: data.form.action,
        type: 'post',
        data: data.field,
        dataType: 'json',
        success: function (ret) {
          if (ret.code === 1) {
            layer.msg('操作成功！');
            layer.close(formIndex);
            renderTable();
          } else {
            layer.msg(ret.msg);
          }
        }
      })
      return false;
    });
    function openPage(str){
      formIndex = layer.open({
        type: 1,
        area: ['60%', '75%'],
        content: str,
        success:function(res){
          form.render();
        }
      })
    }
  });
  