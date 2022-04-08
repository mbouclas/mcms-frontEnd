module.exports = (function (gulp,config,$) {
    'use strict';

    return function (){

        $.log('Copying template files to production');
        $.log(config.templatesDir)
        $.log(config.publicDirTemplates)
        return gulp
            .src(config.templatesDir)
            .pipe(gulp.dest(config.publicDirTemplates));
    }


});
