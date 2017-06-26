describe('foo', function(){
	var foo;

	beforeEach(angular.mock.module('app.foo'));

	beforeEach(inject(function(_foo_) {
		foo = _foo_;
	}));

	it('should be true', function() {
		expect('foo').toBe('foo');
	});

	it('should echo strings', function() {
		expect(foo.echo('bar')).toBe('bar');
	});

});
