<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>选择位置</title>
    <link rel="stylesheet" href="https://a.amap.com/jsapi_demos/static/demo-center/css/demo-center.css" />

    <style>
        html,
        body,
        #container {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div id="container"></div>

<script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.13&key=306f42e565027cf5bd169459c93d6917&plugin=AMap.Geocoder"></script>
<script src="https://a.amap.com/jsapi_demos/static/demo-center/js/demoutils.js"></script>
<script type="text/javascript">
    //初始化地图对象，加载地图
    var map = new AMap.Map("container", {
        resizeEnable: true
    });


    //构建自定义信息窗体
    var infoWindow = new AMap.InfoWindow({
        anchor: 'middle-left',
        content: '这是信息窗体！',
    });

    var geocoder = new AMap.Geocoder({
        city: '全国',
        radius: 1000
    });

    function setAnchor(){
        var anchor = this.id;
        infoWindow.setAnchor(anchor)
    }
    //绑定radio点击事件
    var radios = document.querySelectorAll("#coordinate input");
    radios.forEach(function(ratio) {
        ratio.onclick = setAnchor;
    });

    function showInfoClick(e){
        infoWindow.close();
        var address;
        geocoder.getAddress(e.lnglat.getLng()+','+e.lnglat.getLat(), function(status, result) {
            if (status === 'complete'&&result.regeocode) {
                address = result.regeocode.formattedAddress;
                infoWindow.setContent('<span>['+e.lnglat.getLng()+','+e.lnglat.getLat()+']</span><br/><input name="site" value="'+address+'"/><br/><button onclick="choose('+e.lnglat.getLng()+','+e.lnglat.getLat()+')">选择</button>')
                infoWindow.open(map,[e.lnglat.getLng(),e.lnglat.getLat()])
            }else{alert(JSON.stringify(result))}
        });
    }

    // 事件绑定
    //log.success("绑定事件!");
    map.on('click', showInfoClick);

    function choose(lng,lat){
        log.success("选择位置："+lng+","+lat);
        window.opener.document.getElementById("longitude").value = lng;
        window.opener.document.getElementById("latitude").value =lat;
        window.close();
    }
</script>
</body>
</html>
