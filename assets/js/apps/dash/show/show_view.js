define(["app", "tpl!apps/templates/dash.tpl", "tpl!apps/templates/postmemo.tpl", "tpl!apps/templates/viewmemo.tpl", "money"], 
	function(System, dashTpl, postMemoTpl, viewMemoTpl){
  System.module('DashApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Dash = Marionette.ItemView.extend({      

        template: dashTpl,

        events: {
          "click .ioptions #btn-edit": "setupElements",
        },

        onShow: function(){
          $("#dashcont").unwrap();
          $('.loading').show();
          this.setupGraphs();
          this.setupElements();
        },

        setupElements: function(){          
          var THAT = this;
          var ul = $('#projects');
          ul.empty();
          var ula = $('#invoices');
          ula.empty();
          var ulb = $('#enquiries');
          ulb.empty();
          var ulc = $('#messages');
          ulc.empty();
          $.get(System.coreRoot + '/service/tools/index.php?dirdash', function(result) {
            var data = JSON.parse(result);
            
            $('#prjtot').text('Total: '+data['latestProjects']['total']);
            $('#invtot').text('Total: '+data['latestInvoices']['total']);
            $('#enqtot').text('Total: '+data['latestEnquiries']['total']);

            var projects = data['latestProjects']['projects'];
            var prjstat = ['Pending', 'On Going', 'Stalled', 'Completed', 'Suspended'];
            projects.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'</td><td>'+elem['client']['name']+'</td><td><span class="label label-info">'+prjstat[elem['status']]+'</span></td></tr>');
              tpl.appendTo(ul);
            });

            var invoices = data['latestInvoices']['invoices'];
            invoices.forEach(function(elem){
              var tpl = $('<tr><td># <b>'+elem['id']+'</b></td><td>'+elem['description']+'</td><td>'+elem['client']['name']+'</td><td>'+elem['date']+'</td><td>'+(parseFloat(elem['total']['amount'])).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(ula);
            });

            var enquiries = data['latestEnquiries']['enquiries'];
            enquiries.forEach(function(elem){
              var tpl = $('<li><a href="#" class="item clearfix"><span class="from">'+elem['name']+'</span>'+elem['services']+' - '+elem['details']+'<span class="date">'+elem['date']+'</span></a></li>');
              tpl.appendTo(ulb);
            });

            
          });
        },

        setupGraphs: function() { 
          var THAT = this;
          require(["graphs"], function(){
            $.get(System.coreRoot + '/service/tools/index.php?dirdash', function(result) {
              var data = JSON.parse(result);
              THAT['data'] = data;

              var seriesData = [[], []];
              var thrday = [];
              var svnday = [];
              var today = [];
              var thirtydayr = data['thirtydaydata']['revs'];
              var thirtydaye = data['thirtydaydata']['exps'];

              for (var i = 0; i < thirtydayr.length; i++) {
                var rd = {};
                var ed = {};
                rd['x'] = i;
                rd['y'] = thirtydayr[i];
                seriesData[0].push(rd);

                ed['x'] = i;
                ed['y'] = thirtydaye[i];
                seriesData[1].push(ed);

                thrday[i] = thirtydayr[i] - thirtydaye[i];

                if (i < 7) {
                  svnday[i] = thirtydayr[i] - thirtydaye[i];
                };

              };

              for (var i = 0; i < data['todaydata']['revs'].length; i++) {
                today[i] = data['todaydata']['revs'][i] - data['todaydata']['exps'][i];
              };

              setTimeout(function() {
                // instantiate our graph!
                var graph = new Rickshaw.Graph( {
                  element: document.getElementById("todaysales"),
                  renderer: 'bar',
                  series: [
                    {
                      color: "rgba(38,166,91,1)",
                      data: seriesData[0],
                      name: 'Revenue'
                    }, {
                      color: "rgba(239,72,54,1)",
                      data: seriesData[1],
                      name: 'Expenses'
                    }
                  ]
                } );

                graph.render();

                var hoverDetail = new Rickshaw.Graph.HoverDetail( {
                  graph: graph,
                  formatter: function(series, x, y) {
                    var date = '<span class="date">' + new Date(x * 1000).toUTCString() + '</span>';
                    var swatch = '<span class="detail_swatch" style="margin-bottom: 30px;background-color: ' + series.color + '"></span>';
                    var content = swatch + series.name + ": Ksh. " + (parseFloat(y)).formatMoney(2, '.', ',');
                    return content;
                  }
                } );

                $(".sparkline-green").sparkline(thrday, {
                  type: 'line',
                  width: '105',
                  height: '30',
                  lineColor: '#26a65b',
                  fillColor: '',
                  lineWidth: 2,
                  spotColor: '#074f23',
                  minSpotColor: '#074f23',
                  maxSpotColor: '#074f23',
                  highlightLineColor: '#666666'});

                /* Chart Line Blue */
                $(".sparkline-blue").sparkline(svnday, {
                  type: 'line',
                  width: '105',
                  height: '30',
                  lineColor: '#399bff',
                  fillColor: '',
                  lineWidth: 2,
                  spotColor: '#00478e',
                  minSpotColor: '#00478e',
                  maxSpotColor: '#00478e',
                  highlightLineColor: '#666666',
                  drawNormalOnTop: false});

                $(".sparkline-red").sparkline(today, {
                  type: 'line',
                  width: '105',
                  height: '30',
                  lineColor: '#ef4836',
                  fillColor: '',
                  lineWidth: 2,
                  spotColor: '#a3180b',
                  minSpotColor: '#a3180b',
                  maxSpotColor: '#a3180b',
                  highlightLineColor: '#666666',
                  drawNormalOnTop: false});

                $('.loading').hide(); 
                
              }, 300); 

              var thrmrg = data['thirtydaydata']['margin']/(((data['thirtydaydata']['rsum'] - data['thirtydaydata']['esum']) - data['thirtydaydata']['margin']) || 1) * 100;
              if ((data['thirtydaydata']['rsum'] - data['thirtydaydata']['esum']) - data['thirtydaydata']['margin'] == 0) {
                if (thrmrg < 0) {
                  thrmrg = -100;
                }else{
                  thrmrg = 100;
                }
                
              };
              var thr = $('#thr');
              thr.empty();
              thrmrg = parseInt(thrmrg, 10);
              if ((data['thirtydaydata']['rsum'] - data['thirtydaydata']['esum']) > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600">Ksh. '+(data['thirtydaydata']['rsum'] - data['thirtydaydata']['esum']).formatMoney(2, '.', ',')+'</b><br></span>('+(data['thirtydaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['thirtydaydata']['esum']).formatMoney(2, '.', ',')+')<br>Over last 30 days<i class="chart sparkline-green"></i>');
                tpl.appendTo(thr);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600">Ksh. '+((data['thirtydaydata']['rsum'] - data['thirtydaydata']['esum'])).formatMoney(2, '.', ',')+'</b><br></span>('+(data['thirtydaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['thirtydaydata']['esum']).formatMoney(2, '.', ',')+')<br>Over last 30 days<i class="chart sparkline-green"></i>');
                tpl.appendTo(thr);
              }

              var svnmrg = data['sevendaydata']['margin']/(((data['sevendaydata']['rsum'] - data['sevendaydata']['esum']) - data['sevendaydata']['margin']) || 1) * 100;
              if ((data['sevendaydata']['rsum'] - data['sevendaydata']['esum']) - data['sevendaydata']['margin'] == 0) {
                if (svnmrg < 0) {
                  svnmrg = -100;
                }else{
                  svnmrg = 100;
                }
                
              };
              var svn = $('#svn');
              svn.empty();
              svnmrg = parseInt(svnmrg, 10);
              if ((data['sevendaydata']['rsum'] - data['sevendaydata']['esum']) > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"></i>Ksh. '+(data['sevendaydata']['rsum'] - data['sevendaydata']['esum']).formatMoney(2, '.', ',')+'</b><br></span>('+(data['sevendaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['sevendaydata']['esum']).formatMoney(2, '.', ',')+')<br>Over last 7 days<i class="chart sparkline-blue"></i>');
                tpl.appendTo(svn);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600"></i>Ksh. '+((data['sevendaydata']['rsum'] - data['sevendaydata']['esum'])).formatMoney(2, '.', ',')+'</b><br></span>('+(data['sevendaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['sevendaydata']['esum']).formatMoney(2, '.', ',')+')<br>Over last 7 days<i class="chart sparkline-blue"></i>');
                tpl.appendTo(svn);
              }

              var ymarg = data['yesterdaydata']['rsum'] - data['yesterdaydata']['esum'];
              var tmarg = data['todaydata']['rsum'] - data['todaydata']['esum'];
              var ystmrg = (tmarg - ymarg)/(ymarg || 1) * 100;
              if (ymarg == 0) {
                if (ystmrg < 0) {
                  ystmrg = -100;
                }else{
                  ystmrg = 100;
                }
                
              };
              var yst = $('#yst');
              yst.empty();
              ystmrg = parseInt(ystmrg, 10);
              if (ymarg > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600">Ksh. '+(ymarg).formatMoney(2, '.', ',')+'</b><br></span>('+(data['yesterdaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['yesterdaydata']['esum']).formatMoney(2, '.', ',')+')<br>Yesterday<i class="chart sparkline-red"></i>');
                tpl.appendTo(yst);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600">Ksh. '+(ymarg).formatMoney(2, '.', ',')+'</b><br></span>('+(data['yesterdaydata']['rsum']).formatMoney(2, '.', ',')+' - '+(data['yesterdaydata']['esum']).formatMoney(2, '.', ',')+')<br>Yesterday<i class="chart sparkline-red"></i>');
                tpl.appendTo(yst);
              }

              $('#tdyrev').text('Ksh. '+(parseFloat(data['todaydata']['rsum'])).formatMoney(2, '.', ','));

            });

            /*var optionss = {
              scaleColor: false,
              trackColor: 'rgba(0, 0, 0, 0.2)',
              barColor: '#399BFF',
              lineWidth: 6,
              lineCap: 'butt',
            }; 

            var charts = [];
            [].forEach.call(document.querySelectorAll('.easypie'),  function(el) {
              charts.push(new EasyPieChart(el, optionss));
            });*/           
            
          });
        }
    });

    View.PostMemo = Marionette.ItemView.extend({      

        template: postMemoTpl,

        events: {
          "click .nsave": "postMemo",
        },

        onShow: function(){
          $('.loading').hide();
          this.setup();
        },

        setup: function(){
          $('input').val('');
          $('textarea').val('');
          $('#date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY' }, function(start, end, label) {});
        },

        postMemo: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          //swal("Thank you!", "Query has been posted. Please wait to see consultant.", "success");
          if (data.title != "" && data.message != "") {
            data.name = System.username;
            data.role = System.role.name;
            this.trigger("create", data);
          };
          
        },

        onSuccess: function(e) { 
          swal("Success!", "The memo has been posted.", "success");          
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Command failed! Try again later.", "error");
        }
    });

    View.OfficeMemos = Marionette.ItemView.extend({      

        template: viewMemoTpl,

        events: {
          "click .xcheck": "postMemo",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          this.setup();

        },

        setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/tools/index.php?notices', function(result) {
            var m = JSON.parse(result);
            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['title']+'</td><td>'+elem['message']+'</td><td>'+elem['name']+'</br><span style="font-size:10px">'+elem['position']+'</span></td><td>'+elem['date']+'</td></tr>');
                //+'<td><p class="xstamp" style="display: none;">'+elem['stamp']+'</p><a class="btn btn-small js-edit xcheck" href="#"><i class="icon-pencil"></i>Check</a></td></tr>');
              tpl.appendTo(ul);
            });
            $('.xcheck').off();
            $('.xcheck').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var stamp = $(this).parent().find('.xstamp');
              stamp = parseInt(stamp.text());
              THAT.trigger("check", stamp);
            });
            
          });
        },

        postMemo: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          swal("Thank you!", "Query has been posted. Please wait to see consultant.", "success");
          //this.trigger("create", data);
        },

        onSuccess: function(e) { 
          swal("Success!", "The entry has been checked off from your list.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
        }
    });

  });

  return System.DashApp.Show.View;
});
