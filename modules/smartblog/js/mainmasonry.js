function GridFx(el, options) {
	this.gridEl = el;
	this.items = [].slice.call(this.gridEl.querySelectorAll('.grid__item'));
	this._init();
}
GridFx.prototype._init = function() {
	var self = this;
	new Masonry(self.gridEl, {
		itemSelector: '.grid__item',
		isFitWidth : true
	});
};