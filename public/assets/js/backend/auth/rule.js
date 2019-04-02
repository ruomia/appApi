layui.use('table', function(){
    var table = layui.table;
    let form = layui.form;
    // let layer = layui.layer;
    //第一个实例
    table.render({
      elem: '#demo'
      ,height: 484
      ,url: '/admin/auth/rule/index' //数据接口
      ,page: true //开启分页
      ,cols: [[ //表头
        // {field: 'checkout', fixed: 'left'}
        {field: 'id', title: 'ID', width:80, sort: true, }
        ,{field: 'title', title: '规则名称',}
        ,{field: 'icon', title: '图标', width: 60, templet: '<div>&nbsp;&nbsp;<i class="{{d.icon}}"></i></div>'}
        ,{field: 'condition', title: '条件', width:80, sort: true}
        ,{field: 'status', title: '状态', width:80, templet: function(d){
          if(d.status == 1) {
            return '<span class="layui-badge-dot layui-bg-green"></span>&nbsp;&nbsp;<span style="color: #5FB878">正常</span>'
          } else {
            return '<span class="layui-badge-dot layui-bg-gray"></span>&nbsp;&nbsp;<span style="color: #e2e2e2">隐藏</span>'
          }
        }} 
        ,{field: 'name', title: '规则', width:200} 
        ,{field: 'ismenu', title: '菜单', width:85, templet: '#switchTpl', unresize: true}
        ,{fixed: 'right', title: '操作', width:160, align:'center', toolbar: '#barDemo'} //这里的toolbar值是模板元素的选择器
      //   ,{field: 'sign', title: '签名', width: 177}
      //   ,{field: 'experience', title: '积分', width: 80, sort: true}
      //   ,{field: 'score', title: '评分', width: 80, sort: true}
      //   ,{field: 'classify', title: '职业', width: 80}
      //   ,{field: 'wealth', title: '财富', width: 135, sort: true}
      ]]
    });
    // 监听工具条
    table.on('tool(test)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
      let data = obj.data; //获得当前行数据
      let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
      let tr = obj.tr;   //获得当前行tr 的DOM对象

      if(layEvent === 'detail'){ //查看

      } else if(layEvent === 'del'){ //删除
        layer.confirm('真的删除行么', function(index){
          obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
          layer.close(index);
          // 向服务器发送删除指令
        })
      } else if(layEvent === 'edit'){ //编辑
        
        layer.open({
          type: 2,
          area: ['60%', '75%'],
          content: '/admin/auth/rule/edit?id='+data.id,
          success:function(ret){
            console.log(ret)
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
    form.on('switch(sexDemo)', function(obj) {
      layer.tips(this.value + '' + this.name + ': '+ obj.elem.checked, obj.othis);
    });

  });