var require, themePath;

themePath = "/app/content";

require = {
  baseUrl: themePath + '/js',
  paths: {
    jquery: ["../../../bower_components/jquery/dist/jquery.min"],
    bootstrap: ["../../../bower_components/bootstrap/dist/js/bootstrap.min"],
    underscore: ["../../../bower_components/underscore/underscore-min"],
    handlebars: ["../../../bower_components/handlebars/handlebars.min"],
    init: ["init"],
    Utility: ["../../../wp-content/themes/gigazone-gaming/js/modules/Utility"],
    form: ["../../../wp-content/themes/gigazone-gaming/js/modules/form"]
  },
  waitSeconds: 0,
  shim: {
    "bootstrap": ["jquery"],
    "underscore": {
      exports: "_"
    },
    "handlebars": {
      exports: "Handlebars"
    }
  },
  priority: ["jquery"]
};