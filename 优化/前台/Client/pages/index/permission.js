/**
 * 应用权限判断、打开应用权限设置界面
 */

var isIos
// #ifdef APP-PLUS
isIos = (plus.os.name == "iOS")
// #endif

function requestAndroidPermission(permissionID) {
	return new Promise((resolve, reject) => {
		plus.android.requestPermissions(
			[permissionID],
			function(resultObj) {
				var result = 0;
				for (var i = 0; i < resultObj.granted.length; i++) {
					if (permissionID == resultObj.granted[i]) {
						result = 1;
					}
				}
				for (var i = 0; i < resultObj.deniedPresent.length; i++) {
					if (permissionID == resultObj.deniedPresent[i]) {
						result = 0;
					}
				}
				for (var i = 0; i < resultObj.deniedAlways.length; i++) {
					if (permissionID == resultObj.deniedAlways[i]) {
						result = -1;
					}
				}
				resolve(result);
			},
			function(error) {
				resolve(0);
			}
		);
	});
}

function gotoAppPermissionSetting() {
	if (isIos) {
		var UIApplication = plus.ios.import("UIApplication");
		var application2 = UIApplication.sharedApplication();
		var NSURL2 = plus.ios.import("NSURL");
		var setting2 = NSURL2.URLWithString("app-settings:");
		application2.openURL(setting2);
		plus.ios.deleteObject(setting2);
		plus.ios.deleteObject(NSURL2);
		plus.ios.deleteObject(application2);
	} else {
		var Intent = plus.android.importClass("android.content.Intent");
		var Settings = plus.android.importClass("android.provider.Settings");
		var Uri = plus.android.importClass("android.net.Uri");
		var mainActivity = plus.android.runtimeMainActivity();
		var intent = new Intent();
		intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
		var uri = Uri.fromParts("package", mainActivity.getPackageName(), null);
		intent.setData(uri);
		mainActivity.startActivity(intent);
	}
}

export default {
	requestAndroidPermission,
	gotoAppPermissionSetting
}
