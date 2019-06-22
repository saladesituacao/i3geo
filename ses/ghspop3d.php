<!DOCTYPE html>
<html lang="en">
<head>
<title>KML 3d</title>
<script src="../pacotes/cesium154/Build/CesiumUnminified/Cesium.js"></script>
<style>
@import url(../pacotes/cesium154/Build/Cesium/Widgets/widgets.css);

#cesiumContainer {
	position: absolute;
	top: 0;
	left: 0;
	height: 100%;
	width: 100%;
	margin: 0;
	overflow: hidden;
	padding: 0;
	font-family: sans-serif;
}

.cesium-viewer-bottom {
	display: block;
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	padding-right: 5px;
	left: 10px !Important;
}

html {
	height: 100%;
}

body {
	padding: 0;
	margin: 0;
	overflow: hidden;
	height: 100%;
}

#legenda {
	margin-bottom: 5px;
	margin-left: 5px;
	margin-right: 5px;
	margin-top: 5px;
	padding-bottom: 2px;
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 2px;
	position: absolute;
	top: 10px;
	left: 10px;
	color: white;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

.cesium-viewer-animationContainer {
	display: none;
}
</style>
</head>
<body>
    <div id="cesiumContainer"></div>
    <div id="loadingOverlay"><h1>Carregando...</h1></div>
    <div id="toolbar"></div>
    <div id="legenda">
        <img src="geodados/ghsl/tmepopulacao2015legenda.png" style="width: 100px; background-color: #00000038; padding: 10px;">
    </div>
    <script>

    Cesium.Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI5M2U4NDZhZS1iM2M5LTQ2N2YtYjBlYi1hYzY4MDhjYjRkYTIiLCJpZCI6NzY0OSwic2NvcGVzIjpbImFzciJdLCJhc3NldHMiOltdLCJpYXQiOjE1NTAwNjIyNjh9.Mex2Ke_oSkjJ9zjbJIA6ZfIU15GnliDpgdkimAJ6x08';

	var extent = Cesium.Rectangle.fromDegrees(-48.2851,-16,-47.30,-15.5);
	Cesium.Camera.DEFAULT_VIEW_RECTANGLE = extent;
	Cesium.Camera.DEFAULT_VIEW_FACTOR = 0;
	var viewer = new Cesium.Viewer(
		'cesiumContainer',
		{
			timeline : false,
			baseLayerPicker : true,
			imageryProvider : Cesium.createOpenStreetMapImageryProvider({
			    url : 'https://a.tile.openstreetmap.org/'
			})

		}
	);
	var dataSource1 = new Cesium.KmlDataSource({
	   camera: viewer.scene.camera,
	   canvas: viewer.scene.canvas
	});
	dataSource1.load("geodados/ghsl/tmepopulacao2015.kmz");
	viewer.dataSources.add(dataSource1);
</script>
</body>
</html>
