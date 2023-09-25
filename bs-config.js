/*Browser-sync configuration*/
module.exports = {
	port: 80,
	proxy: 'localhost/datumstruct',
	files: [
		'catalog/view/theme/default/stylesheet/sass/*.sass', 
		'catalog/view/theme/default/stylesheet/sass/*.css', 
		'catalog/view/theme/default/stylesheet/*.sass', 
		'catalog/view/theme/default/stylesheet/*.css', 
		'catalog/view/javascript/*.js', 
		'catalog/view/theme/default/template/**/*.tpl'
	]
};
