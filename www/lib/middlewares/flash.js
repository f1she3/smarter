module.exports = function(request, response, next){
	if(request.session.flash){
		// Unset flash after it was displayed
		response.locals.flashType = request.session.flash = undefined;
		response.locals.flash = request.session.flash = undefined;
	}
	request.flash = function(type, message){
		request.session.flashType = type;
		request.session.flash = message;
		response.locals.flashType = request.session.flashType;
		response.locals.flash = request.session.flash;
	}

	next();
};
