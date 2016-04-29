define(['Backbone', 'lang'], function(Backbone, lang){
	var data = Backbone.Model.extend ({
		urlRoot: "/api/photos/photo",
		defaults: {
			'name': '',
			'alt': '',
			'data': '',
			'event': '',
			'file': '',
			'format': '',
			'csrf_secure': ''
		},
		sync: function (method, model, options) {
			if (method == 'create') {
				var data = new FormData();
				data.append('format', model.attributes.format);
				data.append('name', model.attributes.name);
				data.append('data', model.attributes.data);
				data.append('csrf_secure', model.attributes.csrf_secure);
				options.contentType = false;
				options.processData = false;
				options.cache = false;
				options.data = data;
			}
			Backbone.sync(method, model, options);
			return true;
		},
		validate: function (attr) {
			var file= attr.file;
			var e = attr.event;
			var errors = new Object();
			var failed = false;
			var imageType = /image.*/;
			var nBytes;
			/*if (!file.type.match(imageType)) {
				errors.invalid = Lang['photo_invalid_format'];
				failed = true;
			}*/
			nBytes = file.size;
			for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
				sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
			}
			if (nBytes > 13500000) {
				errors.size = lang['photo_invalid_size'];
				failed = true;
			}
			if (failed == true) {
				return errors;
			}
		}
	});
	return data;
});
