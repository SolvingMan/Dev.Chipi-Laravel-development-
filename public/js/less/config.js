function FireLessProcessor(options) {};
	FireLessProcessor.prototype = {
		process: function (css) {
			css = css.replace(/(@media -sass-debug-info\{)(.*)(\}\n)/g,function(m1,m2,m3,m4){
				m3 = m3.replace('file\\:\\/\\/file\\:\\/\\/','file\\:\\/\\/');
				m3 = m3.replace('file\\:\\/\\/http\\:\\/\\/','http\\:\\/\\/');
				m3 = m3.replace('file\\:\\/\\/https\\:\\/\\/','https\\:\\/\\/');
				m3 = m3.replace(/(font-family:)([^}]+)(})/g, function(r1,r2,r3,r4){
					return r2 + '"' + r3 + '";' + r4;
					});

				return m2 + m3 + m4;
			});
			return css;
		}
	};
	var FireLess = {
		install: function(less,pluginManager) {
			pluginManager.addPostProcessor(new FireLessProcessor());
		}
	};

	var less = {
		env: "development",
		dumpLineNumbers: "mediaquery",
		plugins: [FireLess]
	};