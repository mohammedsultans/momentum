requirejs.config({
  urlArgs: "bust=" + (new Date()).getTime(),
  baseUrl: "assets/js",
  paths: {
    jquery: "vendor/jquery.min",    
    json2: "vendor/json2",
    backbone: "vendor/backbone",
    marionette: "vendor/backbone.marionette",
    tpl: "vendor/tpl",    
    underscore: "vendor/underscore",
    //backbone plugins
    "backbone.picky": "vendor/backbone.picky",
    "backbone.syphon": "vendor/backbone.syphon",    
    "backbone.modelBinder": "vendor/backbone.modelbinder",    
    localstorage: "vendor/backbone.localstorage",
    //bootstrap
    bootstrap: "plugins/bootstrap/bootstrap.min",    
    bootselect: "plugins/bootstrap-select/bootstrap-select",
    boottoggle: "plugins/bootstrap-toggle/bootstrap-toggle.min",
    //wysiwsyg editors
    bootwysi: "plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.min",
    bootwysiboot: "plugins/bootstrap-wysihtml5/bootstrap-wysihtml5",
    summernote: "plugins/summernote/summernote.min",
    //charting
    flotchart: "plugins/flot-chart/flot-chart",
    flottime: "plugins/flot-chart/flot-chart-time",
    flotstack: "plugins/flot-chart/flot-chart-stack",
    flotpie: "plugins/flot-chart/flot-chart-pie",
    chartist: "plugins/chartist/chartist",
    easypie: "plugins/easypiechart/easypiechart",
    sparkline: "plugins/sparkline/sparkline",
    d3: "plugins/rickshaw/d3.v3",
    rickshaw: "plugins/rickshaw/rickshaw",
    //datatables
    datatables: "plugins/datatables/datatables.min",
    //alerts
    sweetalert: "plugins/sweet-alert/sweet-alert.min",
    kodealert: "plugins/kode-alert/main",
    jqueryui: "plugins/jquery-ui/jquery-ui.min",
    moment: "plugins/moment/moment.min",
    calendar: "plugins/full-calendar/fullcalendar",
    daterange: "plugins/date-range-picker/daterangepicker",

    plugins: "plugins/plugins",
    graphs: "plugins/graphing",
    money: "plugins/formatmoney",
    pieplug: "plugins/easypiechart/easypiechart-plugin",
    dash: "dash",

  },

  shim: {
    underscore: {
      exports: "_"
    },

    jquery: {
      exports: "$"
    },
    backbone: {
      deps: ["jquery", "underscore", "json2"],
      exports: "Backbone"
    },
    "backbone.picky": ["backbone"],
    "backbone.syphon": ["backbone"],
    "backbone.modelBinder": ["backbone"],
    marionette: {
      deps: ["backbone"],
      exports: "Marionette"
    },
    d3: {
      deps: ["jquery"],
      exports: 'd3'
    },

    rickshaw: ["d3"],

    flotchart: {
      deps: ["jquery"],
      exports: '$.plot'
    },
    flottime: {
      deps: ["flotchart"]
    },
    flotstack: {
      deps: ["flotchart"]
    },
    flotpie: {
      deps: ["flotchart"]
    },
    
    jqueryui: ["jquery"],
    localstorage: ["backbone"],
    bootstrap: ["jquery"],
    plugins: ["bootstrap"],
    graphs: ["jquery", "bootstrap", "bootselect", "boottoggle", "bootwysiboot", "summernote", "flotchart", "flottime", "flotstack", "flotpie", "chartist", "easypie", "sparkline", "d3", "rickshaw", "datatables", "sweetalert", "kodealert", "jqueryui", "moment", "calendar", "daterange", "money"]
  }
});

require(["app"], function(System){
  System.start();
});
