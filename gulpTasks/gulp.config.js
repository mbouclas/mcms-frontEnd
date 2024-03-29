var path = require('path');
var args = require('yargs').argv;
var BaseDir = path.resolve(__dirname,'../'),
    InstallationDir = path.resolve(__dirname,args.installationDir || '../../');

var Config = {
    BaseDir : BaseDir,
    InstallationDir : InstallationDir,
    appDir : path.join(BaseDir,'resources/assets/app/'),
    publicDir : path.join(BaseDir,'resources/public/'),
    optimizedDirJs : path.join(BaseDir,'resources/public/js/**/*.js'),
    optimizedDirCss : path.join(BaseDir,'resources/public/css/**/*.css'),
    templatesDir : path.join(BaseDir,'resources/assets/app/templates/**/*.html'),
    cssDir : path.join(BaseDir,'resources/assets/css/**/*.css'),
    imgDir : path.join(BaseDir,'resources/assets/img/**/*.css'),
    jsDir : path.join(BaseDir,'resources/assets/js/**/*.js'),
    publicDirJs : path.join(InstallationDir,'public/front-end/js'),
    publicDirCss : path.join(InstallationDir,'public/front-end/css'),
    publicDirImg : path.join(InstallationDir,'public/front-end/img'),
    publicDirTemplates : path.join(InstallationDir,'public/front-end/app/templates')
};

module.exports = Config;
