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
              if (thrmrg > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-up"></i>'+thrmrg+'%</b><br></span>from previous month<i class="chart sparkline-green"></i>');
                tpl.appendTo(thr);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-down"></i>'+(thrmrg * -1)+'%</b><br></span>from previous month<i class="chart sparkline-green"></i>');
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
              if (svnmrg > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-up"></i>'+svnmrg+'%</b><br></span>from previous week<i class="chart sparkline-blue"></i>');
                tpl.appendTo(svn);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-down"></i>'+(svnmrg * -1)+'%</b><br></span>from previous week<i class="chart sparkline-blue"></i>');
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
              if (ystmrg > 0) {
                var tpl = $('<span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-up"></i>'+ystmrg+'%</b><br></span>from yesterday<i class="chart sparkline-red"></i>');
                tpl.appendTo(yst);
              }else{
                var tpl = $('<span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-down"></i>'+(ystmrg * -1)+'%</b><br></span>from yesterday<i class="chart sparkline-red"></i>');
                tpl.appendTo(yst);
              }